# Leaderboard App – Developer Documentation

## Overview

This repository contains a full-stack application structured as a monorepo with two main folders:

- `frontend/`: Vue 3 + TypeScript application using Vite
- `backend/`: Laravel 12 API-only server

---

## Preview

[Welcome + Preview](https://github.com/user-attachments/assets/dfa7c796-00fd-467a-98c7-79171d02c04b)

[Macbook-Air-localhost-oalj88rb1pu7c_.webm](https://github.com/user-attachments/assets/19f1ced7-d7b2-48d0-bf71-937a5d41f581)

[Macbook-Air-localhost--fnpb0k5nvi3q1.webm](https://github.com/user-attachments/assets/9d4079b2-50e0-4791-b2ec-6c07f02e2f07)

[Macbook-Air-localhost-edb4sgaa1vgf-r.webm](https://github.com/user-attachments/assets/e18c9409-f2bc-4bf5-bc3c-b488920f9f29)

---

## Folder Structure

```
/leaderboard-app
├── frontend/       # Vite + Vue 3 + Pinia + Element Plus + UnoCSS
├── backend/        # Laravel 12 API-only
├── package.json    # Root npm scripts (scheduler, backend serve)
```

The frontend and backend run independently, allowing full CORS separation and clean architecture.

---

## Running the Project Locally

### Prerequisites

- Node.js 20+
- PHP 8.2+
- MySQL 8+
- Composer

### Terminal Tabs Setup (Recommended)

| Terminal | Command                                                          |
| -------- | ---------------------------------------------------------------- |
| 1        | `npm run backend` → Laravel dev server                           |
| 2        | `php artisan queue:work` (run from `/backend`)                   |
| 3        | `npm run scheduler` → Runs Laravel schedule every minute         |
| 4        | `npm run frontend` → Vite dev server (run from project root `/`) |

> Note: After the first `artisan migrate --seed`, _PlayerFactory_ with 30 new players will run automatically.

---

## NPM Scripts

Defined in the root `package.json`:

```json
{
  "scripts": {
    "backend": "php backend/artisan serve --host=localhost --port=5000",
    "scheduler": "php backend/scheduler.php",
    "frontend": "cd frontend && npm run dev"
  }
}
```

> `scheduler.php` is a PHP-based script that simulates a CRON scheduler in dev.

---

## Backend Setup

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

> Optionally, run `php artisan players:reset-scores` to clear scores or test manually.

---

## Environment Variables

### `backend/.env`

```dotenv
APP_URL=http://localhost:5000
FRONTEND_URL=http://localhost:3000
DB_DATABASE=leaderboard_app_db
DB_USERNAME=root
DB_PASSWORD=your_password
```

### `frontend/public/env.js`

Used to make API URL globally available at runtime:

```js
class DM_Environment {
  constructor() {
    this.API_URL = "http://localhost:5000";
  }
}
window.DM_ENV = new DM_Environment();
```

---

## Frontend Tooling & Configuration

### Vite Plugins

| Plugin                         | Reason                     |
| ------------------------------ | -------------------------- |
| `vite-plugin-pages`            | File-based routing         |
| `vite-plugin-vue-layouts-next` | Dynamic layouts            |
| `unplugin-auto-import`         | Auto-imports Vue APIs      |
| `unplugin-vue-components`      | Auto-imports Element Plus  |
| `@unocss/preset-wind3`         | Tailwind-compatible UnoCSS |
| `vite-plugin-vue-devtools`     | DevTools integration       |
| `@vueuse/head`                 | Metadata control           |
| `vue-i18n`                     | i18n support               |

> Note: `Vue-Devtools` is enabled by default, but can be easily disabled by commenting `line 84` of `vite.config.ts` file.

---

## Forms, Validation & Toasts

- Form logic via `el-form` + validation rules
- Toasts via `vue-toastification`
- ZIP code input uses API (`zippopotam.us/CA`) to autofill city and province based on `first 3 characters`
- Postal code formatting is done with formatter/parser

---

## Backend Functionality

### Players CRUD

- Uses model binding with hash
- Validates address on create, but allows partial update for score

### QR Code Generation

- Address is formatted via a formatter class
- QR code is stored in `/storage/app/public/qrcodes`

### Scheduled Winner Job

- Runs every 5 minutes
- Stores winner only if there's a **unique** highest scorer
- Stored in `winners` table with timestamp and score snapshot

### Score Reset

- `php artisan players:reset-scores` sets all scores to 0

---

## Important Artisan Commands

| Command                            | Description                                         |
| ---------------------------------- | --------------------------------------------------- |
| `php artisan players:reset-scores` | Resets all scores                                   |
| `php artisan declare:winner`       | Declares a new winner if only one top scorer exists |
| `php artisan queue:work`           | Runs queued jobs (e.g., QR code generation)         |

---

## Production Notes

> **Important decisions intentionally skipped due to time scope:**

- No pagination or backend-filtering for players (would be necessary for large datasets)
- No Docker setup, but project supports it easily
- No automatic CI/CD pipelines
- After the QR_CODE Job, please reload Players page to be possible to check the QR Code in View Mode. (when click in the Player name), for this is not a parallel processing.

---

## Testing

```bash
php artisan test   # Laravel feature & unit tests
```

Tests include:

- Player creation
- Player update
- Player Delete
- Score reset
- Winner job edge cases

---

<details>
  <summary><strong>API Reference – Endpoints & Documentation</strong></summary>

## API Endpoints

Base URL: `{{backend_url}}/api`

### Authentication

All endpoints under `/api/players` require `auth:sanctum`.

---

### Endpoints Summary

| Method | Endpoint               | Description                       |
| ------ | ---------------------- | --------------------------------- |
| GET    | `/players`             | List all players                  |
| GET    | `/players/{hash}`      | Get player details                |
| POST   | `/players`             | Create new player                 |
| PUT    | `/players/{hash}`      | Update player info                |
| DELETE | `/players/{hash}`      | Delete (soft-delete) a player     |
| GET    | `/leaderboard-summary` | Summary of users grouped by score |

---

### Payload Example: Create Player

```json
{
  "name": "John Doe",
  "birth_date": "2000-01-01",
  "address": {
    "postal_code": "V6C 1A1",
    "street": "123 Main St",
    "city": "Vancouver",
    "province": "BC"
  }
}
```

</details>

---

## Final Notes

- Project is intentionally clean and scoped to simulate real-world structure
- Vue 3 + Laravel 12 integration follows best practices
- API is RESTful and consistent with naming, status codes, and payloads
- All models use a Trait called `HasHash` - to create a UUID on boot and use that information instead of ID.
- Also I'm using SoftDeletes to maintain DB entries and restore if needed.

Feel free to reach out if any clarification is needed.
