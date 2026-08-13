# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Stack

Dockerized PHP 8.2 and Apache with MySQL 8. The public product is the original server-rendered, database-backed CallNYC explorer, its category navigation, search, ranking cards, and historical routes. Launch B adds only a small PHP preservation notice and authored CSS within that artifact; it does not introduce a separate successor application, framework, or landing-page layer.

## Users

- New Yorkers exploring what the published 2016 constituent-services records showed about district-office activity.
- Residents who may mistake the historical interface for a current service and need a clear route to their current Council member.
- Civic technologists, open-data advocates, journalists, researchers, Council staff, and NYC OTI staff considering the public value and privacy requirements of renewed constituent-services data publishing.
- Future stewards who need the historical interface, its source boundaries, and the present advocacy request kept distinct.

## Product Purpose

CallNYC.org is a preserved, archived, and unofficial civic-data artifact. The working 2016 explorer is itself the public argument: visitors use the historical interface, encounter its contemporary Politico record, and can ask NYC Council and NYC OTI to restore privacy-protected constituent-services data publishing.

Success means visitors can use the artifact immediately while understanding, before its rankings, that the records are historical, the interface does not describe current members or services, and current help belongs with official Council channels. The public ask must never imply publication of identifiable case files.

## Positioning

CallNYC is one public artifact, not an archive hidden behind a replacement homepage. The original database-backed interaction remains visually and functionally authoritative. A compact preservation notice supplies only the minimum present-day context, current-help route, documentary record, and privacy-protective restoration request the historical interface now needs.

The prior blue-and-serif open-letter successor direction and its Open Letter A/B/C comps are superseded by Launch B and are non-governing. They remain historical design-process records only; they do not define this product, its root composition, or future extensions.

## Operating Context

- CallNYC was built from constituent-services records released by NYC Council in 2016 and remains an unofficial project.
- The public NYC Open Data constituent-services dataset is now labeled historical. Its records and every ranking derived from them must stay in historical tense.
- The public request is addressed to NYC Council and NYC OTI data teams and asks for renewed publishing with privacy protections.
- The archived Politico New York page records contemporary coverage of CallNYC; it is evidence, not endorsement.
- The application is deployed with Dokku. Production movement remains a separate human authorization after exact-candidate verification.

## Capabilities and Constraints

- The root path and preserved aliases resolve through the same database-backed historical explorer.
- Visitors can browse categories, search the archive, inspect ranked historical district-office cards, follow historical service tags, and open the archived Politico record.
- The preservation notice must remain compact enough that the original CallNYC banner and working interface are present in the first viewport.
- Current-help links must point to official NYC Council surfaces; historical Council-member links and records must not be represented as current.
- Advocacy language must ask for privacy-protected aggregate, anonymized, or de-identified publishing—not raw constituent case files.
- Historical statistics, officeholders, categories, contact information, and annualized values are not current guidance or a complete measure of constituent service.
- Claims must remain source-bounded. Observation, historical evidence, inference, and unanswered questions must not be collapsed into one claim.
- The knowledge bank and evaluation materials remain project-internal unless separately approved for public projection.
- Mutating archival behavior remains out of scope for the public artifact.

## Brand Commitments

- Name: CallNYC.
- The original 2016 CallNYC world governs: the CallNYC logo, Materialize coral and teal palette, locally served Roboto, fixed category rail, coral banner, elevated ranking cards, service chips, and compact material controls.
- Contemporary context is a narrow extension inside that world. It uses the same typography, color family, shape language, and density rather than announcing a successor identity.
- The visualized historical data is the central artifact. Explanatory content must not become a landing page before it.
- Public language is direct, source-bounded, privacy-protective, and explicit about archived/unofficial status.
- The Politico page is documentary evidence, not an endorsement.

## Evidence on Hand

- Git lineage for the original implementation and its preserved reconstruction.
- The database schema, seed path, category tree, and historical district data that power the public interface.
- Archived Politico New York PDF and page image under `data/media/`.
- The official NYC Open Data dataset and historical notice.
- Current official NYC Council district lookup for present-day help.
- Project-internal source and decision records supporting bounded public wording.
- No evidence authorizes presenting historical rankings as current or publishing personally identifying constituent case information.

## Product Principles

1. Let people use the artifact before explaining it at length.
2. Keep the 2016 interface recognizable; extend it only where present-day stewardship requires.
3. Mark archived, unofficial, and historical status before any ranking can be misread.
4. Pair transparency advocacy with constituent privacy.
5. Treat documentary evidence as evidence, not endorsement or publication permission.
6. Keep present-day official help one clear action away.

## Accessibility & Inclusion

The artifact must support keyboard navigation, visible focus, semantic landmarks, meaningful alternative text, responsive reflow, and sufficient contrast without depending on remotely hosted fonts or icon libraries. The fixed rail becomes an accessible 48px menu control below 993px; the preservation notice stacks below 601px; the primary advocacy action becomes full-width on small screens. Reduced viewport size must not hide the archived/unofficial warning or the route to current official help.
