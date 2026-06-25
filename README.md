# NoorStudio — Custom WordPress Theme

A production WordPress theme that turns the NoorStudio homepage **design handoff**
into a real, customisable WordPress front page, with the **SEO Content Strategy**
recommendations built directly into the markup, `<head>`, and structured data.

- **Design source:** `index.html` + `tokens.css` + `components.md` (high-fidelity, replicated 1:1).
- **SEO source:** *NoorStudio — Complete SEO Content Strategy* (keyword clusters, page-by-page copy, schema plan, technical SEO).

> The theme lives in [`wp-content/themes/noorstudio/`](wp-content/themes/noorstudio).

---

## Install

1. Copy `wp-content/themes/noorstudio/` into your site's `wp-content/themes/`
   directory (or zip that folder and upload it via **Appearance → Themes → Add New → Upload**).
2. Add the real artwork to `wp-content/themes/noorstudio/assets/images/`
   (filenames are listed in `assets/images/README.md`). Until then, branded
   placeholders render in their place so nothing looks broken.
3. **Appearance → Themes → Activate** "NoorStudio".
4. **Settings → Reading → Your homepage displays → A static page** (or leave on
   "latest posts" — `front-page.php` renders the homepage either way).
5. *(Optional)* Create the supporting pages/menus from the sitemap (see below).
   The nav and footer fall back to the correct URLs automatically until then.

No page builder and no SEO plugin is required — everything is self-contained.
If **Yoast** or **Rank Math** is active, the theme automatically steps back from
emitting `<title>` / meta description / Open Graph (to avoid duplicates) but
still outputs the `SoftwareApplication` / `FAQPage` / `Organization` JSON-LD,
which those plugins don't generate for a custom homepage.

---

## How the SEO strategy is implemented

| Strategy recommendation | Where it lives |
|---|---|
| Meta title `AI Children's Book Generator \| Islamic & Personalized Books` | `inc/seo.php` → `noor_seo_values()` |
| Meta description (158 chars) + canonical | `inc/seo.php` → `noor_head_meta()` |
| Open Graph + Twitter card (+ OG image alt) | `inc/seo.php` → `noor_head_meta()` |
| `SoftwareApplication` + `Offer` + `AggregateRating` (4.9 / 3,200) | `inc/seo.php` → `noor_json_ld()` |
| `FAQPage` schema (synced to the visible accordion) | `inc/seo.php` + `inc/content.php` → `noor_faqs()` |
| `Organization` schema (global, `sameAs`, contactPoint) | `inc/seo.php` → `noor_json_ld()` |
| H1 with primary keyword; one H1, H2-per-section, H3 inside | `template-parts/home/*.php` |
| E-E-A-T copy (experience / expertise / trust) verbatim from the doc | `inc/content.php` + section parts |
| Keyword anti-stuffing (primary KW in H1 + first ¶ + one H2 + meta) | hero + styles section copy |
| Internal-linking architecture / sitemap URLs | `inc/content.php` (`noor_nav_links`), `header.php`, `footer.php` |
| `robots.txt` rules (`/app/`, `/dashboard/`, `/api/`, `/user/`, `*.json`) | `inc/seo.php` → `noor_robots_txt()` |
| Core Web Vitals: preload hero (LCP), `width`/`height` on all images (CLS), deferred JS (INP) | `functions.php` (`noor_preload_hero`), `inc/helpers.php` (`noor_img`), footer-loaded `main.js` |
| Accessibility checklist: skip link, `aria-expanded`/`aria-controls` on FAQ, `role="tablist"` on filter, `alt` text, `prefers-reduced-motion`, hidden-tab pause | `header.php`, FAQ/shelf parts, `style.css`, `main.js` |
| WordPress XML sitemap | Core `wp-sitemap.xml` (enabled by default on WP 5.5+) |

### Keeping schema honest
The FAQ answers and the `FAQPage` JSON-LD are generated from **one** array
(`noor_faqs()` in `inc/content.php`). Edit the copy once and both the page and
the structured data update together — they can't drift out of sync.

---

## Sitemap / pages to create next

The theme is the **homepage**. The strategy defines a full site; the nav and
footer already link to these URLs, so creating WordPress pages at these slugs
wires everything up:

```
/features  /how-it-works  /templates  /pricing  /success-stories  /about  /contact
/islamic-childrens-books  /personalized-childrens-books  /kdp-childrens-book-creator
/create-childrens-book-online  /bedtime-story-generator  /ai-story-generator-for-kids
/custom-books-for-eid  /custom-books-for-ramadan  /personalized-books-for-3-year-olds  …
/blog  +  legal: /privacy-policy /terms-of-service /cookie-policy /refund-policy
```

`index.php` renders any of these acceptably out of the box; build dedicated
templates (e.g. `page-islamic-childrens-books.php`) as you flesh each one out
with the copy already written in the strategy document.

---

## Editing content

- **Copy / FAQ / pricing / books / styles / testimonials:** `inc/content.php`
  (one tidy file of arrays). 
- **Menus:** assign WordPress menus to the `primary` and four `footer_*`
  locations under **Appearance → Menus** to override the built-in fallbacks.
- **Logo:** **Appearance → Customize → Site Identity** (custom-logo supported),
  or drop `img-01.png` into `assets/images/`.

## Structure

```
wp-content/themes/noorstudio/
├── style.css            theme header + full design CSS (tokens, sections, responsive, reduced-motion)
├── functions.php        setup, fonts/preconnect, hero preload, menu fallbacks
├── header.php           <head> + sticky nav + mobile drawer
├── footer.php           footer columns + colophon
├── front-page.php       homepage — assembles the section parts
├── index.php            generic fallback (posts/pages)
├── 404.php
├── inc/
│   ├── content.php      homepage content model (single source of truth)
│   ├── helpers.php      noor_img() placeholder fallback, logo, icons
│   └── seo.php          meta, OG/Twitter, JSON-LD, robots.txt
├── template-parts/home/ hero, specstrip, styles, shelf, how, testimonials, pricing, faq, final
└── assets/
    ├── js/main.js       FAQ, filter tabs, hero rotation (Page Visibility), mobile drawer
    └── images/          drop real artwork here (see its README)
```
