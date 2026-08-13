---
name: CallNYC original-first preservation
status: current
updated: 2026-08-13
colors:
  callnyc-coral: "#ec6c71"
  materialize-red-dark: "#c62828"
  heading-red: "#d32f2f"
  action-orange: "#bf360c"
  materialize-teal: "#26a69a"
  rank-yellow: "#ffcc4c"
  ink: "#424242"
  menu-ink: "#1a232b"
  muted-ink: "#616161"
  search-blue: "#2f6f89"
  control-rule: "#aebcc4"
  white: "#ffffff"
  rule: "#e0e0e0"
  photo-shadow-strong: "rgba(0,0,0,0.48)"
  photo-shadow-medium: "rgba(0,0,0,0.40)"
  photo-shadow-soft: "rgba(0,0,0,0.32)"
  photo-shadow-faint: "rgba(0,0,0,0.24)"
  artifact-shadow: "rgba(0, 0, 0, .16)"
typography:
  family: "Roboto, sans-serif"
  hero: "300 3.5rem/1.15 Roboto, sans-serif"
  archive-heading: "400 1.65rem/1.2 Roboto, sans-serif"
  body: "400 0.94rem/1.5 Roboto, sans-serif"
rounded:
  materialize-control: "2px"
  menu-control: "4px"
  ranking-chip: "2em"
---

# Design system: CallNYC original-first preservation

## North star

The 2016 CallNYC interface is the design system. The public root should feel like the original working civic-data artifact, not like a contemporary site presenting an artifact. Preservation context, press evidence, and public action occupy one compact header in the same Roboto, Materialize, coral, white, and gray language.

## Visual source of truth

The preserved application is the primary design authority. Two screenshots in the predecessor archive were inspected as supporting evidence; their custody and checksums are recorded in `docs/knowledge-bank/sources/callnyc-visual-references.md`. Together they establish the incumbent world:

- a 240px fixed white sidebar on large screens;
- the three-ring CallNYC mark, search field, and expandable service taxonomy;
- a coral title field with large light-weight white type;
- single-column member ranking cards with photographs, circular rank markers, chips, and action rows;
- a right-hand table of contents on wide screens;
- a slide-out navigation drawer on compact screens.

New work must repair loading, safety, accessibility, and archival accuracy without replacing these recognizable affordances.

## Preservation header

The header is a narrow white band immediately above the original coral title field. On wide screens it uses the inherited Materialize grid:

1. archival status, authorship, and the 24-hour sprint context;
2. the real Politico page thumbnail and archived PDF link;
3. the transparency request and pre-populated email action.

At 992px and below, space is reserved for the original menu control. At 600px and below, the text occupies the full row and the thumbnail/action pair share the next row. The header may lengthen enough to read, but it must not become a separate hero or delay access to the original interface.

## Color

The inherited civic coral remains the primary field. It is adjusted from `#ee6e73` to `#ec6c71`, the smallest reviewed change that brings large white hero copy above 3:1 contrast. The preservation heading uses Materialize red darken-2 (`#d32f2f`); the small white email label and historical-data action use red darken-3 (`#c62828`) to exceed 4.5:1. Card actions use inherited deep-orange darken-4 (`#bf360c`) for the same reason.

Materialize teal remains available for inherited controls and links. The original yellow rank marker and existing photographic color remain historical parts of the interface, not new decorative tokens.

## Typography

Use the application's self-hosted Roboto family everywhere. Preserve the original light hero scale, card hierarchy, uppercase button labels, category labels, and compact table-of-contents text. Do not introduce a second display or label family.

## Imagery and depth

Member photographs remain the dominant imagery. The Politico page is a small documentary thumbnail, not a promotional hero. It may use the same restrained shadow already associated with Materialize cards; no other new container needs elevation.

## Interaction

- Root and category links retain the original direct path structure.
- `/archive/2016/` is an inbound compatibility alias, not a navigation destination.
- Historical phone actions are replaced with the official current Council district lookup.
- The preservation action opens a pre-addressed email with subject and privacy-protective body copy.
- The preservation action remains at least 44px tall at every reviewed width.
- Keyboard focus uses the existing rank-yellow as a visible 3px outline.
- User zoom remains enabled; desktop, tablet, mobile, and the open drawer must not overflow horizontally.

## Do

- Keep the original application visible in the first viewport.
- Use the actual Politico screenshot and archived PDF.
- State "Archived and unofficial" before historical statistics.
- Distinguish current institutional activity from the historical public dataset.
- Keep the internal knowledge graph separate from the public page.

## Do not

- Add an explanatory homepage, about page, evidence page, or campaign page.
- Import the visual language or navigation of Jamie's portfolio.
- Present 2016 officeholders, statistics, telephone numbers, or rankings as current.
- Claim an official explanation for the end of current public publishing.
- Add new icon sets, typefaces, gradients, glass effects, or contemporary card systems.
