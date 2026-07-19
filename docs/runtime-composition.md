# Runtime composition

`EnglishLanguagePack::load()` validates the checked-in manifest and artifact containment, byte lengths, SHA-256 hashes, SQLite schema and integrity, solver-index checksum, stable-key digest, data version, tokenization profile, record count, and ordinal-space ID.

The returned object implements `RuntimeLanguagePackInterface`. It exposes language metadata and services, the storage-neutral lexical clue catalog, solver index, stable answer keys in exact global ordinal order, artifact/ordinal identity, and aggregate clue coverage. Applications and builder composition should use these accessors rather than reading `catalog.sqlite`, `solver.idx`, or language-package internals.
