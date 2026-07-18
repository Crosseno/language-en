<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Profile;

final class EnglishProfile
{
    public const NORMALIZATION_ID = 'en.nfc.upper-grid.unicode15.1.v1';
    public const TOKENIZATION_ID = 'en.grapheme-cells.unicode15.1.v1';
    public const UNICODE_VERSION = '15.1.0';
    public const ICU_REFERENCE_VERSION = '74.2';
    public const DIALECT_FALLBACK = 'rfc4647_lookup';

    private function __construct() {}
}
