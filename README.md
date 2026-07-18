# crosseno/language-en

Versioned English language rules and a legally redistributable development lexicon for Crosseno.

The package requires PHP `^8.5`, Intl, mbstring, and the Crosseno core, lexicon, SQLite catalog, and solver-index packages.

```php
use Crosseno\Core\ResourceLimits;
use Crosseno\LanguageEn\EnglishLanguagePack;

$pack = EnglishLanguagePack::load(ResourceLimits::standard());
$gridText = $pack->normalizer()->normalize('ice cream'); // ICECREAM
$cells = $pack->tokenizer()->tokenize($gridText);
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

Build and verify deterministically with:

```sh
composer install
composer artifacts:build
composer check
```
