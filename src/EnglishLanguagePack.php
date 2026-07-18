<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn;

use Crosseno\Core\ResourceLimits;
use Crosseno\LanguageEn\Equivalence\EnglishLexicalEquivalence;
use Crosseno\LanguageEn\Exception\LanguageEnException;
use Crosseno\LanguageEn\Normalization\EnglishAnswerNormalizer;
use Crosseno\LanguageEn\Tokenization\EnglishCellTokenizer;
use Crosseno\Lexicon\Contract\LanguagePackInterface;
use Crosseno\Lexicon\Contract\LexiconInterface;
use Crosseno\Lexicon\Language\AnswerNormalizerInterface;
use Crosseno\Lexicon\Language\CellTokenizerInterface;
use Crosseno\Lexicon\Language\LexicalEquivalenceInterface;
use Crosseno\Lexicon\Manifest\LanguagePackManifest;
use Crosseno\Lexicon\Manifest\LanguagePackMetadata;
use Crosseno\LexiconIndex\BinarySolverIndex;
use Crosseno\LexiconIndex\ReaderLimits;
use Crosseno\LexiconSqlite\Catalog\SchemaInspector;
use Crosseno\LexiconSqlite\CatalogLimits;
use Crosseno\LexiconSqlite\Connection\ReadOnlyConnectionFactory;
use Crosseno\LexiconSqlite\Pack\PackLoader;

final readonly class EnglishLanguagePack implements LanguagePackInterface
{
    private function __construct(
        private LanguagePackManifest $loadedManifest,
        private EnglishLexicon $loadedLexicon,
        private EnglishAnswerNormalizer $answerNormalizer,
        private EnglishCellTokenizer $cellTokenizer,
        private EnglishLexicalEquivalence $lexicalEquivalence,
    ) {}

    public static function load(
        ResourceLimits $resourceLimits,
        ?CatalogLimits $catalogLimits = null,
        ?ReaderLimits $readerLimits = null,
        ?string $packRoot = null,
    ): self {
        $root = $packRoot ?? \dirname(__DIR__) . '/resources';
        $loaded = (new PackLoader(
            new ReadOnlyConnectionFactory(),
            new SchemaInspector(),
            $catalogLimits ?? CatalogLimits::standard(),
            $resourceLimits,
        ))->load($root);
        $index = new BinarySolverIndex($root . '/solver.idx', $readerLimits ?? new ReaderLimits());
        self::assertIndexAgreement($loaded->manifest, $index);

        return new self(
            $loaded->manifest,
            new EnglishLexicon($loaded->catalog, $index),
            new EnglishAnswerNormalizer(),
            new EnglishCellTokenizer(),
            new EnglishLexicalEquivalence(),
        );
    }

    public function metadata(): LanguagePackMetadata
    {
        return $this->loadedManifest->metadata;
    }

    public function manifest(): LanguagePackManifest
    {
        return $this->loadedManifest;
    }

    public function lexicon(): LexiconInterface
    {
        return $this->loadedLexicon;
    }

    public function normalizer(): AnswerNormalizerInterface
    {
        return $this->answerNormalizer;
    }

    public function tokenizer(): CellTokenizerInterface
    {
        return $this->cellTokenizer;
    }

    public function equivalence(): LexicalEquivalenceInterface
    {
        return $this->lexicalEquivalence;
    }

    private static function assertIndexAgreement(LanguagePackManifest $manifest, BinarySolverIndex $index): void
    {
        $metadata = $manifest->metadata;
        $actual = $index->metadata;
        if ($actual->packId !== $metadata->packId
            || $actual->dataVersion !== $metadata->dataVersion
            || $actual->tokenizationProfileId !== $metadata->tokenizationProfileId
            || $actual->stableKeyAlgorithmMajor !== $metadata->stableKeyAlgorithmVersion->major
            || $actual->answerCount !== $manifest->recordCount
            || !hash_equals($actual->stableKeyDigest, $manifest->stableKeyDigest)
            || $actual->ordinalSpaceId !== $manifest->ordinalSpaceId) {
            throw new LanguageEnException('The English catalog, manifest, and solver index do not identify the same release.');
        }
    }
}
