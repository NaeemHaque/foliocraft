# Naeem Portfolio

A custom **classic WordPress theme** built for Golam Sarwer Naeem — software engineer and open-source contributor. The theme implements a lightweight **PHP MVC** layer (Controllers / Models / Views under `inc/`) and a **Tailwind v4** CSS build, recreating a pixel-perfect portfolio and blog. It covers a front-page portfolio grid, blog listing / single / archive, a web résumé template, a contact form, and a 404 — all driven by Customizer settings and two custom post types (Projects, Experience). Requirements: **WordPress 6.4+**, **PHP 7.4+**, **Node 18+** (only needed when rebuilding CSS; compiled assets are committed).

---

## Install

1. Copy (or clone) the `naeem-portfolio` folder into `wp-content/themes/`.
2. Activate under **Appearance → Themes**, or via WP-CLI:

```bash
wp theme activate naeem-portfolio
```

Compiled CSS (`assets/css/app.css`) and hand-written JS (`assets/js/main.js`) are committed, so a fresh clone renders without running any build step.

---

## Build (optional)

Only needed when modifying styles. Requires Node 18+.

```bash
npm install
npm run dev    # watch mode — rebuilds on every save
npm run build  # one-shot minified output
```

Source files live in `assets/src/`:

| File | Purpose |
|---|---|
| `tailwind.css` | Tailwind v4 entry point (imports tokens + components) |
| `tokens.css` | Design tokens (custom properties) |
| `components.css` | Hand-authored component overrides |

All three compile to `assets/css/app.css`. The `assets/js/main.js` file is hand-written and requires no build step.

---

## First-run setup

1. Go to **Settings → Reading** and choose:
   - **A static page** as your front page — select the page titled **Home** (or create one).
   - **Posts page** — select the page titled **Blog** (or create one).

2. *(Optional)* Seed demo content — creates sample Projects, Experience entries, and Posts. The script is idempotent (guarded by a `naeem_seeded` option):

```bash
bash tools/seed-content.sh
```

---

## Adding a project

1. Go to **Projects → Add New**.
2. Fill in the **title** and post **content** (description).
3. Set a **featured image** — used as the project card thumbnail.
4. Assign **Tech** and **Contribution Areas** terms (taxonomy pills on the front-page grid).
5. Complete the **Project Details** meta box:
   - **Live URL** — link to the deployed project.
   - **GitHub URL** — repository link.
   - **Stars** — displayed as a stat on the card.
   - **Language** — primary programming language badge.

Projects appear in the front-page portfolio grid; Tech terms become interactive filter pills.

---

## Writing a post

Go to **Posts → Add New**. Publish normally — categories drive the blog filter. The three most-recent published posts appear automatically on the front page; the full paginated list is the Blog page.

---

## Adding experience

1. Go to **Experience → Add New**.
2. Add the **title** (job title) and post **content** (responsibilities / highlights).
3. Complete the **Experience Details** meta box:
   - **Company** — employer name.
   - **Date range** — e.g. `Jan 2022 – Mar 2024`.
   - **Current role** — check this box to mark the role as current (displays "Present").
4. Control display order via **Page Attributes → Order** (lower = first).

---

## Customizing

All visual and copy settings live in **Appearance → Customize → Portfolio**. Key options:

| Section | Controls |
|---|---|
| **Hero** | Status badge, name, roles (comma-separated), lead paragraph, CTA button labels + URLs, stat numbers |
| **About** | About section body copy |
| **Skills** | Skill list |
| **Open Source** | Open-source section copy |
| **Identity / Brand** | Site name, tagline |
| **Social Links** | GitHub, LinkedIn, Twitter/X, email |
| **Accent color** | Primary accent used throughout the theme |
| **Headshot image** | Avatar displayed in the hero |
| **Résumé PDF** | Downloadable PDF linked from the résumé page |
| **Contact** | Contact section heading, sub-copy, optional **Fluent Forms** shortcode |
| **Footer** | Footer text / credits |

---

## Résumé page

1. Create a new **Page** (any slug; `resume` is conventional).
2. In **Page Attributes → Template**, choose **Résumé**.
3. Publish.

The template renders a fully web-based résumé pulled from your Experience CPT entries, skills Customizer fields, and the résumé PDF link set in the Customizer.

---

## Contact form

The contact form works out of the box — submissions are sent via `wp_mail` to the site admin email set in **Settings → General**. The form is protected by a WordPress nonce and a honeypot field.

To swap in **Fluent Forms** (or any shortcode-based form):

1. Install and configure Fluent Forms.
2. Copy the form shortcode (e.g. `[fluentform id="1"]`).
3. Paste it into **Appearance → Customize → Portfolio → Contact → Form shortcode**.

When a shortcode is present the native form is hidden and the shortcode output is used instead.

---

## Translations

The theme is translation-ready with text domain `naeem-portfolio`. Regenerate the POT after adding new strings:

```bash
wp i18n make-pot . languages/naeem-portfolio.pot --domain=naeem-portfolio --exclude=node_modules,docs
```

Place compiled `.po` / `.mo` files in `languages/` using the standard WordPress locale naming (`naeem-portfolio-{locale}.po`).

---

## Architecture

The theme uses a thin MVC-style layer to keep template files clean:

```
front-page.php          ← entry point → Controllers/Front_Page.php
single.php              ← entry point → Controllers/Single_Post.php
home.php                ← entry point → Controllers/Blog.php
archive.php             ← entry point → Controllers/Archive.php
page.php                ← entry point → Controllers/Page.php
page-resume.php         ← entry point → Controllers/Resume.php
404.php                 ← entry point → Controllers/Not_Found.php
```

Each controller resolves the data it needs via a **Model** (`inc/Models/*`) and renders a view via `Naeem\Core\View`, which maps to a file under `template-parts/`. Business logic stays in models; markup stays in template-parts.

```
inc/
  Controllers/     ← request → data
  Models/          ← data layer (WP_Query wrappers)
  Core/View.php    ← view renderer
  PostTypes/       ← Project_CPT, Experience_CPT, Taxonomies
  Customizer/      ← Customizer panel + settings
  Meta/            ← meta boxes
  Forms/           ← contact form handler
  Assets.php       ← enqueue CSS/JS
  Autoloader.php   ← PSR-4 style class loader
template-parts/
  front/           ← hero, projects, about, skills, contact sections
  blog/            ← blog listing, pagination
  cards/           ← project card, post card
  layout/          ← header, footer partials
  resume.php       ← résumé page view
  page.php         ← generic page view
  404.php          ← 404 view
assets/
  src/             ← Tailwind source (tailwind.css, tokens.css, components.css)
  css/app.css      ← compiled output (committed)
  js/main.js       ← hand-written JS (no build)
docs/              ← design spec and reference
```
