# crosseno/language-en

Versioned English language rules and a legally redistributable development lexicon for Crosseno.

The package requires PHP `^8.5`, Intl, mbstring, and the Crosseno core, lexicon, SQLite catalog, and solver-index packages.

```php
use Crosseno\Core\ResourceLimits;
use Crosseno\LanguageEn\EnglishLanguagePack;

$pack = EnglishLanguagePack::load(ResourceLimits::standard());
$gridText = $pack->normalizer()->normalize('ice cream'); // ICECREAM
$cells = $pack->tokenizer()->tokenize($gridText);

// Full runtime composition, without parsing artifacts in the application:
$catalog = $pack->catalog();
$solverIndex = $pack->solverIndex();
$ordinalKeys = $pack->answerKeysByOrdinal();
$identity = $pack->runtimeIdentity();
$coverage = $pack->clueCoverage();
```

The checked-in artifacts are under `resources/`: `manifest.json`,
`catalog.sqlite`, and `solver.idx`. `profile.json` is the normative profile
publication. It pins NFC and locale-independent Unicode uppercase behavior to
Unicode 15.1, removes Unicode separator and punctuation categories from grid
text, and assigns one extended grapheme cluster per cell. It does not
transliterate. Straight and curly apostrophes, hyphens, spaces, and other
punctuation remain in display text but do not occupy cells.

Dialect tags are canonical BCP 47 tags. Selection uses RFC 4647 lookup, so an
exact region is preferred and may fall back to `en`; it never falls sideways
from one region to another. Thus `theater` is `en-US`, while `theatre` is
available for `en-GB`, `en-CA`, and `en-AU`.

The code is MIT-licensed. The native source data is independently dedicated
under CC0-1.0; its source and technical transformation inventory is in
`resources/source-inventory.json`. The batch plan and editorial status
are recorded in `resources/source/batch-plan.json` and
`resources/review-ledger.json`. Automated draft measurements and known clue
quality are in `resources/draft-quality-report.json`; the multi-request
generation matrix is in `resources/benchmark-report.json`. Dataset size and
update decisions are in `resources/pack-policy.json`.

## Editorial disclosure

Definitions and clue candidates were initially drafted with generative-AI
assistance. Crosseno reviewed, edited, approved, and assumes editorial
responsibility for the published content.

## Data readiness

Readiness level: **expanded development draft**. Data version `2026.07.3`
contains 1,000 compiler-accepted answers, 1,000 senses, and 2,000 English draft
clues. Every sense has exactly two clues, and all answers advertise `en` clue
coverage. Crosseno completed editorial review of the complete batch on
2026-07-26.

This pack is intended for deterministic integration, API examples, and
small-grid benchmark work. Its answers span 3 through 10 cells. Every answer
has an individually authored sense definition and two meaningfully distinct
semantic clue candidates. Crosseno has accepted the content for the development
release; final contractual, legal, packaging, and release checks remain.

The next gate is final release review and publication of the 1,000-answer
development pack. Preview targets are 25,000
answers with two approved clues for at least 90% of included senses and
representative dialect coverage. These are acceptance gates, not claims about
the current pack.

See [runtime composition](docs/runtime-composition.md) and [data readiness](docs/data-readiness.md). The executable builder proof is `crosseno/builder/examples/generate-english.php`.

Build and verify deterministically with:

```sh
composer install
composer source:build
composer artifacts:build
composer check
```
