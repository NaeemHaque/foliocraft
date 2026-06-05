#!/usr/bin/env bash
#
# build.sh — package FolioCraft into a WordPress.org-ready theme zip.
#
# Recompiles the Tailwind CSS, then copies ONLY the files the theme needs to run
# and pass review into <slug>/ and zips it to the theme root as foliocraft.zip.
# Everything not in the INCLUDE list below is left out (VCS, dev tooling,
# node_modules, docs, the seed script, this build script, README.md, etc.).
#
# Usage:  ./build.sh
#
set -euo pipefail

SLUG="foliocraft"
ROOT="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
ZIP="$ROOT/$SLUG.zip"

# ---- What SHIPS in the zip (anything not listed is excluded) ----------------
INCLUDE=(
  # Root templates + required files
  '*.php'          # all PHP templates + functions.php
  style.css        # main stylesheet / theme header
  readme.txt       # WordPress.org readme
  screenshot.png   # theme screenshot (1200x900)
  # Directories
  inc              # MVC classes: controllers, models, customizer, core
  template-parts   # template partials
  languages        # foliocraft.pot translation template
  assets           # compiled css + js + local fonts + unminified css source
)
# -----------------------------------------------------------------------------

echo "→ FolioCraft build"

# 1. Recompile the minified CSS so the shipped app.css is current.
if command -v npm >/dev/null 2>&1 && [ -f "$ROOT/package.json" ]; then
  echo "  • npm run build (Tailwind)…"
  ( cd "$ROOT" && npm run build >/dev/null )
else
  echo "  • npm not found — shipping the existing assets/css/app.css"
fi

# 2. Stage only the whitelisted paths into a temp <slug>/ folder.
STAGE="$( mktemp -d )"
DEST="$STAGE/$SLUG"
mkdir -p "$DEST"
cd "$ROOT"
for item in "${INCLUDE[@]}"; do
  for match in $item; do                 # unquoted: expands globs like *.php
    [ -e "$match" ] || continue
    mkdir -p "$DEST/$( dirname "$match" )"
    cp -R "$match" "$DEST/$match"
  done
done

# 3. Strip OS cruft that may have been copied along.
find "$DEST" -name '.DS_Store' -delete 2>/dev/null || true

# 4. Zip with the slug as the single top-level folder (what WordPress expects).
rm -f "$ZIP"
( cd "$STAGE" && zip -rqX "$ZIP" "$SLUG" )
rm -rf "$STAGE"

echo "✓ Built $ZIP ($( du -h "$ZIP" | cut -f1 ), $( unzip -l "$ZIP" | tail -1 | awk '{print $2}' ) files)"
