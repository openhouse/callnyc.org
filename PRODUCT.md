# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Existing Dockerized PHP 8.2 and Apache application with MySQL 8 for the preserved archive; plain server-rendered HTML, authored CSS, and minimal JavaScript for the contemporary publication layer. This is an inferred implementation choice accepted through Jamie's instruction to proceed from the archival repository; it preserves the working Dokku contract without introducing an unrelated framework migration.

## Users

- New Yorkers who want to understand what constituent-services work Council district offices perform and why current public data is unavailable.
- Residents who arrive seeking help and need an immediate route to current, official Council and NYC311 services.
- Civic technologists, open-data advocates, journalists, researchers, and public officials evaluating the historical project and the present transparency request.
- Future maintainers who need a source-bounded account of the project's lineage, evidence, decisions, and corrections.

## Product Purpose

CallNYC.org is the contemporary steward of an archived, unofficial 2016 civic-data project. It preserves a usable reconstruction of the historical interface, documents the disappearance of a current public constituent-services feed, and gives visitors a concrete way to ask NYC Council and NYC OTI to restore a privacy-protected, documented, regularly refreshed Council Connect publication through NYC Open Data.

Success means that a first-time visitor can quickly distinguish archive from current guidance, understand what the evidence does and does not establish, reach official help if they need it, inspect the historical project, and act on the transparency request.

## Positioning

CallNYC joins a working historical civic-data interface to a source-bounded public argument about the infrastructure that once made that interface current. The archive is not nostalgia or a simulated live service; it is evidence placed inside a present-day campaign for renewed transparency.

## Operating Context

- The archival application was created from NYC Council constituent-services data and remains an unofficial project.
- NYC Open Data now labels the public Council constituent-services dataset as historical and covering 2015–2025.
- Current Council materials show that district offices continue to use Council Connect, a Council-wide constituent-service database.
- No current Council Connect export has been located on NYC Open Data. No official explanation for the missing public connection has been found.
- The public advocacy addressees are NYC Council, which owns the casework system and constituent-services program, and NYC OTI, which operates the Open Data program with publishing organizations.
- The project is deployed with Dokku. Production must not move until an exact candidate has passed staging, route, asset, accessibility, and archive-fidelity checks.

## Capabilities and Constraints

- The root surface is a contemporary archive-and-advocacy publication, not a current Council-member ranking or service directory.
- The historical application remains available behind an explicit archive boundary and carries a global warning on every historical route.
- Current-help links must point to official NYC Council and NYC311 surfaces.
- Advocacy language must ask for privacy-protected aggregate or de-identified publication, not raw constituent case files.
- Historical statistics, officeholders, categories, contact information, and guidance must never be presented as current.
- Claims must distinguish observation, source, inference, and open question.
- The knowledge bank is project-internal by default. Only deliberately approved public-safe records may be projected into the site.
- Mutating archival endpoints remain disabled in archive mode.
- Open decision: the eventual form of direct advocacy participation may be a dataset request, testimony packet, letter campaign, or coalition action. The initial release links to official channels and publishes the request without claiming a coalition that does not yet exist.

## Brand Commitments

- Name: CallNYC.
- Preserve the original CallNYC logo and recognizable civic-red identity inside the historical application.
- The contemporary header inherits the visual language of `jamieburk.art@develop`: clear frames, strong accessible typography, ink and paper neutrals, Broadway blue, ochre, and restrained rule-based structure. It does not inherit Jamie's personal navigation or turn the civic project into portfolio branding.
- Public prose follows Jamie Burkart's “Tender Civic Architecture” voice: warm, precise, civic-lyrical, source-bounded, and oriented toward agency. Keep the warmth, shorten the runway, trust the strongest sentence.
- The Politico New York coverage is evidence, not endorsement. Any visual excerpt must be bounded, attributed, accessible, and linked to the archived source.

## Evidence on Hand

- Git lineage beginning with the original CallNYC implementation and the `feature/archive-2026` reconstruction branch.
- Archived Politico New York PDF at `data/media/Politico-Website-provides-new-information-about-council-members-focus.pdf`.
- Historical CallNYC logo and project media under `data/media/` and the repository root.
- Historical district photographs and reconstructed application data under `data/`.
- The official NYC Open Data constituent-services dataset and metadata.
- Current NYC Council references to Council Connect as the Council-wide constituent-service database.
- In-repository, live-site, and Wayback screenshots used to evaluate reconstruction fidelity.
- No official source presently establishing why a current Council Connect export was not continued. Future work must not invent one.

## Product Principles

1. The present tense belongs to stewardship and advocacy, not obsolete statistics.
2. Keep the person alive inside the data: protect constituent privacy while making institutional work visible.
3. Preserve lineage and uncertainty; distinguish restoration, reconstruction, and new publication.
4. Give every visitor one usable entrance and a next step appropriate to their situation.
5. Access to evidence is not publication permission; public projection remains an explicit human decision.

## Accessibility & Inclusion

The public surface and archive boundary must support keyboard navigation, visible focus, semantic landmarks, meaningful alternative text, sufficient color contrast, responsive reflow, reduced-motion preferences, and plain-language routes to current official help. The design must remain legible without externally hosted fonts or icon stylesheets.
