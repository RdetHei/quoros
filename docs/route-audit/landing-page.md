# Landing Page

## Entry point

`GET /` (`welcome`) in `routes/web.php`
→ `app/Http/Controllers/NovelController.php::landing`
→ `resources/views/landing/index.blade.php`.

The controller still supplies `featuredNovels`, `recentlyUpdated`, and `stats`; its query and business logic were not changed.

## Section map

`resources/views/landing/index.blade.php`

```text
├── landing/sections/styles.blade.php
├── landing/sections/hero.blade.php
├── landing/sections/statistics-ticker.blade.php
├── landing/sections/editor-choice.blade.php
├── landing/sections/latest-updates.blade.php
└── landing/sections/cover-showcase.blade.php
```

`index.blade.php` prepares the former `$featuredCarouselData` and preserves the original section order. Each section retains its previous markup, classes, attributes, links, and Blade conditions.

## External dependencies

| Path | Used by | Scope / refactor impact |
|---|---|---|
| `resources/views/layouts/app.blade.php` | landing index | Shared layout; supplies head, Vite assets, navigation, main wrapper, footer, and global partials. Not moved. |
| `resources/views/partials/live-search-partial.blade.php` | layout nav | Shared; no change. |
| `resources/views/partials/notification-bell.blade.php` | layout nav for authenticated users | Shared; no change. |
| `resources/views/partials/report-modal.blade.php` | layout for authenticated users | Shared; no change. |
| `resources/views/partials/novel-hover-card.blade.php` | layout | Shared; no change. |
| `resources/css/app.css` | layout through Vite | Shared Tailwind/global CSS; no change. Landing-specific inline CSS is now `landing/sections/styles.blade.php`. |
| `resources/js/app.js` | layout through Vite | Shared bootstrapping; detects `landingHero` markup and lazy-loads the landing module. No change. |
| `resources/js/modules/landing-hero.js` | hero section | Landing-specific Alpine carousel behavior; retained in its existing JS module. |
| `public/error.png`, `storage/logo/quorosLogo.png`, novel cover URLs | hero and layout | Existing fallback/logo/cover assets; no change. |

## Dependency flow

```text
Route /
  ↓
NovelController::landing
  ↓
landing/index.blade.php
  ↓
landing sections
  ↓
layouts/app.blade.php + shared partials
  ↓
resources/css/app.css + resources/js/app.js
  ↓
resources/js/modules/landing-hero.js (lazy-loaded)
```

## Legacy view

`resources/views/welcome.blade.php` was removed after searching the project for `view('welcome')`, `view("welcome")`, `View::make`, and `welcome.blade.php`. No remaining application reference was found.
