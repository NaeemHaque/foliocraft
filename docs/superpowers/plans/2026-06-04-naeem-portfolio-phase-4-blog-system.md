# Naeem Portfolio — Phase 4: Blog System & Pages — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build the blog reading experience and supporting page templates — a blog listing (`home.php`/`index.php`) matching `Blog.html`, single-post reading view (`single.php`), archives (`archive.php`, incl. categories), a generic page (`page.php`), and the résumé page (`page-resume.php`) — all through the MVC controllers, pixel-consistent with the theme.

**Architecture:** A static front page ("Home" → `front-page.php` = the portfolio) + a posts page ("Blog" → `home.php` = the listing) are set in Reading settings. Listing templates use the WordPress **main loop** (`have_posts()`) via a shared `template-parts/blog/listing.php` + `cards/post-card-full.php`; `Blog`/`Archive`/`Single_Post`/`Page`/`Resume` controllers are thin entry points. Category filter pills become real **category-archive links** (replacing the prototype's client-side JS filter — correct for pagination). New blog/single CSS is added to `components.css` and recompiled.

**Tech Stack:** PHP 7.4+ · WordPress 7.0 · WP-CLI · Tailwind v4 CLI (`npm run build` when CSS changes).

---

## Spec & source

- Spec §8 (routing), Blog.html design at `docs/design-reference/project/Blog.html` (READ it — listing header, filter, post grid, foot note, and its inline `<style>`).
- The front-page blog preview + `cards/post-card.php` already exist; the **listing** card adds a date footer (`.post-meta-foot`).

## Conventions (every task)

- PHP 7.4+; WP Coding Standards; `defined( 'ABSPATH' ) || exit;`; text domain `naeem-portfolio`; escape output.
- **Commits: NO `Co-Authored-By` trailer.** Branch `phase-4-blog-system`; commit per task; **branch kept after merge** (not deleted).
- When `components.css` changes, run `npm run build` and commit the regenerated `assets/css/app.css`.
- Verify with `wp`, `curl`, `php -l`. `SITE="$(wp option get home)"`.

## Before Task 1
```bash
cd wp-content/themes/naeem-portfolio
git checkout -b phase-4-blog-system
```

---

### Task 1: Reading config + blog listing (home.php / index.php)

**Files:** Create `inc/Controllers/Blog.php`, `template-parts/blog/listing.php`, `template-parts/cards/post-card-full.php`, `home.php`, `index.php` (replace the scaffold fallback); Modify `template-parts/layout/nav.php` (cross-page anchors), `inc/Controllers/Front_Page.php` (blog_url → posts page), `template-parts/front/blog.php` (All-posts link), `assets/src/components.css` (+ rebuild `app.css`).

**Reading config (run in verification/setup):** create a "Home" and a "Blog" page and point Reading at them:
```bash
HOME_ID=$(wp post create --post_type=page --post_title="Home" --post_status=publish --porcelain)
BLOG_ID=$(wp post create --post_type=page --post_title="Blog" --post_status=publish --porcelain)
wp option update show_on_front page
wp option update page_on_front "$HOME_ID"
wp option update page_for_posts "$BLOG_ID"
wp rewrite flush
```
(front-page.php still renders the portfolio for the static front page; home.php renders the posts page.)

**Blog controller:** `Naeem\Controllers\Blog::render()` → `get_header(); \Naeem\Core\View::render('blog/listing', array('title'=>__('Notes from the editor.','naeem-portfolio'),'lead'=>__('Things I learn building WordPress products, contributing upstream, and keeping software clean.','naeem-portfolio'),'eyebrow'=>__('Writing','naeem-portfolio'),'num'=>'06 /')); get_footer();`

**listing.php** — reproduces Blog.html's structure: `<main class="blog-page">` → `.wrap blog-header` (eyebrow `<span class="num"><?php echo esc_html($num); ?></span> <?php echo esc_html($eyebrow); ?>`, `<h1 class="section-title"><?php echo esc_html($title); ?></h1>`, `<p class="section-lead"><?php echo esc_html($lead); ?></p>`, and `.blog-filter` with category pills) → `.wrap` → `.blog-grid` looping the **main query** (`if ( have_posts() ) while ( have_posts() ) { the_post(); \Naeem\Core\View::render('cards/post-card-full'); }` else a "no posts" note) → `the_posts_pagination(array('mid_size'=>1))` → `.blog-foot` note. **Category pills:** "All" links to the posts-page URL (`get_permalink(get_option('page_for_posts'))`), then `get_categories(array('hide_empty'=>true))` each linking `get_category_link($cat)`; mark the current one `active` via `is_category($cat->term_id)`.

**post-card-full.php** — like the front `cards/post-card.php` but for the loop (no passed vars; reads the current post): `<a class="post" href="<?php the_permalink(); ?>">` → `.pmeta` (first category name + reading-time estimate — compute from `get_the_content()` words/200) → `<h3><?php the_title(); ?></h3>` → `<p><?php echo esc_html( get_the_excerpt() ); ?></p>` → `.post-meta-foot` (`<span><?php echo esc_html( get_the_date() ); ?></span>`) → `.more` "Read post" + the arrow SVG (copy from Blog.html line ~123/source). Escape all.

**nav.php cross-page anchors:** the six in-page nav links — make them work from any page: `href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#about"` etc. (On the front page → bare `#about` for smooth scroll; elsewhere → `https://site/#about`.) Apply to the same six anchors in `template-parts/layout/mobile-menu.php` too.

**Front_Page.php:** change `blog_url` from `home_url('/')` to `$blog_url = get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' );` and pass it (already passed to blog preview).

**components.css (+ rebuild):** append the Blog.html listing styles verbatim:
```css
.blog-page { padding-top: 92px; }
.blog-header { padding-bottom: clamp(28px, 4vw, 44px); }
.blog-header .section-title { font-size: clamp(38px, 6vw, 68px); margin-bottom: 14px; }
.blog-filter { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 28px; }
.post-meta-foot { display: flex; align-items: center; gap: 14px; font-family: var(--font-mono); font-size: 12px; color: var(--text-faint); padding-top: 14px; margin-top: auto; border-top: 1px solid var(--border); }
.post .more { margin-top: auto; }
.post.is-hidden { display: none; }
.blog-foot { text-align: center; margin-top: clamp(40px, 6vw, 70px); }
.blog-foot .note { font-family: var(--font-mono); font-size: 13px; color: var(--text-faint); }
.post .pmeta span { white-space: nowrap; }
.blog-pagination, .pagination, .nav-links.pagination { display:flex; gap:8px; flex-wrap:wrap; justify-content:center; margin-top:clamp(32px,5vw,56px); font-family:var(--font-mono); font-size:14px; }
.blog-pagination a, .blog-pagination span, .wp-pagenavi a, .nav-links .page-numbers { padding:9px 14px; border:1px solid var(--border); border-radius:10px; color:var(--text-dim); }
.nav-links .page-numbers.current { background:var(--accent); color:var(--accent-ink); border-color:transparent; }
```
Then `npm run build`.

**index.php** — replace the Phase-0 scaffold body with the Blog controller too (it's the ultimate fallback for the posts index): `get_header(); ( new \Naeem\Controllers\Blog() )->render_body(); get_footer();` — simplest: make `home.php` and `index.php` BOTH just `( new \Naeem\Controllers\Blog() )->render();`. (Blog::render already calls get_header/get_footer.)

- [ ] **Step 1 — failing check:** `wp option get page_for_posts` → `0`; `curl -s "$SITE/?page_id=0"` n/a. Simpler: confirm `home.php` absent: `test -f home.php && echo EXISTS || echo ABSENT` → `ABSENT`.
- [ ] **Step 2 — create files, edits, run the Reading-config block, `npm run build`.**
- [ ] **Step 3 — verify:**
```bash
php -l inc/Controllers/Blog.php home.php index.php template-parts/blog/listing.php template-parts/cards/post-card-full.php template-parts/layout/nav.php 2>&1 | grep -v 'No syntax errors' || echo "lint clean"
SITE="$(wp option get home)"
BLOG_URL="$(wp eval 'echo get_permalink(get_option("page_for_posts"));')"
echo "blog url: $BLOG_URL"
B="$(curl -s "$BLOG_URL")"
printf '%s' "$B" | grep -c 'blog-page'        # 1
printf '%s' "$B" | grep -c 'class="post"'     # >=3 (seeded posts)
printf '%s' "$B" | grep -c 'blog-filter'      # 1
printf '%s' "$B" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
# front page still the portfolio:
curl -s "$SITE" | grep -c 'id="work"'         # 1
curl -s "$SITE" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
Expected: lint clean; blog page shows `blog-page` + ≥3 posts + filter; 0 errors; front page still renders the portfolio.
- [ ] **Step 4 — commit (NO Co-Authored-By):** `git add inc/Controllers/Blog.php home.php index.php template-parts/blog template-parts/cards/post-card-full.php template-parts/layout/nav.php template-parts/layout/mobile-menu.php inc/Controllers/Front_Page.php template-parts/front/blog.php assets/src/components.css assets/css/app.css && git commit -m "feat(blog): blog listing (home/index) + Reading config + cross-page nav"`

---

### Task 2: single.php — single-post reading view

**Files:** Create `inc/Controllers/Single_Post.php`, `template-parts/blog/single.php`, `single.php`; Modify `assets/src/components.css` (+ rebuild) for `.post-single`/`.post-content` typography.

**single.php** → `( new \Naeem\Controllers\Single_Post() )->render();` → `get_header(); while ( have_posts() ) { the_post(); \Naeem\Core\View::render('blog/single'); } get_footer();`.

**blog/single.php** — a centered reading layout using theme tokens:
- `<article class="post-single wrap">`
- back link to the blog page (`get_permalink(get_option('page_for_posts'))`) — "← All posts"
- `.pmeta` (first category · `get_the_date()` · reading-time estimate)
- `<h1 class="post-single-title"><?php the_title(); ?></h1>`
- featured image when present: `<?php if ( has_post_thumbnail() ) the_post_thumbnail('large', array('class'=>'post-single-cover')); ?>`
- `<div class="post-content"><?php the_content(); ?></div>`
- post navigation: `the_post_navigation(array('prev_text'=>'%title','next_text'=>'%title'))`
All dynamic output escaped where not core template tags.

**components.css (+ rebuild):** add (uses existing tokens):
```css
.post-single { max-width: 760px; padding-top: 110px; padding-bottom: clamp(48px,7vw,90px); }
.post-single .back { display:inline-flex; align-items:center; gap:8px; font-family:var(--font-mono); font-size:13px; color:var(--text-dim); margin-bottom:22px; }
.post-single .back:hover { color:var(--accent); }
.post-single .pmeta { display:flex; gap:12px; font-family:var(--font-mono); font-size:12.5px; color:var(--text-faint); margin-bottom:14px; }
.post-single .pmeta .cat { color:var(--accent); }
.post-single-title { font-family:var(--font-display); font-weight:600; font-size:clamp(30px,4.6vw,52px); line-height:1.06; letter-spacing:-.025em; margin:0 0 22px; text-wrap:balance; }
.post-single-cover { width:100%; height:auto; border-radius:var(--radius); border:1px solid var(--border); margin:8px 0 30px; }
.post-content { font-size:18px; line-height:1.8; color:var(--text-dim); }
.post-content > * { margin:0 0 22px; }
.post-content h2 { font-family:var(--font-display); font-size:clamp(22px,3vw,30px); color:var(--text); letter-spacing:-.02em; margin:38px 0 14px; }
.post-content h3 { font-family:var(--font-display); font-size:21px; color:var(--text); margin:30px 0 12px; }
.post-content a { color:var(--link); text-decoration:underline; text-underline-offset:3px; text-decoration-color:var(--accent-line); }
.post-content strong { color:var(--text); }
.post-content ul, .post-content ol { padding-left:24px; }
.post-content li { margin:0 0 8px; }
.post-content blockquote { border-left:3px solid var(--accent-line); padding-left:18px; color:var(--text); font-style:italic; }
.post-content pre { background:linear-gradient(180deg,var(--panel-2),var(--panel)); border:1px solid var(--border); border-radius:var(--radius); padding:18px 20px; overflow-x:auto; font-family:var(--font-mono); font-size:13.5px; }
.post-content code { font-family:var(--font-mono); font-size:.9em; background:var(--panel-2); border:1px solid var(--border); padding:1px 7px; border-radius:6px; color:var(--text); }
.post-content img { border-radius:var(--radius); }
.post-single .post-navigation { margin-top:40px; padding-top:24px; border-top:1px solid var(--border); font-family:var(--font-mono); font-size:14px; }
.post-single .post-navigation a { color:var(--text-dim); }
.post-single .post-navigation a:hover { color:var(--accent); }
```
Then `npm run build`.

- [ ] **Step 1 — failing check:** `test -f single.php && echo EXISTS || echo ABSENT` → `ABSENT`.
- [ ] **Step 2 — create files + CSS + rebuild.**
- [ ] **Step 3 — verify (open a seeded post):**
```bash
php -l single.php && php -l inc/Controllers/Single_Post.php && php -l template-parts/blog/single.php
PURL="$(wp eval '$q=get_posts(array("numberposts"=>1)); echo get_permalink($q[0]->ID);')"
echo "post url: $PURL"; P="$(curl -s "$PURL")"
printf '%s' "$P" | grep -c 'post-single'      # 1
printf '%s' "$P" | grep -c 'post-content'      # 1
printf '%s' "$P" | grep -c 'post-single-title' # 1
printf '%s' "$P" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
grep -q 'post-content' assets/css/app.css && echo "CSS COMPILED"
```
Expected: lint passes; single shows `post-single`/`post-content`/title; 0 errors; CSS compiled.
- [ ] **Step 4 — commit (NO Co-Authored-By):** `git add single.php inc/Controllers/Single_Post.php template-parts/blog/single.php assets/src/components.css assets/css/app.css && git commit -m "feat(blog): single-post reading view"`

---

### Task 3: archive.php — archives + category filter links

**Files:** Create `inc/Controllers/Archive.php`, `archive.php`; (the listing part from Task 1 is reused).

**archive.php** → `( new \Naeem\Controllers\Archive() )->render();` → `get_header(); \Naeem\Core\View::render('blog/listing', array('title'=>get_the_archive_title(),'lead'=>get_the_archive_description() ? wp_strip_all_tags(get_the_archive_description()) : __('Posts in this archive.','naeem-portfolio'),'eyebrow'=>__('Writing','naeem-portfolio'),'num'=>'06 /')); get_footer();`. (Reuses `blog/listing.php`, which loops the main query — for an archive, the main query is already the archive's posts.) `get_the_archive_title()` returns escaped markup; pass it through as the title but in listing.php output the title with `wp_kses_post` if it may contain markup — simplest: in Archive controller use `wp_strip_all_tags(get_the_archive_title())`.

This makes the Task 1 category pills (which link to `get_category_link`) land on `archive.php` (WordPress uses archive.php for category archives when no `category.php`). Confirm the `active` pill state via `is_category()` works.

- [ ] **Step 1 — failing check:** `test -f archive.php && echo EXISTS || echo ABSENT` → `ABSENT`.
- [ ] **Step 2 — create both files.**
- [ ] **Step 3 — verify (open a category archive):**
```bash
php -l archive.php && php -l inc/Controllers/Archive.php
CURL="$(wp eval '$t=get_terms(array("taxonomy"=>"category","hide_empty"=>true,"number"=>1)); echo get_category_link($t[0]->term_id);')"
echo "category url: $CURL"; A="$(curl -s "$CURL")"
printf '%s' "$A" | grep -c 'blog-page'       # 1
printf '%s' "$A" | grep -c 'class="post"'    # >=1
printf '%s' "$A" | grep -c 'filter-btn active'  # >=1 (current category highlighted)
printf '%s' "$A" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
Expected: lint passes; archive renders the listing with ≥1 post and a highlighted current pill; 0 errors.
- [ ] **Step 4 — commit (NO Co-Authored-By):** `git add archive.php inc/Controllers/Archive.php && git commit -m "feat(blog): category/archive listing via shared template"`

---

### Task 4: page.php + page-resume.php + final verification

**Files:** Create `inc/Controllers/Page.php`, `template-parts/page.php`, `page.php`, `inc/Controllers/Resume.php`, `template-parts/resume.php`, `page-resume.php`; Modify `assets/src/components.css` (+ rebuild) for `.page-body`/`.resume` styles.

**page.php** → `( new \Naeem\Controllers\Page() )->render();` → `get_header(); while ( have_posts() ) { the_post(); \Naeem\Core\View::render('page'); } get_footer();`. **template-parts/page.php** — `<article class="page-body wrap">` with `<h1 class="post-single-title"><?php the_title(); ?></h1>` + `<div class="post-content"><?php the_content(); ?></div>` (reuses Task 2's `.post-content`/title styles; add a small `.page-body{max-width:820px;padding-top:110px;padding-bottom:clamp(48px,7vw,90px);}`).

**page-resume.php** (a custom page template — header comment `Template Name: Résumé`) → `( new \Naeem\Controllers\Resume() )->render();`. **Resume controller** assembles `\Naeem\Models\Profile::all()` + `\Naeem\Models\Experience::all()` and renders `template-parts/resume.php`: an inline web résumé using theme tokens — header (name from `identity.name`, role = first hero role, social links), a **Download PDF** button (`$profile['resume']['url']`, shown only when set), an Experience timeline (reuse the `cards/timeline-item.php` shape or a compact list), and a Skills block (from `$profile['skills']['groups']`). Wrap in `get_header()/get_footer()` via the controller. Add `.resume`/`.resume-head` styles to components.css.

**components.css (+ rebuild):** add:
```css
.page-body { max-width:820px; padding-top:110px; padding-bottom:clamp(48px,7vw,90px); }
.resume { max-width:820px; padding-top:110px; padding-bottom:clamp(48px,7vw,90px); }
.resume-head { display:flex; justify-content:space-between; align-items:flex-start; gap:20px; flex-wrap:wrap; border-bottom:1px solid var(--border); padding-bottom:24px; margin-bottom:28px; }
.resume-head h1 { font-family:var(--font-display); font-size:clamp(30px,4.4vw,46px); letter-spacing:-.025em; margin:0 0 6px; }
.resume-head .role { font-family:var(--font-mono); color:var(--accent); font-size:14px; }
.resume-section { margin:30px 0; }
.resume-section h2 { font-family:var(--font-mono); font-size:13px; letter-spacing:.12em; text-transform:uppercase; color:var(--text-dim); margin:0 0 16px; }
```
Then `npm run build`.

**Final Phase 4 verification** (whole flow).

- [ ] **Step 1 — failing check:** `test -f page-resume.php && echo EXISTS || echo ABSENT` → `ABSENT`.
- [ ] **Step 2 — create files + CSS + rebuild; create a test page assigned the Résumé template:**
```bash
RID=$(wp post create --post_type=page --post_title="Résumé" --post_status=publish --porcelain)
wp post meta update "$RID" _wp_page_template page-resume.php
```
- [ ] **Step 3 — verify:**
```bash
for f in page.php page-resume.php inc/Controllers/Page.php inc/Controllers/Resume.php template-parts/page.php template-parts/resume.php; do php -l "$f"; done
RURL="$(wp eval '$p=get_page_by_path("resume"); echo $p?get_permalink($p->ID):"";')"
echo "resume url: $RURL"; R="$(curl -s "$RURL")"
printf '%s' "$R" | grep -c 'class="resume'    # >=1
printf '%s' "$R" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
# whole flow still clean:
SITE="$(wp option get home)"; curl -s "$SITE" | grep -c 'id="hero"'   # 1 (portfolio front)
BLOG_URL="$(wp eval 'echo get_permalink(get_option("page_for_posts"));')"; curl -s "$BLOG_URL" | grep -c 'blog-page'   # 1
```
Expected: lint passes; résumé page renders; 0 errors; front + blog still render.
- [ ] **Step 4 — manual check (note in commit body):** visit the blog page (filter pills → category archives), open a post (single reading view + prev/next), and the Résumé page (web résumé + Download PDF when a PDF is set in the Customizer). Confirm light/dark both look right.
- [ ] **Step 5 — commit (NO Co-Authored-By):** `git add page.php page-resume.php inc/Controllers/Page.php inc/Controllers/Resume.php template-parts/page.php template-parts/resume.php assets/src/components.css assets/css/app.css && git commit -m "feat(blog): generic page + résumé page templates"`

---

## Phase 4 — Definition of Done

- Static front ("Home"→portfolio) + posts page ("Blog"→listing) configured; the blog listing matches `Blog.html` (header, category filter, post grid, pagination).
- Single posts render a clean reading view (title, meta, featured image, styled content, prev/next); category/date/tag archives reuse the listing with the correct title and a highlighted current pill.
- `page.php` renders generic pages; `page-resume.php` renders an inline web résumé with a Download-PDF button (Customizer PDF).
- Nav anchors work from every page (front-page smooth scroll; absolute from sub-pages). **Zero PHP notices** across front/blog/single/archive/page/résumé.
- Branch `phase-4-blog-system` holds 4 commits (no Co-Authored-By), kept after merge.

## Self-review (plan author)

- **Spec coverage (§8):** home.php/index.php (T1), single.php (T2), archive.php/category (T3 — archive.php serves categories), page.php + page-resume.php (T4). Reading config makes the listing reachable.
- **Placeholder scan:** none — each task names files, controllers, the listing/loop mechanism, the CSS to add, and concrete verification (real seeded post/category URLs via `wp eval`).
- **Name consistency:** controllers `Blog`/`Single_Post`/`Archive`/`Page`/`Resume`; shared `blog/listing.php` + `cards/post-card-full.php`; reuses existing `.post`/`.blog-grid` styles + new `.blog-page`/`.post-content`/`.resume` styles compiled into `app.css`.
- **Adaptation flagged:** the prototype's client-side blog category filter becomes real category-archive links (correct with pagination); noted in the architecture line.
- **Deferred:** native contact-form handler, `404.php`, i18n `.pot`, README, a11y/perf polish → Phase 5.
