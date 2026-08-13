---
name: CallNYC
description: A public record room for an archived civic-data project and a present-day transparency request.
colors:
  broadway-blue: "#2f6f89"
  broadway-blue-dark: "#23566b"
  oil-white: "#ffffff"
  oil-paper: "#f3f6f8"
  oil-ink: "#1a232b"
  muted-ink: "#51606b"
  confirmation-green: "#4e6f61"
  yellow-ochre: "#d1a23f"
  ochre-text: "#82520a"
  civic-red: "#c83b32"
  divider-gray: "#b9c3c9"
  request-copy: "#eef2f4"
  request-link: "#c6e1ed"
  dark-secondary-rule: "#9dabb4"
  artifact-rule: "#d5d9dc"
  sticky-header: "rgba(255, 255, 255, .97)"
typography:
  display:
    fontFamily: "Iowan Old Style, Palatino Linotype, Palatino, Baskerville, Georgia, serif"
    fontSize: "clamp(3.25rem, 7.1vw, 6rem)"
    fontWeight: 500
    lineHeight: 0.96
    letterSpacing: "-0.025em"
  headline:
    fontFamily: "Iowan Old Style, Palatino Linotype, Palatino, Baskerville, Georgia, serif"
    fontSize: "clamp(2.25rem, 4.8vw, 4.7rem)"
    fontWeight: 500
    lineHeight: 1.02
    letterSpacing: "-0.025em"
  title:
    fontFamily: "Iowan Old Style, Palatino Linotype, Palatino, Baskerville, Georgia, serif"
    fontSize: "1.45rem"
    fontWeight: 700
    lineHeight: 1.25
  deck:
    fontFamily: "Karla, Avenir Next, Avenir, sans-serif"
    fontSize: "clamp(1.15rem, 1.7vw, 1.45rem)"
    fontWeight: 400
    lineHeight: 1.5
  body:
    fontFamily: "Karla, Avenir Next, Avenir, sans-serif"
    fontSize: "1.0625rem"
    fontWeight: 400
    lineHeight: 1.65
  label:
    fontFamily: "Oswald, Arial Narrow, sans-serif"
    fontSize: "0.78rem"
    fontWeight: 550
    lineHeight: 1.3
    letterSpacing: "0.1em"
  small:
    fontFamily: "Karla, Avenir Next, Avenir, sans-serif"
    fontSize: "0.95rem"
    fontWeight: 400
    lineHeight: 1.65
  caption:
    fontFamily: "Karla, Avenir Next, Avenir, sans-serif"
    fontSize: "0.92rem"
    fontWeight: 400
    lineHeight: 1.65
rounded:
  status: "2px"
  control: "4px"
spacing:
  xs: "0.5rem"
  sm: "0.75rem"
  md: "1rem"
  lg: "1.25rem"
  xl: "1.5rem"
  xxl: "2rem"
  section: "clamp(4.5rem, 7vw, 8rem)"
components:
  button-primary:
    backgroundColor: "{colors.broadway-blue}"
    textColor: "{colors.oil-white}"
    rounded: "{rounded.control}"
    padding: "0.75rem 1rem"
    height: "3.4rem"
  button-secondary:
    backgroundColor: "transparent"
    textColor: "{colors.broadway-blue-dark}"
    rounded: "{rounded.control}"
    padding: "0.75rem 1rem"
    height: "3.4rem"
  status-confirmed:
    backgroundColor: "transparent"
    textColor: "{colors.confirmation-green}"
    typography: "{typography.label}"
    rounded: "{rounded.status}"
    padding: "0.25rem 0.55rem"
  status-open:
    backgroundColor: "transparent"
    textColor: "{colors.ochre-text}"
    typography: "{typography.label}"
    rounded: "{rounded.status}"
    padding: "0.25rem 0.55rem"
---

# Design System: CallNYC

## Overview

**Creative North Star: "The Public Record Room"**

CallNYC is a calm, exacting civic record room: bright paper, dark ink, measured blue rules, ochre for caution, and historic CallNYC red held in a supporting role. It inherits the public-record visual language of `jamieburk.art@develop` while speaking as a civic project in its own right.

The system is editorial without impersonating a newspaper and institutional without imitating government. An asymmetric service-first opening gives the public letter the larger field while keeping official help immediately available. Large humanist headings make the central claim inhabitable; compact labels organize source state; rules, spacing, and real artifacts carry hierarchy.

**Key Characteristics:**

- Light, high-contrast, and source-first.
- Full-width frames and flat rules instead of ornamental containers.
- One large civic sentence followed by present-day help, structured evidence, and bounded action.
- A deliberate boundary between the contemporary successor system and the inherited 2016 application.
- Historical CallNYC red supports lineage and inline-link hover; it is not the primary action color.

## Colors

Broadway blue carries public action and links; oil white, oil paper, and oil ink make the reading room; ochre and green communicate evidence state; civic red remembers the historical project and marks inline-link hover.

### Primary

- **Broadway Blue:** Primary controls, rules, wordmark emphasis, and public-action links.
- **Deep Broadway Blue:** Default text-link color and primary-control hover state.

### Secondary

- **Confirmation Green:** Confirmed evidence labels.
- **Yellow Ochre:** Visible focus and caution accents.
- **Ochre Text:** The accessible text treatment for open-question labels on white.
- **Civic Red:** Historical CallNYC lineage and the global inline-link hover state; never the successor surface's main control color.

### Neutral

- **Oil White:** Main reading surface and light text on dark or blue fields.
- **Oil Paper:** Official-help, archive-exhibit, and footer fields.
- **Oil Ink:** Primary copy and the dark request field.
- **Muted Ink:** Secondary explanation and source notes on light surfaces.
- **Divider Gray:** Default one-pixel rules.
- **Request Copy:** Supporting copy on the dark request field.
- **Request Link:** Link color on the dark request field.
- **Dark Secondary Rule:** Secondary-control border on the dark request field.
- **Artifact Rule:** Hairline border around the Politico document image.
- **Sticky Header:** Near-opaque white that preserves legibility over scrolling content.

**The Three Signals Rule.** Blue asks, ochre cautions, and red remembers. Green confirms; none of these colors is interchangeable decoration.

**The Archive Palette Boundary Rule.** Materialize teal (`#26a69a`), Materialize red (`#ee6e73`), the inline yellow rank marker (`#ffcc4c`), and other colors embedded in the 2016 application are historical exceptions. Preserve them inside the archive; do not promote them into contemporary successor tokens.

## Typography

**Display Font:** Iowan Old Style with Palatino Linotype, Palatino, Baskerville, and Georgia fallbacks  
**Body Font:** Self-hosted variable Karla with Avenir Next and Avenir fallbacks  
**Label Font:** Self-hosted variable Oswald with an Arial Narrow fallback

**Character:** The display stack is humane and civic; Karla is open and conversational; Oswald is compact enough to organize evidence without becoming bureaucratic. Karla and Oswald are self-hosted so the successor surface and archive boundary remain legible without third-party font services.

### Hierarchy

- **Display** (500, fluid 3.25–6rem, 0.96): The governing sentence. At the compact breakpoint it uses a separate fluid 3–4.4rem range so it continues to reflow instead of shrinking to a fixed step.
- **Headline** (500, fluid 2.25–4.7rem, 1.02): Major argumentative turns. The official-help heading uses its own established fluid 2.5–4rem step within this role.
- **Title** (700, 1.45rem, 1.25): Evidence-docket findings. General tertiary headings use the smaller 1.18rem body-family step.
- **Deck** (400, fluid 1.15–1.45rem, 1.5): The hero's bounded explanation.
- **Body** (400, 1.0625rem, 1.65): Public explanation and source context, reduced to 1rem at the compact breakpoint and usually capped between 56 and 73 characters according to function.
- **Label** (550, 0.78rem, 1.3, 0.1em, uppercase): Evidence status. Nearby functional label steps range from 0.8–0.85rem with 0.12em tracking; the archive artifact label is a deliberately smaller 0.73rem exception.
- **Small** (400, 0.95rem, 1.65): Emergency and supporting utility text; the footer uses the adjacent 0.94rem step.
- **Caption** (400, 0.92rem, 1.65): Documentary attribution and limits.

**The Landing Sentence Rule.** Braided explanation earns one short structural sentence. Do not turn every paragraph into a pull quote.

**The Self-Hosted Civic Type Rule.** Karla carries public reading and controls; Oswald carries compact evidence and archive-boundary labels. Never replace either with a remotely hosted font or an icon font.

## Layout

The successor surface is a full-width sequence, not a centered card or fixed-width site container. Shared side gutters use `clamp(1.25rem, 4vw, 4.5rem)`. The sticky header is at least 5rem tall; the hero fills the remaining first viewport and divides into a 1.9fr letter field and a minimum-19rem, 0.95fr official-help field. A one-pixel rule separates the fields.

The evidence docket uses four columns: 9.5rem for status, a finding column with a 15rem minimum, an explanation column with a 13rem minimum and 1.6fr share, then the source link. The dark request and archive exhibit use asymmetric two-column grids. Major section padding is fluid, commonly 4.5–8rem; the request expands to 5–10rem.

At 960px the hero, request, and archive exhibit become single-column, and the docket becomes two columns. At 680px the header becomes static, the navigation becomes a three-column native grid, official help moves before the letter, the docket and other paired structures become one column, and actions fill the available width. The inherited archive wrapper has its own 992px and 600px adaptation points; these are compatibility exceptions, not successor breakpoints to copy into new surfaces.

## Elevation & Depth

The successor system is flat by default. Paper tone, ink fields, one-pixel rules, and spatial changes establish hierarchy. Elevation belongs only to a real documentary artifact: the full Politico page on the contemporary home and its smaller preview in the global archive boundary.

### Shadow Vocabulary

- **Full Documentary Artifact** (`0 18px 40px rgba(34, 43, 54, .16)`): The legible Politico page in the archive exhibit.
- **Compact Documentary Artifact** (`0 10px 24px rgba(34, 43, 54, .14)`): The smaller Politico preview in the archive boundary.

**The Evidence Casts the Shadow Rule.** Only a physical or documentary artifact receives elevation; ordinary content regions remain flat.

Materialize elevation classes, inline ranking shadows, and text shadows inside the preserved 2016 application remain historical implementation exceptions. Do not reuse them on successor surfaces and do not reinterpret them as contemporary elevation tokens.

## Shapes

Successor controls are compact rectangles with gently squared 4px corners. Evidence-status labels are deliberately tighter at 2px. Rules are one pixel except the two-pixel docket and source-list opening rules and the three-pixel ochre request note. The Politico page preserves a sharp letter-page silhouette; the original CallNYC logo retains its own geometry.

**The Two-Radius Rule.** Use 4px for actionable controls and 2px for compact evidence-state labels. Pills, circles, and the varied corners embedded in the 2016 archive are historical exceptions, not successor primitives.

## Components

### Buttons

- **Shape:** Gently squared controls (4px), at least 3.4rem tall, with 0.75rem × 1rem internal padding and a one-pixel blue rule.
- **Primary:** Broadway blue field with oil-white text; the dark request field reverses the primary control to oil white with oil-ink text.
- **Secondary:** Transparent field with Deep Broadway Blue text; on the dark request field it uses oil-white text and the Dark Secondary Rule.
- **Hover / Focus:** The primary field deepens to Deep Broadway Blue on hover. All links and controls use a three-pixel ochre focus outline with a four-pixel offset. Dark-field controls reverse field and text without losing the rule.

### Evidence Status

- **Shape:** Compact 2px corners, one-pixel current-color rule, and 0.25rem × 0.55rem padding.
- **Confirmed:** Confirmation Green text and rule.
- **Open Question:** Ochre Text and rule; the wording remains present so state is never communicated by color alone.

### Evidence Docket / Source Record

- **Structure:** State first, then one exact finding, its bounded explanation, and a direct source link.
- **Rules:** A two-pixel ink opening rule and one-pixel gray row dividers.
- **Behavior:** Reflows from four columns to two at 960px and a single reading sequence at 680px. It does not become an equal-height card grid.

### Navigation

Navigation uses ink text, Karla at 650 weight, at least 44px targets, and a near-opaque white sticky field separated by a one-pixel rule. On compact screens it remains a native three-column HTML navigation rather than becoming a custom animated drawer.

### Documentary Artifact

The Politico page is shown at readable scale with a one-pixel Artifact Rule, source attribution, date, meaningful alternative text, and a direct archived-PDF link. It is evidence of contemporary coverage, not endorsement, and is the only signature object permitted to use the documentary shadows.

### Archive Boundary

Every inherited route begins with a contemporary Oil Paper boundary using the successor display, body, and label roles. It states that the underlying application is historical and unofficial, routes people to current official help, and makes the Politico artifact available. Styling below that boundary remains preserved archive behavior rather than a source of new successor patterns.

## Do's and Don'ts

### Do:

- **Do** give the governing sentence room to carry the first viewport while keeping official help immediately available.
- **Do** use self-hosted Karla and Oswald with their established local fallbacks.
- **Do** use real evidence artifacts at legible scale with human-readable attribution.
- **Do** distinguish confirmed findings, inference, and open questions visually and verbally.
- **Do** keep help-now, archive, and advocacy routes keyboard reachable and findable within seconds.
- **Do** preserve visible three-pixel ochre focus outlines with a four-pixel offset and honor reduced-motion preferences.
- **Do** preserve inherited 2016 styling inside the explicit archive boundary.

### Don't:

- **Don't** reproduce Jamie's personal portfolio navigation or make Jamie the masthead.
- **Don't** use historical red as the primary contemporary action color.
- **Don't** present equal-sized icon cards as the page's structural grammar.
- **Don't** let ordinary successor containers cast shadows.
- **Don't** promote Materialize colors, inline shadows, pill radii, or other 2016 archive exceptions into contemporary tokens.
- **Don't** decorate the surface with gradients, glass, generic civic seals, or faux-government motifs.
- **Don't** use external font or icon stylesheets.
