# Wncms Novels

A modular **Wncms** package providing full CRUD support for novels and chapters — including backend management, frontend display, and API endpoints.

---

## Installation

Require the package via Composer:

```bash
composer require secretwebmaster/wncms-novels
````

The service provider will be auto-discovered.
If not, you may manually register it in your `config/app.php`:

```php
'providers' => [
    Secretwebmaster\WncmsNovels\Providers\WncmsNovelsServiceProvider::class,
],
```

---

## Publish and Migrate

Publish migrations, seeds, and resources:

```bash
php artisan vendor:publish --provider="Secretwebmaster\WncmsNovels\Providers\WncmsNovelsServiceProvider"
```

Run migrations:

```bash
php artisan migrate
```

Seed demo data (optional):

```bash
php artisan db:seed --class="Secretwebmaster\\WncmsNovels\\Database\\Seeders\\NovelSeeder"
```

---

## Demo Data Generator

Generate random demo novels and chapters using Faker:

```bash
php artisan wncms-novels:generate {count=5} {--min=3} {--max=5}
```

**Example:**

```bash
php artisan wncms-novels:generate 10 --min=5 --max=8
```

This will create 10 novels, each with 5–8 chapters.

---

## Routes Overview

| File                  | Purpose              |
| --------------------- | -------------------- |
| `routes/api.php`      | API endpoints (v1)   |
| `routes/backend.php`  | Admin backend routes |
| `routes/frontend.php` | Frontend routes      |
| `routes/web.php`      | Shared web routes    |

---

## API Endpoints

| Endpoint              | Method    | Description                     |
| --------------------- | --------- | ------------------------------- |
| `/api/v1/novels`      | GET       | List all novels                 |
| `/api/v1/novels/{id}` | GET       | Show novel details and chapters |
| `/api/v1/novels`      | POST      | Create a new novel              |
| `/api/v1/novels/{id}` | PUT/PATCH | Update an existing novel        |
| `/api/v1/novels/{id}` | DELETE    | Delete a novel                  |

> 🔐 POST/PUT/DELETE endpoints require `api_token` authentication.

---

## Structure

```
wncms-novels/
├── database/
│   ├── migrations/               # Tables for novels & chapters
│   └── seeders/                  # Demo data seeder
├── resources/
│   ├── lang/                     # Multi-language translations
│   └── views/                    # Blade templates for backend/frontend
├── routes/                       # API, backend, frontend, web routes
└── src/
    ├── Console/                  # Artisan commands
    ├── Http/Controllers/         # MVC controllers
    ├── Models/                   # Novel & Chapter models
    ├── Providers/                # Service provider
    └── Services/Managers/        # Business logic managers
```

---

## Development

Generate factories or seed demo data locally:

```bash
php artisan wncms-novels:generate 3
```

Run tests or manual checks after migration:

```bash
php artisan migrate:fresh --seed
```

---

## Versioning

Current version: **v1.0.0**

Follow [Semantic Versioning](https://semver.org/).

---

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).