# FolioCraft — Customizer Reference

Every front-facing string, list, image, and section title is editable under
**Appearance → Customize → Portfolio**. This documents the full panel structure.

## Behavior

- **Empty = hidden.** Clearing any field hides that element. Clearing every field in a
  section — or removing all rows from a repeater — hides the whole section *and* its nav link.
- **Section label** is the eyebrow (e.g. `01 / About`). The number is positional/automatic;
  the label text is what you edit.
- **Repeaters** (Projects, Experience) show the bundled demo rows only until you save your own;
  once cleared they stay empty (the section hides).
- **Hero visual** can be the code terminal, an image/GIF, or hidden — and the hero text
  expands to full width when it's hidden.

## Portfolio (panel)

```
Portfolio
│
├─ Identity
│    • Full name              (text)
│    • Brand slug             (text)
│    • Accent color           (color)
│    • Headshot image         (media)
│
├─ Hero
│    • Status pill text       (text)
│    • Name line 1            (text)
│    • Name line 2            (text)
│    • Typed roles            (textarea — one role per line)
│    • Lead paragraph         (textarea)
│    • Primary CTA label      (text)
│    • Primary CTA URL        (text)
│    • Stat 1 number / label  (text ×2)
│    • Stat 2 number / label  (text ×2)
│    • Stat 3 number / label  (text ×2)
│    • Hero visual            (radio: Code terminal | Image/GIF | Hidden)
│    • Terminal title bar     (text)
│    • Terminal code          (textarea — lightly syntax-highlighted)
│    • Hero image / GIF       (media — used in "Image/GIF" mode; animated GIF works)
│
├─ About
│    • Section label          (text)   ← eyebrow "01 / …"
│    • Heading                (text)
│    • Paragraph 1 / 2        (textarea ×2)
│    • Pillar 1–3 title       (text ×3)
│    • Pillar 1–3 description (text ×3)
│
├─ Experience
│    • Section label          (text)   ← "02 / …"
│    • Heading                (text)
│    • Experience             (repeater — title, company, date range, "current",
│                                        description, tech rows)
│
├─ Projects
│    • Section label          (text)   ← "03 / …"
│    • Heading                (text)
│    • Projects               (repeater — title, description, image, tech,
│                                        live URL, GitHub URL, stars, language)
│
├─ Skills
│    • Section label          (text)   ← "05 / …"
│    • Heading                (text)
│    • Group 1–4 label        (text ×4)
│    • Group 1–4 pills        (textarea ×4 — one per line)
│
├─ Open Source
│    • Section label          (text)   ← "04 / …"
│    • Heading                (text)
│    • Lead paragraph         (textarea)
│    • Areas                  (textarea — one per line)
│    • Card 1–4 title/role/blurb  (text ×12)
│
├─ Writing
│    • Section label          (text)   ← "06 / …"
│    • Heading                (text)
│    (the posts themselves come from the latest 3 published posts)
│
├─ Social Links
│    • GitHub URL             (url)
│    • LinkedIn URL           (url)
│    • X (Twitter) URL        (url)
│    • Contact email          (email)
│
├─ Contact
│    • Section label          (text)   ← "07 / …"
│    • Heading                (text)
│    • Lead paragraph         (textarea)
│    • Contact form shortcode (text — paste a form-plugin shortcode to replace the
│                                     email button, e.g. [fluentform id="1"])
│
├─ Résumé
│    • Résumé button label    (text — labels BOTH the hero button and the
│                                     résumé-page download button)
│    • Résumé PDF             (media)
│
└─ Footer
     • Footer copy            (textarea)
```

## Notes

- Sections appear in the Customizer in registration order; the eyebrow numbers
  (`01`–`07`) reflect the on-page order, which is About → Experience → Work →
  Open Source → Stack → Writing → Contact.
- Hero, Identity, Social Links, Résumé, and Footer have no eyebrow (they aren't
  numbered on-page).
- This file lives under `docs/` and is excluded from the distributed theme zip
  (`build.sh` / `.gitattributes` export-ignore).
