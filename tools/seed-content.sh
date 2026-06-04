#!/usr/bin/env bash
# Seed sample content for the naeem-portfolio theme. Idempotent via the
# naeem_seeded option (re-running does nothing once seeded).
set -uo pipefail

if [ "$(wp option get naeem_seeded 2>/dev/null || echo 0)" = "1" ]; then
  echo "Already seeded; nothing to do."
  exit 0
fi

set_tech() { # $1=post id, $2=pipe-delimited term names
  local id="$1"; local -a arr
  IFS='|' read -ra arr <<< "$2"
  wp post term set "$id" tech "${arr[@]}" >/dev/null
}

project() { # title | desc | terms(|) | stars | lang | github
  local id
  id=$(wp post create --post_type=project --post_status=publish --post_title="$1" --post_excerpt="$2" --porcelain)
  set_tech "$id" "$3"
  wp post meta update "$id" _naeem_stars "$4" >/dev/null
  wp post meta update "$id" _naeem_lang "$5" >/dev/null
  wp post meta update "$id" _naeem_github_url "$6" >/dev/null
  echo "  project #$id: $1"
}

experience() { # title | company | range | current | order | terms(|) | content
  local id
  id=$(wp post create --post_type=experience --post_status=publish --post_title="$1" --post_content="$7" --menu_order="$5" --porcelain)
  set_tech "$id" "$6"
  wp post meta update "$id" _naeem_company "$2" >/dev/null
  wp post meta update "$id" _naeem_date_range "$3" >/dev/null
  wp post meta update "$id" _naeem_is_current "$4" >/dev/null
  echo "  experience #$id: $1"
}

post_entry() { # title | category | excerpt | content
  local catid id
  catid=$(wp term list category --name="$2" --field=term_id 2>/dev/null | head -1)
  if [ -z "$catid" ]; then catid=$(wp term create category "$2" --porcelain); fi
  id=$(wp post create --post_status=publish --post_title="$1" --post_excerpt="$3" --post_content="$4" --post_category="$catid" --porcelain)
  echo "  post #$id: $1"
}

echo "Seeding projects…"
project "DevPulse" "A team productivity dashboard surfacing PRs, deploys, and CI health in real time. Laravel API, Vue front-end, MySQL." "Laravel|Vue|MySQL|PHP" "312" "PHP" "https://github.com/naeemHaque/devpulse"
project "WP Schema Pilot" "WordPress plugin that auto-generates schema.org structured data — Yoast/Rank Math friendly, zero config." "WordPress|PHP" "1.2k" "PHP" "https://github.com/naeemHaque/wp-schema-pilot"
project "QueryLens" "Slow-query analyzer for MySQL with a web UI — visualizes EXPLAIN plans and suggests indexes." "PHP|MySQL" "486" "PHP" "https://github.com/naeemHaque/querylens"
project "Fluent Blocks Kit" "A collection of reusable Vue-powered blocks and components for WordPress and EmDash CMS projects." "Vue|WordPress" "740" "Vue" "https://github.com/naeemHaque/fluent-blocks-kit"
project "LaravelKit Starter" "Opinionated Laravel starter with auth, queues, and a Vue + Tailwind front-end wired for clean architecture." "Laravel|Vue|MySQL|PHP" "928" "PHP" "https://github.com/naeemHaque/laravelkit-starter"
project "Polyglot Helper" "A contributor tool for WordPress Polyglots — speeds up string review and translation suggestions." "WordPress|Vue|PHP" "203" "JavaScript" "https://github.com/naeemHaque/polyglot-helper"

echo "Seeding experience…"
experience "Software Engineer" "WPManageNinja" "2022 — Present" "1" "1" "PHP|WordPress|Vue.js|REST APIs|MySQL" "Product engineering on WordPress software used by millions of users and businesses worldwide. Building scalable features, developer-facing tooling, and the architecture behind it — from data models to REST APIs to front-end with Vue."
experience "Full-Stack Developer" "Product & web app work" "2020 — 2022" "0" "2" "Laravel|MySQL|Vue.js|REST APIs" "Built and maintained scalable web apps and backend systems with PHP, Laravel, and MySQL — designing clean data models and REST APIs, and pairing them with Vue.js front-ends."
experience "Open Source Contributor" "WordPress Project · EmDash CMS" "2019 — Present" "0" "3" "Core|Polyglots|Meta|Photos" "Ongoing contributions across WordPress Core, Plugins, Meta, Polyglots, and Photos, plus EmDash CMS — fixing issues, reviewing, and translating with the global community."
experience "Computer Science & Engineering" "Sylhet International University" "B.Sc. CSE" "0" "4" "Algorithms|Data Structures|Competitive Programming" "Studied CSE and competed heavily in programming contests — the foundation for detail-oriented, fast problem-solving that still shapes how I engineer today."

echo "Seeding posts…"
post_entry "Building a CPT-driven portfolio the WordPress way" "WordPress" "Why custom post types and taxonomies beat hardcoded HTML, and how to model projects so anyone can manage them from wp-admin." "Custom post types and taxonomies are the WordPress-native way to model structured content like a project portfolio. Instead of hardcoding HTML, register a project CPT with a tech taxonomy and a couple of meta fields, and the whole grid becomes editable from wp-admin with no code changes."
post_entry "How I fold AI into my daily dev workflow" "Workflow" "From research and feature planning to debugging — a practical look at where AI actually saves time, and where it doesn't." "AI has become a steady part of my daily development workflow — research, feature planning, scaffolding, and debugging — while I keep a close eye on architecture and correctness. The trick is knowing where it accelerates you and where a careful human pass still matters."
post_entry "What competitive programming taught me about shipping" "Career" "Contest habits — reading edge cases first, thinking on your feet — that quietly made me a better product engineer." "Years of competitive programming left me with habits that translate directly to shipping software: read the edge cases first, decompose the problem, and think on your feet under pressure. Those instincts make me a calmer, more precise product engineer."

wp option update naeem_seeded 1 >/dev/null
echo "Done; naeem_seeded=1."
