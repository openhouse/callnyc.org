# Decision 0002: The artifact is the public front door

- **ID:** `decision.artifact-first-launch-b`
- **Date:** 2026-08-13
- **Status:** Adopted for launch-B review
- **Supersedes:** `decision.contemporary-successor`

## Decision

Make the reconstructed 2016 CallNYC application the sole public experience at `/`. Keep `/archive/2016/` as an inbound compatibility alias, not as a second destination, and generate all new artifact navigation on the original root-level routes.

Add only a compact preservation notice above the original red CallNYC banner. The notice identifies the project as archived and unofficial, records the 24-hour sprint context, presents the preserved Politico New York thumbnail, links residents to current Council contacts, and opens a pre-addressed email asking the Council Data Team and NYC Open Data Team to restore privacy-protected constituent-services data publishing.

Production remains unchanged until an exact candidate passes no-index staging, database-backed route, asset, responsive, accessibility, and rollback checks.

## Why

A civic-space hiring reader should encounter the affordances Jamie built, not a contemporary explanation placed before them. The artifact demonstrates the work. The new layer supplies only the facts and action the historical interface cannot supply for itself.
