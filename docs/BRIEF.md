# Elite Stone — Website Brief

Source of truth: `database/seeders/assets/elite-stone-profile-2025.pdf` (Company Profile 2025).
Anything not in the profile is marked **assumed** below so the client can confirm or correct it from the admin panel.

## 1. Business

- **Company:** Elite Stone (إيليت ستون), Cairo, Egypt, founded 2019. Tagline: *From Concept to Creation*.
- **What they sell:** specialised floor systems (industrial epoxy, hygienic, decorative/metallic, cementitious) and contracting
  (concrete repair, water/thermal proofing, leak injection, finishing & fit-out, interior design, project management).
- **Who buys (B2B):** facility managers, factory owners, hotel/F&B operators, developers, consultants, main contractors.
- **Proof points used on the site:** 23 projects from the profile, clients (EDITA, Marriott, Nile Ritz-Carlton, ESCA, Elsewedy…),
  material partners (BASF Master Builders, Sika, Mapei…).

## 2. Research — what international leaders do

Benchmarks: Flowcrete (Tremco CPG), Stonhard (RPM), Sika Flooring, and B2B contractor-site guidance.

| Pattern seen on leaders | Applied here |
| --- | --- |
| Navigation by **sector** (food & beverage, healthcare, hospitality…) as well as by product | Sectors grid on home; project filter by sector and service |
| **Case studies** with challenge → solution → result | Project page: summary, challenge, solution, services, gallery |
| Prominent **RFQ** form with project fields (area, timeline, facility type) | `/contact` quote form with reference number and email + admin notification |
| Phone / WhatsApp always reachable (MENA buyers prefer calling) | Floating WhatsApp, header CTA, one-tap call in admin |
| Downloadable company profile | `/company-profile.pdf` in the footer and About page |
| Manufacturer partner logos as a trust signal | Partners wall |
| Bilingual with true RTL, not mirrored English | `/en` + `/ar`, Arabic typography (IBM Plex Sans Arabic), hreflang |

## 3. Design system

- **Pattern:** Trust & Authority + Conversion (from `ui-ux-pro-max`).
- **Colour (from the logo):** navy `#233565` / deep navy `#1E293F`, greige `#E2DDD9`, white. Accent only for status colours.
- **Type:** Vollkorn (display serif, echoes the logo wordmark), Manrope (body), IBM Plex Sans Arabic (Arabic).
- **Signature graphics:** the angled "shards" from the profile cover, the stone mark as a watermark.
- **Photography:** only real profile photos; large, honest, no stock-looking overlays.
- **Motion:** subtle reveal on scroll, disabled under `prefers-reduced-motion`.

## 4. Prompts used to drive the build

**Design prompt**

> Design a bilingual (EN/AR, full RTL) corporate website for Elite Stone, a Cairo specialist flooring and contracting
> company selling B2B to factories, hotels and developers. Mood: engineered, calm, premium; navy #233565 / greige
> #E2DDD9; serif display (Vollkorn) with a clean sans body. Every page must lead to one action: request a quote (or
> WhatsApp/call). Prove capability with real project photos, client and partner logos, a 4-step process and numbers.
> Mobile first, AA contrast, 44px touch targets, visible labels, no jargon.

**Admin prompt**

> Build a Filament admin for staff who are not technical. Arabic by default with a one-click language switch.
> Every bilingual field shows English and Arabic side by side with a one-line hint. Images upload by drag-and-drop and
> are resized automatically. Lists reorder by drag. Quote requests are an inbox: a "new" badge, status in one click,
> and Call / WhatsApp / Email buttons. The dashboard opens with a welcome card and the three most common tasks.

## 5. Assumptions to confirm with the client

- Working hours: Saturday–Thursday, 9:00–18:00.
- Office location shown as Cairo (map pin is the city, not an exact address).
- Project locations, years and the challenge/solution texts are written from the profile's project list and are
  representative; edit them in **Admin → Projects**.
- Project cover photos are matched to each project from the profile's photo pages; replace with the actual site photos when available.
- Stats ("projects delivered", "years") are calculated from the content in the admin.

## 6. Going live checklist

- `.env`: `APP_URL=https://elitestone-corp.com`, `APP_ENV=production`, `APP_DEBUG=false`, real `MAIL_*` settings.
- Set `ADMIN_PASSWORD` before seeding, or change the password from the profile page after the first login.
- `php artisan migrate --seed --force && php artisan storage:link && npm ci && npm run build`.
- Set "notification email" in **Admin → Website settings** to the inbox that should receive quote requests.
