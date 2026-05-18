---
name: Academic Excellence System
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#444651'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#757682'
  outline-variant: '#c5c5d3'
  surface-tint: '#4059aa'
  primary: '#00236f'
  on-primary: '#ffffff'
  primary-container: '#1e3a8a'
  on-primary-container: '#90a8ff'
  inverse-primary: '#b6c4ff'
  secondary: '#006a61'
  on-secondary: '#ffffff'
  secondary-container: '#86f2e4'
  on-secondary-container: '#006f66'
  tertiary: '#1b2b3f'
  on-tertiary: '#ffffff'
  tertiary-container: '#314156'
  on-tertiary-container: '#9dadc6'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dce1ff'
  primary-fixed-dim: '#b6c4ff'
  on-primary-fixed: '#00164e'
  on-primary-fixed-variant: '#264191'
  secondary-fixed: '#89f5e7'
  secondary-fixed-dim: '#6bd8cb'
  on-secondary-fixed: '#00201d'
  on-secondary-fixed-variant: '#005049'
  tertiary-fixed: '#d3e4fe'
  tertiary-fixed-dim: '#b7c8e1'
  on-tertiary-fixed: '#0b1c30'
  on-tertiary-fixed-variant: '#38485d'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display-lg:
    fontFamily: Inter
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Inter
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  headline-sm:
    fontFamily: Inter
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  body-lg:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Inter
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-md:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  headline-lg-mobile:
    fontFamily: Inter
    fontSize: 28px
    fontWeight: '600'
    lineHeight: 36px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  sidebar-width: 280px
  container-max-width: 1440px
  gutter: 1.5rem
  margin-mobile: 1rem
  margin-desktop: 2.5rem
  stack-sm: 0.5rem
  stack-md: 1rem
  stack-lg: 2rem
---

## Brand & Style

The design system is engineered for high-stakes academic environments where clarity, authority, and organization are paramount. It adopts a **Corporate / Modern** aesthetic, prioritizing information density without sacrificing visual comfort. The system is designed to evoke a sense of institutional stability and digital maturity, ensuring that administrators, faculty, and students feel a sense of trust and "quiet efficiency" while navigating complex data structures.

The visual language focuses on a "Data-First" philosophy. This means every design decision—from the generous whitespace to the systematic typography—is intended to reduce cognitive load during long sessions of grading, scheduling, or enrollment management.

## Colors

The palette is anchored by **Academic Blue (#1E3A8A)**, a deep, authoritative indigo that serves as the primary touchpoint for navigation and primary actions. **Teal (#0D9488)** acts as a secondary accent, used specifically for progress indicators, success states, and secondary interactive elements to provide a modern, refreshing contrast to the traditional blue.

The background system utilizes a "layered white" approach. The base canvas is a very light slate gray (`#F8FAFC`), while interactive modules and cards are pure white (`#FFFFFF`) to create a clear, physical separation between the workspace and the container. Semantic colors for success, warning, and error follow industry standards but are slightly desaturated to maintain the professional tone of the system.

## Typography

This design system utilizes **Inter** exclusively to leverage its exceptional legibility in data-heavy interfaces. The typographic scale is highly structured, using subtle weight variations rather than dramatic size shifts to indicate hierarchy.

- **Headlines:** Use Semi-Bold (600) for section titles and page headers to establish immediate context.
- **Body Text:** Set at 16px for primary reading and 14px for data tables and sidebars to maximize information density.
- **Labels:** Small caps or medium-weight (500) 12px labels are used for table headers and form input descriptors to provide clarity without competing with user data.
- **Numerical Data:** Tabular lining should be enabled in CSS to ensure that grades, IDs, and financial figures align perfectly in vertical columns.

## Layout & Spacing

The design system employs a **Sidebar-Heavy Fixed Grid** model. The navigation remains persistent on the left at a fixed width of 280px, while the main content area utilizes a 12-column fluid grid that caps at 1440px to prevent excessive line lengths on ultra-wide monitors.

A strict 8px (0.5rem) spacing scale is used to maintain rhythm. 
- **Desktop:** 40px (2.5rem) outer margins provide "breathing room" for dense dashboards.
- **Mobile:** The sidebar collapses into a bottom sheet or "hamburger" drawer, and margins shrink to 16px (1rem).
- **Alignment:** All form elements and data points should align to the vertical rhythm of the stack, ensuring that even the most complex "Student Profile" pages feel cohesive.

## Elevation & Depth

The design system uses **Tonal Layers** combined with **Ambient Shadows** to create a functional hierarchy rather than a decorative one.

1.  **Level 0 (Canvas):** The base background layer (#F8FAFC). No shadow.
2.  **Level 1 (Cards/Modules):** Pure white surfaces with a very soft, diffused shadow (`0px 1px 3px rgba(0,0,0,0.1), 0px 1px 2px rgba(0,0,0,0.06)`). This level is used for the primary content widgets and data tables.
3.  **Level 2 (Overlays/Popovers):** Moderate shadow depth to indicate temporary focus, such as dropdown menus or date pickers.
4.  **Level 3 (Modals):** High-diffusion shadows with a 20% black backdrop blur to isolate critical tasks like "Submit Final Grades."

Low-contrast outlines (1px solid #E2E8F0) are used on all Level 1 elements to maintain structure in high-glare environments.

## Shapes

The design system utilizes **Rounded** geometry (8px / 0.5rem base) to soften the "institutional" feel and make the software feel modern and approachable. 

- **Primary Components:** Buttons, input fields, and small cards use the base 8px radius.
- **Large Containers:** Main dashboard widgets and modal containers use `rounded-lg` (16px) to create a clear visual distinction between small UI controls and large content areas.
- **Selection Indicators:** Active states in the sidebar navigation use a subtle 4px radius or a "pill" shape on one side to indicate the current page without cluttering the view.

## Components

### Buttons
Primary buttons use the Academic Blue background with white text. Secondary buttons use a ghost style (Academic Blue border/text) or a light gray fill. For destructive actions like "Delete Course," a subtle red outline is preferred over a solid red fill unless it's a final confirmation.

### Data Tables
Tables are the heart of this system. They should feature:
- Sticky headers for long student rosters.
- Zebra striping (very light gray #F1F5F9) for readability.
- Inline status chips (e.g., "Enrolled," "Alumni," "Withdrawn").

### Form Fields
Inputs must have clear, persistent labels and high-contrast focus states using a 2px Academic Blue ring. Error states should be accompanied by a small red icon to ensure accessibility for color-blind users.

### Progress Indicators
Used for degree tracking or grade averages. These should use the secondary Teal color to provide a positive visual "reward" for progress.

### Sidebar Navigation
The sidebar should use a slightly darker version of the primary color or a neutral deep slate to differentiate navigation from content. Icons should be paired with text labels for clarity.

### Chips & Badges
Used for tags like "Semester 1" or "Required Course." These use the base 8px roundedness with a desaturated version of the category color and high-contrast text.