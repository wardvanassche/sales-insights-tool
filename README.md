# Every Day Quickscan

An internal sales tool that turns a company name or website into a ready-to-use sales briefing. It looks up the company in the Dutch business registry through [CompanyInfo](https://www.companyinfo.nl/), then has an LLM write a sales analysis in Dutch from that data.

Built for **Every Day**, a Dutch AI-first digital agency, so sales staff can prepare for prospect conversations in seconds instead of researching by hand.

## How it works

1. **Input:** the user enters a company name and/or website URL on the *Quickscan* page and picks a model (OpenAI or Ollama).
2. **Company lookup:** `CompanyInfoClient` calls the CompanyInfo API:
   - `dutchBusinessSearch` finds the company and selects its main establishment.
   - `dutchBusinessGetDossierV3` gets the dossier: legal identity, contact details, number of employees and annual figures (revenue, profit, assets).
3. **Analysis:** the company profile is sent as JSON to the selected LLM with a sales-consultant prompt. The model returns a structured Markdown analysis in Dutch.
4. **Output:** the analysis is rendered in the Vue frontend.

```
Quickscan form ─▶ QuickscanController ─▶ CompanyInfoClient ─▶ CompanyInfo API
                                     └─▶ OpenAI / Ollama ─▶ Markdown analysis ─▶ UI
```

## Tech stack

| Layer    | Technology                                                    |
| -------- | ------------------------------------------------------------- |
| Backend  | Laravel 12 (PHP 8.5), Laravel Fortify (auth + 2FA)            |
| Frontend | Vue 3 + TypeScript, Inertia.js, Tailwind CSS 4, shadcn-vue    |
| AI       | OpenAI (`gpt-4o-mini`) or a self-hosted model through Ollama  |
| Data     | CompanyInfo API (Dutch Chamber of Commerce data)              |
| Database | SQLite (default)                                              |
| Testing  | Pest                                                          |

## Project structure

```
app/
├── Http/Controllers/
│   ├── QuickscanController.php      # Validates input, runs lookup + analysis
│   └── DashboardController.php
└── Services/
    ├── CompanyInfo/CompanyInfoClient.php   # CompanyInfo API client + XML parsing
    └── Llm/
        ├── OpenAI.php               # OpenAI chat completions + prompt
        └── Ollama.php               # Local/self-hosted LLM alternative
resources/js/pages/
├── Dashboard.vue
└── Quickscan.vue                    # Main tool UI
```

## Getting started

### Requirements
- PHP 8.5+ and Composer
- Node.js 24 (see `.nvmrc`) and npm
- CompanyInfo API credentials
- An OpenAI API key and/or a running Ollama instance

### Installation

```bash
git clone https://github.com/wardvanassche/sales-insights-tool.git
cd sales-insights-tool

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed
```

Public registration is disabled. The seeder creates a login: `test@example.com` / `password`.

### Configuration

Fill in these values in `.env`:

```env
# OpenAI
OPENAI_API_KEY=

# Ollama (optional)
OLLAMA_BASE_URL=
OLLAMA_MODEL=

# CompanyInfo
COMPANYINFO_RPC_ENDPOINT=
COMPANYINFO_RPC_FALLBACK=
COMPANYINFO_USERNAME=
COMPANYINFO_PASSWORD=
```

### Running locally

```bash
composer run dev
```

This starts the Laravel server, queue worker, log viewer (Pail) and Vite dev server together. Open the app, log in and go to **Quickscan**.

## Scripts

| Command             | Description                      |
| ------------------- | -------------------------------- |
| `composer run dev`  | Start the full dev environment   |
| `npm run build`     | Build frontend assets            |
| `npm run lint`      | Lint and fix with ESLint         |
| `npm run format`    | Format with Prettier             |
| `php artisan test`  | Run the Pest test suite          |

## Author

**Ward van Assche** – [github.com/wardvanassche](https://github.com/wardvanassche)
