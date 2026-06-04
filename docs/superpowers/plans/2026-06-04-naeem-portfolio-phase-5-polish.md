# Naeem Portfolio — Phase 5: Polish (Contact, 404, i18n, README, a11y/perf) — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Finish the theme: a working native contact form (nonce + sanitization + `wp_mail`, honeypot, progressive enhancement, Fluent Forms still swappable), a themed `404.php`, generated translation `.pot` with clean i18n, a complete `README.md`, and an accessibility + performance pass.

**Architecture:** `Naeem\Forms\Contact` handles `admin-post.php` submissions for both no-JS (validate → `wp_mail` → redirect with a status flag) and JS (same handler returns JSON when `naeem_ajax=1`); `contact.php` gains the form action/nonce/honeypot and a server-rendered success/error banner; `main.js` submits via `fetch` and shows the inline success without reload. 404 + a11y/perf are small, theme-wide touches; the README documents the whole workflow.

**Tech Stack:** PHP 7.4+ · WordPress 7.0 · WP-CLI (`wp i18n make-pot`) · Tailwind v4 CLI.

---

## Spec & source

- Spec §11 (contact handler), §12 (standards/i18n/a11y/SEO/perf), §14 Phase 5, §5-deliverable (README contents).

## Conventions (every task)

- PHP 7.4+; WP Coding Standards; `defined( 'ABSPATH' ) || exit;`; text domain `naeem-portfolio`; escape output, sanitize input, nonces on the form.
- **Commits: NO `Co-Authored-By` trailer.** Branch `phase-5-polish`; commit per task; **branch kept after merge** (not deleted).
- When `components.css` changes → `npm run build` + commit `assets/css/app.css`.
- Verify with `wp eval`, `curl`, `php -l`, `node --check`. `SITE="$(wp option get home)"`.

## Before Task 1
```bash
cd wp-content/themes/naeem-portfolio
git checkout -b phase-5-polish
```

---

### Task 1: Native contact form handler

**Files:** Create `inc/Forms/Contact.php`; Modify `functions.php` (boot), `template-parts/front/contact.php` (form action/nonce/honeypot/status banner), `assets/js/main.js` (fetch submit), `assets/src/components.css` (honeypot hide + status banner; rebuild).

**Contact.php** — `Naeem\Forms\Contact`:
```php
<?php
/**
 * Native contact form handler (admin-post), nonce + sanitize + wp_mail.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Forms;

defined( 'ABSPATH' ) || exit;

class Contact {

	const ACTION = 'naeem_contact';

	public static function init() {
		add_action( 'admin_post_' . self::ACTION, array( __CLASS__, 'handle' ) );
		add_action( 'admin_post_nopriv_' . self::ACTION, array( __CLASS__, 'handle' ) );
	}

	public static function handle() {
		$ajax  = ! empty( $_POST['naeem_ajax'] );
		$nonce = isset( $_POST['naeem_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['naeem_contact_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, self::ACTION ) ) {
			self::respond( $ajax, false, __( 'Security check failed — please refresh and try again.', 'naeem-portfolio' ) );
		}

		// Honeypot: a filled hidden field means a bot. Pretend success, send nothing.
		if ( ! empty( $_POST['naeem_website'] ) ) {
			self::respond( $ajax, true, __( "Thanks — your message is on its way.", 'naeem-portfolio' ) );
		}

		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

		if ( '' === $name || ! is_email( $email ) || strlen( $message ) < 10 ) {
			self::respond( $ajax, false, __( 'Please add your name, a valid email, and a longer message.', 'naeem-portfolio' ) );
		}

		$to      = get_option( 'admin_email' );
		/* translators: %s: sender name. */
		$subject = sprintf( __( 'Portfolio contact from %s', 'naeem-portfolio' ), $name );
		$body    = sprintf( "Name: %s\nEmail: %s\n\n%s", $name, $email, $message );
		$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
		$sent    = wp_mail( $to, $subject, $body, $headers );

		self::respond(
			$ajax,
			(bool) $sent,
			$sent
				? __( "Thanks — your message is on its way. I'll get back to you soon.", 'naeem-portfolio' )
				: __( 'Sorry, something went wrong sending your message. Email me directly instead.', 'naeem-portfolio' )
		);
	}

	private static function respond( $ajax, $ok, $message ) {
		if ( $ajax ) {
			if ( $ok ) {
				wp_send_json_success( array( 'message' => $message ) );
			}
			wp_send_json_error( array( 'message' => $message ) );
		}
		$base = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		wp_safe_redirect( add_query_arg( 'contact', $ok ? 'sent' : 'error', $base ) . '#contact' );
		exit;
	}
}
```
**functions.php:** add `\Naeem\Forms\Contact::init();`.

**contact.php** — when a Fluent Forms shortcode is set, leave that branch as-is. In the NATIVE `<form>` branch:
- Tag: `<form class="form" id="contactForm" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate data-reveal data-delay="2">`
- Immediately inside: `<input type="hidden" name="action" value="naeem_contact" />`, `<?php wp_nonce_field( 'naeem_contact', 'naeem_contact_nonce' ); ?>`, and a honeypot: `<p class="naeem-hp" aria-hidden="true"><label>Website<input type="text" name="naeem_website" tabindex="-1" autocomplete="off" /></label></p>`.
- The `#formOk` success block: server-render it open when `?contact=sent`: change its class to `form-ok<?php echo ( isset( $_GET['contact'] ) && 'sent' === $_GET['contact'] ) ? ' show' : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only flag ?>`. Add an error banner directly after it: `<?php if ( isset( $_GET['contact'] ) && 'error' === $_GET['contact'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?><div class="form-err show"><?php esc_html_e( 'Something went wrong — please try again or email me directly.', 'naeem-portfolio' ); ?></div><?php endif; ?>`

**main.js** — replace the fake `setTimeout` success in the submit handler with a real fetch:
```js
var fd = new FormData(form);
fd.append('naeem_ajax', '1');
btn.disabled = true; btn.style.opacity = '.6';
fetch(form.action, { method: 'POST', body: fd, credentials: 'same-origin' })
  .then(function (r) { return r.json(); })
  .then(function (res) {
    form.reset();
    var ok = $('#formOk');
    ok.textContent ? null : null; // keep existing markup
    $('#formOk').classList.add('show');
    setTimeout(function () { $('#formOk').classList.remove('show'); }, 6000);
  })
  .catch(function () { /* fall back: native submit */ form.submit(); })
  .finally(function () { btn.disabled = false; btn.style.opacity = ''; });
```
(Keep the existing client-side field validation BEFORE this; only submit via fetch when valid. Do not remove the validation block.)

**components.css (append + rebuild):**
```css
.naeem-hp { position:absolute !important; left:-9999px !important; width:1px; height:1px; overflow:hidden; }
.form-err { display:flex; align-items:center; gap:11px; padding:14px 18px; border:1px solid #ff6b6b; background:color-mix(in oklab,#ff6b6b 12%,var(--bg)); border-radius:12px; font-size:14.5px; color:var(--text); }
```
Then `npm run build`.

- [ ] **Step 1 — failing check:** `wp eval 'echo has_action("admin_post_nopriv_naeem_contact")?"YES":"NO";'` → `NO`.
- [ ] **Step 2 — create Contact.php, boot it, wire contact.php + main.js + CSS, rebuild.**
- [ ] **Step 3 — verify:**
```bash
php -l inc/Forms/Contact.php && php -l functions.php && php -l template-parts/front/contact.php && node --check assets/js/main.js
wp eval 'echo has_action("admin_post_nopriv_naeem_contact") && has_action("admin_post_naeem_contact") ? "HANDLER OK" : "FAIL";'
SITE="$(wp option get home)"; H="$(curl -s "$SITE")"
printf '%s' "$H" | grep -c 'name="naeem_contact_nonce"'   # 1 (nonce field)
printf '%s' "$H" | grep -c 'admin-post.php'                # 1 (form action)
printf '%s' "$H" | grep -c 'naeem_website'                 # 1 (honeypot)
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
# success banner shows with the flag:
curl -s "$SITE/?contact=sent" | grep -c 'form-ok show'     # 1
```
Expected: lint + node pass; `HANDLER OK`; nonce/action/honeypot present; 0 errors; success banner shows with `?contact=sent`.
- [ ] **Step 4 — commit (NO Co-Authored-By):** `git add inc/Forms/Contact.php functions.php template-parts/front/contact.php assets/js/main.js assets/src/components.css assets/css/app.css && git commit -m "feat(contact): native wp_mail handler with nonce, honeypot, and JS enhancement"`

---

### Task 2: 404 template + accessibility & performance pass

**Files:** Create `inc/Controllers/Not_Found.php`, `template-parts/404.php`, `404.php`; Modify `assets/src/components.css` (focus-visible + 404 styles; rebuild), `template-parts/front/hero.php` (lazy headshot).

**404.php** → `( new \Naeem\Controllers\Not_Found() )->render();` → controller does `get_header(); \Naeem\Core\View::render( '404' ); get_footer();`. **template-parts/404.php** — a centered block using tokens: a mono "404" eyebrow, a Space-Grotesk heading "Page not found.", a lead, and two buttons (Home → `home_url('/')` primary; All posts → posts page) + `get_search_form()`. Wrap in `<section class="section wrap" style="min-height:60svh;display:grid;place-items:center;text-align:center;padding-top:120px;">` (or a `.notfound` class).

**Accessibility & performance (append to components.css + small template touches):**
```css
:focus-visible { outline: 2px solid var(--accent); outline-offset: 2px; border-radius: 4px; }
.btn:focus-visible, .filter-btn:focus-visible, .icon-btn:focus-visible { outline-offset: 3px; }
.notfound { min-height:60svh; display:grid; place-items:center; text-align:center; padding-top:120px; padding-bottom:80px; }
.notfound .code { font-family:var(--font-mono); color:var(--accent); letter-spacing:.2em; }
.notfound h1 { font-family:var(--font-display); font-size:clamp(34px,6vw,64px); letter-spacing:-.03em; margin:14px 0 14px; }
.notfound .cta { display:flex; gap:12px; justify-content:center; flex-wrap:wrap; margin-top:26px; }
```
- **Lazy images:** in `template-parts/front/hero.php`, the headshot `<img>` (when set) gets `loading="lazy" decoding="async"`. (Project featured images via `wp_get_attachment_image` and post thumbnails already get `loading="lazy"` from WP core — no change needed.)
- Then `npm run build`.

- [ ] **Step 1 — failing check:** `curl -s "$(wp option get home)/this-page-does-not-exist-xyz/" | grep -c 'notfound'` → `0`.
- [ ] **Step 2 — create 404 files + a11y/perf CSS + lazy headshot; rebuild.**
- [ ] **Step 3 — verify:**
```bash
php -l 404.php && php -l inc/Controllers/Not_Found.php && php -l template-parts/404.php && php -l template-parts/front/hero.php
grep -q 'focus-visible' assets/css/app.css && echo "A11Y CSS COMPILED"
SITE="$(wp option get home)"
N="$(curl -s "$SITE/this-page-does-not-exist-xyz/")"
printf '%s' "$N" | grep -c 'notfound'        # 1
printf '%s' "$N" | grep -ci 'not found'       # >=1
# 404 status code:
curl -s -o /dev/null -w "%{http_code}\n" "$SITE/this-page-does-not-exist-xyz/"   # 404
printf '%s' "$N" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
Expected: lint passes; `A11Y CSS COMPILED`; 404 page shows `notfound` + "not found"; HTTP **404**; 0 errors.
- [ ] **Step 4 — commit (NO Co-Authored-By):** `git add 404.php inc/Controllers/Not_Found.php template-parts/404.php template-parts/front/hero.php assets/src/components.css assets/css/app.css && git commit -m "feat(theme): themed 404 + focus-visible a11y + lazy headshot"`

---

### Task 3: i18n .pot + README + final QA

**Files:** Create `languages/naeem-portfolio.pot`, `README.md`.

**i18n:** generate the POT (WP-CLI i18n is available):
```bash
wp i18n make-pot . languages/naeem-portfolio.pot --domain=naeem-portfolio --exclude=node_modules,docs,assets/css
```
If `make-pot` reports any unwrapped-string warnings worth fixing in the theme's own PHP, note them; the generated `.pot` is the deliverable. (Confirm the file is created and contains entries.)

**README.md** (theme root) — cover, in clear sections (use fenced commands):
- **Overview** — what the theme is (custom classic theme, PHP MVC, Tailwind v4), requirements (WP 6.4+, PHP 7.4+, Node for builds).
- **Install** — copy into `wp-content/themes/`, activate; or `git clone` (compiled CSS/JS are committed, so it works without building).
- **Build** — `npm install`, `npm run dev` (watch) / `npm run build` (minify); what compiles (`assets/src/*` → `assets/css/app.css`).
- **First-run setup** — set a static front page (Home) + posts page (Blog) under Settings → Reading; optionally run `bash tools/seed-content.sh` for sample content.
- **Add a project** — Projects → Add New: title, content, featured image, **Tech** + **Contribution Areas** terms, and the **Project Details** box (Live/GitHub URL, Stars, Language).
- **Write a post** — Posts → Add New (categories drive the blog filter; latest 3 show on the front page).
- **Add experience** — Experience → Add New + the **Experience Details** box (Company, Date range, Current).
- **Customize** — Appearance → Customize → **Portfolio**: hero, about, skills, open source, social, accent color, **headshot**, **résumé PDF**, footer.
- **Résumé page** — create a Page using the **Résumé** template.
- **Contact form** — native by default (sends via `wp_mail` to the site admin email); or paste a **Fluent Forms** shortcode in Customize → Contact to swap it in.
- **Translations** — `wp i18n make-pot . languages/naeem-portfolio.pot --domain=naeem-portfolio` to regenerate the POT.
- **Architecture** — one paragraph pointing to `docs/superpowers/specs` + `docs/design-reference`, and the MVC layout (`inc/Controllers`, `inc/Models`, `template-parts`).

**Final QA** (theme-wide):
- [ ] **Step 1 — failing check:** `test -f README.md && echo EXISTS || echo ABSENT` → `ABSENT`.
- [ ] **Step 2 — generate the POT + write the README.**
- [ ] **Step 3 — verify:**
```bash
test -f languages/naeem-portfolio.pot && echo "POT EXISTS"
grep -c 'msgid' languages/naeem-portfolio.pot    # >0
test -f README.md && wc -l README.md
# whole-site sweep — every route renders with zero PHP notices:
SITE="$(wp option get home)"
BLOG="$(wp eval 'echo get_permalink(get_option("page_for_posts"));')"
POST="$(wp eval '$q=get_posts(array("numberposts"=>1)); echo get_permalink($q[0]->ID);')"
CAT="$(wp eval '$t=get_terms(array("taxonomy"=>"category","hide_empty"=>true,"number"=>1)); echo get_category_link($t[0]->term_id);')"
RES="$(wp eval '$p=get_page_by_path("resume"); echo get_permalink($p->ID);')"
for u in "$SITE" "$BLOG" "$POST" "$CAT" "$RES" "$SITE/nope-404-xyz/"; do echo "$u -> errors=$(curl -s "$u" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true)"; done
```
Expected: `POT EXISTS` + msgid count >0; README present; **every route errors=0**.
- [ ] **Step 4 — manual check (note in commit body):** with `WP_DEBUG` on (if convenient), browse front/blog/single/archive/résumé/404 and submit the contact form (check the admin email inbox / mail log); confirm light+dark + keyboard focus rings.
- [ ] **Step 5 — commit (NO Co-Authored-By):** `git add languages/naeem-portfolio.pot README.md && git commit -m "docs(i18n): generate translation POT + theme README"`

---

## Phase 5 — Definition of Done

- Contact form sends via `wp_mail` with a verified nonce + sanitized input + honeypot; works without JS (redirect + banner) and with JS (inline success); Fluent Forms shortcode still swaps it out.
- Themed `404.php` returns HTTP 404 and offers Home / All posts / search.
- `languages/naeem-portfolio.pot` generated; strings translatable with the `naeem-portfolio` text domain.
- `README.md` documents install, build, content entry, Customizer, résumé, contact, and translations.
- Focus-visible rings + lazy images; **every route renders with zero PHP notices**.
- Branch `phase-5-polish` holds 3 commits (no Co-Authored-By), kept after merge.

## Self-review (plan author)

- **Spec coverage:** §11 contact handler (T1), §12 i18n/a11y/perf (T2 + T3), Phase 5 list — contact, 404, .pot, README, a11y/perf all mapped.
- **Placeholder scan:** none — full handler code, exact form/JS/CSS edits, the make-pot command, and the README outline are concrete; verification uses real routes via `wp eval`.
- **Name consistency:** action `naeem_contact`; nonce field `naeem_contact_nonce`; honeypot `naeem_website`; ajax flag `naeem_ajax`; status flag `?contact=sent|error`; `.form-ok`/`.form-err`/`.naeem-hp` classes; `Not_Found` controller + `template-parts/404.php`.
- **Security note:** the `$_GET['contact']` display flag is read without nonce verification (it only toggles a banner, no state change) — annotated with a phpcs:ignore; the actual submission is fully nonce-protected.
- **Done after this phase:** the theme is feature-complete per the spec; remaining work would be optional enhancements (e.g., real project data, a dedicated experience meta UI already added in Phase 2, block patterns).
