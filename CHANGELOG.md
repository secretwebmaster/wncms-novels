# Change Log

## v1.0.2 2025-12-05

## v1.0.1 2025-12-04

-   Improved novels and chapters index pages to match WNCMS v6 backend layout (toolbar wrappers, unified message include, `table-xs` styling, and consistent pagination/summary blocks).
-   Added optional detailed columns for novels and chapters when `show_detail` is enabled, including slug, label, description, remark, order, series status, word count, boolean flags, price, publish/expire dates, source, ref_id, chapter_count, and author.
-   Updated novels and chapters index views to reuse shared partials for status and boolean display (`wncms::common.table_status`, `wncms::common.table_is_active`) for consistent backend UI.
-   Enhanced novel edit form to load all users from the configured `user` model; when users are available, show a `user_id` select; otherwise, show a disabled text field.
-   Increased Tagify limits for novel categories and tags (`maxTags` from 10 to 999).
-   Fixed frontend view paths from `frontend.theme.{theme}` to `frontend.themes.{theme}`.
-   Upgraded `Novel` model to implement `HasMedia` and `ApiModelInterface`; added `$packageId`, `$modelKey`, and `$tagMetas`.
-   Upgraded `NovelChapter` model with media library and API support.
-   Added `Novel::updateWordCount()` to recalculate total `word_count` based on all chapters.
-   Cleaned up series status constants and labels, using correct translation namespace.
-   Refactored `WncmsNovelsServiceProvider` to WNCMS v6 registration structure.
-   Rewrote Novel API `store` action to use core API helpers.
-   Extended Novel API `store` endpoint to support auto-create/update of novel + chapter.
-   Added automatic `word_count` recalculation and cache flushing for novels and chapters.
-   Updated frontend chapter route structure to `/novel/{novelSlug}chapter/{chapterSlug}`.
-   Added `NovelChapterController` for frontend chapter rendering.
-   Added API route registry arrays `$apiRoutes` to Novel and NovelChapter models.
-   Improved backend Novel index page to display author's username and user ID.
-   Added eager loading of `user` relation to backend NovelController for performance.
-   Updated ChapterManager default ordering key from `chapter_number` to `number`.
-   Added model relation macros (user → novels, novel → user, novel → chapters, chapter → novel), including `latestChapter()` and `chapter_count` accessor.

## v1.0.0 2025-11-01

-   Initial release
