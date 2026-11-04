# BuniAfrika CBO website: technology overview

## Application architecture

- Plain PHP, server-rendered pages; no framework or CMS migration
- MySQL-compatible database through `mysqli`
- Existing Apache/XAMPP local environment and `.htaccess` deployment rules
- Vanilla HTML, CSS, and JavaScript

## Shared application components

- `includes/` contains public bootstrap, database, authentication helpers,
  reusable functions, mailer, and shared page layout.
- `admin/` contains the existing authenticated content-management interface.
- `assets/uploads/` stores uploaded media; uploads are configured to prevent
  PHP-family script execution.
- `database/migrations/` contains additive schema changes.

## External services and configuration

The public language selector uses browser storage and the external translation
service proxied by `translate.php`; translation can be incomplete when that
service is unavailable or rate-limited. Email delivery requires valid
organisation-approved SMTP settings in `.env`; the availability and delivery
of email have not been verified. Payment configuration has not been changed;
the displayed donation guidance asks users to confirm current details with
finance.

## Unified cPanel deployment architecture

The unified cPanel deployment directory layout is:

```text
buniafrika-files/
│
├── secrets/
│   └── .env
│
└── buniafrika/
    │
    ├── buniafrika/
    │   └── [Complete Frontend Application]
    │
    └── buniafrika.co.ke/
        └── [Complete Backend Application]
```

- **Secrets Isolation**: All sensitive production environment variables and credentials are stored strictly in `buniafrika-files/secrets/.env` (protected by `.htaccess` denying all HTTP access). No `.env` exists inside the frontend or backend roots.
- **Frontend Application** (`buniafrika/buniafrika/`): Document root for the main website (`buniafrika.co.ke`). Serves public pages, assets, stylesheets, scripts, and administrative content management.
- **Backend Application** (`buniafrika/buniafrika.co.ke/`): Independent backend core containing database connectivity, schema migrations, mailer services, logging, and public API endpoints (`public/`).
- **cPanel Document Roots**:
  - Frontend domain (`buniafrika.co.ke`): `buniafrika-files/buniafrika/buniafrika`
  - Backend subdomain (`api.buniafrika.co.ke`): `buniafrika-files/buniafrika/buniafrika.co.ke/public`

Never commit `.env`, database exports, logs, or credentials. Production
deployment must use the hosting provider's approved secret configuration and
be tested on staging before switching live traffic.
