# Wncms Novels

Novel and chapter management package for WNCMS (Laravel-based CMS), providing backend CRUD, frontend routes, and integration with the WNCMS ecosystem.

## Requirements

-   PHP `^8.2`
-   `secretwebmaster/wncms-core` `^6.0` (WNCMS v6)

This package is designed to run inside an existing WNCMS installation and is not intended to be used as a standalone Laravel package.

## Installation

Install via Composer:

```bash
composer require secretwebmaster/wncms-novels
```

The service provider is auto-discovered through Laravel’s package discovery (see `composer.json`).

After Composer finishes, no manual provider registration is required.

## Activation in WNCMS

Once installed, enable the package from the WNCMS backend:

1. Log in to your WNCMS backend.
2. Open the Packages management page on left sidebar menu.
3. Locate the “Novels” package.
4. Activate it.

When the package is activated:

-   Database migrations under `database/migrations` are automatically loaded and applied.
-   The "Novels" and "Chapters" menus are registered in the WNCMS backend.
-   Permissions for managing novels and chapters are registered.
-   Route files are loaded, and backend/frontend routes become available.

You do **not** need to run:

-   `php artisan migrate`
-   `php artisan vendor:publish`
-   `php artisan db:seed`

All core setup is handled by WNCMS during activation.

## Demo Data Generator (Optional)

This package provides an optional console command to generate demo novels and chapters using Faker (English content):

```bash
php artisan wncms-novels:generate {count=5} {--min=3} {--max=5}
```

-   `count` – number of novels to create (default: `5`)
-   `--min` – minimum number of chapters per novel (default: `3`)
-   `--max` – maximum number of chapters per novel (default: `5`)

Example:

```bash
php artisan wncms-novels:generate 10 --min=5 --max=8
```

This will:

-   Create 10 novels using the WNCMS novel model.
-   For each novel, create between 5 and 8 chapters.
-   Populate fields like `title`, `slug`, `description`, `content`, `author`, `status`, `published_at`.
-   Update the `chapter_count` column for each novel.

Use this command only on development or demo environments.

## Routes

### Backend

```
-   GET /panel/novels → novels.index
-   GET /panel/novels/create → novels.create
-   GET /panel/novels/create/{id} → novels.clone
-   POST /panel/novels → novels.store
-   GET /panel/novels/{id}/edit → novels.edit
-   PATCH /panel/novels/{id} → novels.update
-   DELETE /panel/novels/{id} → novels.destroy

-   GET /panel/novel-chapters → novel_chapters.index
-   GET /panel/novel-chapters/create → novel_chapters.create
-   GET /panel/novel-chapters/create/{id} → novel_chapters.clone
-   POST /panel/novel-chapters → novel_chapters.store
-   GET /panel/novel-chapters/{id}/edit → novel_chapters.edit
-   PATCH /panel/novel-chapters/{id} → novel_chapters.update
-   DELETE /panel/novel-chapters/{id} → novel_chapters.destroy
```

### Frontend

```
-   GET /novel → frontend.novels.index
-   GET /novel/search → frontend.novels.search
-   POST /novel/search → frontend.novels.search
-   GET /novel/search/{keyword} → frontend.novels.search.result
-   GET /novel/{type}/{slug} → frontend.novels.tag
-   GET /novel/{slug} → frontend.novels.show

-   GET /novel/{novelSlug}chapter/{chapterSlug} → frontend.novels.chapters.show
```

## Backend Features

Once activated, the package adds two main sections in the WNCMS backend:

-   Novels management
-   Chapters management

The backend UI:

-   Uses the core WNCMS backend layout (`wncms::layouts.backend`).
-   Provides toolbar filters (status, category, text search, etc.).
-   Integrates with standard WNCMS components:

    -   `wncms::backend.common.default_toolbar_filters`
    -   `wncms::backend.common.default_toolbar_buttons`
    -   `wncms::backend.common.showing_item_of_total`
    -   `wncms::common.table_status`
    -   `wncms::common.table_is_active`

-   Supports an optional `show_detail` toggle for more columns, including slug, categories, tags, flags, pricing, dates, source, and chapter_count.
-   Links directly to the frontend show page for each novel.
-   Provides a quick link to manage chapters for each novel.

Permissions:

-   `novel_index`, `novel_create`, `novel_edit`, `novel_delete`
-   `novel_chapter_index`, `novel_chapter_create`, `novel_chapter_edit`, `novel_chapter_delete`

These permissions are registered by the service provider and can be assigned within WNCMS.

## Data Model Overview

The package defines two core models:

-   `Secretwebmaster\WncmsNovels\Models\Novel`
-   `Secretwebmaster\WncmsNovels\Models\NovelChapter`

Key relationships (registered via `MacroableModels`):

-   User → Novels:

    -   A user can have many novels (`user()->novels()`).

-   Novel → User:

    -   A novel belongs to a user (`novel()->user()`).

-   Novel → Chapters:

    -   A novel has many chapters (`novel()->chapters()`).

-   Chapter → Novel:

    -   A chapter belongs to a novel (`chapter()->novel()`).

Additional helpers on the novel model:

-   `chapter_count` attribute:

    -   Counts related chapters.

-   `latestChapter()` method:

    -   Returns the latest chapter ordered by `number` descending.

## Tags and Taxonomy

The package integrates with WNCMS’s tag system via the Tag model:

-   Category tag type: `novel_category`
-   Standard tag type: `novel_tag`

Examples (used in the backend views):

-   `$novel->TagsWithType('novel_category')->implode('name', ',')`
-   `$novel->TagsWithType('novel_tag')->implode('name', ',')`

Tag type configuration is handled by the core WNCMS tag package.

## API Usage

This package provides REST-style JSON APIs for novels and chapters under the `api/v1` prefix.

All endpoints require:

-   Middleware: `api`, `is_installed`, `has_website`
-   Authentication method defined by your WNCMS API setup (typically `api_token` or JWT)

Below are practical examples of how to use the API.

---

### List Novels

```
GET /api/v1/novels
```

Example:

```bash
curl -X GET "https://your-site.com/api/v1/novels" \
     -H "Authorization: Bearer YOUR_API_TOKEN"
```

---

### Get Single Novel

```
GET /api/v1/novels/{id}
```

Example:

```bash
curl -X GET "https://your-site.com/api/v1/novels/12" \
     -H "Authorization: Bearer YOUR_API_TOKEN"
```

---

### Create Novel

```
POST /api/v1/novels
```

Example:

```bash
curl -X POST "https://your-site.com/api/v1/novels" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
           "title": "My New Novel",
           "slug": "my-new-novel",
           "status": "published",
           "author": "Admin"
         }'
```

---

### Update Novel

```
PUT /api/v1/novels/{id}
```

Example:

```bash
curl -X PUT "https://your-site.com/api/v1/novels/12" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{ "title": "Updated Title" }'
```

---

### Delete Novel

```
DELETE /api/v1/novels/{id}
```

Example:

```bash
curl -X DELETE "https://your-site.com/api/v1/novels/12" \
     -H "Authorization: Bearer YOUR_API_TOKEN"
```

---

### List Chapters

```
GET /api/v1/chapters
```

Example:

```bash
curl -X GET "https://your-site.com/api/v1/chapters" \
     -H "Authorization: Bearer YOUR_API_TOKEN"
```

---

### Get Single Chapter

```
GET /api/v1/chapters/{id}
```

Example:

```bash
curl -X GET "https://your-site.com/api/v1/chapters/88" \
     -H "Authorization: Bearer YOUR_API_TOKEN"
```

---

### Create Chapter

```
POST /api/v1/chapters
```

Example:

```bash
curl -X POST "https://your-site.com/api/v1/chapters" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{
           "novel_id": 12,
           "title": "Chapter 1",
           "slug": "chapter-1",
           "content": "This is chapter content",
           "status": "published"
         }'
```

---

### Update Chapter

```
PUT /api/v1/chapters/{id}
```

Example:

```bash
curl -X PUT "https://your-site.com/api/v1/chapters/88" \
     -H "Authorization: Bearer YOUR_API_TOKEN" \
     -H "Content-Type: application/json" \
     -d '{ "title": "New Chapter Title" }'
```

---

### Delete Chapter

```
DELETE /api/v1/chapters/{id}
```

Example:

```bash
curl -X DELETE "https://your-site.com/api/v1/chapters/88" \
     -H "Authorization: Bearer YOUR_API_TOKEN"
```

## Managers and Access

The service provider registers singleton managers for convenient access:

-   `Secretwebmaster\WncmsNovels\Services\Managers\NovelManager`
-   `Secretwebmaster\WncmsNovels\Services\Managers\ChapterManager`

They are bound under:

-   `wncms.novel`
-   `wncms.novel_chapter`

Within WNCMS, you can typically access them through the WNCMS helper, for example:

```php
$novels = wncms()->package('wncms-novels')->novel()->getList([...]);
$chapter = wncms()->package('wncms-novels')->novel_chapter()->get([...]);
```

Exact usage depends on the WNCMS core helper API in your project.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).
