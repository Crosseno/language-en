# English data readiness

| Measure | Current development pack |
|---|---:|
| Data version | `2026.07.3` |
| Compiler-accepted answers | 1,000 |
| Editorially approved answers | 1,000 |
| Senses | 1,000 |
| English draft clues | 2,000 |
| Clues per sense | exactly 2 |
| Answers with English clue coverage | 1,000 (100%) |
| Cell-length range | 3–10 |
| Catalog size | 3,616,768 bytes |
| Solver index size | 483,321 bytes |

The pack exists to verify deterministic compilation and the real runtime path
at the first scale milestone. Its thematic vocabulary, answer-specific
definitions, and semantic clues passed Crosseno editorial review on 2026-07-26.
The source also passed schema, eligibility, identity, and
normalization-collision checks with zero compiler rejections.

Automated checks report zero direct normalized-leakage failures, zero exact
clue duplicates, and 2,000 unique clue texts. Each clue pair also passes a
token-similarity threshold and a metadata-template rejection check. These
checks establish draft quality, not factual or editorial approval. See
`resources/draft-quality-report.json`.

The 1,000 answers are distributed by cell count as follows: 60 of length 3,
245 of length 4, 255 of length 5, 235 of length 6, 128 of length 7, 51 of
length 8, 17 of length 9, and 9 of length 10. A supported generation envelope
still requires representative benchmark results; individual dimensions and
seeds can be unsatisfiable.

The current deterministic matrix covers `5×5`, `7×7`, and `9×9` dimensions;
fast and balanced strategies; all three difficulty levels; and seeds `1`, `42`,
and `12345`. All 54 requests succeeded and passed the example’s repeated-build
reproduction check. Three seeds per group demonstrate integration compatibility
but are not enough to claim a general production success rate. Detailed entry
and explored-node totals are in `resources/benchmark-report.json`.

Targets:

1. Complete final contractual, legal, packaging, and release checks for the reviewed 1,000-answer pack.
2. Preview: 25,000 answers, two clues for at least 90% of senses, representative dialects, and published benchmark results.
3. Production: 100,000 reviewed eligible answers, two clues for at least 95% of senses, stable supported-size/strategy success gates, full provenance and redistribution review, artifact-size acceptance, and an installation/support matrix.

Counts alone never promote readiness. Review quality, clue leakage, difficulty calibration, dialect behavior, licensing, reproducibility, and generation benchmarks are mandatory acceptance inputs.
