# Where Food Takes Us — Custom Block Theme (starter scaffold)

This is a working block theme skeleton, not a tutorial stub — install it
and it'll run. But almost every design value in it is a **placeholder**
that needs to be replaced with what's actually live on
wherefoodtakesus.com. Nothing here should go near production; it's for
your local environment first.

## What's in here

- `style.css` — just the required theme header comment. Block themes
  don't style through CSS files the way classic themes do.
- `theme.json` — the real source of truth for color, typography, and
  spacing. Confirmed real value: the `nav` font family is Quattrocento,
  matching the override you already have live. Everything else
  (`primary`/`secondary` colors, `body` font, font sizes, content width)
  is a placeholder — pull the real values from the live site's computed
  styles (DevTools → inspect any element → check the CSS custom
  properties under `:root` or `.wp-site-blocks`) and swap them in.
- `templates/` — `index.html`, `archive.html`, `single.html`,
  `front-page.html`. Minimal but real block markup.
- `parts/` — `header.html`, `footer.html`.
- `functions.php` — theme setup only. No plugin dependencies. Has TODO
  stubs marking exactly where each later migration phase plugs in, so
  nothing gets forgotten.

## Before you start (local environment checklist)

To get real parity when you install this locally:

1. **Match versions** — same PHP version as production (check via
   SiteGround dashboard or `wp cli version`/`php -v` over SSH) and the
   same WordPress version (7.0).
2. **Install the plugins you're keeping**, so the local site behaves
   like production: Yoast SEO, WP Rocket, Imagify, Cloudflare, ACF
   (free), SiteGround Security Optimizer's non-hosting-dependent
   settings, UpdraftPlus.
3. **Don't install** JetEngine, JetSmartFilters, Spectra, or Twenty
   Twenty Three locally — the whole point is building without them.
4. **Pull a copy of production content** — either a full DB export via
   UpdraftPlus/WP-CLI (`wp db export`), or just enough sample posts to
   test templates against real data, including a post that currently
   uses the Locations map so you can verify the replacement later.

## Next steps, in order

1. Install this theme locally, confirm it activates without errors.
2. Open the Site Editor and replace the placeholder colors/fonts/
   spacing in `theme.json` with the real values from the live site.
3. Rebuild the header/footer in the Site Editor until they visually
   match production (logo, menu structure, footer content).
4. Once the visual shell matches, move to the ACF migration phase —
   registering the Locations custom post type and field group.

Ping me once local is running and you've pulled the real design token
values — that's when we build out the ACF field group + Locations CPT,
and the custom AJAX filter for the destination/category archives.
