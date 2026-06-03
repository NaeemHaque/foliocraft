# Naeem Portfolio — Phase 1: Static Front Page — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Render the full single-page front end pixel-perfectly from `Naeem Portfolio.html` — nav, mobile menu, scroll progress, all 8 sections, footer — driven by a `Front_Page` MVC controller and the ported vanilla `main.js`, in light + dark across breakpoints.

**Architecture:** `front-page.php` → `Naeem\Controllers\Front_Page::render()` → renders `template-parts/layout/*` + `template-parts/front/*` views (looping sections delegate to `template-parts/cards/*`). Content is static/sample in this phase (CPT + Customizer wiring is Phases 2–3); the controller already passes sample arrays for the looping sections so Phase 2 only swaps the data source. Styling is the already-compiled `app.css`; no CSS rebuild needed (templates use the design's existing component classes, not new Tailwind utilities).

**Tech Stack:** PHP 7.4+ · WordPress 7.0 · WP-CLI · vanilla JS (no build) · Tailwind v4 (unchanged from Phase 0).

---

## Spec & source references

- Spec: `docs/superpowers/specs/2026-06-03-naeem-portfolio-theme-design.md` (§7 section mapping, §9 JS, §8 routing).
- **Pixel-perfect source of truth (READ THESE):**
  - Markup: `docs/design-reference/project/Naeem Portfolio.html`
  - JS: `docs/design-reference/project/assets/site.js`
  - The component CSS classes used here already exist in `assets/src/components.css` / compiled `app.css`.

## Conventions (every task)

- PHP 7.4+; WP Coding Standards; tab indent; every PHP file starts with `defined( 'ABSPATH' ) || exit;`.
- **Escaping:** wrap text output in `esc_html_e()` / `esc_html__()`; URLs in `esc_url()`; attributes in `esc_attr()`. SVG icon blocks are static trusted markup — output them directly (no escaping) exactly as in the source.
- **Text domain** `naeem-portfolio` on all human-readable strings.
- **Porting rule:** copy the markup from the named source line range **verbatim**, then apply only the listed transformations (PHP wrapper, i18n on visible text, swap prototype-only bits). Do not restyle or change class names — the classes are the pixel-perfect contract.
- Paths are relative to the theme root `wp-content/themes/naeem-portfolio/`.
- Work on branch `phase-1-front-page`. Each task ends in a commit.

## Prototype-only → theme adaptations (apply wherever they appear)

- Drop the React/Babel Tweaks panel scripts and `#tweaks-root` (not ported).
- Replace `<image-slot ...>` (hero headshot) with a static placeholder `<div class="headshot-slot" aria-hidden="true"></div>` (Customizer image wired in Phase 3).
- Blog links that point to `Blog.html` → `esc_url( $blog_url )` where the controller sets `$blog_url = home_url( '/' )` for now (finalized to the posts page in Phase 4).
- Footer "Theme architecture" link (the `WordPress Architecture.html` anchor) is **removed** (prototype handoff doc, not a live page). Keep GitHub, LinkedIn, back-to-top.
- The hero "Download Résumé" + nav "Contact" buttons keep their markup; résumé stays a JS toast (Phase 3 wires the Customizer PDF).

## Testing approach (test-first, verification-based)

Per task: run the failing check first (section marker absent), implement, re-verify. Tools: `php -l`, `node --check` (JS syntax), `curl -s "$SITE" | grep`, and `curl ... | grep -Eic 'fatal|parse|<b>warning|<b>notice'` (expect 0). `SITE="$(wp option get home)"` (it is `http://naeem-portfolio.test`). Final task notes a manual browser pixel/QA pass (light + dark, ≤900px, ≤620px) since console/visual checks can't be automated here.

## Before Task 1

```bash
cd wp-content/themes/naeem-portfolio
git checkout -b phase-1-front-page
```

---

### Task 1: Port `main.js` (full vanilla interactions)

**Files:** Modify (replace stub): `assets/js/main.js`

**Port from** `docs/design-reference/project/assets/site.js` (lines 1–286) **with these changes:**
- Keep verbatim: the `$`/`$$` helpers, year stamp, theme toggle, nav `scrolled` + scroll `progress`, mobile menu, résumé toast (`#resumeHero`), the toast `flash()` helper, scroll-reveal (rect-based + safety net), active-nav, typed role, count-up.
- **Remove** the projects data array and the grid-building block (site.js lines ~182–227: `var projects = [...]`, `grid.innerHTML = ...`, and the `.proj-links` dead-link handler) — projects are server-rendered in Task 6. **Keep** the project filter block (site.js ~229–243) unchanged (it filters server-rendered `.proj-card` by `data-filters`).
- Keep the contact-form validation/submit block (site.js ~245–284) verbatim (Phase 5 swaps the fake submit for the real handler).
- The typed-role `roles` array stays as in site.js (Phase 3 will localize it from the Customizer).

- [ ] **Step 1 — failing check:** `grep -c 'IntersectionObserver\|checkReveal' assets/js/main.js` → expect `0` (still the stub).
- [ ] **Step 2 — write the ported file** at `assets/js/main.js` per the rules above.
- [ ] **Step 3 — verify:**
```bash
node --check assets/js/main.js && echo "JS SYNTAX OK"
grep -c "getElementById\|querySelector" assets/js/main.js   # > 0
grep -c "var projects = \[" assets/js/main.js                # expect 0 (removed)
grep -c "data-filters" assets/js/main.js                     # expect >=1 (filter kept)
```
Expected: `JS SYNTAX OK`; a positive count; `0`; `>=1`.
- [ ] **Step 4 — commit:**
```bash
git add assets/js/main.js
git commit -m "feat(front): port vanilla interactions (theme, reveal, typed, filter, form)"
```

---

### Task 2: Layout shell + Front_Page controller + front-page.php

**Files:**
- Create: `template-parts/layout/nav.php`, `template-parts/layout/mobile-menu.php`, `template-parts/layout/progress.php`, `template-parts/layout/footer.php`
- Create: `inc/Controllers/Front_Page.php`
- Create: `front-page.php`
- Modify: `header.php` (render progress + nav + mobile menu after `wp_body_open`), `footer.php` (render the footer layout part before `wp_footer`)

**nav.php** — port `Naeem Portfolio.html` lines 31–58 (`<header class="nav" id="nav">…</header>`) verbatim; i18n the visible "Contact" text; the brand, nav-links, SVGs stay as-is. The nav links remain in-page anchors (`#about` etc.).
**mobile-menu.php** — port lines 60–72 (`<div class="mobile-menu" id="mobileMenu">…</div>`) verbatim; i18n nothing structural (anchors only).
**progress.php** — port line 29: `<div class="progress" id="progress"></div>`.
**footer.php (layout part)** — port lines 414–428 (`<footer class="footer">…</footer>`) verbatim, **but remove the "Theme architecture" anchor** (the `<a href="WordPress Architecture.html" …>` block, ~419–422). Replace the hardcoded year with `<?php echo esc_html( gmdate( 'Y' ) ); ?>` inside `#year` (the JS also stamps it; keep both safe). Keep GitHub, LinkedIn, back-to-top.

**Front_Page.php controller:**
```php
<?php
/**
 * Front page controller — assembles section data and renders the views.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

defined( 'ABSPATH' ) || exit;

class Front_Page {

	public function render() {
		get_header();

		$data = array( 'blog_url' => home_url( '/' ) );

		View::render( 'front/hero', $data );
		View::render( 'front/about', $data );
		View::render( 'front/experience', array( 'items' => $this->experience() ) );
		View::render( 'front/projects', array( 'projects' => $this->projects() ) );
		View::render( 'front/opensource', $data );
		View::render( 'front/skills', $data );
		View::render( 'front/blog', array( 'posts' => $this->posts(), 'blog_url' => home_url( '/' ) ) );
		View::render( 'front/contact', $data );

		get_footer();
	}

	/** Sample experience entries (replaced by the experience CPT in Phase 2). */
	private function experience() {
		return array(
			array(
				'role'    => __( 'Software Engineer', 'naeem-portfolio' ),
				'company' => 'WPManageNinja',
				'badge'   => __( 'WordPress products', 'naeem-portfolio' ),
				'when'    => __( '2022 — Present', 'naeem-portfolio' ),
				'current' => true,
				'desc'    => __( 'Product engineering on WordPress software used by millions of users and businesses worldwide. Building scalable features, developer-facing tooling, and the architecture behind it — from data models to REST APIs to front-end with Vue.', 'naeem-portfolio' ),
				'tags'    => array( 'PHP', 'WordPress', 'Vue.js', 'REST APIs', 'MySQL' ),
			),
			array(
				'role'    => __( 'Full-Stack Developer', 'naeem-portfolio' ),
				'company' => __( 'Product & web app work', 'naeem-portfolio' ),
				'badge'   => '',
				'when'    => __( '2020 — 2022', 'naeem-portfolio' ),
				'current' => false,
				'desc'    => __( 'Built and maintained scalable web apps and backend systems with PHP, Laravel, and MySQL — designing clean data models and REST APIs, and pairing them with Vue.js front-ends.', 'naeem-portfolio' ),
				'tags'    => array( 'Laravel', 'MySQL', 'Vue.js', 'REST APIs' ),
			),
			array(
				'role'    => __( 'Open Source Contributor', 'naeem-portfolio' ),
				'company' => 'WordPress Project · EmDash CMS',
				'badge'   => '',
				'when'    => __( '2019 — Present', 'naeem-portfolio' ),
				'current' => false,
				'desc'    => __( 'Ongoing contributions across WordPress Core, Plugins, Meta, Polyglots, and Photos, plus EmDash CMS — fixing issues, reviewing, and translating with the global community.', 'naeem-portfolio' ),
				'tags'    => array( 'Core', 'Polyglots', 'Meta', 'Photos' ),
			),
			array(
				'role'    => __( 'Computer Science & Engineering', 'naeem-portfolio' ),
				'company' => 'Sylhet International University',
				'badge'   => '',
				'when'    => __( 'B.Sc. CSE', 'naeem-portfolio' ),
				'current' => false,
				'desc'    => __( 'Studied CSE and competed heavily in programming contests — the foundation for detail-oriented, fast problem-solving that still shapes how I engineer today.', 'naeem-portfolio' ),
				'tags'    => array( 'Algorithms', 'Data Structures', 'Competitive Programming' ),
			),
		);
	}

	/** Sample projects (replaced by the project CPT in Phase 2). Mirrors site.js data. */
	private function projects() {
		return array(
			array( 'name' => 'DevPulse', 'icon' => 'dash', 'desc' => __( 'A team productivity dashboard surfacing PRs, deploys, and CI health in real time. Laravel API, Vue front-end, MySQL.', 'naeem-portfolio' ), 'tags' => array( 'Laravel', 'Vue', 'MySQL', 'REST API' ), 'filters' => array( 'laravel', 'vue', 'mysql', 'php' ), 'stars' => '312', 'lang' => 'PHP' ),
			array( 'name' => 'WP Schema Pilot', 'icon' => 'plug', 'desc' => __( 'WordPress plugin that auto-generates schema.org structured data — Yoast/Rank Math friendly, zero config.', 'naeem-portfolio' ), 'tags' => array( 'WordPress', 'PHP', 'JavaScript' ), 'filters' => array( 'wordpress', 'php' ), 'stars' => '1.2k', 'lang' => 'PHP' ),
			array( 'name' => 'QueryLens', 'icon' => 'db', 'desc' => __( 'Slow-query analyzer for MySQL with a web UI — visualizes EXPLAIN plans and suggests indexes.', 'naeem-portfolio' ), 'tags' => array( 'PHP', 'MySQL', 'REST API' ), 'filters' => array( 'php', 'mysql' ), 'stars' => '486', 'lang' => 'PHP' ),
			array( 'name' => 'Fluent Blocks Kit', 'icon' => 'blocks', 'desc' => __( 'A collection of reusable Vue-powered blocks and components for WordPress and EmDash CMS projects.', 'naeem-portfolio' ), 'tags' => array( 'Vue', 'WordPress', 'Tailwind' ), 'filters' => array( 'vue', 'wordpress' ), 'stars' => '740', 'lang' => 'Vue' ),
			array( 'name' => 'LaravelKit Starter', 'icon' => 'rocket', 'desc' => __( 'Opinionated Laravel starter with auth, queues, and a Vue + Tailwind front-end wired for clean architecture.', 'naeem-portfolio' ), 'tags' => array( 'Laravel', 'Vue', 'MySQL' ), 'filters' => array( 'laravel', 'vue', 'mysql', 'php' ), 'stars' => '928', 'lang' => 'PHP' ),
			array( 'name' => 'Polyglot Helper', 'icon' => 'globe', 'desc' => __( 'A contributor tool for WordPress Polyglots — speeds up string review and translation suggestions.', 'naeem-portfolio' ), 'tags' => array( 'WordPress', 'Vue', 'i18n' ), 'filters' => array( 'wordpress', 'vue', 'php' ), 'stars' => '203', 'lang' => 'JavaScript' ),
		);
	}

	/** Sample latest posts (replaced by a WP_Query in Phase 2/4). */
	private function posts() {
		return array(
			array( 'cat' => __( 'WordPress', 'naeem-portfolio' ), 'read' => __( '8 min read', 'naeem-portfolio' ), 'title' => __( 'Building a CPT-driven portfolio the WordPress way', 'naeem-portfolio' ), 'excerpt' => __( 'Why custom post types and taxonomies beat hardcoded HTML, and how to model projects so anyone can manage them from wp-admin.', 'naeem-portfolio' ) ),
			array( 'cat' => __( 'Workflow', 'naeem-portfolio' ), 'read' => __( '6 min read', 'naeem-portfolio' ), 'title' => __( 'How I fold AI into my daily dev workflow', 'naeem-portfolio' ), 'excerpt' => __( 'From research and feature planning to debugging — a practical look at where AI actually saves time, and where it doesn’t.', 'naeem-portfolio' ) ),
			array( 'cat' => __( 'Career', 'naeem-portfolio' ), 'read' => __( '5 min read', 'naeem-portfolio' ), 'title' => __( 'What competitive programming taught me about shipping', 'naeem-portfolio' ), 'excerpt' => __( 'Contest habits — reading edge cases first, thinking on your feet — that quietly made me a better product engineer.', 'naeem-portfolio' ) ),
		);
	}
}
```

**front-page.php:**
```php
<?php
/**
 * Front page.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;

( new \Naeem\Controllers\Front_Page() )->render();
```

**header.php change:** after the `<a class="skip-link …>` line, append:
```php
<?php
\Naeem\Core\View::render( 'layout/progress' );
\Naeem\Core\View::render( 'layout/nav' );
\Naeem\Core\View::render( 'layout/mobile-menu' );
?>
<main id="top">
```
**footer.php change:** before `<?php wp_footer(); ?>`, insert `</main>` then `\Naeem\Core\View::render( 'layout/footer' );` Note: the `index.php` fallback wraps its own `<main>` — to avoid a double `<main>`, move the `<main id="top">` open into `header.php` (as above) and remove the `<main id="top" class="wrap section">` wrapper from `index.php`, replacing it with `<div class="wrap section">…</div>`. Update `index.php` accordingly in this task.

- [ ] **Step 1 — failing check:** `SITE="$(wp option get home)"; curl -s "$SITE" | grep -c 'id="nav"'` → expect `0`.
- [ ] **Step 2 — create the four layout parts, the controller, and front-page.php; modify header.php/footer.php/index.php** per above.
- [ ] **Step 3 — verify:**
```bash
php -l inc/Controllers/Front_Page.php && php -l front-page.php && for f in template-parts/layout/*.php; do php -l "$f"; done && php -l header.php && php -l footer.php && php -l index.php
SITE="$(wp option get home)"
curl -s "$SITE" | grep -c 'id="nav"'              # 1
curl -s "$SITE" | grep -c 'id="progress"'         # 1
curl -s "$SITE" | grep -c 'id="mobileMenu"'       # 1
curl -s "$SITE" | grep -c 'class="footer"'        # 1
curl -s "$SITE" | grep -c 'WordPress Architecture.html'   # 0 (removed)
curl -s "$SITE" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
- [ ] **Step 4 — commit:**
```bash
git add template-parts/layout inc/Controllers/Front_Page.php front-page.php header.php footer.php index.php
git commit -m "feat(front): layout shell (nav/mobile-menu/progress/footer) + Front_Page controller"
```

---

### Task 3: Hero section view

**Files:** Create `template-parts/front/hero.php`

Port `Naeem Portfolio.html` lines 76–123 (`<section class="hero wrap" id="hero">…</section>`) verbatim, then:
- Replace `<image-slot id="headshot" …></image-slot>` (line ~119) with `<div class="headshot-slot" aria-hidden="true"></div>`.
- i18n visible text (status pill, lead, button labels, stat labels) via `esc_html_e()`; keep `<span id="typed"></span>` empty (filled by JS) and the `data-count` stats.
- Keep the terminal card markup and all SVGs verbatim.

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'id="hero"'` → `0`.
- [ ] **Step 2 — create the file.**
- [ ] **Step 3 — verify:** `php -l template-parts/front/hero.php`; `curl -s "$SITE" | grep -c 'id="hero"'` → `1`; `grep -c 'id="typed"'` → `1`; `grep -c 'image-slot'` → `0`; error grep → `0`.
- [ ] **Step 4 — commit:** `git add template-parts/front/hero.php && git commit -m "feat(front): hero section"`

---

### Task 4: About section view

**Files:** Create `template-parts/front/about.php`

Port lines 125–160 (`<section class="section" id="about">…</section>`) verbatim; i18n the eyebrow label, heading, the two `<p>` paragraphs (use `wp_kses_post()` for paragraphs containing `<strong>`), and the three pillar titles/descriptions. Keep SVGs verbatim. (Content becomes Customizer-driven in Phase 3 — add a `// Phase 3: Customizer` comment near the copy.)

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'id="about"'` → `0`.
- [ ] **Step 2 — create the file.**
- [ ] **Step 3 — verify:** `php -l …/about.php`; `grep -c 'id="about"'` → `1`; `grep -c 'pillar'` → `>=3`; error grep → `0`.
- [ ] **Step 4 — commit:** `git commit -m "feat(front): about section"`

---

### Task 5: Experience section + timeline card

**Files:** Create `template-parts/front/experience.php`, `template-parts/cards/timeline-item.php`

`experience.php` renders the eyebrow (03→02 — use the source's `02 /`) + title, then loops `$items` (passed by the controller) calling `\Naeem\Core\View::render( 'cards/timeline-item', $item )` for each. Wrap in `<div class="timeline">`. Reference source lines 162–214 for the section/timeline structure.

`timeline-item.php` reproduces one `.tl-item` (source lines 168–211 show the variants). Use `$current` to add the `tl-featured` class and the `<span class="now">● …</span>` + `CURRENT` treatment; otherwise plain `.tl-when`. Output: `$when`, `$role`, `$company`, optional `$badge` (`<span class="badge">`), `$desc`, and `$tags` (loop → `<span class="chip<?php echo $current ? ' chip--accent' : ''; ?>">`). Escape all text (`esc_html`); the first (featured) item uses `chip--accent` chips per the source.

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'class="timeline"'` → `0`.
- [ ] **Step 2 — create both files; confirm the controller already passes `items` (it does, from Task 2).**
- [ ] **Step 3 — verify:** `php -l` both; `grep -c 'id="experience"'` → `1`; `grep -c 'tl-item'` → `>=4`; `grep -c 'tl-featured'` → `1`; `grep -c 'CURRENT'` via CSS is not in HTML (it's `::after` content) so instead check `grep -c 'tl-featured'`; error grep → `0`.
- [ ] **Step 4 — commit:** `git commit -m "feat(front): experience timeline (sample data)"`

---

### Task 6: Projects section + project card (server-rendered, JS-filtered)

**Files:** Create `template-parts/front/projects.php`, `template-parts/cards/project-card.php`

`projects.php` reproduces source lines 216–233: eyebrow `03 /` + `proj-head` (title + `#filterBar` with the filter buttons `all/WordPress/laravel/vue/php/mysql` exactly as in source) + `<div class="proj-grid" id="projGrid">`, then loops `$projects` calling `cards/project-card`.

`project-card.php` reproduces the card markup that site.js built (site.js lines ~207–220): `<article class="proj-card" data-filters="<?php echo esc_attr( implode( ' ', $filters ) ); ?>">` with the icon (map `$icon` key → the SVG from site.js `icons`/`iconGithub`/`iconExt`/`iconStar`/`iconDot`; **copy those exact SVG strings into a small PHP `$icons` map in this card file**), the live/GitHub `proj-links` (href `#` for now), `<h3>` name, `.desc`, `.proj-tags` chips, and `.proj-meta` (stars + lang). Escape text; output SVGs raw.

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'id="projGrid"'` → `0`.
- [ ] **Step 2 — create both files.**
- [ ] **Step 3 — verify:** `php -l` both; `grep -c 'id="filterBar"'` → `1`; `grep -c 'proj-card'` → `>=6`; `grep -c 'data-filters'` → `>=6`; error grep → `0`. (Filtering itself is exercised by the Task 1 JS — confirm in the manual QA task.)
- [ ] **Step 4 — commit:** `git commit -m "feat(front): projects grid + card (sample data, JS filter)"`

---

### Task 7: Open Source section + os card

**Files:** Create `template-parts/front/opensource.php`, `template-parts/cards/os-card.php`

Port source lines 235–284. Left column: eyebrow `04 /`, title, lead, and the `.os-areas` list (Core/Plugins/Meta/Polyglots/Photos/EmDash CMS). Right column `.os-cards`: loop a local array of the four cards (WP Core/`Patches & triage`, Polyglots/`Translation`, Photos & Meta/`Community`, EmDash CMS/`Contributor`) → `cards/os-card`. `os-card.php` reproduces one `.os-card` (source 253–259): `.glyph` SVG (copy the four exact SVGs from source), `.ot` (`<h4>` title + `.role`), and `<p>` blurb. i18n text; SVGs raw. (Content → Customizer in Phase 3.)

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'id="opensource"'` → `0`.
- [ ] **Step 2 — create both files.**
- [ ] **Step 3 — verify:** `php -l` both; `grep -c 'os-area'` → `>=6`; `grep -c 'os-card'` → `>=4`; error grep → `0`.
- [ ] **Step 4 — commit:** `git commit -m "feat(front): open source section + cards"`

---

### Task 8: Skills section view

**Files:** Create `template-parts/front/skills.php`

Port source lines 286–310: eyebrow `05 /` + title + `.skill-grid` with the four `.skill-group`s (Backend / Frontend / Databases / Tools & AI) — each a `.gh` (icon SVG + `<h3>`) and a `.skill-list` of `.skill-pill`s. May render from a local PHP array of `array( 'icon' => '<svg…>', 'group' => '…', 'pills' => array(…) )` to keep it DRY, or port the four blocks verbatim. i18n group names + pills; SVGs raw. (→ Customizer in Phase 3.)

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'id="stack"'` → `0`.
- [ ] **Step 2 — create the file.**
- [ ] **Step 3 — verify:** `php -l`; `grep -c 'skill-group'` → `4`; `grep -c 'skill-pill'` → `>=16`; error grep → `0`.
- [ ] **Step 4 — commit:** `git commit -m "feat(front): skills section"`

---

### Task 9: Blog preview section + post card

**Files:** Create `template-parts/front/blog.php`, `template-parts/cards/post-card.php`

Port source lines 312–346: `proj-head` (eyebrow `06 /` + title `From the blog.` + an "All posts" button linking `esc_url( $blog_url )`), then `.blog-grid` looping `$posts` → `cards/post-card`. `post-card.php` reproduces the `.post` anchor (source 326–331): `href="<?php echo esc_url( $blog_url ); ?>"`, `.pmeta` (cat + read), `<h3>` title, `<p>` excerpt, and the `.more` "Read post" + arrow SVG. i18n; SVG raw. (The controller passes `posts` + `blog_url`.)

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'id="writing"'` → `0`.
- [ ] **Step 2 — create both files.**
- [ ] **Step 3 — verify:** `php -l` both; `grep -c 'blog-grid'` → `1`; `grep -c 'class="post"' ` → `>=3`; error grep → `0`.
- [ ] **Step 4 — commit:** `git commit -m "feat(front): blog preview section + post card"`

---

### Task 10: Contact section + final full-page verification

**Files:** Create `template-parts/front/contact.php`

Port source lines 348–410 verbatim: eyebrow `07 /`, `.contact-grid` → `.contact-left` (title, lead, `.contact-channels` with email/GitHub/LinkedIn `.channel`s) + the `<form class="form" id="contactForm" novalidate>` (name/email/message fields, `.err` spans, submit button, `.form-note` "Powered by Fluent Forms", `#formOk`). i18n all visible text/labels/placeholders (`esc_attr_e` for placeholders); keep `href="mailto:…"`, GitHub/LinkedIn URLs via `esc_url`; SVGs raw. The form posts nowhere yet — Task 1's JS handles validation + fake success (Phase 5 wires the real handler).

- [ ] **Step 1 — failing check:** `curl -s "$SITE" | grep -c 'id="contactForm"'` → `0`.
- [ ] **Step 2 — create the file.**
- [ ] **Step 3 — verify (section + whole page):**
```bash
php -l template-parts/front/contact.php
SITE="$(wp option get home)"
curl -s "$SITE" | grep -c 'id="contact"'        # 1
H="$(curl -s "$SITE")"
for id in hero about experience work opensource stack writing contact; do printf "%s=%s " "$id" "$(printf '%s' "$H" | grep -c "id=\"$id\"")"; done; echo
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
Expected: all eight section ids `=1`; error grep `0`.
- [ ] **Step 4 — manual pixel/QA pass (record results in the commit message body):** open `http://naeem-portfolio.test` and check against `Naeem Portfolio.html`:
  - Dark + light (toggle the theme button) match the prototype.
  - Responsive at ≤900px (nav collapses to the hamburger; about/os/contact stack) and ≤620px (timeline/projects single column).
  - Interactions: typed role animates, scroll progress bar, reveal-on-scroll, project filter pills filter cards, mobile menu opens/closes, contact form validates + shows success, console has **zero errors**.
- [ ] **Step 5 — commit:**
```bash
git add template-parts/front/contact.php
git commit -m "feat(front): contact section + complete front page"
```

---

## Phase 1 — Definition of Done

- All eight sections + nav + mobile menu + scroll progress + footer render on `http://naeem-portfolio.test`.
- Front page is pixel-perfect vs. `Naeem Portfolio.html` in light + dark; responsive at 900px/620px.
- All interactions work (theme toggle, reveal, typed role, count-up, project filter, mobile menu, form validation); **zero console errors**, **zero PHP notices**.
- MVC structure in place: `front-page.php` → `Front_Page` controller → `View::render` of layout + front + card parts.
- Branch `phase-1-front-page` holds 10 commits.

## Self-review (plan author)

- **Spec coverage:** §7 sections all mapped to tasks 3–10; nav/footer/progress/mobile-menu → Task 2; §9 JS → Task 1 (with project-render moved to PHP per §9 "server-rendered cards"); §8 `front-page.php` routing → Task 2. Prototype-only removals (Tweaks, image-slot, architecture link, Blog.html links) handled in the global adaptations + per task.
- **Placeholder scan:** none — every task names exact source line ranges + transformations and exact verification commands; the controller's sample data is fully written out.
- **Name consistency:** controller passes `items`/`projects`/`posts`/`blog_url`; card views consume those exact names; `View::render` paths (`front/*`, `cards/*`, `layout/*`) match the created files.
- **Deferred (correct for this phase):** dynamic data (Phase 2), Customizer copy (Phase 3), blog page URL + single/archive (Phase 4), real contact handler (Phase 5). Each noted inline where the static stand-in lives.
- **Note:** `main.js` lives at `assets/js/main.js` (hand-written, no JS build step) — carried over from Phase 0; Tailwind/`app.css` is unchanged this phase (no new utility classes).
