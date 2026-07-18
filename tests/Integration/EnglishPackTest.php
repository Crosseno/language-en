<?php

declare(strict_types=1);

namespace Crosseno\LanguageEn\Tests\Integration;

use Crosseno\Core\Grid\CellSymbol;
use Crosseno\Core\ResourceLimits;
use Crosseno\LanguageEn\EnglishLanguagePack;
use Crosseno\LanguageEn\Profile\EnglishProfile;
use Crosseno\Lexicon\Candidate\CandidateConstraints;
use Crosseno\Lexicon\Candidate\CandidateOrdering;
use Crosseno\Lexicon\Candidate\CandidateQuery;
use Crosseno\Lexicon\Pattern\CandidatePattern;
use Crosseno\Lexicon\Pattern\PatternCell;
use PHPUnit\Framework\TestCase;

final class EnglishPackTest extends TestCase
{
    public function testManifestCatalogAndIndexAgreeAndCanSupplyCandidates(): void
    {
        $pack = EnglishLanguagePack::load(ResourceLimits::standard());
        $manifest = $pack->manifest();

        self::assertSame('en', $manifest->metadata->answerLanguage->value);
        self::assertSame(EnglishProfile::NORMALIZATION_ID, $manifest->metadata->normalizationProfileId);
        self::assertSame(EnglishProfile::TOKENIZATION_ID, $manifest->metadata->tokenizationProfileId);
        self::assertSame(25, $manifest->recordCount);
        self::assertSame(0, $manifest->rejectionCount);
        self::assertCount(1, $manifest->sources());

        $result = $pack->lexicon()->candidates(new CandidateQuery(
            new CandidatePattern([
                PatternCell::fixed(new CellSymbol('C')),
                PatternCell::unknown(),
                PatternCell::fixed(new CellSymbol('T')),
            ]),
            CandidateConstraints::permissive(),
            10,
            CandidateOrdering::RankDescending,
        ));

        self::assertSame(1, $result->totalMatches);
        self::assertSame('cat', $result->records()[0]->answer->displayText);
        self::assertCount(3, $result->records()[0]->answer->cells());
    }

    public function testEveryManifestSourceHasACompatibleLocalLicenseNotice(): void
    {
        $pack = EnglishLanguagePack::load(ResourceLimits::standard());
        foreach ($pack->manifest()->sources() as $source) {
            self::assertSame('redistributable', $source->redistributionStatus);
            self::assertFileExists(\dirname(__DIR__, 2) . '/resources/LICENSES/' . $source->licenseExpression . '.txt');
        }
    }
}
