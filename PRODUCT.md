# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Existing Dockerized PHP 8.2 and Apache application with MySQL 8 for the preserved archive; plain server-rendered HTML, inherited Materialize CSS, and minimal JavaScript. This preserves the working Dokku contract and the original interaction model without introducing a framework migration.

## Users

- New Yorkers who want to understand the historical constituent-services record and why no current public export has been located.
- Residents who arrive seeking help and need an immediate route to current, official Council and NYC311 services.
- Civic technologists, open-data advocates, journalists, researchers, and public officials evaluating the historical project and the present transparency request.
- Future maintainers who need a source-bounded account of the project's lineage, evidence, decisions, and corrections.

## Product Purpose

CallNYC.org is the contemporary steward of an archived, unofficial 2016 civic-data project. It preserves a usable reconstruction of the historical interface, documents the bounded finding that no current public Council Connect export has been located, and gives visitors a concrete way to ask NYC Council and NYC OTI to restore a privacy-protected, documented, regularly refreshed publication through NYC Open Data.

Success means that a first-time visitor immediately experiences the original project, can distinguish its 2016 evidence from current guidance, understands the bounded transparency request, and can act without leaving the page.

## Positioning

CallNYC treats the working historical civic-data interface as the primary evidence. A compact preservation header gives that artifact a present-day boundary and action without replacing its visual language or inserting an explanatory homepage.

## Operating Context

- The archival application was created from NYC Council constituent-services data and remains an unofficial project.
- NYC Open Data now labels the public Council constituent-services dataset as historical and covering 2015–2025.
- A current Council district-office job posting instructs that office's staff to enter constituent cases in Council Connect. This does not establish uniform practice across all offices.
- No current Council Connect export has been located on NYC Open Data. No official explanation for the missing public connection has been found.
- The public advocacy addressees are NYC Council, which owns the casework system and constituent-services program, and NYC OTI, which operates the Open Data program with publishing organizations.
- The project is deployed with Dokku. Production must not move until an exact candidate has passed staging, route, asset, accessibility, and archive-fidelity checks.

## Capabilities and Constraints

- The historical application remains at the public root and is not a current Council-member ranking or service directory.
- Every route carries one compact archived/unofficial header; no additional public content page is introduced.
- Current-help links must point to official NYC Council and NYC311 surfaces.
- Advocacy language must ask for privacy-protected aggregate or de-identified publication, not raw constituent case files.
- Historical statistics, officeholders, categories, contact information, and guidance must never be presented as current.
- Claims must distinguish observation, source, inference, and open question.
- The knowledge bank is project-internal by default. Only deliberately approved public-safe records may be projected into the site.
- Mutating archival endpoints remain disabled in archive mode.
- The initial action is a pre-populated email to the current Council Technology chair's public office and the NYC Open Data team. It does not claim a coalition that does not yet exist.

## Brand Commitments

- Name: CallNYC.
- Preserve the original CallNYC logo, Roboto typography, Materialize components, civic-red identity, fixed sidebar, category tree, and ranking cards across the whole public surface.
- The compact preservation header uses only that inherited visual language. It does not borrow Jamie's portfolio styling or turn the civic project into portfolio branding.
- Public prose follows Jamie Burkart's “Tender Civic Architecture” voice: warm, precise, civic-lyrical, source-bounded, and oriented toward agency. Keep the warmth, shorten the runway, trust the strongest sentence.
- The Politico New York coverage is evidence, not endorsement. Any visual excerpt must be bounded, attributed, accessible, and linked to the archived source.

## Evidence on Hand

- Git lineage beginning with the original CallNYC implementation and the `feature/archive-2026` reconstruction branch.
- Archived Politico New York PDF at `data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf`.
- Historical CallNYC logo and project media under `data/media/` and the repository root.
- Historical district photographs and reconstructed application data under `data/`.
- The official NYC Open Data constituent-services dataset and metadata.
- A current NYC Council district-office reference to Council Connect, bounded to that office.
- Preserved application code, predecessor-archive screenshots, and launch-C browser captures used to evaluate reconstruction fidelity.
- No official source presently establishing why a current Council Connect export was not continued. Future work must not invent one.

## Product Principles

1. The present tense belongs to stewardship and advocacy, not obsolete statistics.
2. Keep the person alive inside the data: protect constituent privacy while making institutional work visible.
3. Preserve lineage and uncertainty; distinguish restoration, reconstruction, and new publication.
4. Give every visitor one usable entrance and a next step appropriate to their situation.
5. Access to evidence is not publication permission; public projection remains an explicit human decision.

## Accessibility & Inclusion

The public surface and archive boundary must support keyboard navigation, visible focus, semantic landmarks, meaningful alternative text, sufficient color contrast, responsive reflow, reduced-motion preferences, and plain-language routes to current official help. The design must remain legible without externally hosted fonts or icon stylesheets.
