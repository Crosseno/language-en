<?php

declare(strict_types=1);

const TARGET_ANSWER_COUNT = 1000;

$root = \dirname(__DIR__);
$groups = loadVocabulary($root . '/resources/source/native-vocabulary.php');
$content = loadAuthoredContent($root . '/resources/source/content');
$records = [];
$seen = [];

foreach ($groups as $theme => $group) {
    $partOfSpeech = $group['part_of_speech'];
    $words = preg_split('/\s*,\s*/', trim($group['words']));
    if ($words === false) {
        throw new RuntimeException('Could not parse vocabulary group: ' . $theme);
    }

    foreach ($words as $answer) {
        $answer = strtolower(trim($answer));
        if ($answer === '' || isset($seen[$answer])) {
            continue;
        }
        if (preg_match('/^[a-z]{3,12}$/D', $answer) !== 1) {
            throw new RuntimeException('Invalid native answer: ' . $answer);
        }

        $seen[$answer] = true;
        $cellCount = \strlen($answer);
        if (!isset($content[$answer])) {
            throw new RuntimeException('Missing authored content for: ' . $answer);
        }
        $authored = $content[$answer];
        $dialects = match ($answer) {
            'theater' => ['en-US'],
            'theatre' => ['en-AU', 'en-CA', 'en-GB'],
            default => ['en'],
        };

        $records[] = [
            'answer' => $answer,
            'lemma' => $answer,
            'language' => 'en',
            'part_of_speech' => $partOfSpeech,
            'sense_id' => \sprintf('native-%s-%s-01', $theme, $answer),
            'definition' => $authored['definition'],
            'frequency' => selectionRank($answer),
            'difficulty' => answerDifficulty($answer),
            'proper_name' => 'no',
            'abbreviation' => 'no',
            'answer_classes' => ['standard'],
            'dialects' => $dialects,
            'themes' => [$theme],
            'clues' => [
                [
                    'language' => 'en',
                    'text' => $authored['clues'][0],
                    'difficulty' => min(45, 20 + ($cellCount * 2)),
                ],
                [
                    'language' => 'en',
                    'text' => $authored['clues'][1],
                    'difficulty' => min(92, 78 + $cellCount),
                ],
            ],
        ];
        if (\count($records) === TARGET_ANSWER_COUNT) {
            break 2;
        }
    }
}

if (\count($records) !== TARGET_ANSWER_COUNT) {
    throw new RuntimeException(\sprintf(
        'Expected %d unique eligible answers, found %d.',
        TARGET_ANSWER_COUNT,
        \count($records),
    ));
}
if (array_diff_key($content, $seen) !== []) {
    throw new RuntimeException('Authored content contains answers outside the selected milestone.');
}

$clueOwners = [];
foreach ($records as $record) {
    foreach ([$record['definition'], ...array_column($record['clues'], 'text')] as $text) {
        if (textLeaksAnswer($record['answer'], $text)) {
            throw new RuntimeException('Draft text leaks its answer: ' . $record['answer']);
        }
    }
    $clueTexts = array_column($record['clues'], 'text');
    if (clueSimilarity($clueTexts[0], $clueTexts[1]) >= 0.6) {
        throw new RuntimeException('Clue pair is excessively similar for: ' . $record['answer']);
    }
    foreach ($clueTexts as $clueText) {
        $normalizedClue = mb_strtolower(trim($clueText), 'UTF-8');
        if (isset($clueOwners[$normalizedClue])) {
            throw new RuntimeException(\sprintf(
                'Duplicate clue text for %s and %s.',
                $clueOwners[$normalizedClue],
                $record['answer'],
            ));
        }
        if (preg_match('/\b(?:catalog|vowels?|initial . final|metadata|part of speech|vocabulary)\b/ui', $clueText) === 1) {
            throw new RuntimeException('Metadata-driven clue text for: ' . $record['answer']);
        }
        $clueOwners[$normalizedClue] = $record['answer'];
    }
}

usort(
    $records,
    static fn(array $left, array $right): int => $left['answer'] <=> $right['answer'],
);

$json = json_encode($records, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) . "\n";
$destination = $root . '/resources/source/curated-en.json';
if (file_put_contents($destination, $json) !== \strlen($json)) {
    throw new RuntimeException('Could not write the native English source.');
}

fwrite(STDOUT, \sprintf("Built %d native English draft records.\n", \count($records)));

/**
 * @return array<string, array{part_of_speech: string, words: string}>
 */
function loadVocabulary(string $path): array
{
    $input = require $path;
    if (!\is_array($input)) {
        throw new RuntimeException('Native vocabulary must return an array.');
    }

    $groups = [];
    foreach ($input as $theme => $group) {
        if (
            !\is_string($theme)
            || !\is_array($group)
            || !\is_string($group['part_of_speech'] ?? null)
            || !\is_string($group['words'] ?? null)
        ) {
            throw new RuntimeException('Native vocabulary contains an invalid group.');
        }
        $groups[$theme] = [
            'part_of_speech' => $group['part_of_speech'],
            'words' => $group['words'],
        ];
    }

    return $groups;
}

/**
 * @return array<string, array{definition: string, clues: array{string, string}}>
 */
function loadAuthoredContent(string $directory): array
{
    $paths = glob($directory . '/*.php');
    if ($paths === false || $paths === []) {
        throw new RuntimeException('No authored content files were found.');
    }
    sort($paths, SORT_STRING);

    $content = [];
    foreach ($paths as $path) {
        $input = require $path;
        if (!\is_array($input)) {
            throw new RuntimeException('Authored content file must return an array: ' . $path);
        }
        foreach ($input as $answer => $entry) {
            if (
                !\is_string($answer)
                || !\is_array($entry)
                || !\is_string($entry['definition'] ?? null)
                || !\is_array($entry['clues'] ?? null)
                || \count($entry['clues']) !== 2
                || !\is_string($entry['clues'][0] ?? null)
                || !\is_string($entry['clues'][1] ?? null)
            ) {
                throw new RuntimeException('Invalid authored content entry in: ' . $path);
            }
            if (isset($content[$answer])) {
                throw new RuntimeException('Duplicate authored content for: ' . $answer);
            }
            $content[$answer] = [
                'definition' => $entry['definition'],
                'clues' => [$entry['clues'][0], $entry['clues'][1]],
            ];
        }
    }

    return $content;
}

function normalizeLetters(string $value): string
{
    $normalized = preg_replace('/[^A-Z]/', '', strtoupper($value));
    if ($normalized === null) {
        throw new RuntimeException('Could not normalize draft text.');
    }

    return $normalized;
}

function textLeaksAnswer(string $answer, string $text): bool
{
    if (str_contains(normalizeLetters($text), normalizeLetters($answer))) {
        return true;
    }
    $tokens = preg_split('/[^\p{L}]+/u', mb_strtolower($text, 'UTF-8'), -1, PREG_SPLIT_NO_EMPTY);
    if ($tokens === false) {
        throw new RuntimeException('Could not tokenize draft text for leakage validation.');
    }
    $forms = match ($answer) {
        'break' => ['broke', 'broken'],
        'bring' => ['brought'],
        'buy' => ['bought'],
        'catch' => ['caught'],
        'choose' => ['chose', 'chosen'],
        'eat' => ['ate', 'eaten'],
        'feel' => ['felt'],
        'find' => ['found'],
        'fly' => ['flew', 'flown'],
        'give' => ['gave', 'given'],
        'grow' => ['grew', 'grown'],
        'keep' => ['kept'],
        'know' => ['knew', 'known'],
        'leave' => ['left'],
        'lose' => ['lost'],
        'make' => ['made', 'making'],
        'meet' => ['met'],
        'pay' => ['paid'],
        'rise' => ['rose', 'risen'],
        'run' => ['ran'],
        'say' => ['said'],
        'see' => ['saw', 'seen'],
        'sell' => ['sold'],
        'send' => ['sent'],
        'sing' => ['sang', 'sung'],
        'sit' => ['sat'],
        'sleep' => ['slept'],
        'speak' => ['spoke', 'spoken'],
        'stand' => ['stood'],
        'take' => ['took', 'taken'],
        'teach' => ['taught'],
        'tell' => ['told'],
        'think' => ['thought'],
        'throw' => ['threw', 'thrown'],
        'understand' => ['understood'],
        'wear' => ['wore', 'worn'],
        'win' => ['won'],
        'write' => ['wrote', 'written'],
        default => [],
    };
    if (str_ends_with($answer, 'e')) {
        $forms[] = substr($answer, 0, -1) . 'ing';
    }
    if (str_ends_with($answer, 'y')) {
        $stem = substr($answer, 0, -1);
        $forms[] = $stem . 'ied';
        $forms[] = $stem . 'ies';
    }

    return array_intersect($tokens, $forms) !== [];
}

function clueSimilarity(string $left, string $right): float
{
    $stopWords = array_fill_keys(
        ['a', 'an', 'and', 'as', 'at', 'by', 'for', 'from', 'in', 'into', 'of', 'on', 'or', 'the', 'to', 'used', 'with'],
        true,
    );
    $tokenize = static function (string $text) use ($stopWords): array {
        $tokens = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($text, 'UTF-8'), -1, PREG_SPLIT_NO_EMPTY);
        if ($tokens === false) {
            throw new RuntimeException('Could not tokenize clue text.');
        }

        return array_values(array_unique(array_filter(
            $tokens,
            static fn(string $token): bool => !isset($stopWords[$token]),
        )));
    };

    $leftTokens = $tokenize($left);
    $rightTokens = $tokenize($right);
    $union = array_unique([...$leftTokens, ...$rightTokens]);
    if ($union === []) {
        return 1.0;
    }

    return \count(array_intersect($leftTokens, $rightTokens)) / \count($union);
}

function selectionRank(string $answer): int
{
    $letterWeights = [
        'a' => 8, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 12, 'f' => 2, 'g' => 3,
        'h' => 6, 'i' => 7, 'j' => 1, 'k' => 1, 'l' => 4, 'm' => 2, 'n' => 7,
        'o' => 7, 'p' => 2, 'q' => 1, 'r' => 6, 's' => 6, 't' => 9, 'u' => 3,
        'v' => 1, 'w' => 2, 'x' => 1, 'y' => 2, 'z' => 1,
    ];
    $score = 500 - (abs(\strlen($answer) - 5) * 35);
    foreach (str_split($answer) as $letter) {
        $score += $letterWeights[$letter] ?? 0;
    }
    $score += \count(array_unique(str_split($answer))) * 8;

    return max(1, min(1000, $score));
}

function answerDifficulty(string $answer): int
{
    $rareLetters = preg_match_all('/[jqxz]/', $answer);
    if ($rareLetters === false) {
        throw new RuntimeException('Could not score answer difficulty: ' . $answer);
    }

    $hardAnswerList = preg_split(
        '/\s+/',
        trim('
            antelope badger bat beaver beetle bison butterfly camel cattle cheetah cricket crocodile falcon ferret
            flamingo giraffe gorilla hedgehog insect jaguar kangaroo leopard lizard lobster moose otter raccoon raven
            salmon seal snail swan ballet canvas chorus drama gallery melody opera portrait rhythm trumpet ankle elbow
            nerve spine throat blouse collar costume fabric gown vest bill business trade value blend carve dice grate
            knead mash roast season whisk degree diploma essay grade lesson poem ruler subject almond bagel biscuit
            broccoli cabbage cinnamon cocoa coconut eggplant flour gravy melon muffin mushroom olive pasta peanut pickle
            pudding sausage spinach syrup vinegar allergy clinic pulse therapy attic cellar faucet furnace pantry radiator
            staircase cliff desert meadow pebble prairie reef soil valley volcano wilderness avenue courthouse harbor
            palace plaza suburb temple acorn bamboo cedar clover elm fern ivy maple moss orchid poppy shrub thorn tulip
            willow atom biology carbon cell chemical energy force fossil gravity laboratory magnet matter metal mineral
            oxygen physics protein sample species theory citizen council culture custom nation public hockey tennis cursor
            device network signal software century decade chisel pliers spade wrench canoe carriage ferry lane railway
            route sail subway clerk mechanic tailor
        '),
    );
    if ($hardAnswerList === false) {
        throw new RuntimeException('Could not load the hard-answer calibration set.');
    }
    $hardAnswers = array_fill_keys($hardAnswerList, true);
    if (isset($hardAnswers[$answer])) {
        return min(95, 65 + (\strlen($answer) * 2) + ($rareLetters * 4));
    }

    if (\strlen($answer) <= 5) {
        return min(39, 25 + (\strlen($answer) * 2) + ($rareLetters * 3));
    }

    return min(64, 38 + ((\strlen($answer) - 5) * 5) + ($rareLetters * 5));
}
