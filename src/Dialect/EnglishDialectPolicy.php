<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Dialect;

use Crosseno\Lexicon\Language\LanguageCode;
use Crosseno\Lexicon\Language\LanguageMatchingPolicy;
use Crosseno\Lexicon\Language\Rfc4647LanguageMatcher;

final readonly class EnglishDialectPolicy
{
    public function __construct(private Rfc4647LanguageMatcher $matcher = new Rfc4647LanguageMatcher()) {}

    /** @param iterable<LanguageCode> $available */
    public function select(LanguageCode $requested, iterable $available): ?LanguageCode
    {
        if ($requested->value !== 'en' && !str_starts_with($requested->value, 'en-')) {
            return null;
        }

        return $this->matcher->select($requested, $available, LanguageMatchingPolicy::Lookup);
    }

    public function matchingPolicy(): LanguageMatchingPolicy
    {
        return LanguageMatchingPolicy::Lookup;
    }
}
