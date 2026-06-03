# Naeem Portfolio — Phase 2: Dynamic Content Models — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make the Projects, Experience, and Blog-preview sections CMS-managed — register the `project` + `experience` CPTs and `tech` + `contribution_area` taxonomies, add a sanitized project meta box, expose the data through Model classes, wire the `Front_Page` controller to those models, and seed sample content — with the front page rendering identically to Phase 1.

**Architecture:** Models (`inc/Models/*`) wrap `WP_Query`/CPT/meta/terms and return the **same array shapes** the Phase 1 views already consume, so the views barely change. CPTs/taxonomies/meta register from `inc/PostTypes/*` + `inc/Meta/*`, booted in `functions.php`. The controller swaps its hardcoded sample arrays for model calls; the project filter pills become the live `tech` terms.

**Tech Stack:** PHP 7.4+ · WordPress 7.0 · WP-CLI (registration checks + content seeding).

---

## Spec & source

- Spec `docs/superpowers/specs/2026-06-03-naeem-portfolio-theme-design.md` §7 (content model) + §8.
- Phase 1 views consume these shapes (preserve them):
  - **project:** `name, icon, desc, tags[], filters[], stars, lang` (+ now: `live_url, github_url` for the card links). The Phase 1 card has `<a href="#">` links and an `$icons[$icon]` glyph — Task 4 updates the card to real links + featured-image/default-icon.
  - **experience item:** `role, company, badge, when, current(bool), desc, tags[]`.
  - **post:** `cat, read, title, excerpt` (+ now: `url` permalink).

## Phase 2 modeling decisions (documented; implement as written)

- **D-P2-1 Project icon:** the `.proj-icon` box shows the **featured image** (`post-thumbnail`, sized `naeem-project` cropped to the box) when set; otherwise a **default SVG glyph** (use the prototype's `blocks` icon). No per-post icon-picker meta (YAGNI).
- **D-P2-2 Project meta:** `_naeem_live_url`, `_naeem_github_url` (spec) **plus optional** `_naeem_stars`, `_naeem_lang` so the card's `.proj-meta` row (stars · language) stays faithful to the design. All registered with `register_post_meta` (`show_in_rest`, `auth_callback` = `current_user_can('edit_posts')`) and saved through one nonce-protected meta box. The `.proj-meta` row renders only when `stars` or `lang` is non-empty.
- **D-P2-3 Project desc:** `get_the_excerpt()` (WP auto-trims content when no manual excerpt).
- **D-P2-4 Filter pills:** built from the `tech` terms actually attached to published projects — `all` + one pill per term (`data-filter` = term slug, label = term name). Project `filters[]` = the project's `tech` term slugs.
- **D-P2-5 Experience order/featured:** query ordered by `menu_order ASC, date DESC`; the `tl-featured` + accent-chip treatment is driven by `_naeem_is_current`. `when` = `_naeem_date_range`; `company` = `_naeem_company`; `badge` = empty for now (kept in the shape; a later phase may add a badge meta). `tags[]` = the entry's `tech` terms.
- **D-P2-6 Blog preview:** latest 3 published posts via `WP_Query`. `cat` = first category name; `read` = estimated reading time from word count (`max(1, ceil(str_word_count(wp_strip_all_tags(content)) / 200))` + " min read"); `excerpt` = `get_the_excerpt()`; `url` = `get_permalink()`. The card links to the permalink; "All posts" links to `home_url('/')` (Phase 4 swaps in the posts page + single.php).

## Conventions (every task)

- PHP 7.4+; WP Coding Standards; `defined( 'ABSPATH' ) || exit;` after any namespace; text domain `naeem-portfolio`.
- Escape on output; sanitize on input; nonces on the meta box.
- Paths relative to theme root. Work on branch `phase-2-dynamic-content`; commit per task.
- Verification uses `wp eval`, `wp post-type list`, `wp taxonomy list`, `wp post create/meta`, and `curl`. `SITE="$(wp option get home)"`.

## Before Task 1
```bash
cd wp-content/themes/naeem-portfolio
git checkout -b phase-2-dynamic-content
```

---

### Task 1: Register taxonomies + Project & Experience CPTs

**Files:** Create `inc/PostTypes/Taxonomies.php`, `inc/PostTypes/Project_CPT.php`, `inc/PostTypes/Experience_CPT.php`; Modify `functions.php` (boot them).

**Taxonomies.php** — class `Naeem\PostTypes\Taxonomies` with `init()` adding an `init` action `register()`:
```php
register_taxonomy( 'tech', array( 'project', 'experience' ), array(
    'labels'            => array( 'name' => __( 'Tech', 'naeem-portfolio' ), 'singular_name' => __( 'Tech', 'naeem-portfolio' ) ),
    'public'            => true,
    'hierarchical'      => false,
    'show_admin_column' => true,
    'show_in_rest'      => true,
) );
register_taxonomy( 'contribution_area', array( 'project' ), array(
    'labels'            => array( 'name' => __( 'Contribution Areas', 'naeem-portfolio' ), 'singular_name' => __( 'Contribution Area', 'naeem-portfolio' ) ),
    'public'            => true,
    'hierarchical'      => false,
    'show_admin_column' => true,
    'show_in_rest'      => true,
) );
```
**Project_CPT.php** — `init()` adds `init` → `register()`:
```php
register_post_type( 'project', array(
    'labels'       => array( 'name' => __( 'Projects', 'naeem-portfolio' ), 'singular_name' => __( 'Project', 'naeem-portfolio' ), 'add_new_item' => __( 'Add New Project', 'naeem-portfolio' ) ),
    'public'       => true,
    'menu_icon'    => 'dashicons-portfolio',
    'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
    'has_archive'  => false,
    'show_in_rest' => true,
    'rewrite'      => array( 'slug' => 'work' ),
) );
```
**Experience_CPT.php** — `init()` adds `init` → `register()`:
```php
register_post_type( 'experience', array(
    'labels'       => array( 'name' => __( 'Experience', 'naeem-portfolio' ), 'singular_name' => __( 'Experience', 'naeem-portfolio' ), 'add_new_item' => __( 'Add New Entry', 'naeem-portfolio' ) ),
    'public'       => false,
    'show_ui'      => true,
    'menu_icon'    => 'dashicons-businessperson',
    'supports'     => array( 'title', 'editor', 'page-attributes' ),
    'show_in_rest' => true,
) );
```
Each file: `<?php namespace Naeem\PostTypes; defined(...)||exit;` then the class. `functions.php`: after `Assets::init();` add `\Naeem\PostTypes\Taxonomies::init(); \Naeem\PostTypes\Project_CPT::init(); \Naeem\PostTypes\Experience_CPT::init();`. After registering, flush rewrite once: run `wp rewrite flush` in verification.

- [ ] **Step 1 — failing check:** `wp post-type list --field=name | grep -x project || echo ABSENT` → `ABSENT`.
- [ ] **Step 2 — create the three files + wire functions.php.**
- [ ] **Step 3 — verify:**
```bash
for f in inc/PostTypes/*.php; do php -l "$f"; done && php -l functions.php
wp rewrite flush
wp post-type list --field=name | grep -Ex 'project|experience'    # both
wp taxonomy list --field=name | grep -Ex 'tech|contribution_area' # both
wp eval 'echo post_type_exists("project") && post_type_exists("experience") && taxonomy_exists("tech") && taxonomy_exists("contribution_area") ? "ALL REGISTERED" : "FAIL";'
```
Expected: lint passes; `project`+`experience`; `tech`+`contribution_area`; `ALL REGISTERED`.
- [ ] **Step 4 — commit:** `git add inc/PostTypes functions.php && git commit -m "feat(cpt): register project + experience CPTs and tech/contribution_area taxonomies"`

---

### Task 2: Project meta box (Live/GitHub URL + optional stars/lang)

**Files:** Create `inc/Meta/Project_Meta.php`; Modify `functions.php` (boot it).

`Naeem\Meta\Project_Meta::init()` hooks: `init` → `register_meta()`, `add_meta_boxes` → `add_box()`, `save_post_project` → `save()`.
- `register_meta()` calls `register_post_meta('project', $key, array('type'=>'string','single'=>true,'show_in_rest'=>true,'sanitize_callback'=>$cb,'auth_callback'=>function(){return current_user_can('edit_posts');}))` for the four keys: `_naeem_live_url` + `_naeem_github_url` (sanitize `esc_url_raw`), `_naeem_stars` + `_naeem_lang` (sanitize `sanitize_text_field`).
- `add_box()` → `add_meta_box('naeem_project_details', __('Project Details','naeem-portfolio'), array(__CLASS__,'box'), 'project', 'side')`.
- `box($post)` → outputs `wp_nonce_field('naeem_project_save','naeem_project_nonce')` + four labelled `<input>`s (`esc_attr` the current `get_post_meta` values; `type="url"` for the URLs).
- `save($post_id)` → bail on autosave / missing-or-invalid nonce (`! isset($_POST['naeem_project_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['naeem_project_nonce'])),'naeem_project_save')`) / `! current_user_can('edit_post',$post_id)`; then for each field `update_post_meta` with the sanitized, unslashed value (`esc_url_raw(wp_unslash(...))` for URLs, `sanitize_text_field(wp_unslash(...))` for stars/lang).

`functions.php`: add `\Naeem\Meta\Project_Meta::init();`.

- [ ] **Step 1 — failing check:** `wp eval 'echo registered_meta_key_exists("post","_naeem_live_url","project")?"YES":"NO";'` → `NO`.
- [ ] **Step 2 — create the file + wire functions.php.**
- [ ] **Step 3 — verify (registration + round-trip save):**
```bash
php -l inc/Meta/Project_Meta.php && php -l functions.php
wp eval 'echo registered_meta_key_exists("post","_naeem_live_url","project") && registered_meta_key_exists("post","_naeem_github_url","project") ? "META OK" : "FAIL";'
PID=$(wp post create --post_type=project --post_title="Meta Test" --post_status=draft --porcelain)
wp post meta update "$PID" _naeem_github_url "https://github.com/x/y"
wp eval "echo esc_html( get_post_meta($PID,'_naeem_github_url',true) );"   # the URL
wp post delete "$PID" --force
```
Expected: lint passes; `META OK`; the URL echoes back.
- [ ] **Step 4 — commit:** `git add inc/Meta functions.php && git commit -m "feat(cpt): sanitized project meta box (live/github url, stars, lang)"`

---

### Task 3: Model classes (Project, Experience, Post)

**Files:** Create `inc/Models/Project.php`, `inc/Models/Experience.php`, `inc/Models/Post.php`.

Each exposes a static method returning an array of associative arrays in the **exact shape Phase 1 views expect** (see "shapes" above), so the views need only the small card tweaks in Task 4.

- `Naeem\Models\Project::all()` → `WP_Query` (`post_type=project`, `posts_per_page=-1`, `post_status=publish`, `orderby=menu_order date`, `order=ASC`). For each: `name`=title, `desc`=`get_the_excerpt()`, `live_url`/`github_url`/`stars`/`lang`=meta, `tags`=`wp_get_post_terms($id,'tech',array('fields'=>'names'))`, `filters`=same terms' slugs, `thumb_id`=`get_post_thumbnail_id()`. Restore post data (`wp_reset_postdata`).
- `Naeem\Models\Project::tech_terms()` → `get_terms(array('taxonomy'=>'tech','hide_empty'=>true))` mapped to `array('slug'=>..., 'name'=>...)` for the filter pills.
- `Naeem\Models\Experience::all()` → `WP_Query` (`post_type=experience`, `-1`, `orderby=menu_order date`, `order=ASC`). For each: `role`=title, `company`=`_naeem_company`, `when`=`_naeem_date_range`, `current`=`(bool) _naeem_is_current`, `badge`=`''`, `desc`=`get_the_excerpt()` (or content), `tags`=`tech` term names.
- `Naeem\Models\Post::latest($n = 3)` → `WP_Query` (`post_type=post`, `posts_per_page=$n`, `post_status=publish`). For each: `cat`=first category name (`get_the_category`), `read`=reading-time estimate (D-P2-6), `title`=title, `excerpt`=`get_the_excerpt()`, `url`=`get_permalink()`.

Each method must use `wp_reset_postdata()` after the loop and read meta/terms by post ID (not rely on global `$post` beyond `setup_postdata` if used).

- [ ] **Step 1 — failing check:** `wp eval 'echo class_exists("Naeem\\Models\\Project")?"YES":"NO";'` → `NO`.
- [ ] **Step 2 — create the three model files.**
- [ ] **Step 3 — verify (with a temp project):**
```bash
for f in inc/Models/*.php; do php -l "$f"; done
PID=$(wp post create --post_type=project --post_title="Model Test" --post_status=publish --post_excerpt="Desc here" --porcelain)
wp post term set "$PID" tech php vue
wp eval '$p = Naeem\Models\Project::all(); echo is_array($p) && ! empty($p) && $p[0]["name"]==="Model Test" && in_array("php", $p[0]["filters"], true) ? "PROJECT MODEL OK" : "FAIL";'
wp eval '$t = Naeem\Models\Project::tech_terms(); echo is_array($t) ? "TERMS OK(".count($t).")" : "FAIL";'
wp eval 'echo is_array(Naeem\Models\Experience::all()) ? "EXP OK" : "FAIL";'
wp eval 'echo is_array(Naeem\Models\Post::latest(3)) ? "POST OK" : "FAIL";'
wp post delete "$PID" --force
```
Expected: lint passes; `PROJECT MODEL OK`; `TERMS OK(n)`; `EXP OK`; `POST OK`.
- [ ] **Step 4 — commit:** `git add inc/Models && git commit -m "feat(models): Project, Experience, Post data models over CPTs/WP_Query"`

---

### Task 4: Wire Front_Page controller + card templates to the models

**Files:** Modify `inc/Controllers/Front_Page.php`; Modify `template-parts/front/projects.php` (filter pills from terms), `template-parts/cards/project-card.php` (real links + featured-image/default icon + conditional meta), `template-parts/cards/post-card.php` (permalink).

- **Controller:** delete the `experience()`/`projects()`/`posts()` sample methods. In `render()`:
  ```php
  View::render( 'front/projects', array( 'projects' => \Naeem\Models\Project::all(), 'terms' => \Naeem\Models\Project::tech_terms() ) );
  ...
  View::render( 'front/experience', array( 'items' => \Naeem\Models\Experience::all() ) );
  ...
  View::render( 'front/blog', array( 'posts' => \Naeem\Models\Post::latest( 3 ), 'blog_url' => home_url( '/' ) ) );
  ```
  (hero/about/opensource/skills/contact unchanged.)
- **projects.php:** replace the hardcoded `filter-btn` buttons after the `all` button with a loop over `$terms`: `<button class="filter-btn" data-filter="<?php echo esc_attr($term['slug']); ?>"><?php echo esc_html($term['name']); ?></button>`. Keep the `all` button (`active`). Guard: if `empty($projects)`, render a muted "Projects coming soon" `<p>` in the grid (so an empty CMS still looks intentional).
- **project-card.php:** the card now receives `name, desc, tags, filters, stars, lang, live_url, github_url, thumb_id`. Changes: (a) `.proj-icon` → if `! empty($thumb_id)`, `echo wp_get_attachment_image($thumb_id,'naeem-project',false,array('alt'=>''))`; else echo the default `blocks` SVG (keep the `$icons['blocks']` glyph). (b) links → `href="<?php echo $live_url ? esc_url($live_url) : '#'; ?>"` and likewise github; add `target="_blank" rel="noopener"` when a real URL is present. (c) `.proj-meta` row → wrap in `<?php if ($stars || $lang) : ?>…<?php endif; ?>`, showing star icon+`$stars` only if `$stars`, dot icon+`$lang` only if `$lang`. Keep all SVGs + classes.
- **post-card.php:** it already links to a passed URL — change so each post links to its own permalink: the controller passes `posts` whose items include `url`; in blog.php, pass `array_merge($post, array('blog_url'=>$post['url']))` OR change post-card to use `$url`. Simplest: edit `post-card.php` to use `$url` (the post permalink) for the `href`, and `blog.php` to pass the item as-is (each item already has `url`). Keep "All posts" → `$blog_url`.

- [ ] **Step 1 — failing check (controller still has sample data):** `grep -c "private function projects" inc/Controllers/Front_Page.php` → `1` (present, to be removed).
- [ ] **Step 2 — apply the controller + three template edits.**
- [ ] **Step 3 — verify (no content yet → graceful, no errors):**
```bash
php -l inc/Controllers/Front_Page.php && php -l template-parts/front/projects.php && php -l template-parts/cards/project-card.php && php -l template-parts/cards/post-card.php
grep -c "private function projects" inc/Controllers/Front_Page.php   # 0 (removed)
SITE="$(wp option get home)"; H="$(curl -s "$SITE")"
printf '%s' "$H" | grep -c 'id="filterBar"'   # 1 (filter bar still renders)
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
Expected: lint passes; `0`; `1`; `0`. (Grids may be empty until Task 5 seeds content — that is expected and must not error.)
- [ ] **Step 4 — commit:** `git add inc/Controllers/Front_Page.php template-parts/front/projects.php template-parts/cards/project-card.php template-parts/cards/post-card.php && git commit -m "feat(front): wire projects/experience/blog to CPT models"`

---

### Task 5: Seed sample content + final verification

**Files:** Create `tools/seed-content.sh` (a WP-CLI seeding script, idempotent-ish: it checks a sentinel option so re-runs don't duplicate).

The script (run via `bash tools/seed-content.sh`) uses `wp` to create, only if `wp option get naeem_seeded` is unset:
- The **6 projects** (titles/descriptions/tech terms/`_naeem_stars`/`_naeem_lang`/`_naeem_github_url` from the Phase 1 sample data in `inc/Controllers/Front_Page.php` history / the prototype). Set `tech` terms per project's `filters`. Publish.
- The **4 experience entries** (titles + `_naeem_company` + `_naeem_date_range` + `_naeem_is_current` for WPManageNinja; `menu_order` 1..4; `tech` terms = tags; content = the description). Publish.
- **3 posts** (titles/excerpts/categories: WordPress, Workflow, Career) matching the Phase 1 blog preview. Publish.
- Then `wp option update naeem_seeded 1`.
Each `wp post create` uses `--porcelain` to capture the ID for `wp post meta update` / `wp post term set`.

- [ ] **Step 1 — failing check:** `wp post list --post_type=project --format=count` → `0`.
- [ ] **Step 2 — write `tools/seed-content.sh` with the full content; run it: `bash tools/seed-content.sh`.**
- [ ] **Step 3 — verify (content + front page):**
```bash
wp post list --post_type=project --format=count       # 6
wp post list --post_type=experience --format=count    # 4
wp post list --post_type=post --post_status=publish --format=count   # >=3
SITE="$(wp option get home)"; H="$(curl -s "$SITE")"
printf '%s' "$H" | grep -c 'proj-card'     # 6
printf '%s' "$H" | grep -c 'tl-item'       # 4
printf '%s' "$H" | grep -c 'class="post"'  # 3
printf '%s' "$H" | grep -c 'filter-btn'    # >=2 (all + >=1 tech term)
printf '%s' "$H" | grep -Eic 'fatal error|parse error|<b>warning|<b>notice' || true   # 0
```
Expected: 6 / 4 / ≥3; cards 6 / 4 / 3; filter buttons ≥2; errors 0.
- [ ] **Step 4 — manual check:** in wp-admin, **Projects → Add New** has the Tech + Contribution Areas boxes + the "Project Details" meta box (Live/GitHub/Stars/Lang); editing a project + saving updates the front-page grid. Note results in the commit body.
- [ ] **Step 5 — commit:** `git add tools/seed-content.sh && git commit -m "chore(seed): sample projects, experience, and posts via WP-CLI"`

---

## Phase 2 — Definition of Done

- `project` + `experience` CPTs and `tech` + `contribution_area` taxonomies registered and visible in wp-admin.
- Project meta box saves Live/GitHub/Stars/Lang with a nonce + sanitization.
- Front page renders Projects (with live filter pills), Experience timeline, and Blog preview entirely from the database; **zero PHP notices**.
- Adding/editing a project or experience entry or post in wp-admin updates the front page with no code changes.
- Sample content seeded; the page matches Phase 1 visually.
- Branch `phase-2-dynamic-content` holds 5 commits.

## Self-review (plan author)

- **Spec coverage:** §7 project CPT + `tech`/`contribution_area` (T1), project meta (T2, +stars/lang per D-P2-2), experience CPT (T1), models + wiring (T3–T4), blog via posts (T3/T4), seeding (T5). `contribution_area` is registered (T1) and available on projects for the Open Source section's future Customizer use (Phase 3) — not yet surfaced on the front end, which is correct for this phase.
- **Placeholder scan:** none — registration/meta/save code given explicitly; model shapes enumerated; seeding content enumerated by reference to the established sample set.
- **Name consistency:** meta keys `_naeem_live_url/_github_url/_stars/_lang`; model methods `Project::all()/tech_terms()`, `Experience::all()`, `Post::latest()`; view data keys unchanged from Phase 1 except the added `live_url/github_url/thumb_id/url`, all consumed in the Task 4 card edits.
- **Deferred:** Customizer copy + Open Source/skills/hero editability (Phase 3); single/archive/blog-page + résumé page (Phase 4); contact handler + i18n .pot + a11y/perf polish (Phase 5).
