# Change Log

## v1.0.1 2025-12-04

-   Improved novels and chapters index pages to match WNCMS v6 backend layout (toolbar wrappers, unified message include, `table-xs` styling, and consistent pagination/summary blocks).
-   Added optional detailed columns for novels and chapters when `show_detail` is enabled, including slug, label, description, remark, order, series status, word count, boolean flags, price, publish/expire dates, source, ref_id, chapter_count, and author.
-   Updated novels and chapters index views to reuse shared partials for status and boolean display (`wncms::common.table_status`, `wncms::common.table_is_active`) for consistent backend UI.
-   Enhanced novel edit form to load all users from the configured `user` model; when users are available show a `user_id` select, otherwise show a disabled text field with the current user ID.
-   Increased Tagify limits for novel categories and tags (`maxTags` from `10` to `999`) to support large taxonomy sets.
-   Fixed frontend view paths from `frontend.theme.{theme}` to `frontend.themes.{theme}` in Novel and Chapter frontend controllers to align with the updated WNCMS theme directory structure.
-   Upgraded `Novel` model to implement `HasMedia` and `ApiModelInterface`, and to use `InteractsWithMedia` and `HasApi`; added `$packageId`, `$modelKey`, and `$tagMetas` for package/model registration.
-   Upgraded `NovelChapter` model with media library and API support (`HasMedia`, `InteractsWithMedia`, `ApiModelInterface`, `HasApi`) and simplified relation definitions to use local class references.
-   Added `Novel::updateWordCount()` to recalculate and persist total `word_count` based on all chapter contents using multibyte-safe length calculation.
-   Cleaned up series status constants and labels, and fixed the `series_status_label` accessor to use the correct translation namespace `wncms-novels::word.*`.
-   Refactored `WncmsNovelsServiceProvider` to the new WNCMS v6 package registration structure with top-level `base`, `controllers`, `managers`, and `models` keys, removing the legacy `paths[...]` configuration.
-   Rewrote the Novel API `store` action to use the core API helpers (`checkEnabled`, `checkAuthSetting`) and unified API-token authentication flow used by WNCMS posts.
-   Extended the Novel API `store` endpoint to support creating or updating a novel and its first chapter in a single request, with automatic slug generation, sensible defaults, tag/category syncing, and chapter de-duplication by title/content.
-   Added automatic `word_count` recalculation on API writes and flushes of the `novels` and `novel_chapters` cache tags after successful Novel API operations.

## v1.0.0 2025-11-01

-   Initial release
