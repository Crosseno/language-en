# English package architecture

`crosseno/language-en` supplies versioned English language rules and a small,
redistributable language pack. `EnglishLanguagePack` is the runtime façade: it
loads the signed-off manifest and read-only catalog through
`crosseno/lexicon-sqlite`, opens the solver index through
`crosseno/lexicon-index`, and rejects disagreement between their compatibility
tuples before exposing either reader.

Normalization, tokenization, dialect matching, lexical equivalence, and build
eligibility are separate policies. They preserve display text while producing
locale-independent normalized grid text and extended-grapheme-cluster cells.
The exact behavior and Unicode baseline are versioned in `resources/profile.json`.

Artifact compilation is an offline development concern. The curated source,
compiler configuration, source inventory, license records, and generated
manifest/catalog/index are checked in so builds and verification remain
deterministic. Runtime code never invokes the compiler and never mutates the
pack.
