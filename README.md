# Leaderboard App – Developer Documentation

## Overview

This repository contains a full-stack application structured as a monorepo with two main folders:
- `frontend/`: Vue 3 + TypeScript application using Vite
- `backend/`: Laravel 12 API-only server

---

## Folder Structure

```
/leaderboard-app
├── frontend/   # Vite + Vue 3 + Pinia + Element Plus + UnoCSS
├── backend/    # Laravel 12 API-only
├── package.json (workspace root)
```

The frontend and backend run independently, allowing full CORS separation and clean architecture.

---

## Running the Project Locally

### Prerequisites
- Node.js 20+
- PHP 8.2+
- MySQL 8+
- Composer

### 1. Frontend
```bash
cd frontend
npm install
npm run dev
```

### 2. Backend
```bash
cd ..
npm run backend
```

This command is a shortcut for:
```bash
php artisan serve --host=localhost --port=5000
```

This is **required** due to CORS and cookie domain matching for CSRF protection.

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

> ⚠️ You must configure your local MySQL credentials under `.env`.

### `frontend/public/env.js`
Used to make API URL globally available at runtime (non-bundled):
```js
class DM_Environment {
  constructor() {
    this.API_URL = 'http://localhost:5000'
  }
}
window.DM_ENV = new DM_Environment()
```

---

## Frontend Tooling & Configuration

### Vite Plugins

| Plugin | Reason |
|--------|--------|
| `vite-plugin-pages` | File-based routing for clean route organization |
| `vite-plugin-vue-layouts-next` | Supports dynamic layout injection per route (`blank`, `default`) |
| `unplugin-auto-import` | Automatically imports Vue, Vue Router, and VueUse APIs |
| `unplugin-vue-components` | Auto-imports Element Plus components |
| `@unocss/preset-wind3` | UnoCSS utility-first setup with Tailwind-compatible syntax |
| `vite-plugin-vue-devtools` | Enhanced Vue DevTools integration |
| `@vueuse/head` | Head management for SEO and metadata |
| `vue-i18n` | Internationalization support |

---

## 🧩 Plugins and Global Setup

### `main.ts`
All plugins are initialized in a central location:
```ts
registerPlugins(app)
app.use(ElementPlus, { locale: pt })
```

### `plugins/index.ts`
Registers:
- Router
- Head
- Pinia
- i18n
- Toastification (with options)
- Enables dark mode via VueUse (`useDark()`)

---

## 🎨 Styling

- Global styles from Element Plus theme
- UnoCSS using `@unocss/preset-wind3` (customizable utility classes)
- Custom SCSS file used via `@use "@/styles/element/index.scss"`

---

## 🧠 Architecture Decisions

### Pages & Layouts

- We use `vite-plugin-pages` for automatic routing.
- Layouts are handled via `vite-plugin-vue-layouts-next`, supporting:
  - `Default.vue` → standard with header/sidebar/footer
  - `Blank.vue` → used for onboarding/auth routes

### Auto Imports
Handled via:
- `unplugin-auto-import` for composables, router, vueuse, etc.
- `unplugin-vue-components` for Element Plus components

### API Calls
- Axios instance configured at `src/api/config.ts`
- Handles:
  - Base URL
  - Credentials/cookies
  - CSRF token interception
  - Error handling via Toasts

### CSRF Handling
- Automatically intercepted for unsafe HTTP methods
- Calls `/sanctum/csrf-cookie` once and adds the token to headers via interceptor

---

## ✅ Forms & Validation

- Forms use `element-plus` components
- Validation handled by `el-form` rules
- Form pages use `<el-card>` for styling
- `vue-toastification` handles notifications

---

## 🔐 Authentication

Auth routes:
- `/auth/register`
- `/auth/login`

Auth APIs:
- `POST /register`
- `POST /login`

Sessions and CSRF protection follow Laravel Sanctum pattern using cookies.

---

## 🧪 Testing

Frontend tests (WIP) use:
- `Vitest`
- `@vue/test-utils`

Linting/formatting:
```bash
npm run lint
npm run format
```

Type checks:
```bash
npm run type-check
```

---

## 📌 Final Notes

- The pixel art on the welcome page is just an aesthetic introduction.
- The rest of the app follows clean, modern design with accessibility and responsiveness in mind.
- Layouts, components, and API paths are centralized to ensure scalability and DRY architecture.

---

For any further clarification, please review `vite.config.ts`, `main.ts`, and `src/plugins/index.ts` or contact the developer directly.

