---
name: ux-journey-review
description: Use before and while building, reviewing, or fixing any UI feature, screen, form, dashboard, card, panel, or workflow in any product — maps the user journey, isolates the flow and its happy path, enforces reuse of the existing design system, and runs a states + detail-polish pass before the work is called done. Trigger this for ANY frontend/UI task even if the user only describes functionality and never says "UX" — e.g. "add a shortlist feature", "build the payment panel", "where should X show up". Also trigger when reviewing or critiquing an existing screen.
---

# UX Journey Review

Purpose: stop shipping features that technically "work" but leave the user lost, and stop shipping screens where the functionality is right but the states, icons, spacing, and layout look unfinished. Every UI task runs through this before it's presented as done.

## 0. Get the three concepts straight

These are not the same thing. Don't collapse them.

- **User Journey** — the big picture. The user's motivations, emotions, and pain points, and how they navigate across channels and pages from *before* they enter the system until they reach their goal. It is **not linear**: users jump between sections, backtrack, and re-decide. Do not force it into one path.
- **User Flow** — the concrete steps to accomplish one specific task inside the product (e.g. adding a candidate to a shortlist, paying for a membership).
- **Happy Path** — the fastest, error-free, linear route through a single flow. This is the thing that gets the one-line test below, not the whole journey.

When you reason about placement and navigation, think in **journey** terms (where does this fit in the user's larger movement through the app). When you build the actual screen, think in **flow + happy path** terms.

## 1. Define the flow before writing code

For the task at hand, state these in your response before touching the UI:

- **Single Goal** — the flow leads to exactly one endpoint (e.g. "candidate added to shortlist"). If you find a second goal, split it into its own sub-flow; don't cram two goals into one flow.
- **Entry & Exit points** — a clear Trigger (what opens this flow) and a clear delivered Value (the defined end). Name both.
- **The one-line test** — write the happy path as one sentence, 3–5 steps: `Poster → Bounty detail → Applicants → Shortlist`. If you can't say it in 3–5 steps, the design has unnecessary screens or is doing too much — simplify before building.
- **Decision points & edge cases** — the happy path is linear, but mark the decision diamonds around it: what if there's no data yet? what if the user rejects instead of accepts? what if they lack permission? Each branch needs a defined destination.

Never implement a feature literally from the request without first checking it lands somewhere reachable within the user's journey. "It exists somewhere in the app" is not "the user can reach it."

## 2. Linear or branched — choose deliberately

Match the structure to the task:

- **Linear** (guide the user step by step, minimize branching): checkout, registration/onboarding, any quick specific task. Branching here raises drop-off — keep it a single corridor.
- **Non-linear / branched** (give the user freedom to choose where to go): dashboards, SaaS control surfaces, content browsing and discovery. The user decides order based on their need — don't trap them in a forced sequence.

State which one this screen is and why. A dashboard funneled like a checkout is as wrong as a checkout scattered like a dashboard.

## 3. Check the information architecture, not just the screen

Before adding a feature to a page, ask: **does this functionally belong on this page, or is it a different surface pretending to fit here?**

Two tells that something is misplaced:
- It mixes a *discovery/public* concept into a *personal/owned* space (e.g. a public "browse" board living inside a private workspace) — or the reverse.
- Reaching it requires the user to guess or backtrack through an unrelated flow instead of it sitting one step from where it's needed.

Rule of thumb:
- **Personal / owned area** = things already tied to the user (their items, people they've worked with, their history).
- **Discovery area** = things not yet theirs (browsing new options, open listings).

If a feature could describe itself as "stuff I already own" vs. "stuff I'm discovering," it must live on the matching side. If you're unsure which side, say so and pick the side that matches the user's mental model — don't silently default to wherever is easiest to code.

## 4. Reuse the design system — never invent on the side

- Before adding any icon, check whether the product's own icon set / brand icons already cover it. Don't drop in a generic library icon (stock Phosphor/Feather/etc.) when a branded equivalent exists (e.g. a currency icon should be the brand's coin icon, not a generic hexagon).
- Before building any recurring pattern — attachments, payment-method selection, summary cards, gating messages — check how it's already built elsewhere and match it exactly. A one-off variant of an existing pattern is a bug, not a feature.
- Only design something new when nothing existing fits, and say so explicitly when you do.

## 5. Layout discipline & adaptability

- A section that's technically "one column" but has scattered fields and dead whitespace should usually be split into logical steps (e.g. step 1: attach proof, step 2: pay) rather than left as one sparse block.
- Dense content (summary cards, totals) should be tightened to its content — no leftover empty space just because the container is wide.
- Decide grouping on purpose; don't leave it as the framework default.

**Responsiveness & layout adaptability** — every layout must be designed for more than one viewport:
- Define how the layout reflows at mobile / tablet / desktop, not just the widest breakpoint.
- Multi-column blocks should stack in a sensible reading order on narrow screens, not overflow or squash.
- Touch targets, spacing, and font sizes stay usable at the smallest supported width.
- State explicitly what happens to each major block when the screen narrows.

## 6. UI States — design all of them, not just the full one

For every data-driven component, define and build each state — not only the populated success case:
- **Empty** — first-time / no data yet. Tells the user what this is and what to do next, never a bare "nothing here."
- **Loading** — skeletons or spinners; the user knows something is happening.
- **Error** — what failed, why, and the recovery action.
- **Partial / no-results** (for filtered or searched views) — distinct from empty; offer a way to clear or adjust.
- **Populated** — the normal case.
- **Permission / gated** — gating messages explain *why* and *what to do next* (e.g. "A membership is required for that — [Upgrade]"), never a dead blocking string.

If a state isn't handled, the feature isn't done.

## 7. Feedback & micro-interactions

Every user action must produce a visible, immediate response:
- Buttons show pressed / loading / disabled states; nothing looks inert after a tap.
- Success and failure are acknowledged (toast, inline confirmation, state change) — the user never wonders "did that work?"
- Destructive or irreversible actions confirm before firing.
- Transitions (open/close, add/remove, tab switch) are smooth and purposeful, not abrupt — but never so slow they get in the way.
- Hover/focus states exist for interactive elements (and focus states are keyboard-visible for accessibility).

## 8. Detail pass — run before calling anything done

- [ ] Icons aligned, consistent in size/weight, correct brand icon (not a generic placeholder)
- [ ] Spacing inside text blocks/panels is even — no cramped or inconsistent line gaps in cards
- [ ] Card footers align with the rest of the card, not floating separately
- [ ] All UI states (§6) are implemented, not just the populated one
- [ ] Feedback exists for every action (§7)
- [ ] Layout reflows correctly at every supported breakpoint (§5)

## 9. Self-check before presenting

Ask: *for this actor, is the flow's happy path reachable in 3–5 steps without hunting; are the decision branches and all UI states handled; does every action give feedback; does it hold up across breakpoints; and does it look intentionally designed rather than default-generated?* If any answer is no, fix it now — don't wait for it to be caught in review. When genuinely unsure where something belongs or whether a pattern already exists, flag the open question rather than guessing silently.
