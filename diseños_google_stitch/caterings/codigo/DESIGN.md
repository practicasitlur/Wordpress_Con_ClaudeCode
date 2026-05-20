---
name: Artisan Structure
colors:
  surface: '#fbf9f8'
  surface-dim: '#dbdad9'
  surface-bright: '#fbf9f8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f5f3f2'
  surface-container: '#efedec'
  surface-container-high: '#eae8e7'
  surface-container-highest: '#e4e2e1'
  on-surface: '#1b1c1b'
  on-surface-variant: '#4e4636'
  inverse-surface: '#303030'
  inverse-on-surface: '#f2f0ef'
  outline: '#807664'
  outline-variant: '#d2c5b1'
  surface-tint: '#795900'
  primary: '#795900'
  on-primary: '#ffffff'
  primary-container: '#d4a843'
  on-primary-container: '#553e00'
  inverse-primary: '#eec058'
  secondary: '#5f5e5e'
  on-secondary: '#ffffff'
  secondary-container: '#e2dfde'
  on-secondary-container: '#636262'
  tertiary: '#3c5d9d'
  on-tertiary: '#ffffff'
  tertiary-container: '#8eaef4'
  on-tertiary-container: '#1b407f'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdf9f'
  primary-fixed-dim: '#eec058'
  on-primary-fixed: '#261a00'
  on-primary-fixed-variant: '#5b4300'
  secondary-fixed: '#e5e2e1'
  secondary-fixed-dim: '#c8c6c5'
  on-secondary-fixed: '#1c1b1b'
  on-secondary-fixed-variant: '#474746'
  tertiary-fixed: '#d8e2ff'
  tertiary-fixed-dim: '#aec6ff'
  on-tertiary-fixed: '#001a42'
  on-tertiary-fixed-variant: '#214584'
  background: '#fbf9f8'
  on-background: '#1b1c1b'
  surface-variant: '#e4e2e1'
  pure-white: '#FFFFFF'
  border-subtle: rgba(26, 26, 26, 0.1)
  border-strong: '#1A1A1A'
typography:
  headline-xl:
    fontFamily: Hanken Grotesk
    fontSize: 64px
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Hanken Grotesk
    fontSize: 40px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Hanken Grotesk
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
  headline-md:
    fontFamily: Hanken Grotesk
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.3'
  body-lg:
    fontFamily: Manrope
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-md:
    fontFamily: Manrope
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  label-caps:
    fontFamily: Space Mono
    fontSize: 12px
    fontWeight: '700'
    lineHeight: '1.0'
    letterSpacing: 0.1em
  label-sm:
    fontFamily: Space Mono
    fontSize: 11px
    fontWeight: '400'
    lineHeight: '1.4'
rounded:
  sm: 0.125rem
  DEFAULT: 0.25rem
  md: 0.375rem
  lg: 0.5rem
  xl: 0.75rem
  full: 9999px
spacing:
  base: 8px
  gutter: 24px
  margin-desktop: 64px
  margin-mobile: 20px
  container-max: 1280px
---

## Brand & Style

This design system embodies a premium, artisanal spirit through a lens of modern architectural structure. It targets an urban, sophisticated audience that appreciates the intersection of craft and contemporary design.

The aesthetic follows a **Minimalist-Structured** approach: clean layouts, expansive whitespace, and high-contrast accents. It utilizes thin, deliberate borders to define space rather than relying on shadows, creating a "grid-first" visual language that feels both editorial and functional. The emotional response should be one of curated quality—precise, bold, and refreshingly clear.

## Colors

The palette is anchored by a high-contrast relationship between **Corporate Yellow** (#D4A843) and **Deep Obsidian** (#1A1A1A). 

- **Primary (Yellow):** Used as a surgical accent for call-to-actions, highlights, and brand moments. It represents the "pop" of the product.
- **Secondary (Obsidian):** The primary color for typography and structural elements (borders). It provides the weight and authority.
- **Neutral (Off-white):** Used for large background surfaces to reduce eye strain compared to pure white, maintaining a premium, "paper-like" quality.
- **Pure White:** Reserved for card backgrounds and internal containers to create subtle depth against the neutral background.

## Typography

The typographic system is built on a hierarchy of three distinct voices:

1.  **Headlines (Hanken Grotesk):** Sharp, contemporary, and authoritative. Use tight tracking for larger sizes to emphasize the "structured" feel.
2.  **Body (Manrope):** Chosen for its exceptional legibility and balanced proportions. It keeps the long-form content feeling approachable and modern.
3.  **Data & Labels (Space Mono):** Used for technical details, prices, and secondary navigation. This monospaced font introduces a "modular/inventory" aesthetic that reinforces the brand's structured boxes.

All headlines should favor sentence case for a modern look, while labels should frequently utilize uppercase with increased letter spacing.

## Layout & Spacing

This design system utilizes a **Fixed Grid** model on desktop and a **Fluid Grid** on mobile. 

- **Desktop:** A 12-column grid with a 1280px max-width. Columns are separated by 24px gutters. Content is often housed in "structured boxes"—explicitly bordered containers that align strictly to the grid lines.
- **Mobile:** A 4-column fluid grid. Margins scale down to 20px. 
- **Rhythm:** An 8px base unit governs all internal padding and component heights. 

Layouts should favor asymmetrical balance—for example, a large image spanning 7 columns adjacent to text spanning 4 columns, leaving one column of "active" whitespace.

## Elevation & Depth

This system rejects heavy shadows in favor of **Tonal Layering** and **Fine Outlines**.

- **Depth through Borders:** Surfaces are separated by 1px solid borders (`border-subtle`). When an element is hovered or active, the border thickness remains the same but the color shifts to `border-strong`.
- **Surface Tiers:** 
  - **Level 0 (Background):** `neutral_color_hex` (#F1EFEE).
  - **Level 1 (Cards/Containers):** `pure-white` (#FFFFFF) with a 1px border.
  - **Level 2 (Popovers/Modals):** `pure-white` with a crisp, non-diffused 4px "hard shadow" in `secondary_color_hex` at 5% opacity to simulate a slight lift without losing the minimalist edge.

## Shapes

The shape language is "Soft-Geometric." While the overall layout feels rigid and grid-based, components use a **0.25rem (4px)** corner radius to prevent the UI from feeling overly aggressive or "brutalist."

- **Standard Elements:** 4px radius (Buttons, Input fields, Chips).
- **Containers:** 8px radius (Cards, Modals).
- **Interactive States:** Maintain consistent rounding; do not use pill shapes for buttons to preserve the architectural integrity of the design.

## Components

### Buttons
- **Primary:** solid `secondary_color_hex` background with `primary_color_hex` text. No shadow. 4px radius.
- **Secondary:** Transparent background, 1px `border-strong`, `secondary_color_hex` text.
- **Tertiary:** `label_font` with an underline that grows on hover.

### Cards
- Background: `pure-white`.
- Border: 1px solid `border-subtle`.
- Layout: Rigid internal padding (24px). Headers within cards should use `label-caps` to categorize content.

### Inputs & Forms
- Background: `pure-white`.
- Border: 1px solid `border-subtle`. On focus, the border becomes `primary_color_hex`.
- Labels: Always use `label-caps` positioned above the input.

### Chips & Tags
- Small, rectangular containers with `spaceMono` text. 
- Use `primary_color_hex` backgrounds for high-priority status and `neutral_color_hex` for metadata.

### Lists
- Separated by horizontal 1px lines. Use `spaceMono` for index numbering (e.g., 01, 02, 03) to emphasize the organized, structured nature of the brand.