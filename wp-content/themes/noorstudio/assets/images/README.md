# Theme images

Drop the image assets from the design handoff bundle here. The theme references
them by these exact filenames; until a file is present, the theme renders a
branded placeholder in its place (so the layout never breaks).

| File | Used for | Recommended spec |
|------|----------|------------------|
| `img-01.png` | Logo / wordmark (nav + footer) | SVG preferred; transparent PNG ok |
| `img-02.jpg` | Hero main portrait (LCP, preloaded) | ~1:1, 440×440, WebP/JPEG, < 150 KB |
| `img-03.jpg` – `img-07.jpg` | Hero satellite portraits + style cards | ~1:1, 140–180 px |
| `img-08.png` – `img-14.png` | Library book covers | 2:2.7, 1390×1720 min, PNG/WebP |
| `img-15.jpg` | Hero rotation / testimonial avatar | ~1:1 |
| `og-image.jpg` *(optional)* | Social share card | 1200×630, < 200 KB |

Tips for Core Web Vitals (from the SEO strategy, PART 6):
- Export the hero image as WebP/AVIF and compress to **< 150 KB** (target LCP < 2.5s).
- Keep the same aspect ratios listed above so the explicit `width`/`height` the
  theme emits keep CLS at 0.
