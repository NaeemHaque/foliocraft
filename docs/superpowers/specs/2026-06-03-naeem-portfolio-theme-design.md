# Naeem Portfolio — WordPress Theme Design Spec

**Date:** 2026-06-03
**Author:** Golam Sarwer Naeem (with Claude)
**Status:** Approved — ready for implementation planning

---

## 1. Overview

Build `naeem-portfolio`: a custom, production-ready **classic WordPress theme** for the personal
portfolio of Golam Sarwer Naeem (Software Engineer & Open Source Contributor at WPManageNinja).

The theme recreates the approved high-fidelity prototype **pixel-perfectly** while being **fully
manageable from `wp-admin`** — no code edits to add a project, write a post, or update the
résumé. It is organized with a **lightweight custom PHP MVC layer** sitting underneath the standard
WordPress template hierarchy, and styled with the prototype's existing **tokenized component CSS
compiled through a Tailwind / PostCSS pipeline**.

### Design source of truth

The visual spec is the prototype bundle saved in the theme repo at `docs/design-reference/` (all paths in this spec are relative to the theme root, `wp-content/themes/naeem-portfolio/`):

- `project/Naeem Portfolio.html` — **primary** single-page design (all 8 sections). Confirmed by the user as the exact target.
- `project/assets/theme.css` — the ~800-line tokenized component CSS (the pixel-perfect spec).
- `project/assets/site.js` — vanilla interactions to port.
- `project/Blog.html` — the "all posts" listing design.
- `project/WordPress Architecture.html` — the handoff doc describing the intended WP architecture (this spec follows it).
- `chats/chat1.md` — the full build prompt + design iteration history.

> Prototype-only artifacts that are **not** ported: the React/Babel "Tweaks" panel
> (`tweaks-panel.jsx`, `tweaks-app.jsx`), the `<image-slot>` web component (`image-slot.js`), and
> the footer "Theme architecture" link (the architecture HTML is a handoff doc, not a live page).

---

## 2. Goals & non-goals

### Goals
- Pixel-perfect recreation of `Naeem Portfolio.html` + `Blog.html` in both light and dark themes.
- Fully responsive, mobile-first (breakpoints match the design: 900px and 620px).
- Custom PHP MVC layering (Models / Controllers / Views) with no heavy framework dependency.
- All content editable from `wp-admin` via CPTs, taxonomies, post meta, and the Customizer.
- Tailwind/PostCSS build pipeline; compiled CSS/JS committed so a fresh clone runs without building.
- WordPress coding standards: escaping/sanitization, nonces, i18n (`naeem-portfolio` text domain), theme supports, accessibility (WCAG AA), SEO-friendly markup.

### Non-goals
- Not a block/FSE theme (classic theme chosen — see Decision D1).
- No required third-party plugins out of the box (Fluent Forms is an optional drop-in).
- No multi-author/membership features; single-author portfolio.
- Not porting the prototype's Tweaks panel (accent/font/motion swapping) — accent is a Customizer setting instead.

---

## 3. Confirmed decisions

| # | Decision | Choice | Rationale |
|---|---|---|---|
| D1 | Theme type | **Classic theme + Tailwind** | Full markup/asset control, maps to template hierarchy, can still register blocks; matches handoff doc. |
| D2 | MVC implementation | **Custom PHP MVC** (no Sage/Timber) | No framework lock-in, lightest runtime, dependency-free (npm only), maximum pixel-perfect control. |
| D3 | CSS strategy | **Keep component CSS + Tailwind layer** | Protects the pixel-perfect spec; tokens shared between utilities and components. |
| D4 | Scope | **Full dynamic theme, phased** | Complete brief delivered in reviewable phases. |
| D5 | Experience data | **`experience` CPT** | Clean post-style editing + `menu_order` ordering (vs. clunky Customizer repeater). |
| D6 | Open Source data | **`contribution_area` taxonomy + Customizer cards** | Follows handoff doc; keeps admin lean. |
| D7 | Version control | **`git init` inside the theme dir** (`wp-content/themes/naeem-portfolio/`) | The theme is the self-contained versioned unit; the surrounding WordPress install is **not** versioned. |
| D8 | Contact form | **Native nonce + `wp_mail` handler**, Fluent Forms optional via Customizer shortcode | Works with zero plugins; on-brand Fluent Forms drop-in available. |
| D9 | Project meta | **Native meta box** (Live URL, GitHub URL) | No ACF dependency; sanitized + nonce-protected. |

---

## 4. MVC architecture

WordPress routing (the template hierarchy) is preserved. Each PHP template file is a **thin entry
point** that instantiates a controller and renders:

```php
// front-page.php
( new \Naeem\Controllers\Front_Page() )->render();
```

- **Models** (`inc/Models/`) — the only layer that talks to `WP_Query`, CPTs, taxonomies, post
  meta, and Customizer values. They return plain context arrays/DTOs. Views never query the DB.
- **Controllers** (`inc/Controllers/`) — one per template/route. Pull from models, assemble a view
  context, render the appropriate view(s).
- **Views** (`template-parts/`) — presentation-only PHP partials that receive a `$data` context and
  emit **escaped** markup.

A small `Core\View` helper renders views:

```php
\Naeem\Core\View::render( 'front/hero', $data ); // extract + include template-parts/front/hero.php
```

Class autoloading: a ~20-line `spl_autoload_register` maps the `Naeem\` namespace to `inc/`
(`Naeem\Controllers\Front_Page` → `inc/Controllers/Front_Page.php`). **No Composer required.**

Bootstrap order in `functions.php`: autoloader → `Theme` (supports/menus/image sizes) →
`Assets` (enqueue) → `PostTypes\*` + `Taxonomies` → `Meta\Project_Meta` → `Customizer` →
`Forms\Contact`.

---

## 5. File structure

```
wp-content/themes/naeem-portfolio/
├── style.css                 # theme header (meta only; compiled CSS lives in assets/css)
├── functions.php             # bootstrap
├── header.php · footer.php · front-page.php
├── index.php · home.php · single.php · archive.php · category.php
├── page.php · page-resume.php · 404.php
├── inc/
│   ├── Autoloader.php · Theme.php · Assets.php
│   ├── Core/View.php
│   ├── Models/ (Project.php, Experience.php, Post.php, Profile.php, Contribution.php)
│   ├── Controllers/ (Front_Page.php, Blog.php, Single_Post.php, Archive.php, Page.php, Resume.php)
│   ├── PostTypes/ (Project_CPT.php, Experience_CPT.php, Taxonomies.php)
│   ├── Meta/Project_Meta.php
│   ├── Customizer/Customizer.php
│   └── Forms/Contact.php
├── template-parts/
│   ├── layout/ (nav.php, mobile-menu.php, progress.php, footer.php)
│   ├── front/ (hero.php, about.php, experience.php, projects.php, opensource.php, skills.php, blog.php, contact.php)
│   ├── cards/ (project-card.php, post-card.php, os-card.php, pillar.php, timeline-item.php)
│   └── blog/ (post-grid.php, post-filter.php)
├── assets/
│   ├── src/ (tailwind.css, tokens.css, components.css, main.js)
│   ├── css/app.css           # compiled + committed
│   └── js/main.js            # committed
│   └── img/                  # static assets, headshot fallback
├── languages/naeem-portfolio.pot
├── docs/ (design-reference/, superpowers/specs/)   # versioned with the theme
├── tailwind.config.js · postcss.config.js · package.json · .gitignore · README.md
```

> The theme directory is also the **git repo root** (D7) — `docs/` lives inside it, not at the WordPress install root.

---

## 6. CSS / Tailwind strategy

- **Tokens** ported from `theme.css :root` into `assets/src/tokens.css` (CSS custom properties) and
  mirrored into `tailwind.config.js` `theme.extend` so utilities and components share one source.
  Key tokens to preserve exactly:
  - Fonts: `--font-display: "Space Grotesk","Sora"`, `--font-body: "IBM Plex Sans"`, `--font-mono: "IBM Plex Mono"`.
  - Accent: `--accent: #e6926b` (locked coral default; exposed as a Customizer color setting).
  - Rhythm: `--section-pad: clamp(72px,9vw,140px)`, `--gutter: clamp(20px,5vw,64px)`, `--maxw: 1180px`.
  - Radii: `--radius: 14px`, `--radius-sm: 9px`, `--radius-lg: 22px`; `--ease: cubic-bezier(.22,.61,.36,1)`.
  - Full dark palette (`--bg:#0a0c11`, `--panel:#10131a`, `--text:#e7ebf2`, …) and light palette (`--bg:#f7f7f4`, `--panel:#fff`, `--text:#14171d`, …) under `[data-theme="dark"]` / `[data-theme="light"]`.
- **Component CSS**: the design's classes (`.hero`, `.btn`, `.proj-card`, `.timeline`, `.os-card`,
  `.channel`, `.form`, etc.) ported verbatim into `assets/src/components.css`, included via
  `@layer components`. **This is the pixel-perfect guarantee** — match it exactly.
- `assets/src/tailwind.css`:
  ```css
  @import "./tokens.css";
  @tailwind base;
  @tailwind components;
  @tailwind utilities;
  @layer components { @import "./components.css"; }   /* postcss-import inlines it into the components layer */
  ```
  (The exact import/layer mechanism is finalized in the implementation plan; the requirement is that component styles win over Tailwind base without `!important`.)
- Utilities prefixed (`tw-`) to avoid clashes with plugin CSS on the front end.
- Build: Tailwind CLI + PostCSS (`postcss-import`, `autoprefixer`, `cssnano` on build).
  `npm run dev` watches; `npm run build` minifies to `assets/css/app.css` (committed).
- Google Fonts enqueued (preconnect + the existing `css2` request for Space Grotesk, Sora, IBM Plex Sans, IBM Plex Mono).
- Light/dark via `data-theme` attribute + a **pre-paint inline script** in `header.php`
  (reads `localStorage['naeem-theme']`, default `dark`) to avoid flash; the toggle persists.

---

## 7. Content model & section mapping

Front page section order and anchors match the design exactly:
`#hero → #about (01) → #experience (02) → #work (03) → #opensource (04) → #stack (05) → #writing (06) → #contact (07)`.
Primary nav links: about, experience, work, open-source, stack, writing. Contact is the nav-right
button + the mobile menu (matches the design after its final iteration).

| Section | Source | Editing | Seed content |
|---|---|---|---|
| **Hero** | `Profile` model (Customizer) | name, rotating role lines, status pill text, one-liner, CTA labels+links, 3 stats, headshot image, résumé PDF | Roles: Software Engineer / Open Source Contributor / WordPress Product Engineer / Laravel + Vue developer / Clean-architecture advocate. Stats: `5+` Years building, `6` WP focus areas, `M+` Users reached. Terminal card = static decorative `profile.php` markup. |
| **About** | `Profile` (Customizer) | heading, 2 body paragraphs, 3 "pillar" cards (icon + title + text) | Pillars: Clean architecture / Open by default / Contest-grade detail. Bio per brief. |
| **Experience** | `experience` CPT | per entry: role (title), company, date range, "current" flag, description, tech tags (`tech` taxonomy), `menu_order` | 4 entries: WPManageNinja (Current), Full-Stack Developer, Open Source Contributor, B.Sc. CSE. |
| **Projects** | `project` CPT + `tech` taxonomy + meta (Live URL, GitHub URL) | Projects → Add New; title, editor/excerpt (desc), featured image/icon, tech terms, URLs | 6 projects: DevPulse, WP Schema Pilot, QueryLens, Fluent Blocks Kit, LaravelKit Starter, Polyglot Helper. Filter pills auto-built from `tech` terms (all + each term). |
| **Open Source** | `contribution_area` taxonomy + Customizer | areas list + 4 highlight cards (title, role label, blurb, and an icon picked from a predefined SVG set) | Areas: Core, Plugins, Meta, Polyglots, Photos, EmDash CMS. Cards: WP Core / Polyglots / Photos & Meta / EmDash CMS. |
| **Skills** | `Profile` (Customizer) | 4 groups, each a label + list | Backend (PHP, Laravel, WordPress, REST APIs); Frontend (Vue.js, Tailwind CSS, JavaScript, HTML/CSS); Databases (MySQL, Schema design, Query tuning); Tools & AI (Git, AI workflow, Composer, Vite, CLI tooling). |
| **Blog preview** | `Post` model (latest 3 posts) | Posts → Add New | 3 latest posts; "All posts" → blog listing. |
| **Contact** | `Profile` (Customizer) + `Forms\Contact` | intro copy, channels (email/GitHub/LinkedIn), optional Fluent Forms shortcode | Email hello@naeem.dev, github.com/naeemHaque, in/golam-sarwer. |
| **Footer** | `Profile` (Customizer) | copyright text, social links | © year · GitHub · LinkedIn · back-to-top. (No "Theme architecture" link.) |

### CPTs & taxonomies
- `project` CPT — slug `work`; supports title, editor, thumbnail, excerpt; `show_in_rest: true`;
  `menu_icon: dashicons-portfolio`. Meta: `_naeem_live_url`, `_naeem_github_url` (sanitized
  `esc_url_raw`, nonce-protected meta box, `show_in_rest` registered).
- `experience` CPT — supports title, editor; ordered by `menu_order`; meta: `_naeem_company`,
  `_naeem_date_range`, `_naeem_is_current` (bool). Tags via shared `tech` taxonomy.
- `tech` taxonomy — non-hierarchical, on `project` + `experience`, `show_in_rest: true`.
- `contribution_area` taxonomy — non-hierarchical, on `project`, `show_in_rest: true`.

---

## 8. Templates & routing map

| WP template | Controller | Renders |
|---|---|---|
| `front-page.php` | `Front_Page` | layout/nav + all 8 front sections + layout/footer |
| `home.php` / `index.php` | `Blog` | blog listing (Blog.html design): header, category filter, uniform post grid |
| `single.php` | `Single_Post` | single post reading view (consistent with theme typography) |
| `archive.php` / `category.php` | `Archive` | post archives (category drives the filter context) |
| `page.php` | `Page` | generic page |
| `page-resume.php` | `Resume` | inline web résumé + Download PDF (Customizer media) |
| `404.php` | (inline/simple) | themed 404 |

`header.php` outputs `<head>` (fonts, enqueued `app.css`, pre-paint theme script, `wp_head()`) +
opening body/skip-link. `footer.php` closes with `wp_footer()`.

---

## 9. JavaScript (ported from `site.js`)

`assets/src/main.js` — **vanilla, zero-dependency**, enqueued deferred in the footer:

- Theme toggle (persist `localStorage['naeem-theme']`; sun/moon icons via `data-theme`).
- Scroll progress bar; nav `.scrolled` state on scroll.
- Mobile menu open/close; close on link click.
- Scroll-reveal: rect-based (IO-independent), with the always-visible safety net + `prefers-reduced-motion` guard (exactly as the prototype solved it).
- Active nav link tracking (scroll-based).
- Typed hero role (lines from a `data-*`/localized array); blinking cursor; respects reduced motion.
- Count-up stats (scroll-triggered).
- Client-side project filter (over server-rendered cards, by `data-filters`).
- Contact form: inline validation (name required, email regex, message ≥10 chars) + submit to the native handler; `.form-ok` success state.
- Résumé buttons link to the Customizer-provided PDF URL.

Data the JS needs (roles, ajax URL, nonce) passed via `wp_localize_script` / `wp_add_inline_script`.

---

## 10. Customizer settings (`Profile`)

Panel **"Portfolio"** with sections; all settings sanitized, `postMessage` transport where useful:

- **Identity**: name, brand text, custom-logo support.
- **Hero**: rotating role lines (textarea, one per line), status pill text, one-liner/lead (`wp_kses_post`), CTA labels + links, accent color (`sanitize_hex_color`).
- **Stats**: 3 × (number + label).
- **About**: heading, body paragraphs, 3 pillar cards.
- **Skills**: 4 group labels + lists.
- **Open Source**: section copy, areas list, 4 cards.
- **Social**: GitHub, LinkedIn, email (`esc_url_raw` / `sanitize_email`).
- **Résumé**: PDF (Media Library, `WP_Customize_Media_Control`), button label.
- **Headshot**: image (Media Library).
- **Contact**: intro copy, optional Fluent Forms shortcode (`sanitize_text_field`).
- **Footer**: copyright text.

---

## 11. Contact form handler (`Forms\Contact`)

- Native form posts to `admin-post.php` (`admin_post_naeem_contact` + `admin_post_nopriv_*`) **or**
  REST/AJAX; nonce verified; fields sanitized (`sanitize_text_field`, `sanitize_email`,
  `sanitize_textarea_field`); sends via `wp_mail` to the site admin / Customizer email.
- Honeypot + basic rate-guard for spam.
- Success/error reflected in the design's `.form-ok` / `.field.invalid` states (progressive
  enhancement: works without JS via standard POST + redirect; enhanced via fetch when JS is on).
- If a Fluent Forms shortcode is set in the Customizer, render it instead (styled to match).

---

## 12. Standards, i18n, accessibility, SEO

- **Escaping/sanitization** on all output (`esc_html`, `esc_url`, `esc_attr`, `wp_kses_post`) and input; nonces on the form + meta box.
- **i18n**: text domain `naeem-portfolio`; all strings wrapped in `__()`/`esc_html__()`/`_e()`; `languages/naeem-portfolio.pot` generated via `wp i18n make-pot`.
- **Theme supports**: `title-tag`, `post-thumbnails`, `custom-logo`, `html5`, `automatic-feed-links`, `responsive-embeds`, custom image sizes for project/post thumbnails; nav menu (`primary`); skip link.
- **Accessibility**: semantic landmarks, ARIA from the design, keyboard nav, focus-visible styles, WCAG AA contrast (palettes already clear it), `prefers-reduced-motion` honored.
- **SEO**: clean semantic markup, schema-friendly structure, compatible with Yoast / Rank Math.
- **Performance**: minimal vanilla JS, lazy-loaded images, proper enqueue + asset versioning, font preconnect.

---

## 13. Build & tooling

- `package.json`: `dev` = `tailwindcss -i assets/src/tailwind.css -o assets/css/app.css --watch`;
  `build` = same with `--minify`. (Plus a JS copy/minify step for `main.js`.)
- PostCSS: `postcss-import`, `tailwindcss`, `autoprefixer`, `cssnano` (build only).
- Compiled `assets/css/app.css` and `assets/js/main.js` **committed** (fresh clone works without building).
- Assets enqueued via `inc/Assets.php` with `get_theme_file_uri` + version constants (never hardcoded `<link>`/`<script>`).

### Git (D7)
- The **theme directory is the git repo root**: `git init` inside `wp-content/themes/naeem-portfolio/`. The surrounding WordPress install is **not** versioned.
- Theme-level `.gitignore` ignores `node_modules/` and OS/editor cruft (`.DS_Store`, etc.). Compiled `assets/css/app.css` and `assets/js/main.js` **are** committed (a fresh clone runs without building).
- `docs/` (this spec + the design-reference bundle) lives inside the theme repo and is versioned with it.
- Feature work happens on a branch; the spec + design-reference land in the initial commit.

---

## 14. Phased delivery

Each phase is independently reviewable and leaves the site working.

- **Phase 0 — Scaffold & tooling.** Theme header, `functions.php` bootstrap, autoloader, `View`/`Theme`/`Assets`, npm + Tailwind + PostCSS config, `tokens.css` + `components.css` compiling to `app.css`, `git init` + `.gitignore`. *Done when:* theme activates and the front page loads with the design's typography/background.
- **Phase 1 — Static front page.** `header`/`footer`/`nav`/`mobile-menu`/`progress`, `Front_Page` controller + all 8 section views with sample data, ported `main.js`. *Done when:* the front page is pixel-perfect vs. `Naeem Portfolio.html` in light + dark, fully responsive.
- **Phase 2 — Dynamic models.** `project` + `experience` CPTs, `tech` + `contribution_area` taxonomies, project meta box; wire Projects, Experience, and latest-posts sections to models; seed sample content. *Done when:* editing/adding a project or experience updates the front page.
- **Phase 3 — Customizer.** All `Profile` settings + sanitizers; wire hero/about/skills/opensource/footer/headshot/résumé. *Done when:* all non-CPT content is editable from Appearance → Customize.
- **Phase 4 — Blog system.** `home.php`/`index.php` listing (Blog.html), `single.php`, `archive.php`/`category.php`, `page-resume.php`. *Done when:* the blog listing/single/archives render pixel-perfect and category filtering works.
- **Phase 5 — Polish.** Contact handler, `404.php`, i18n + `.pot`, README (install / build / add project / write post / update résumé / configure form / generate translations), accessibility + performance QA, final pixel-perfect pass in both themes.

---

## 15. Acceptance criteria

- Front page and blog visually match the prototype in light **and** dark at desktop, tablet (≤900px), and mobile (≤620px) breakpoints.
- A new project (with tech terms + URLs) and a new post appear on the front page/blog with no code edits.
- Hero/skills/socials/résumé/headshot all editable via the Customizer.
- Theme activates cleanly with **zero PHP notices/warnings** (`WP_DEBUG` on) and **zero console errors**.
- Contact form sends mail with a valid nonce; rejects invalid input; degrades without JS.
- All output escaped; all strings translatable; `.pot` generates.
- A fresh checkout renders correctly **without** running the build (compiled assets committed).

---

## 16. Resolved questions

- MVC flavor → custom PHP MVC (D2).
- Tailwind vs. existing CSS → keep components + Tailwind layer (D3).
- Experience modeling → `experience` CPT (D5).
- Open Source modeling → `contribution_area` taxonomy + Customizer (D6).
- Contact form → native handler, Fluent Forms optional (D8).
- Project fields → native meta box, no ACF (D9).
- Git → init at project root (D7).

No open questions remain.
