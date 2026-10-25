---
name: ui-feedback-patterns
description: Diagnose and fix confusing or unsafe interactive behavior: buttons that do nothing, controls that look enabled but aren't, raw server errors (403/500/limit-exceeded) instead of a clear explanation, blank first-run pages, multi-step setups with no visible next step, forms that validate only at the last step, free-text fields for values that must match a fixed set, and save-incomplete-work actions that wrongly demand full completion. Covers visibility of system status, affordance, signifiers, error prevention, progressive disclosure, empty states, eager validation, bounded and closed-set inputs, intent-aware validation, and the happy-path-only root cause behind these bugs. Use whenever reviewing, designing, or fixing any interactive flow: forms, buttons, uploads, submissions, quotas, permissions, payments, onboarding, wizards, draft/save-for-later actions - even if the user describes the bug in plain language ("nothing happens when I click", "user got an error page", "it should have stopped them earlier").
---

# UI Feedback & Guidance Patterns

## The core principle

People read an interface literally. If a control looks enabled, they assume it works *right now* — they don't pause to wonder what prerequisite they're missing. If they click it and nothing visible happens, they don't conclude "I must be missing something." They conclude "this is broken."

The fix is never more documentation or a help article. It's making the interface state itself explain what's going on and what to do next, at the exact moment the user needs it. Apply this skill any time a UI action can be blocked, limited, unavailable, gated behind a prerequisite, or simply has no data yet.

## The root cause to look for: happy-path-only code

Almost every bug this skill addresses traces back to the same origin: the code was written and tested only for the ideal case — correct data, sufficient balance or permissions, a fully completed form, a user who clicks things in the intended order. Nobody built the paths for the empty, zero, invalid, boundary, or partial versions of the same flow.

So the fastest way to find these issues isn't to wait for a bug report — it's to actively ask, for any flow: *what happens right now if the relevant value is zero, missing, invalid, out of range, or only half-finished?* If the honest answer is "an unhandled error" or "nothing, it just breaks," that's the gap to close, using the concepts below.

## The concepts

### 1. Visibility of system status (feedback)
The user should always be able to tell what's happening and why, without guessing. Silence is the enemy — a click that produces no visible reaction reads as a bug even when it "worked correctly" by doing nothing.

- **Symptom:** a control is clicked (or hovered) and the user gets zero information about why it isn't proceeding.
- **Fix:** surface the reason immediately and in place — a tooltip on hover, an inline message next to the control, or a toast on click. "You need to verify your email before continuing" beats silence every time.
- **Fix:** Many times when the Agent try to design aligned icon in column in same row or same line with text the Agent forgot to add align-cneter that cause the icon be at the heavy top or just floating icon 

### 2. Affordance and signifiers
An *affordance* is what an element can actually do (a button can be pressed). A *signifier* is the visual cue that communicates that affordance (color, shape, cursor, shadow). When a control's signifiers say "active" but nothing happens on click, that's a **false signifier** — the single most common cause of "this app is broken" reports.

- **Fix A — Make the signifier match reality:** if the action truly isn't available yet, visually disable the control (reduced opacity, muted color, `not-allowed` cursor) so its appearance matches its behavior.
- **Fix B — Keep it active, intercept the click:** often better UX than a disabled control, especially when the missing prerequisite is easy to explain. Keep the control looking normal; on click, open a small modal or toast that names the exact missing step and offers a way to complete it. This turns a dead end into a guided next step instead of a mystery.

Choose A when the prerequisite is far away or the control shouldn't be discoverable yet. Choose B when the prerequisite is one short step away and naming it is more helpful than hiding it.

### 3. Error prevention (defensive design)
A good interface prevents the user from ever reaching a failure state, rather than explaining the failure well after the fact. A raw server error (403 Forbidden, 500, a generic "request failed") is a technical response, not a design — the user should almost never see one for a condition the client already knows about.

- **Symptom:** the user is allowed to attempt an action that the system already knows will be rejected (a quota exceeded, a permission missing, a plan limit reached), and only finds out after submitting.
- **Fix:** track the relevant state client-side and change the control *before* the user can trigger the failure. Once a limit is reached, the primary action should visibly change — its label, its icon, or its behavior on click — to reflect the new situation ("Limit reached" / "Upgrade to continue") rather than silently letting the request go through and fail.

### 4. Progressive disclosure (contextual guidance)
Don't front-load a wall of instructions or a separate help page. Show the user only the next thing they actually need to do, exactly where they need to do it.

- **Fix:** an inline checklist or short "setup progress" widget placed right next to the blocked action, showing completed and remaining steps in order — not an exhaustive manual, just the next step made unmissable. A person should never have to read paragraphs to figure out what to click first.

### 5. Empty states (zero-data states)
The first time a user reaches a section with nothing in it yet is a critical, high-leverage moment — and a bare blank list with a disabled button is the worst version of it.

- **Fix:** design the empty state deliberately. Combine a short illustration or icon, one sentence explaining what this section is for, and a single primary call-to-action that matches the user's *actual* current state — "Get started" if they're ready, or "Finish setup to continue" if a prerequisite is still outstanding. Never leave a first-run screen to speak for itself.

### 6. Eager (early) validation
A constraint should be checked the moment it becomes knowable, not saved up for the final submit. If choosing an option immediately implies a limit or rule — a balance, a stock count, a compatibility requirement — that's the moment to check it, using state the client already has or a lightweight request, not the moment the user has finished filling in everything else.

- **Symptom:** the user does several steps of work, clicks the final action, and only *then* learns about a constraint that was already true when they started.
- **Fix:** validate that constraint as soon as it's chosen or entered, and reflect the result immediately in the UI, so the rest of the flow (see concept 7) can already be shaped around it.

### 7. Constrain inputs to the real domain
It's better to make an invalid value impossible to enter than to let it be entered and reject it afterward. Two recurring versions of this:

- **Bounded values:** when a number has a known ceiling (an available balance, a stock count, a quota), set that ceiling as the input's actual bound so a value beyond it can't be typed in the first place. When the ceiling is zero, disable the input entirely, state the current value plainly ("Your balance is 0 — you need at least N to continue"), and give a direct way to resolve it (a link or button to the place that fixes the underlying cause).
- **Closed-set values:** when a field's only valid values are a fixed, known set that already exists in the system, never expose it as free text. Use a selection control (dropdown, radio group, autocomplete) populated from that real set. Free text on a closed-set field invites typos, invalid values, and data the backend has to defend against — the fix belongs at the input itself, not in a validation message after submission.

### 8. Match validation strictness to intent
Not every submission of a form means "this must be complete and correct." An action whose whole purpose is to save incomplete or in-progress work (a draft, an autosave, a "finish later") has a genuinely different intent than one that finalizes or publishes it — and needs a different, looser set of required fields, typically just enough to identify the record later (e.g. a name or title).

- **Symptom:** an incomplete-save action fails with "this field is required," because it runs through the exact same validation rules as the final, complete action.
- **Fix:** pass the actual intent to the backend explicitly (a status or mode flag), and branch the required-fields logic on it — the complete-and-correct rules apply only to the action that claims to produce a complete and correct result.

## Quick diagnostic table

| Symptom the user reports | Concept it violates | What to do about it |
|---|---|---|
| "I clicked it and nothing happened" | Visibility of system status | Surface the reason at the moment of the click |
| A control looks active but does nothing useful | False signifier | Either visually disable it, or keep it active and intercept the click with an explanation |
| User hits a raw error page (403/500/limit) instead of a warning | Error prevention | Detect the blocking condition on the client and change the control before the request can fail |
| User has no idea what to do next in a multi-step setup | Progressive disclosure | Replace instructions with an inline "next step" checklist near the blocked action |
| A first-run page is just blank | Empty state | Replace it with a purpose statement, a visual, and one clear CTA matched to the user's state |
| A constraint (balance, availability) is only discovered at final submit | Missing eager validation | Check it the moment the choice that depends on it is made, not only at submit |
| The user can type a number beyond a known real limit | Missing bounded-input constraint | Set the input's real max from the known limit; disable it entirely at zero, with a message and a way to resolve it |
| A field only makes sense as one of a fixed set of values, but it's a text box | Closed-set field exposed as free text | Replace it with a selection control sourced from the system's real valid values |
| Saving incomplete/in-progress work throws a "field required" error | Validation not matched to intent | Give that action a distinct mode/flag and relax the required-fields logic for that mode specifically |

## How to apply this when reviewing or building anything

Run through this whenever you touch an interactive element, not just the one that was reported as broken:

1. **Can this control ever be disabled, blocked, or limited?** If yes — does the UI say why, right there, without the user having to ask or guess?
2. **Does the visual style match the real behavior?** Enabled should look enabled and do something; disabled should look disabled. There should be no state where it *looks* active but silently does nothing.
3. **Can the user trigger an action that the system already knows will fail?** If so, catch that condition before the click is even actionable, not after the server responds.
4. **Is guidance delivered one step at a time, in context** — or does the user have to read a block of instructions to figure out what applies to them right now?
5. **Does every empty / first-run version of this screen explain itself and offer exactly one clear next action?**
6. **Is any constraint here checked only at final submit**, even though it was already knowable earlier in the flow?
7. **Does any input allow values that are already known to be invalid** — a number past a real limit, or free text for a field that should only ever hold one of a fixed set of values?
8. **Does this action share validation rules with a different action that has a different intent** (e.g. saving unfinished work vs. finalizing it)? If so, does each get the requirements that actually match its own purpose?

If the answer to any of these is "no," that's the specific fix to make — not a general "improve the UX" note.

## Worked example (generic flow)

A new account reaches a page for creating some kind of item, before and after meeting prerequisites, and after hitting a usage limit:

- **First visit, prerequisites unmet:** the list is empty. Instead of a blank table and a disabled button, show a one-line explanation of what this feature does and a CTA that reads "Complete setup to get started" (Empty state + Signifiers).
- **Prerequisites unmet, checklist visible:** near the primary action, an inline checklist shows what's done and what's left — e.g. one verified step and one outstanding step, in order, with the outstanding one clickable to resolve directly (Progressive disclosure).
- **Prerequisites met:** the primary action becomes genuinely active — its look now matches its behavior (Affordance/Signifiers restored).
- **Usage limit reached:** the primary action's label and behavior change to reflect the limit ("Limit reached — Upgrade" or similar). Clicking it opens an explanation with a path forward instead of letting the request hit the server and fail (Error prevention + Visibility of system status).

The throughline: at every stage, the interface itself tells the user what's true right now and what to do about it — nothing is left for them to infer, and nothing that looks active is allowed to do nothing.

## Worked example (generic form/validation flow)

A form lets a user pay with an internal balance, choose from a fixed set of system-defined options, and either finish the form or save it unfinished for later:

- **Choosing the balance-based payment method:** the moment it's selected, the available balance is checked (Eager validation) — not deferred to the final submit.
- **Entering the amount:** the input's maximum is set to the real available balance, so a value beyond it can't be typed at all; if the balance is zero, the input is disabled with a plain statement of the balance and a link to top it up (Bounded values).
- **Choosing from a fixed set of system values:** that field is a selection control populated from the system's real list, not a text box — the user picks, never types (Closed-set values).
- **Saving unfinished work:** the "finish later" action sends its own mode flag, and the backend only requires enough to identify the record (e.g. a name), instead of running the full required-fields check meant for a completed submission (Intent-aware validation).

The throughline here: nothing is left for the backend to reject after the fact that the frontend could already have prevented, and nothing is required of an action that never claimed to produce a finished result.

## Vocabulary reference

Use these terms precisely when discussing, documenting, or writing tickets about interface issues — they let you name the exact problem instead of describing symptoms:

- **Visibility of system status** — the system continuously informs the user of its state
- **Affordance** — what an element can actually do
- **Signifier** — the visual cue indicating an affordance
- **False signifier** — a signifier that promises an affordance that isn't actually available
- **Error prevention / defensive design** — preventing failure states rather than explaining them after the fact
- **Progressive disclosure** — revealing only what's relevant to the current step
- **Empty state / zero-data state** — the deliberate design of a screen with no content yet
- **Call-to-action (CTA)** — the single primary next action offered to the user
- **Happy path** — the flow that assumes everything goes right; code that only handles this is the root cause behind most of the symptoms above
- **Eager / early validation** — checking a constraint as soon as it's knowable, instead of waiting for final submit
- **Bounded input** — an input whose limits are set to match a real, known constraint, so out-of-range values can't be entered
- **Closed-set input** — a field restricted to a fixed set of known valid values, exposed through a selection control rather than free text
- **Intent-aware (mode-aware) validation** — required-fields logic that varies with what the action actually claims to produce (e.g. a saved draft vs. a finished submission)

## Resources for further grounding

- Nielsen Norman Group, *10 Usability Heuristics for User Interface Design* — the original source for "Visibility of System Status" and "Error Prevention" as named heuristics.
- Don Norman, *The Design of Everyday Things* — the source of the "affordance" and "signifier" vocabulary used above.
- Laws of UX (lawsofux.com) — short, citable summaries of these and adjacent principles, useful for quick reference or linking in documentation.
- Platform design guidelines (e.g. Material Design, Apple Human Interface Guidelines) — concrete conventions for how disabled states, tooltips, and inline errors should look and behave.
- Any collection of real empty-state design examples — useful as visual reference when a zero-data screen needs more than a sentence and a button.
- OWASP input validation guidance — the security-side case for closed-set controls and server-side bounds, beyond the pure UX argument.
- Baymard Institute's form and checkout UX research — evidence-based patterns for validation timing, field types, and error messaging in real forms.
