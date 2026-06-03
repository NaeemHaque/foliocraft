# Naeem Portfolio — Phase 0: Scaffold & Tooling — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Stand up an activatable `naeem-portfolio` theme whose front page renders the design's full tokenized CSS, fonts, and light/dark scaffolding — the foundation every later phase builds on.

**Architecture:** Classic WordPress theme with a custom PHP MVC layer (Autoloader → `Naeem\` namespace under `inc/`; a `Core\View` render helper; `Theme` + `Assets` service classes booted from `functions.php`). Styling is the prototype's hand-tuned component CSS compiled through a Tailwind v4 / CLI pipeline that imports **utilities only (no Preflight)** so the design's own base styles stay authoritative.

**Tech Stack:** PHP 7.4+ · WordPress (installed locally, served via Laravel Herd) · WP-CLI · Node 25 / npm 11 · Tailwind CSS v4 (`@tailwindcss/cli`).

---

## Spec reference

Implements **Phase 0** of `docs/superpowers/specs/2026-06-03-naeem-portfolio-theme-design.md` (§4 MVC, §5 file structure, §6 CSS/Tailwind, §13 build/git). Read that spec before starting.

## Conventions (apply to every task)

- **PHP 7.4+ compatible**; WordPress PHP Coding Standards (long `array()` syntax, Yoda not required but escape everything).
- Every PHP file starts with `defined( 'ABSPATH' ) || exit;` (after any `namespace`).
- Text domain `naeem-portfolio` on all user-facing strings.
- All paths below are **relative to the theme root**: `wp-content/themes/naeem-portfolio/`.
- Compiled assets (`assets/css/app.css`) **are committed** (D7).

## Testing approach (test-first, verification-based)

A classic theme isn't unit-TDD'd end-to-end, so each task's "test" is a concrete **verification command run first to confirm the failing state, then again to confirm it passes**:
- `php -l <file>` — syntax lint.
- `wp eval '...'` — class/function existence and behavior inside a booted WP.
- `wp theme ...` — activation/status.
- `curl -s "$SITE"` + `grep` — rendered output (`$SITE` from `wp option get home`).
- Manual browser QA for the visual checkpoint.

Run all `wp` and `curl` commands from the theme root. Set `SITE="$(wp option get home)"` once per shell.

## Before Task 1 — create the working branch

```bash
cd wp-content/themes/naeem-portfolio
git checkout -b phase-0-scaffold
```

---

### Task 1: Minimal activatable theme

**Files:**
- Create: `style.css`
- Create: `index.php`

- [ ] **Step 1: Verify the theme is not yet registered (failing state)**

Run:
```bash
wp theme list --field=name | grep -x naeem-portfolio || echo "ABSENT"
```
Expected: `ABSENT`.

- [ ] **Step 2: Create `style.css` (theme header only)**

```css
/*
Theme Name: Naeem Portfolio
Theme URI: https://github.com/naeemHaque/naeem-portfolio
Author: Golam Sarwer Naeem
Author URI: https://github.com/naeemHaque
Description: Custom MVC + Tailwind portfolio theme for Golam Sarwer Naeem — software engineer & open source contributor.
Version: 0.1.0
Requires at least: 6.4
Tested up to: 7.0
Requires PHP: 7.4
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: naeem-portfolio
Tags: portfolio, custom-colors, translation-ready, full-width-template
*/

/* Compiled styles are enqueued from assets/css/app.css (see inc/Assets.php).
   WordPress only requires the header block above to live in this file. */
```

- [ ] **Step 3: Create a minimal `index.php`**

```php
<?php
/**
 * Fallback template (replaced with header/footer wiring in Task 5).
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;

echo '<!doctype html><meta charset="utf-8"><title>Naeem Portfolio</title><p>Theme active.</p>';
```

- [ ] **Step 4: Verify it registers and activates**

Run:
```bash
wp theme list --field=name | grep -x naeem-portfolio
wp theme activate naeem-portfolio
wp theme list --status=active --field=name
```
Expected: `naeem-portfolio` printed by the first command; "Success: Switched to..." from activate; `naeem-portfolio` from the third.

- [ ] **Step 5: Commit**

```bash
git add style.css index.php
git commit -m "feat(theme): minimal activatable theme header + fallback index"
```

---

### Task 2: Bootstrap — functions.php, Autoloader, Core\View

**Files:**
- Create: `functions.php`
- Create: `inc/Autoloader.php`
- Create: `inc/Core/View.php`

- [ ] **Step 1: Verify the View class does not yet exist (failing state)**

Run:
```bash
wp eval 'echo class_exists("Naeem\\Core\\View") ? "OK" : "MISSING";'
```
Expected: `MISSING`.

- [ ] **Step 2: Create `inc/Autoloader.php`**

```php
<?php
/**
 * PSR-4-style autoloader mapping the Naeem\ namespace to inc/.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem;

defined( 'ABSPATH' ) || exit;

class Autoloader {

	/** Register the autoloader with SPL. */
	public static function register() {
		spl_autoload_register( array( __CLASS__, 'load' ) );
	}

	/**
	 * Load a class file for the Naeem\ namespace.
	 *
	 * Naeem\Core\View -> inc/Core/View.php
	 *
	 * @param string $class Fully-qualified class name.
	 */
	public static function load( $class ) {
		$prefix = 'Naeem\\';
		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}
		$relative = substr( $class, strlen( $prefix ) );
		$path     = NAEEM_DIR . '/inc/' . str_replace( '\\', '/', $relative ) . '.php';
		if ( is_readable( $path ) ) {
			require $path;
		}
	}
}
```

- [ ] **Step 3: Create `inc/Core/View.php`**

```php
<?php
/**
 * Presentation-only view renderer.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Core;

defined( 'ABSPATH' ) || exit;

class View {

	/**
	 * Render a template part with an injected, escaped-at-output data context.
	 *
	 * @param string $template Path under template-parts/ without extension, e.g. 'front/hero'.
	 * @param array  $data     Variables extracted into the template scope.
	 */
	public static function render( $template, array $data = array() ) {
		$path = get_theme_file_path( 'template-parts/' . $template . '.php' );
		if ( ! is_readable( $path ) ) {
			return;
		}
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- controlled view context.
		extract( $data, EXTR_SKIP );
		require $path;
	}

	/**
	 * Render a template part and return it as a string.
	 *
	 * @param string $template Path under template-parts/ without extension.
	 * @param array  $data     Variables extracted into the template scope.
	 * @return string
	 */
	public static function capture( $template, array $data = array() ) {
		ob_start();
		self::render( $template, $data );
		return (string) ob_get_clean();
	}
}
```

- [ ] **Step 4: Create `functions.php` (constants + autoloader)**

```php
<?php
/**
 * Naeem Portfolio theme bootstrap.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;

define( 'NAEEM_VERSION', '0.1.0' );
define( 'NAEEM_DIR', get_template_directory() );
define( 'NAEEM_URI', get_template_directory_uri() );

require_once NAEEM_DIR . '/inc/Autoloader.php';
\Naeem\Autoloader::register();

// Service classes are booted in Task 4:
// \Naeem\Theme::init();
// \Naeem\Assets::init();
```

- [ ] **Step 5: Lint and verify the classes load + View renders**

Run:
```bash
php -l functions.php && php -l inc/Autoloader.php && php -l inc/Core/View.php
# create a throwaway template, render it, assert output, clean up
mkdir -p template-parts/_smoke
printf '<?php echo "VIEW:" . esc_html( $who );' > template-parts/_smoke/hello.php
wp eval 'echo Naeem\Core\View::capture("_smoke/hello", array("who" => "naeem"));'
rm -rf template-parts/_smoke
```
Expected: three `No syntax errors detected` lines, then `VIEW:naeem`.

- [ ] **Step 6: Commit**

```bash
git add functions.php inc/Autoloader.php inc/Core/View.php
git commit -m "feat(theme): bootstrap with namespace autoloader and View renderer"
```

---

### Task 3: Tailwind v4 build pipeline + ported design CSS

**Files:**
- Create: `package.json`
- Create: `assets/src/tokens.css`
- Create: `assets/src/components.css`
- Create: `assets/src/tailwind.css`
- Create (generated, committed): `assets/css/app.css`
- Modify: `.gitignore` is already correct (ignores `node_modules/`) — no change.

- [ ] **Step 1: Verify the compiled stylesheet does not yet exist (failing state)**

Run:
```bash
test -f assets/css/app.css && echo "EXISTS" || echo "ABSENT"
```
Expected: `ABSENT`.

- [ ] **Step 2: Create `package.json`**

```json
{
  "name": "naeem-portfolio",
  "version": "0.1.0",
  "private": true,
  "description": "Build pipeline for the Naeem Portfolio WordPress theme.",
  "scripts": {
    "dev": "tailwindcss -i ./assets/src/tailwind.css -o ./assets/css/app.css --watch",
    "build": "tailwindcss -i ./assets/src/tailwind.css -o ./assets/css/app.css --minify"
  },
  "devDependencies": {
    "@tailwindcss/cli": "^4.0.0"
  }
}
```

- [ ] **Step 3: Create `assets/src/tokens.css` (design tokens — light/dark)**

Paste exactly (these are the `:root` / `[data-theme]` blocks from `docs/design-reference/project/assets/theme.css`, extracted so both utilities and components share one source):

```css
/* Design tokens — ported from the approved prototype. */
:root {
  --font-display: "Space Grotesk", "Sora", system-ui, sans-serif;
  --font-body: "IBM Plex Sans", system-ui, sans-serif;
  --font-mono: "IBM Plex Mono", ui-monospace, "SFMono-Regular", monospace;

  --accent: #e6926b;

  --section-pad: clamp(72px, 9vw, 140px);
  --gutter: clamp(20px, 5vw, 64px);
  --maxw: 1180px;

  --radius: 14px;
  --radius-sm: 9px;
  --radius-lg: 22px;

  --ease: cubic-bezier(.22, .61, .36, 1);
}

:root,
[data-theme="dark"] {
  --bg: #0a0c11;
  --bg-grid: rgba(255,255,255,.03);
  --panel: #10131a;
  --panel-2: #161a23;
  --panel-hover: #1b202b;
  --border: rgba(255,255,255,.085);
  --border-strong: rgba(255,255,255,.16);
  --text: #e7ebf2;
  --text-dim: #99a2b2;
  --text-faint: #646d7d;
  --link: color-mix(in oklab, var(--accent) 78%, white);
  --accent-ink: #1a0c05;
  --accent-soft: color-mix(in oklab, var(--accent) 16%, var(--bg));
  --accent-line: color-mix(in oklab, var(--accent) 40%, transparent);
  --shadow: 0 24px 60px -20px rgba(0,0,0,.7);
  --shadow-sm: 0 8px 24px -12px rgba(0,0,0,.6);
  color-scheme: dark;
}

[data-theme="light"] {
  --bg: #f7f7f4;
  --bg-grid: rgba(10,12,16,.035);
  --panel: #ffffff;
  --panel-2: #f1f1ec;
  --panel-hover: #f6f6f2;
  --border: rgba(12,14,20,.10);
  --border-strong: rgba(12,14,20,.20);
  --text: #14171d;
  --text-dim: #555c68;
  --text-faint: #8a91a0;
  --link: color-mix(in oklab, var(--accent) 72%, #3a1a0a);
  --accent-ink: #1a0c05;
  --accent-soft: color-mix(in oklab, var(--accent) 14%, var(--bg));
  --accent-line: color-mix(in oklab, var(--accent) 45%, transparent);
  --shadow: 0 24px 60px -26px rgba(20,30,50,.28);
  --shadow-sm: 0 10px 28px -18px rgba(20,30,50,.25);
  color-scheme: light;
}
```

- [ ] **Step 4: Create `assets/src/components.css` (ported component + base styles)**

Copy **all rules from `docs/design-reference/project/assets/theme.css` EXCEPT the three token blocks** already moved in Step 3 — i.e. copy the file from the `* { box-sizing: border-box; }` rule (just after the `[data-theme="light"]` block, ~line 70) through the end of the file (~line 807). This includes the base resets (`html`, `body`, `::selection`, `a`, `img`, `button`), all component classes (`.wrap`, `.nav`, `.btn`, `.hero`, `.terminal`, `.about-grid`, `.timeline`, `.proj-card`, `.os-card`, `.skill-grid`, `.blog-grid`, `.contact-grid`, `.form`, `.footer`), the `[data-reveal]` reveal system, `.progress`, and all `@media` blocks. Do not alter values — this file is the pixel-perfect contract.

Then append this accessibility helper at the end (needed by the skip link added in Task 5; the prototype had no server-rendered skip link):

```css
/* ---------- a11y: skip link / screen-reader text ---------- */
.screen-reader-text {
  position: absolute;
  width: 1px; height: 1px;
  padding: 0; margin: -1px;
  overflow: hidden; clip: rect(0,0,0,0);
  white-space: nowrap; border: 0;
}
.skip-link:focus {
  position: fixed; top: 10px; left: 10px;
  width: auto; height: auto;
  z-index: 300;
  padding: 10px 16px; clip: auto;
  background: var(--accent); color: var(--accent-ink);
  border-radius: 10px; font-family: var(--font-mono); font-size: 13px;
}
```

- [ ] **Step 5: Create `assets/src/tailwind.css` (build entry)**

```css
/* Tailwind v4 entry for the Naeem Portfolio theme.
   We import the UTILITIES layer only and deliberately SKIP Preflight, so the
   prototype's hand-tuned base styles (in components.css) remain authoritative
   and pixel-perfection is never disturbed by Tailwind's reset. */
@import "tailwindcss/utilities.css" layer(utilities);

/* Design tokens (CSS custom properties; light/dark via [data-theme]). */
@import "./tokens.css";

/* Ported pixel-perfect component + base styles. */
@import "./components.css";

/* Where Tailwind should look for utility class usage (added as markup lands). */
@source "../../template-parts";
@source "../../inc";
```

- [ ] **Step 6: Install deps and build**

Run:
```bash
npm install
npm run build
```
Expected: install completes; build prints a Tailwind "Done" line and writes `assets/css/app.css`.

- [ ] **Step 7: Verify the compiled CSS contains tokens + components**

Run:
```bash
test -f assets/css/app.css && echo "EXISTS"
grep -q -- '--accent' assets/css/app.css && echo "TOKENS OK"
grep -q '\.proj-card' assets/css/app.css && echo "COMPONENTS OK"
grep -q '\.hero' assets/css/app.css && echo "HERO OK"
```
Expected: `EXISTS`, `TOKENS OK`, `COMPONENTS OK`, `HERO OK`. (No utility classes are exercised yet — that begins in Phase 1; this is expected.)

- [ ] **Step 8: Commit (including the compiled file)**

```bash
git add package.json package-lock.json assets/src/ assets/css/app.css
git commit -m "build(css): tailwind v4 pipeline + ported design tokens and components"
```

---

### Task 4: Theme + Assets service classes

**Files:**
- Create: `inc/Theme.php`
- Create: `inc/Assets.php`
- Create: `assets/js/main.js` (stub; populated in Phase 1)
- Modify: `functions.php` (boot the services)

- [ ] **Step 1: Verify the compiled CSS is not yet enqueued (failing state)**

Run:
```bash
SITE="$(wp option get home)"
curl -s "$SITE" | grep -c 'assets/css/app.css' || true
```
Expected: `0`.

- [ ] **Step 2: Create `inc/Theme.php`**

```php
<?php
/**
 * Theme setup: supports, menus, image sizes, text domain.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem;

defined( 'ABSPATH' ) || exit;

class Theme {

	public static function init() {
		add_action( 'after_setup_theme', array( __CLASS__, 'setup' ) );
	}

	public static function setup() {
		load_theme_textdomain( 'naeem-portfolio', NAEEM_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
		);

		register_nav_menus(
			array( 'primary' => __( 'Primary Menu', 'naeem-portfolio' ) )
		);

		add_image_size( 'naeem-project', 800, 600, true );
		add_image_size( 'naeem-post', 720, 480, true );
	}
}
```

- [ ] **Step 3: Create `assets/js/main.js` (stub)**

```js
/* Naeem Portfolio — front-end interactions.
   Populated in Phase 1 (theme toggle, reveal, typed role, filters, form). */
(function () {
  'use strict';
}());
```

- [ ] **Step 4: Create `inc/Assets.php`**

```php
<?php
/**
 * Front-end asset enqueueing.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem;

defined( 'ABSPATH' ) || exit;

class Assets {

	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	public static function enqueue() {
		wp_enqueue_style(
			'naeem-fonts',
			'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Sora:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'naeem-app',
			NAEEM_URI . '/assets/css/app.css',
			array( 'naeem-fonts' ),
			NAEEM_VERSION
		);

		wp_enqueue_script(
			'naeem-main',
			NAEEM_URI . '/assets/js/main.js',
			array(),
			NAEEM_VERSION,
			true
		);
	}
}
```

- [ ] **Step 5: Boot the services in `functions.php`**

Replace the trailing comment block in `functions.php` with the active boot calls:

```php
require_once NAEEM_DIR . '/inc/Autoloader.php';
\Naeem\Autoloader::register();

\Naeem\Theme::init();
\Naeem\Assets::init();
```

- [ ] **Step 6: Lint, then verify supports + enqueue**

Run:
```bash
php -l inc/Theme.php && php -l inc/Assets.php && php -l functions.php
wp eval 'echo current_theme_supports("title-tag") ? "TITLE-TAG OK" : "FAIL";'
wp eval 'echo has_nav_menu("primary") || true ? "MENU REGISTERED" : "FAIL"; $m = get_registered_nav_menus(); echo isset($m["primary"]) ? " primary OK" : " primary MISSING";'
SITE="$(wp option get home)"
curl -s "$SITE" | grep -c 'assets/css/app.css'
curl -s "$SITE" | grep -c 'fonts.googleapis.com'
```
Expected: three `No syntax errors detected`; `TITLE-TAG OK`; `MENU REGISTERED primary OK`; `1` for `app.css`; `1` for the fonts URL.

- [ ] **Step 7: Commit**

```bash
git add inc/Theme.php inc/Assets.php assets/js/main.js functions.php
git commit -m "feat(theme): theme supports, nav menu, and asset enqueueing"
```

---

### Task 5: header.php, footer.php, and styled fallback render

**Files:**
- Create: `header.php`
- Create: `footer.php`
- Modify: `index.php`

- [ ] **Step 1: Verify the homepage has no pre-paint theme script yet (failing state)**

Run:
```bash
SITE="$(wp option get home)"
curl -s "$SITE" | grep -c "localStorage.getItem('naeem-theme')" || true
```
Expected: `0`.

- [ ] **Step 2: Create `header.php`**

```php
<?php
/**
 * Theme header.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?> data-motion="medium">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<script>
  /* Set theme + enable reveal animations before paint (avoids FOUC). */
  (function () {
    try {
      var t = localStorage.getItem('naeem-theme') || 'dark';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) { document.documentElement.setAttribute('data-theme', 'dark'); }
    try {
      if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.classList.add('reveal-on');
      }
    } catch (e) {}
  })();
</script>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#top"><?php esc_html_e( 'Skip to content', 'naeem-portfolio' ); ?></a>
```

- [ ] **Step 3: Create `footer.php`**

```php
<?php
/**
 * Theme footer.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;
?>
<?php wp_footer(); ?>
</body>
</html>
```

- [ ] **Step 4: Rewrite `index.php` to use header/footer**

```php
<?php
/**
 * Fallback template.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="top" class="wrap section">
	<h1 class="section-title"><?php esc_html_e( 'Naeem Portfolio', 'naeem-portfolio' ); ?></h1>
	<p class="section-lead"><?php esc_html_e( 'Theme scaffold active. Front-page sections arrive in Phase 1.', 'naeem-portfolio' ); ?></p>
</main>
<?php
get_footer();
```

- [ ] **Step 5: Lint and verify rendered markup**

Run:
```bash
php -l header.php && php -l footer.php && php -l index.php
SITE="$(wp option get home)"
curl -s "$SITE" | grep -c "localStorage.getItem('naeem-theme')"
curl -s "$SITE" | grep -c 'data-motion="medium"'
curl -s "$SITE" | grep -c 'section-title'
curl -s "$SITE" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true
```
Expected: three `No syntax errors detected`; `1`, `1`, `1`; and `0` for the error/notice grep.

- [ ] **Step 6: Manual visual checkpoint (browser)**

Open `"$SITE"` (run `wp option get home` to get the URL; Herd serves it). Confirm:
- Background is near-black `#0a0c11` (dark default), IBM Plex / Space Grotesk fonts are loading (heading uses Space Grotesk).
- Toggling the OS to light does **not** change it yet (no toggle UI until Phase 1) — that's expected.
- Browser console shows **zero errors**.

- [ ] **Step 7: Commit**

```bash
git add header.php footer.php index.php
git commit -m "feat(theme): header/footer with pre-paint theming + styled fallback page"
```

---

## Phase 0 — Definition of Done

- `wp theme list --status=active` shows `naeem-portfolio`.
- Home page renders with the design's dark background, fonts, and `.section-title`/`.section-lead` styling.
- No PHP notices/warnings in the rendered output; no console errors.
- `assets/css/app.css` is committed and contains the ported tokens + components.
- Branch `phase-0-scaffold` holds 5 commits, ready to merge or hand to Phase 1.

---

## Self-review (completed by plan author)

- **Spec coverage (Phase 0 scope):** §4 MVC bootstrap → Tasks 2 (autoloader, View) + 4 (services). §5 file structure (style.css, functions.php, inc/, header/footer, assets) → Tasks 1–5. §6 CSS/Tailwind (tokens shared, components kept, utilities-only build) → Task 3. §13 build + git (committed app.css, theme-level repo, branch) → Tasks 3 + branch step. ✅ No Phase-0 requirement left unmapped.
- **Placeholder scan:** No "TBD/TODO/handle edge cases"; the two copy-from-source steps (tokens, components) name the exact file and line ranges in the repo. ✅
- **Type/name consistency:** `NAEEM_DIR`/`NAEEM_URI`/`NAEEM_VERSION`, `Naeem\Autoloader::register()`, `Naeem\Core\View::render|capture`, `Naeem\Theme::init`, `Naeem\Assets::init`, handles `naeem-fonts`/`naeem-app`/`naeem-main` are used consistently across Tasks 2/4/5. ✅
- **Deviation flagged:** Tailwind v4 utilities-only import (no Preflight) and no `tw-` prefix — protects pixel-perfection; revisit prefixing only if front-end class clashes appear (spec §6 left the exact mechanism to the plan).

---

## Remaining phases (detailed plan written just-in-time per phase)

Each subsequent phase gets its own complete, bite-sized plan written when we reach it (keeps every plan placeholder-free). Roadmap from spec §14:

1. **Phase 1 — Static front page.** `template-parts/layout/*` (nav, mobile-menu, progress, footer) + `Front_Page` controller + all 8 `front/*` section views with sample data + ported `main.js` (theme toggle, scroll progress, reveal, active-nav, typed role, count-up, project filter, form validation). Pixel-perfect QA vs. `Naeem Portfolio.html` in light + dark across the 900px/620px breakpoints.
2. **Phase 2 — Dynamic models.** `project` + `experience` CPTs, `tech` + `contribution_area` taxonomies, project meta box (Live/GitHub URL); `Project`/`Experience`/`Post` models; wire the Projects, Experience, and latest-posts sections; seed sample content via WP-CLI.
3. **Phase 3 — Customizer.** `Profile` model + all Customizer settings/sanitizers; wire hero, about, skills, open-source, footer, headshot, résumé.
4. **Phase 4 — Blog system.** `home.php`/`index.php` listing (Blog.html design), `single.php`, `archive.php`/`category.php`, `page-resume.php` + controllers.
5. **Phase 5 — Polish.** Native contact form handler (`Forms\Contact`), `404.php`, i18n + `.pot`, README, accessibility + performance QA, final pixel-perfect pass in both themes.
