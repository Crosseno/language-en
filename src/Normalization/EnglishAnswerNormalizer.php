<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Normalization;

use Crosseno\LanguageEn\Exception\InvalidEnglishAnswer;
use Crosseno\LanguageEn\Profile\EnglishProfile;
use Crosseno\Lexicon\Language\AnswerNormalizerInterface;

final readonly class EnglishAnswerNormalizer implements AnswerNormalizerInterface
{
    public function __construct()
    {
        if (!self::supportsIcuDataVersion(INTL_ICU_DATA_VERSION)) {
            throw new InvalidEnglishAnswer(\sprintf(
                'English profile %s requires ICU data %s; runtime provides %s.',
                EnglishProfile::NORMALIZATION_ID,
                EnglishProfile::ICU_REFERENCE_VERSION,
                INTL_ICU_DATA_VERSION,
            ));
        }
    }

    private static function supportsIcuDataVersion(string $version): bool
    {
        return $version === EnglishProfile::ICU_REFERENCE_VERSION;
    }

    public function profileId(): string
    {
        return EnglishProfile::NORMALIZATION_ID;
    }

    public function normalize(string $answer): string
    {
        if ($answer === '' || preg_match('//u', $answer) !== 1
            || preg_match('/[\x00-\x1F\x7F]/u', $answer) === 1) {
            throw new InvalidEnglishAnswer('English answer text must be non-empty valid UTF-8 without control characters.');
        }

        $nfc = \Normalizer::normalize($answer, \Normalizer::FORM_C);
        if (!\is_string($nfc)) {
            throw new InvalidEnglishAnswer('English answer text could not be NFC-normalized.');
        }

        // Separators and punctuation are display-only; symbols and numbers are not
        // silently discarded because that could merge semantically distinct answers.
        $grid = preg_replace('/[\p{Z}\p{P}]+/u', '', $nfc);
        if (!\is_string($grid) || $grid === '') {
            throw new InvalidEnglishAnswer('English answer text contains no grid cells.');
        }
        $uppercase = mb_strtoupper($grid, 'UTF-8');
        $normalized = \Normalizer::normalize($uppercase, \Normalizer::FORM_C);

        return \is_string($normalized)
            ? $normalized
            : throw new InvalidEnglishAnswer('English grid text could not be NFC-normalized.');
    }
}
