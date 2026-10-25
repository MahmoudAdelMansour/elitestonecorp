---
name: card-ui-skill
description: Use when building or reviewing in-app cards, banners, toasts, or "action needed" prompts (bookings, approvals, invites, reschedules). Enforces actionable content, one clear hierarchy, and a restrained visual style.
by: Mahmoud Mansour
---

# Card UI

## 1. Content rules (most important)
- **Lead with the change, not the event type.** "New time: Thu 29 Oct, 14:00" beats "Mentor proposed a new time".
- **Show the actual data** the user must decide on (time, amount, name). Never make them click just to see it.
- **Show before → after** for changes: old value struck through and muted, new value bold.
- **No repetition.** Title and body must not restate each other. If they do, delete the body.
- **Name the actor once**, with an avatar/initials, in the title or subtitle — not both.
- **Environment tags** (Demo, Test, Beta) go in small muted metadata or a pill — never `[Demo]` in the title.
- **Buttons say the outcome**: "Accept", "Suggest time", "Review". Never "Answer", "OK", "Submit", "Click here".
- **Queue info is secondary**: "3 more bookings need a reply · View all" goes in a footer row or badge, below the primary action.

## 2. Actions
- If the decision is binary and low-risk → put both actions inline (primary + secondary).
- If the decision needs context → one "Review" button that opens the detail view.
- Max **2 buttons**. Primary = filled accent; secondary = white with a 1px border.
- Primary goes on the right in a two-button grid, or first/wider in a flex row.
- Minimum hit target **44px** height.

## 3. Visual hierarchy
Exactly three text levels:
| Level | Size / weight | Colour |
|---|---|---|
| Title / key value | 15–17px, 600–700 | ink `#1b1d3a` |
| Supporting | 13px, 400–500 | muted `#6b6f8a` |
| Accent (one value only) | same as title | accent `#4338ca` |

- **One accent colour per card.** Never mix brown/orange/navy text on the same surface.
- Use colour for meaning (new value, primary action), not for decoration.

## 4. Surface & layout
- White card, radius 14–16px, padding 18–20px, internal `gap` 14–16px.
- Soft layered shadow: `0 1px 2px rgba(27,29,58,.06), 0 8px 24px rgba(27,29,58,.08)`.
- Group the key data in an inset tile: `#f5f6fa`, radius 12px, padding 12–14px.
- Dark variant for compact banners: background `#1b1d3a`, white title, `#c3c6dd` subtitle, white button with ink text.
- Use flex/grid with `gap` — no margin-stacking.
- Stacked-card edges (2 offset layers below the card) are fine to show "more items in the queue".

## 5. Banned patterns
- Coloured left-border accent stripes.
- Icons that are clipped by or overhang the card edge.
- Warm cream/yellow backgrounds on a cool (indigo/navy) app.
- Decorative bell icons when an avatar or the data itself says more.
- Emoji, gradients, more than one accent hue.
- Full-width buttons with vague labels as the only content-bearing element.

## 6. Tokens
You can sample and example this colors with the Brand of the platform and identiy
```
--ink:        #1b1d3a
--muted:      #6b6f8a
--body:       #3a3d5c
--accent:     #4338ca
--accent-bg:  #e0e7ff
--tile:       #f5f6fa
--border:     #d4d7e3
--divider:    #eceef3
--radius-card: 16px
--radius-btn:  10px
font: "DM Sans", system-ui, sans-serif
```

## 7. Pick a variant
- **Inline decision** — user can decide from the card alone (accept/decline). Avatar + title, before/after tile, 2-button grid, queue footer.
- **Compact banner** — space is tight (top of a page, mobile header). One line of data, one "Review" button, count badge underneath.
- **Stacked queue** — several pending items processed one by one. "1 of N" label, one sentence with the accented value, primary + secondary buttons.

## 8. Review checklist
- [ ] Can the user act without opening another screen? If not, is "Review" the only button?
- [ ] Is the decisive value (time/amount) visible and the most prominent text?
- [ ] Do the title and body say different things?
- [ ] Is there ≤1 accent colour and ≤3 text styles?
- [ ] Are all buttons ≥44px and labelled with an outcome?
- [ ] No left stripes, clipped icons, emoji, or gradients?
- [ ] Is text contrast ≥4.5:1?
