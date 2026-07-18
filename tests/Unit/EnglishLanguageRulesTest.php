<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Tests\Unit;

use Crosseno\LanguageEn\Dialect\EnglishDialectPolicy;
use Crosseno\LanguageEn\Equivalence\EnglishLexicalEquivalence;
use Crosseno\LanguageEn\Normalization\EnglishAnswerNormalizer;
use Crosseno\LanguageEn\Profile\EnglishProfile;
use Crosseno\LanguageEn\Tokenization\EnglishCellTokenizer;
use Crosseno\Lexicon\Language\LanguageCode;
use Crosseno\Lexicon\Language\LanguageMatchingPolicy;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class EnglishLanguageRulesTest extends TestCase
{
    #[DataProvider('answerForms')]
    public function testNormalizationAndTokenization(string $display, string $grid, int $cells): void
    {
        $normalizer = new EnglishAnswerNormalizer();
        $tokenizer = new EnglishCellTokenizer();

        $normalized = $normalizer->normalize($display);

        self::assertSame($grid, $normalized);
        self::assertCount($cells, $tokenizer->tokenize($normalized));
        self::assertSame(EnglishProfile::NORMALIZATION_ID, $normalizer->profileId());
        self::assertSame(EnglishProfile::TOKENIZATION_ID, $tokenizer->profileId());
    }

    /** @return iterable<string, array{string, string, int}> */
    public static function answerForms(): iterable
    {
        yield 'space and casing' => ['ice cream', 'ICECREAM', 8];
        yield 'hyphens' => ['mother-in-law', 'MOTHERINLAW', 11];
        yield 'straight apostrophe' => ["don't", 'DONT', 4];
        yield 'curly apostrophe' => ["don’t", 'DONT', 4];
        yield 'punctuation' => ['St. James!', 'STJAMES', 7];
        yield 'nfc' => ["cafe\u{0301}", 'CAFÉ', 4];
    }

    public function testInflectionRootsAreConservativeAndDeterministic(): void
    {
        $equivalence = new EnglishLexicalEquivalence();

        self::assertTrue($equivalence->equivalent('CAT', 'cats'));
        self::assertTrue($equivalence->equivalent('RUN', 'running'));
        self::assertTrue($equivalence->equivalent('CHILD', 'children'));
        self::assertFalse($equivalence->equivalent('READ', 'reader'));
    }

    public function testDialectPolicyUsesLookupWithoutCrossRegionFallback(): void
    {
        $policy = new EnglishDialectPolicy();
        $available = [new LanguageCode('en'), new LanguageCode('en-GB'), new LanguageCode('en-US')];

        self::assertSame(LanguageMatchingPolicy::Lookup, $policy->matchingPolicy());
        self::assertSame('en-GB', $policy->select(new LanguageCode('en-GB'), $available)?->value);
        self::assertSame('en', $policy->select(new LanguageCode('en-AU'), $available)?->value);
        self::assertNull($policy->select(new LanguageCode('pl'), $available));
    }
}
