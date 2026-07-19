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

The code is MIT-licensed. The curated source data is independently dedicated
under CC0-1.0; its complete provenance and transformation inventory is in
`resources/source-inventory.json`. Dataset size and update decisions are in
`resources/pack-policy.json`.

## Data readiness

Readiness level: **development**. Data version `2026.07.1` contains 25 accepted answers, 25 senses, and 25 English clues. Every sense has exactly one clue, and all 25 answers advertise `en` clue coverage.

This pack is intended for deterministic integration, API examples, and small free-form grids. The current example supports deliberately permissive `3×3` through `7×7` requests; success is request- and seed-dependent. Dense, theme-focused, high-quality, large fixed-grid, and general publication requests are outside its readiness level. A successful full-stack build proves integration only.

The next reviewed development target is 1,000 answers with at least one clue per sense. Preview targets are 25,000 answers with two clues for at least 90% of included senses and representative dialect coverage. Production acceptance requires at least 100,000 reviewed eligible answers, two clues for at least 95% of senses, repeatable supported-size generation and quality benchmarks, complete provenance/license review, artifact-size review, and a published support matrix. These are acceptance gates, not claims about the current pack.

See [runtime composition](docs/runtime-composition.md) and [data readiness](docs/data-readiness.md). The executable builder proof is `crosseno/builder/examples/generate-english.php`.

Build and verify deterministically with:

```sh
composer install
composer artifacts:build
composer check
```
