# CallNYC knowledge bank

This project-internal wiki is a typed, source-bounded record of what CallNYC knows, what it infers, what it has decided, and what remains unanswered. Stable identifiers let this graph compose with Jamie Burkart’s broader connected knowledge system when a reader has access to more than one project.

The graph is not automatically a public website. Repository access, source access, evidentiary support, sensitivity, and publication authorization are separate conditions. The public site may use only the nodes listed in `public-registry.json`, and even that registry requires human review before publication.

## Read the graph

- `data/graph.json` is the machine-readable node-and-edge layer.
- `claims/` distinguishes sourced statements from interpretation.
- `questions/` preserves unresolved questions without laundering them into claims.
- `sources/` records provenance, scope, and limits.
- `decisions/` records consequential project choices and their reasons.
- `corrections.md` is the correction path and change ledger.
- `public-registry.json` is the explicit, human-reviewed public projection.

Validate the graph from the repository root:

```sh
php bin/validate-knowledge.php
```

## Governance

1. Prefer primary official sources for claims about current government systems.
2. Record the exact source and access date; do not collapse “not found” into “does not exist.”
3. Keep historical descriptions inside their historical period.
4. Protect constituent privacy. This project advocates aggregate or de-identified publication, never public case details.
5. Add a node to the public registry only after reviewing accuracy, rights, sensitivity, attribution, and present-tense wording.

