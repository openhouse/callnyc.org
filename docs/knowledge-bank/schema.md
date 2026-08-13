# Graph schema

## Node

Every node has:

- `id`: stable, project-qualified identifier.
- `type`: one of `project`, `organization`, `system`, `dataset`, `source`, `claim`, `question`, `decision`, or `action`.
- `label`: concise human-readable title.

Claim and question nodes may add `status`. Source nodes may add `url` and `accessed`. Prose records in the adjacent folders carry the full evidence and limitations.

## Edge

Every edge has:

- `source`: an existing node ID.
- `type`: the asserted relationship.
- `target`: an existing node ID.

The validator rejects duplicate node IDs and dangling source or target references. Semantic review remains a human gate.

