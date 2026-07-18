<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn;

use Crosseno\Lexicon\Candidate\CandidateQuery;
use Crosseno\Lexicon\Candidate\CandidateSet;
use Crosseno\Lexicon\Contract\LexiconInterface;
use Crosseno\Lexicon\Identity\LexemeKey;
use Crosseno\Lexicon\Identity\StableAnswerKey;
use Crosseno\Lexicon\Identity\StableSenseKey;
use Crosseno\Lexicon\Record\AnswerRecord;
use Crosseno\LexiconIndex\BinarySolverIndex;
use Crosseno\LexiconSqlite\Catalog\SqliteLexicalCatalog;

final readonly class EnglishLexicon implements LexiconInterface
{
    public function __construct(
        private SqliteLexicalCatalog $catalog,
        private BinarySolverIndex $index,
    ) {}

    public function answer(StableAnswerKey $key): ?AnswerRecord
    {
        return $this->catalog->answer($key);
    }

    public function answersForLexeme(LexemeKey $key): array
    {
        return $this->catalog->answersForLexeme($key);
    }

    public function answersForSense(StableSenseKey $key): array
    {
        return $this->catalog->answersForSense($key);
    }

    public function candidates(CandidateQuery $query): CandidateSet
    {
        return $this->index->candidates($query);
    }
}
