<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Equivalence;

use Crosseno\LanguageEn\Normalization\EnglishAnswerNormalizer;
use Crosseno\Lexicon\Language\LexicalEquivalenceInterface;

final readonly class EnglishLexicalEquivalence implements LexicalEquivalenceInterface
{
    private const IRREGULAR = [
        'CHILDREN' => 'CHILD', 'FEET' => 'FOOT', 'GEESE' => 'GOOSE', 'MEN' => 'MAN',
        'MICE' => 'MOUSE', 'TEETH' => 'TOOTH', 'WENT' => 'GO', 'WOMEN' => 'WOMAN',
    ];

    public function __construct(private EnglishAnswerNormalizer $normalizer = new EnglishAnswerNormalizer()) {}

    public function root(string $normalizedText): string
    {
        $word = $this->normalizer->normalize($normalizedText);
        if (isset(self::IRREGULAR[$word])) {
            return self::IRREGULAR[$word];
        }
        if (mb_strlen($word, 'UTF-8') < 4) {
            return $word;
        }

        // Conservative crossword leakage roots: common regular inflections only.
        if (preg_match('/IES$/u', $word) === 1 && mb_strlen($word, 'UTF-8') > 4) {
            return mb_substr($word, 0, -3, 'UTF-8') . 'Y';
        }
        if (preg_match('/(?:SSES|SHES|CHES|XES|ZES)$/u', $word) === 1) {
            return mb_substr($word, 0, -2, 'UTF-8');
        }
        if (str_ends_with($word, 'ING') && mb_strlen($word, 'UTF-8') > 5) {
            return $this->undouble(mb_substr($word, 0, -3, 'UTF-8'));
        }
        if (str_ends_with($word, 'ED') && mb_strlen($word, 'UTF-8') > 4) {
            return $this->undouble(mb_substr($word, 0, -2, 'UTF-8'));
        }
        if (str_ends_with($word, 'S') && !str_ends_with($word, 'SS')) {
            return mb_substr($word, 0, -1, 'UTF-8');
        }

        return $word;
    }

    public function equivalent(string $leftNormalizedText, string $rightNormalizedText): bool
    {
        return $this->root($leftNormalizedText) === $this->root($rightNormalizedText);
    }

    private function undouble(string $stem): string
    {
        return preg_match('/([^AEIOU])\1$/u', $stem) === 1
            ? mb_substr($stem, 0, -1, 'UTF-8')
            : $stem;
    }
}
