<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Tokenization;

use Crosseno\Core\Grid\CellSymbol;
use Crosseno\LanguageEn\Exception\InvalidEnglishAnswer;
use Crosseno\LanguageEn\Profile\EnglishProfile;
use Crosseno\Lexicon\Language\CellTokenizerInterface;

final readonly class EnglishCellTokenizer implements CellTokenizerInterface
{
    public function profileId(): string
    {
        return EnglishProfile::TOKENIZATION_ID;
    }

    public function tokenize(string $normalizedAnswer): array
    {
        if ($normalizedAnswer === '' || preg_match('//u', $normalizedAnswer) !== 1
            || !\Normalizer::isNormalized($normalizedAnswer, \Normalizer::FORM_C)
            || $normalizedAnswer !== mb_strtoupper($normalizedAnswer, 'UTF-8')) {
            throw new InvalidEnglishAnswer('English grid text must be non-empty NFC uppercase UTF-8.');
        }
        if (preg_match('/^[\p{L}\p{M}]+$/u', $normalizedAnswer) !== 1) {
            throw new InvalidEnglishAnswer('English grid text may contain letters and combining marks only.');
        }
        preg_match_all('/\X/u', $normalizedAnswer, $matches);
        $graphemes = $matches[0];
        if ($graphemes === []) {
            throw new InvalidEnglishAnswer('English grid text contains no cells.');
        }

        /** @var non-empty-list<CellSymbol> $cells */
        $cells = array_map(static fn(string $value): CellSymbol => new CellSymbol($value), $graphemes);

        return $cells;
    }
}
