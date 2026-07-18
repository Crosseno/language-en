<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Build;

use Crosseno\Compiler\Import\RawLexicalRecord;
use Crosseno\Compiler\Pipeline\EligibilityPolicyInterface;

final readonly class EnglishEligibilityPolicy implements EligibilityPolicyInterface
{
    public function rejectionReason(string $normalizedAnswer, array $cells, RawLexicalRecord $record): ?string
    {
        $count = \count($cells);
        if ($count < 2) {
            return 'answer_too_short';
        }
        if ($count > 32) {
            return 'answer_too_long';
        }
        if (preg_match('/^[\p{L}\p{M}]+$/u', $normalizedAnswer) !== 1) {
            return 'ineligible_character';
        }

        return null;
    }
}
