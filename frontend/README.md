# React + Vite

This template provides a minimal setup to get React working in Vite with HMR and some ESLint rules.

Currently, two official plugins are available:

- [@vitejs/plugin-react](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react) uses [Oxc](https://oxc.rs)
- [@vitejs/plugin-react-swc](https://github.com/vitejs/vite-plugin-react/blob/main/packages/plugin-react-swc) uses [SWC](https://swc.rs/)

## React Compiler

The React Compiler is not enabled on this template because of its impact on dev & build performances. To add it, see [this documentation](https://react.dev/learn/react-compiler/installation).

## Expanding the ESLint configuration

If you are developing a production application, we recommend using TypeScript with type-aware lint rules enabled. Check out the [TS template](https://github.com/vitejs/vite/tree/main/packages/create-vite/template-react-ts) for information on how to integrate TypeScript and [`typescript-eslint`](https://typescript-eslint.io) in your project.

---

## Development with the existing PHP backend

This repo keeps the original PHP project as the backend (root folder) and adds this React app as a new frontend (this `frontend/` folder).

### 1) Start the PHP backend (port 8000)

From the repo root:

```bash
php -S localhost:8000 -t .
```

You can verify it works by opening:

- http://localhost:8000/index.php

### 2) Start the React frontend (port 5173)

From `frontend/`:

```bash
npm install
npm run dev
```

### 3) Calling PHP endpoints from React

The Vite dev server is configured to proxy any request under `/backend/*` to `http://localhost:8000/*`.

Examples:

- Frontend calls: `fetch('/backend/get_sentence.php')`
- That becomes: `http://localhost:8000/get_sentence.php`

This avoids CORS and keeps cookies/sessions working in development.

---

## Team workflow (no routing)

This frontend is intentionally kept **without React Router**.

- Game work goes in `src/game/*` (see `src/game/GameSandbox.jsx`)
- Admin work goes in `src/admin/*` (see `src/admin/AdminSandbox.jsx`)
- Shared backend calls go in `src/api/*`

### Admin JSON endpoints (for React)

To migrate the admin panel to React, this repo includes minimal JSON endpoints:

- `POST /admin/api/list_sentences.php` (params: `lang`)
- `POST /admin/api/delete_sentence.php` (params: `lang`, `dificultad`, `frase`)

They require an authenticated admin session. In dev, log in first at:

- http://localhost:8000/admin/login.php
