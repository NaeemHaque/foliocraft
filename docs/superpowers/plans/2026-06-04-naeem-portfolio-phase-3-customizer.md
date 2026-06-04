# Naeem Portfolio — Phase 3: Customizer & Profile — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make every remaining hardcoded section editable from **Appearance → Customize** — hero, about, skills, open source, social links, contact copy, footer, accent color, the headshot image, and the résumé PDF — via a `Profile` model and a Customizer panel, with defaults that keep the site visually identical until the user changes something.

**Architecture:** A `Naeem\Models\Profile` model exposes `all()` → a structured array read from `get_theme_mod( $key, $default )` (defaults = the current hardcoded content, so unset = unchanged). The `Front_Page` controller passes `$profile` to every front view; each view reads `$profile[...]` instead of hardcoded text. A `Naeem\Customizer\Customizer` class registers a "Portfolio" panel with one section per content area, all settings sanitized. Fixed-count structures (3 stats, 3 pillars, 4 skill groups, 4 OS cards) use flat numbered settings; variable lists (role lines, OS areas, skill pills) use one-per-line textareas — **no custom repeater JS needed**. Section/card **icons stay in the templates** (not user-editable); only text/links/images are editable.

**Tech Stack:** PHP 7.4+ · WordPress 7.0 Customizer API · WP-CLI (`wp theme mod`, `wp eval`) for verification.

---

## Spec & source

- Spec `docs/superpowers/specs/2026-06-03-naeem-portfolio-theme-design.md` §10 (Customizer settings).
- **Defaults = the exact text currently in the views** — when a getter needs a default, copy it verbatim from the matching `template-parts/front/*.php` (those are the approved strings). Read the view before wiring it.

## Conventions (every task)

- PHP 7.4+; WP Coding Standards; `defined( 'ABSPATH' ) || exit;` after any namespace; text domain `naeem-portfolio`.
- Every Customizer setting has a `sanitize_callback`. Output stays escaped in the views (`esc_html`/`esc_url`/`esc_attr`/`wp_kses_post` for rich text).
- Theme-mod key prefix: `naeem_` (e.g., `naeem_hero_lead`).
- **Commits: NO `Co-Authored-By` trailer.** Work on branch `phase-3-customizer`; commit per task. Branch is kept after merge (not deleted).
- Verify with `wp eval`, `wp theme mod set/get`, and `curl`. `SITE="$(wp option get home)"`.

## Sanitizer helpers (in the Customizer class)

- `sanitize_text` → `sanitize_text_field`
- `sanitize_html` → `wp_kses_post` (for the lead/about paragraphs containing `<strong>`)
- `sanitize_url` → `esc_url_raw`
- `sanitize_email` → `sanitize_email`
- `sanitize_lines` → `sanitize_textarea_field` (one-per-line lists)
- `sanitize_hex` → `sanitize_hex_color`
- Numbers/ints where needed → `absint`

## Profile model shape (`Profile::all()`)

```
identity   => [ name, brand ]
hero       => [ status, name_line1, name_line2, roles[] (from lines), lead(html),
                cta_primary_label, cta_primary_url, cta_resume_label, stats[ {num,label} x3 ] ]
about      => [ heading, paragraphs[ html x2 ], pillars[ {title,desc} x3 ] ]
skills     => [ groups[ {label, pills[]} x4 ] ]
opensource => [ heading, lead, areas[], cards[ {title,role,blurb} x4 ] ]
social     => [ github, linkedin, email ]
contact    => [ heading, lead, fluent_shortcode ]
resume     => [ url, label ]
headshot   => [ url ]
accent     => hex
footer     => [ copy ]
```
A list getter splits a textarea theme-mod on newlines: `array_values( array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) )`, falling back to the default array when empty.

## Before Task 1
```bash
cd wp-content/themes/naeem-portfolio
git checkout -b phase-3-customizer
```

---

### Task 1: Profile model + Customizer scaffold + controller threading

**Files:** Create `inc/Models/Profile.php`, `inc/Customizer/Customizer.php`; Modify `functions.php` (boot both), `inc/Controllers/Front_Page.php` (pass `$profile` to all front views).

- **Profile.php** — `Naeem\Models\Profile` with a static `all()` returning the full structured array above. Each leaf reads `get_theme_mod( 'naeem_<key>', <default> )`. **Defaults = current view text** (read each `template-parts/front/*.php` to copy the exact strings). Provide a private `lines( $key, array $default )` helper for the textarea-backed lists (roles, areas, pills). For nested fixed structures (stats/pillars/groups/cards), read each flat sub-key (e.g., `naeem_stat1_num`, `naeem_pillar2_title`).
- **Customizer.php** — `Naeem\Customizer\Customizer::init()` adds `customize_register` → `register( $wp_customize )` which adds `$wp_customize->add_panel( 'naeem_portfolio', array( 'title' => __( 'Portfolio', 'naeem-portfolio' ), 'priority' => 30 ) );` and the empty sections that later tasks populate: `naeem_identity`, `naeem_hero`, `naeem_about`, `naeem_skills`, `naeem_opensource`, `naeem_social`, `naeem_contact`, `naeem_resume`, `naeem_footer` (each `add_section` under the panel). Include the sanitizer helper methods (above) as `public static` so later tasks reuse them. Register NO controls yet beyond the panel/sections.
- **functions.php** — after the meta inits, add `\Naeem\Customizer\Customizer::init();` (Profile is a model, autoloaded on demand — no boot needed).
- **Front_Page.php** — at the top of `render()`, `$profile = \Naeem\Models\Profile::all();` then merge it into each front view's data, e.g. `View::render( 'front/hero', array( 'profile' => $profile ) );` … for hero, about, opensource, skills, contact (the looping sections keep their model data and ALSO get `'profile' => $profile`). Keep `blog_url`/`items`/`projects`/`terms`/`posts` as-is, adding `'profile' => $profile` to each array.

This task makes **no visual change** (views still use hardcoded text; they'll read `$profile` in later tasks).

- [ ] **Step 1 — failing check:** `wp eval 'echo class_exists("Naeem\\Models\\Profile")?"YES":"NO";'` → `NO`.
- [ ] **Step 2 — create the two classes + edit functions.php + controller.**
- [ ] **Step 3 — verify:**
```bash
php -l inc/Models/Profile.php && php -l inc/Customizer/Customizer.php && php -l functions.php && php -l inc/Controllers/Front_Page.php
wp eval '$p = Naeem\Models\Profile::all(); echo (isset($p["hero"]["roles"]) && is_array($p["hero"]["roles"]) && isset($p["about"]["pillars"][0]["title"]) && isset($p["accent"])) ? "PROFILE OK" : "FAIL";'
wp eval 'global $wp_customize; require_once ABSPATH."wp-includes/class-wp-customize-manager.php"; $m = new WP_Customize_Manager(); do_action("customize_register",$m); echo $m->get_panel("naeem_portfolio") ? "PANEL OK" : "FAIL";'
SITE="$(wp option get home)"; curl -s "$SITE" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
Expected: lint passes; `PROFILE OK`; `PANEL OK`; `0`.
- [ ] **Step 4 — commit:** `git add inc/Models/Profile.php inc/Customizer functions.php inc/Controllers/Front_Page.php && git commit -m "feat(customizer): Profile model + Customizer panel scaffold + controller threading"`

---

### Task 2: Identity, Social, Footer, Accent — settings + wiring

**Files:** Modify `inc/Customizer/Customizer.php` (add controls); `template-parts/layout/footer.php`, `template-parts/layout/nav.php`, `header.php`, `template-parts/front/contact.php` (use `$profile`).

**Controls to register** (each `add_setting` with `default` + sanitize + `add_control`):
- `naeem_identity` section: `naeem_brand` (text, default `naeem`), plus `add_theme_support('custom-logo')` already exists.
- `naeem_social` section: `naeem_social_github` (url), `naeem_social_linkedin` (url), `naeem_social_email` (email) — defaults from the current contact/footer links.
- `naeem_footer` section: `naeem_footer_copy` (text; default from `template-parts/layout/footer.php` copy line).
- `naeem_identity` (or a "Theme" section): `naeem_accent` (`WP_Customize_Color_Control`, default `#e6926b`, sanitize `sanitize_hex_color`).

**Wiring:**
- **Accent:** in `header.php`, after the pre-paint theme script, output `<style>:root{--accent:<?php echo esc_html( $accent ); ?>;}</style>` only when the mod differs from default — get it via `\Naeem\Models\Profile::all()['accent']`. (Inline so it overrides the stylesheet token without a rebuild.)
- **footer.php (layout):** the `<span id="year">…</span>` line keeps the year; replace the surrounding copyright text with `$profile['footer']['copy']` (pass `$profile` to it: in `footer.php` theme template, `\Naeem\Core\View::render( 'layout/footer', array( 'profile' => \Naeem\Models\Profile::all() ) )`). The GitHub/LinkedIn footer links use `esc_url( $profile['social']['github'/'linkedin'] )`.
- **nav.php:** brand text uses `$profile['identity']['brand']` — pass profile to it from `header.php` similarly, or call `Profile::all()` inside. (Keep it simple: `header.php` computes `$naeem_profile = \Naeem\Models\Profile::all();` once and passes to progress/nav/mobile-menu/footer renders.)
- **contact.php:** the three channel hrefs (`mailto:`, github, linkedin) use `$profile['social']`.

- [ ] **Step 1 — failing check:** `wp eval 'global $wp_customize; require_once ABSPATH."wp-includes/class-wp-customize-manager.php"; $m=new WP_Customize_Manager(); do_action("customize_register",$m); echo $m->get_setting("naeem_social_github")?"YES":"NO";'` → `NO`.
- [ ] **Step 2 — add the controls + wire the views/header.**
- [ ] **Step 3 — verify (set a mod, see it render):**
```bash
php -l inc/Customizer/Customizer.php && php -l header.php && php -l template-parts/layout/footer.php && php -l template-parts/layout/nav.php && php -l template-parts/front/contact.php
wp theme mod set naeem_accent "#4f9da6"
wp theme mod set naeem_footer_copy "© Custom footer test"
SITE="$(wp option get home)"; H="$(curl -s "$SITE")"
printf '%s' "$H" | grep -c '#4f9da6'              # >=1 (accent override emitted)
printf '%s' "$H" | grep -c 'Custom footer test'   # 1
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
wp theme mod remove naeem_accent; wp theme mod remove naeem_footer_copy
```
Expected: lint passes; accent hex present; custom footer present; `0` errors. (Cleanup removes the test mods so defaults render again.)
- [ ] **Step 4 — commit:** `git add inc/Customizer/Customizer.php header.php template-parts/layout/footer.php template-parts/layout/nav.php template-parts/front/contact.php && git commit -m "feat(customizer): identity, social, footer, and accent-color settings"`

---

### Task 3: Hero — settings + wiring + role localization

**Files:** Modify `inc/Customizer/Customizer.php`, `template-parts/front/hero.php`, `inc/Assets.php`, `assets/js/main.js`.

**Controls** (`naeem_hero` section): `naeem_hero_status` (text), `naeem_hero_name1` + `naeem_hero_name2` (text), `naeem_hero_roles` (textarea, one role per line, sanitize_lines), `naeem_hero_lead` (textarea, sanitize_html), `naeem_hero_cta_label` + `naeem_hero_cta_url` (text/url), `naeem_hero_resume_label` (text), and three stats `naeem_stat{1,2,3}_num` + `naeem_stat{1,2,3}_label` (text). Defaults from `template-parts/front/hero.php`.

**Wiring:**
- **hero.php:** read everything from `$profile['hero']` (status, name lines, lead via `wp_kses_post`, CTA label/url, résumé label, the three stats num/label). Keep the `<span id="typed"></span>`, terminal card, and SVGs as-is.
- **Roles → JS:** in `inc/Assets.php` `enqueue()`, after enqueuing `naeem-main`, `wp_localize_script( 'naeem-main', 'NaeemData', array( 'roles' => \Naeem\Models\Profile::all()['hero']['roles'] ) );`.
- **main.js:** change the `roles` array initialization to `var roles = (window.NaeemData && window.NaeemData.roles && window.NaeemData.roles.length) ? window.NaeemData.roles : [ ...existing default array... ];` (keep the existing array as the fallback). Rebuild not needed (main.js is shipped un-built).

- [ ] **Step 1 — failing check:** `curl -s "$(wp option get home)" | grep -c 'id="typed"'` → `1` (hero already renders; this confirms baseline). Then `wp eval` that `naeem_hero_status` setting is absent (as Task 2 pattern).
- [ ] **Step 2 — add controls + wire hero + localize roles + main.js fallback.**
- [ ] **Step 3 — verify:**
```bash
php -l template-parts/front/hero.php && php -l inc/Assets.php && php -l inc/Customizer/Customizer.php && node --check assets/js/main.js
wp theme mod set naeem_hero_status "Custom status line"
SITE="$(wp option get home)"; H="$(curl -s "$SITE")"
printf '%s' "$H" | grep -c 'Custom status line'   # 1
printf '%s' "$H" | grep -c 'NaeemData'            # >=1 (localized roles inline)
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
wp theme mod remove naeem_hero_status
```
Expected: lint + node check pass; custom status renders; `NaeemData` present; `0` errors.
- [ ] **Step 4 — commit:** `git add inc/Customizer/Customizer.php template-parts/front/hero.php inc/Assets.php assets/js/main.js && git commit -m "feat(customizer): hero settings + Customizer-driven typed roles"`

---

### Task 4: About + Skills — settings + wiring

**Files:** Modify `inc/Customizer/Customizer.php`, `template-parts/front/about.php`, `template-parts/front/skills.php`.

**Controls:**
- `naeem_about` section: `naeem_about_heading` (text), `naeem_about_p1` + `naeem_about_p2` (textarea, sanitize_html), and three pillars `naeem_pillar{1,2,3}_title` (text) + `naeem_pillar{1,2,3}_desc` (text). Defaults from `template-parts/front/about.php`.
- `naeem_skills` section: four groups `naeem_skillgroup{1..4}_label` (text) + `naeem_skillgroup{1..4}_pills` (textarea, one pill per line, sanitize_lines). Defaults from `template-parts/front/skills.php`.

**Wiring:**
- **about.php:** heading from `$profile['about']['heading']`; the two paragraphs from `$profile['about']['paragraphs']` (via `wp_kses_post`); loop the three pillars from `$profile['about']['pillars']` for title/desc (keep the three pillar **icons** hardcoded in order).
- **skills.php:** loop the four groups from `$profile['skills']['groups']` — group label + pills (keep the four group **icons** hardcoded in order). Each pills list renders `<span class="skill-pill">` per entry.

- [ ] **Step 1 — failing check:** `curl -s "$(wp option get home)" | grep -c 'class="pillar"'` → `3` (baseline).
- [ ] **Step 2 — add controls + wire both views.**
- [ ] **Step 3 — verify:**
```bash
php -l inc/Customizer/Customizer.php && php -l template-parts/front/about.php && php -l template-parts/front/skills.php
wp theme mod set naeem_about_heading "Custom about heading"
wp theme mod set naeem_skillgroup1_label "Custom Group"
SITE="$(wp option get home)"; H="$(curl -s "$SITE")"
printf '%s' "$H" | grep -c 'Custom about heading'   # 1
printf '%s' "$H" | grep -c 'Custom Group'           # 1
printf '%s' "$H" | grep -c 'class="pillar"'         # 3 (still 3)
printf '%s' "$H" | grep -c 'skill-group'            # 4
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
wp theme mod remove naeem_about_heading; wp theme mod remove naeem_skillgroup1_label
```
Expected: lint passes; custom heading + group render; 3 pillars / 4 groups; `0` errors.
- [ ] **Step 4 — commit:** `git add inc/Customizer/Customizer.php template-parts/front/about.php template-parts/front/skills.php && git commit -m "feat(customizer): about + skills settings"`

---

### Task 5: Open Source + Contact + Résumé + Headshot — settings, wiring, final verification

**Files:** Modify `inc/Customizer/Customizer.php`, `template-parts/front/opensource.php`, `template-parts/front/contact.php`, `template-parts/front/hero.php`.

**Controls:**
- `naeem_opensource` section: `naeem_os_heading` (text), `naeem_os_lead` (textarea, sanitize_html), `naeem_os_areas` (textarea, one area per line, sanitize_lines), and four cards `naeem_oscard{1..4}_title` + `_role` + `_blurb` (text). Defaults from `template-parts/front/opensource.php`.
- `naeem_contact` section: `naeem_contact_heading` (text), `naeem_contact_lead` (textarea, sanitize_html), `naeem_contact_fluent` (text — a Fluent Forms shortcode; default empty).
- `naeem_resume` section: `naeem_resume_pdf` (`WP_Customize_Media_Control`, `mime_type => application/pdf`, setting sanitize `esc_url_raw`), `naeem_resume_label` (text, default "Download Résumé").
- `naeem_identity` (headshot): `naeem_headshot` (`WP_Customize_Media_Control`, `mime_type => image`, sanitize `esc_url_raw`).

**Wiring:**
- **opensource.php:** heading/lead from `$profile['opensource']`; the `.os-areas` loop from `$profile['opensource']['areas']` (keep the ✓ tick); the four `.os-card`s loop from `$profile['opensource']['cards']` (title/role/blurb), keeping the four **glyph icons** hardcoded in order.
- **contact.php:** heading + lead from `$profile['contact']`. If `$profile['contact']['fluent_shortcode']` is non-empty, render `echo do_shortcode( $shortcode )` **inside the form column instead of** the native `<form>` (wrap the native form in `<?php if ( empty($fluent) ) : ?>…native form…<?php else : ?><?php echo do_shortcode($fluent); ?><?php endif; ?>`).
- **hero.php:** the résumé button — if `$profile['resume']['url']` is set, link it (`href="<?php echo esc_url($url); ?>"` + `download` removed of the JS toast for real files); label from `$profile['resume']['label']`. The headshot `<div class="headshot-slot">` — if `$profile['headshot']['url']` set, render `<img src="…" alt="" class="headshot-slot" />` (or set it as a background) instead of the empty placeholder.

**Final Phase 3 verification (whole page + a couple of mods):**
- [ ] **Step 1 — failing check:** `wp eval` that `naeem_os_heading` setting is absent (Task 2 pattern).
- [ ] **Step 2 — add controls + wire the three views.**
- [ ] **Step 3 — verify:**
```bash
php -l inc/Customizer/Customizer.php && php -l template-parts/front/opensource.php && php -l template-parts/front/contact.php && php -l template-parts/front/hero.php
wp theme mod set naeem_os_heading "Custom OS heading"
wp theme mod set naeem_contact_heading "Custom contact heading"
SITE="$(wp option get home)"; H="$(curl -s "$SITE")"
printf '%s' "$H" | grep -c 'Custom OS heading'       # 1
printf '%s' "$H" | grep -c 'Custom contact heading'  # 1
printf '%s' "$H" | grep -c 'os-card'                 # >=4 (still 4)
printf '%s' "$H" | grep -c 'id="contactForm"'        # 1 (native form, no shortcode set)
for id in hero about experience work opensource stack writing contact; do printf "%s=%s " "$id" "$(printf '%s' "$H" | grep -c "id=\"$id\"")"; done; echo
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
wp theme mod remove naeem_os_heading; wp theme mod remove naeem_contact_heading
```
Expected: lint passes; both custom headings render; 4 OS cards; native form present; all 8 sections `=1`; `0` errors.
- [ ] **Step 4 — manual check (note in commit body):** Appearance → Customize shows the **Portfolio** panel with all sections; editing Hero/About/Skills/Open Source/Social/Résumé/Headshot live-previews and saves; with all mods cleared the site is identical to Phase 2.
- [ ] **Step 5 — commit:** `git add inc/Customizer/Customizer.php template-parts/front/opensource.php template-parts/front/contact.php template-parts/front/hero.php && git commit -m "feat(customizer): open source, contact, résumé, and headshot settings"`

---

## Phase 3 — Definition of Done

- **Appearance → Customize → Portfolio** panel exposes hero, about, skills, open source, identity/brand, social, contact, résumé PDF, headshot, footer, and accent color — all sanitized.
- Every front section reads its text/links/images from the `Profile` model; with no mods set the page is visually identical to Phase 2.
- Changing a setting (e.g., accent, hero status, a skill group) updates the front page; the résumé button links the uploaded PDF; the headshot image renders when set; a Fluent Forms shortcode (if provided) replaces the native form.
- **Zero PHP notices**; main.js still passes `node --check`; all 8 sections render.
- Branch `phase-3-customizer` holds 5 commits (no Co-Authored-By).

## Self-review (plan author)

- **Spec coverage (§10):** identity/brand/logo (T2), hero incl. roles/CTAs/stats + accent (T3, T2), social (T2), about + skills (T4), open source + contact + résumé + headshot (T5), footer (T2). All sanitized via the shared helpers.
- **Placeholder scan:** none — every task names its theme-mod keys, control types, sanitizers, and the view to wire; defaults are sourced verbatim from the existing views (concrete, in-repo).
- **Name consistency:** `naeem_` mod prefix throughout; `Profile::all()` shape consumed by views via `$profile[...]`; `NaeemData.roles` localized key matches the main.js fallback read.
- **No-visual-change guarantee:** Task 1 threads `$profile` but views keep hardcoded text until their section task wires them — so the build never regresses visually between tasks, and defaults equal the current strings.
- **Deferred:** single/archive/blog-page + résumé *page template* (Phase 4); contact form *handler* + i18n `.pot` + a11y/perf polish (Phase 5). (Phase 3 wires the résumé *PDF link* and the Fluent Forms *shortcode option*, not the native handler.)
