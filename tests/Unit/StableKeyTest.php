<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Tests\Unit;

use Crosseno\LanguageEn\Normalization\EnglishAnswerNormalizer;
use Crosseno\Lexicon\Identity\StableKeyAlgorithmVersion;
use Crosseno\Lexicon\Identity\StableKeyFactory;
use PHPUnit\Framework\TestCase;

final class StableKeyTest extends TestCase
{
    public function testEquivalentDisplayFormsHaveOneStableAnswerKey(): void
    {
        $normalizer = new EnglishAnswerNormalizer();
        $keys = new StableKeyFactory();

        $straight = $keys->answer('crosseno.language-en', [$normalizer->normalize("don't")], StableKeyAlgorithmVersion::v1());
        $curly = $keys->answer('crosseno.language-en', [$normalizer->normalize("don’t")], StableKeyAlgorithmVersion::v1());

        self::assertSame($straight->coreKey->value, $curly->coreKey->value);
        self::assertSame(
            'xk1:answer:crosseno.language-en:c07239331bbeca1fa46e36f050fa0afd5be0b369e6ccd64bfe321a2d0a6a45f6',
            $straight->coreKey->value,
        );
    }
}
