---
name: ui-design-principles
description: Use for ANY frontend/UI task — building, editing, or reviewing screens, components, cards, forms, modals, banners, dashboards, lists, or empty states. Enforces content-first design, clear hierarchy, restrained color, consistent spacing, and bans common visual anti-patterns. Apply even when the request only describes functionality.
by: Mahmoud Mansour
---

# UI Design Principles

Before writing UI code, answer: **What does the user need to know, and what do they need to do?** Every element must serve one of those two. Delete everything else.

## 1. Content first
- **Lead with the specific fact, not the category.** "Payment of $120 failed" > "Payment notification". "New time: Thu 14:00" > "Time proposed".
- **Show the data the user decides on** (time, amount, name, status) directly. Never hide it behind a click.
- **Show changes as before → after**: old value muted/struck, new value prominent.
- **No repetition.** If title and body say the same thing, cut one.
- **Say each thing once.** Don't repeat a name, a count, or a status in two places.
- **Metadata stays small.** Environment tags (Demo/Test/Beta), timestamps, and IDs go in muted text or a pill. Never `[Tag]` brackets in titles.
- **Write in plain words.** Sentence case, no jargon, no exclamation marks, no filler ("Please note that…").

## 2. Actions
- **Labels say the outcome**: "Accept", "Save changes", "Delete project", "Review". Banned: "OK", "Submit", "Answer", "Click here", "Go".
- **One primary action per view/card.** Max 2–3 visible actions; put the rest in an overflow menu.
- **Primary** = filled accent. **Secondary** = neutral outline. **Destructive** = red, and confirm or allow undo.
- **Let users act where they are.** If a decision is simple and low-risk, put the buttons inline instead of sending them to another page.
- **Hit targets ≥ 44px** on touch, ≥ 32px on desktop.
- Every interactive element needs **hover, focus-visible, active, disabled, and loading** states.

## 3. Hierarchy & typography
- **Max 3 text levels per component**: primary (ink, 600–700), secondary (muted, 400–500), accent (one key value only).
- **1 font family** (2 max: one UI, one mono for code/IDs). Avoid overused defaults (Inter, Roboto, Arial) unless the project already uses them.
- Type scale: 12 / 13 / 15 / 17 / 20 / 24 / 32. Don't invent sizes in between.
- Body line-height 1.45–1.6; headings 1.2–1.35. Use `text-wrap: pretty` for prose and `balance` for headings.
- The most important item gets the **biggest or boldest** type, not the most colour.

## 4. Color
- **Neutral base + 1 accent.** A second accent is allowed only for meaning (success/warn/error).
- **Never mix unrelated hues in text** within one component (e.g. navy title + brown body + grey footnote).
- Match temperature: don't put warm cream/yellow surfaces in a cool (blue/indigo) app, or the reverse.
- Use colour for **meaning** (state, action, new value), not decoration.
- Contrast: body text ≥ 4.5:1, large text ≥ 3:1. Don't use opacity to make text look muted; use a solid muted colour.
- Define colours as tokens; never hard-code one-off hex values in components.

## 5. Spacing & layout
- **4px base grid**: 4 / 8 / 12 / 16 / 20 / 24 / 32 / 48 / 64.
- Use **flex/grid with `gap`**, not margins on children.
- **Space shows grouping:** items inside a group sit closer together than the groups sit to each other.
- Align to one left edge. No centred body text in cards or forms.
- Layouts must reflow: `max-width`, `minmax(0,1fr)`, wrapping rows, no fixed heights on text containers.
- Test at 360px, 768px, and 1280px widths.

## 6. Surfaces
- Cards: radius 12–16px, padding 16–24px, either a 1px border **or** a soft shadow, not both heavy.
- Shadow: `0 1px 2px rgba(0,0,0,.06), 0 8px 24px rgba(0,0,0,.08)`.
- Group key data in an inset tile (slightly tinted neutral, radius 8–12px).
- Nested radius = outer radius − padding (roughly).
- Max 2 levels of nested containers.

## 7. Icons & imagery
- Icons must **add meaning**. If the text already says it, drop the icon.
- Prefer avatars/initials or the real thumbnail over generic icons (bell, info, star).
- One icon set, one stroke weight, sized 16 / 20 / 24.
- **Nothing clipped or overhanging a container edge** unless that's a deliberate, consistent pattern.
- No emoji in UI unless the brand uses them.

## 8. States (always design all of them)
- **Empty**: explain what goes here and give the first action.
- **Loading**: skeletons that match the layout. Show a spinner only for actions under ~1 second.
- **Error**: say what happened, why, and how to fix it. Never show raw server messages (403/500).
- **Partial / overflow**: long names truncate with a tooltip; long lists show a count and "View all".
- **Success**: confirm briefly, inline or as a toast, and offer undo where possible.

## 9. Banned anti-patterns
- Coloured left-border "accent stripes" on cards or alerts.
- Aggressive gradients and glassmorphism used as decoration.
- Icons clipped by or overhanging the card edge.
- Three or more text colours in one component.
- Vague full-width CTAs as the main content ("Answer", "Continue").
- Bracketed prefixes in titles: `[Demo]`, `[NEW]`.
- Redundant title + body copy.
- Placeholder or filler content, fake stats, decorative numbers.
- All-caps paragraphs, centred multi-line body text.
- Disabled buttons with no explanation of why.

## 10. Process
1. **Read the existing design system or codebase first.** Reuse its tokens, components, and patterns. Don't invent parallel ones.
2. Write the copy (title, value, actions) **before** styling.
3. Build the happy path, then every state from §8.
4. Run the checklist below. Fix every failure before calling it done.

## Pre-ship checklist
- [ ] Is the single most important fact the most prominent thing?
- [ ] Can the user act without leaving the screen (when the action is simple)?
- [ ] Do the button labels say the outcome? Is there one primary action?
- [ ] Are there ≤ 3 text styles and ≤ 1 accent colour per component?
- [ ] Is there any repeated or filler copy?
- [ ] Are spacing values on the 4px grid and gaps used consistently?
- [ ] Are hover, focus, disabled, loading, empty, and error states all present?
- [ ] Is contrast ≥ 4.5:1 and are hit targets ≥ 44px on touch?
- [ ] Does it reflow cleanly at 360px?
- [ ] Are any banned patterns from §9 present?
