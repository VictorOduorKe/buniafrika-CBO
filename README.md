# Buniafrika Unified cPanel Deployment Directory

This repository is structured for deployment to shared cPanel hosting using a unified directory layout under `buniafrika-files`.

## Directory Architecture

```text
buniafrika-files/
│
├── secrets/
│   ├── .env                       # Production environment configuration (outside public web roots)
│   └── .htaccess                  # Require all denied (blocks HTTP access)
│
└── buniafrika/
    │
    ├── buniafrika/                # [Complete Frontend Application]
    │   ├── index.php              # Public website pages
    │   ├── admin/                 # Administration dashboard
    │   ├── assets/                # Media, logos, uploads
    │   ├── css/                   # Stylesheets
    │   ├── js/                    # Client scripts (API requests use production backend URL)
    │   ├── includes/              # Shared layout and bridge loaders
    │   ├── process_*.php          # Form processing handlers
    │   └── .htaccess              # MIME types, HTTPS rules, security
    │
    └── buniafrika.co.ke/          # [Complete Backend Application]
        ├── public/                # Backend API public document root
        │   ├── index.php          # API status & router
        │   ├── process_*.php      # Backend API endpoints (CORS enabled)
        │   ├── translate.php      # Translation proxy endpoint
        │   └── .htaccess          # HTTPS & security rules
        ├── includes/              # Private backend core (db, bootstrap, mailer, functions)
        ├── PHPMailer/             # Email delivery library
        ├── database/              # Schema migrations and database notes
        ├── logs/                  # Application error logs
        └── .htaccess              # Denies access to internal private folders
```

## cPanel Document Root Configuration

Configure your domains in cPanel (Domains / Subdomains / Addon Domains):

| Component | Domain / Subdomain | Document Root |
| :--- | :--- | :--- |
| **Frontend** | `buniafrika.co.ke` (or `www.buniafrika.co.ke`) | `buniafrika-files/buniafrika/buniafrika` |
| **Backend API** | `api.buniafrika.co.ke` | `buniafrika-files/buniafrika/buniafrika.co.ke/public` |

> **Security Note**: Never point the backend domain to the entire `buniafrika.co.ke` root directory. Pointing to `buniafrika.co.ke/public` ensures internal directories like `database/` (SQL exports), `logs/`, and `includes/` are completely isolated from HTTP requests.

## Environment Configuration

All sensitive configuration is loaded from:

```text
/home/ACCOUNT/buniafrika-files/secrets/.env
```

Key variables in `secrets/.env`:
- Database: `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_PORT`
- Mail: `SMTP_HOST`, `SMTP_PORT`, `SMTP_USERNAME`, `SMTP_PASSWORD`, `SMTP_FROM`
- Application: `BACKEND_API_URL` (e.g. `https://buniafrika.co.ke` or `https://api.buniafrika.co.ke`), `FRONTEND_URL`

## GitHub Actions Deployment (SavanaHost SSH)

The automated deployment workflow is located in `.github/workflows/deploy.yml`:
1. Validates PHP syntax across all files.
2. Validates JavaScript syntax.
3. Verifies secrets isolation (ensuring no `.env` exists in web roots).
4. Synchronizes both frontend and backend to the unified destination under `SSH_DEPLOY_PATH`:
   - Frontend: `$SSH_DEPLOY_PATH/buniafrika/`
   - Backend: `$SSH_DEPLOY_PATH/buniafrika.co.ke/`
5. Preserves server `secrets/` and user-uploaded media (`assets/uploads/`) untouched without destructive deletion.

Required GitHub Repository Secrets:
- `SSH_HOST`: SavanaHost hostname or server IP
- `SSH_USERNAME`: cPanel account username
- `SSH_PRIVATE_KEY`: OpenSSH private key for authentication
- `SSH_PORT`: SSH port (default: 22)
- `SSH_DEPLOY_PATH`: Unified destination path (e.g. `/home/CPANEL_USERNAME/buniafrika/`)

For complete step-by-step setup, see [SavanaHost Deployment Guide](docs/DEPLOYMENT_GUIDE.md).

---

## Running the Project Locally (Before Deployment)

Follow these steps to run, test, and verify the application locally before deploying to cPanel.

### 1. Prerequisites

- **PHP 8.1 or 8.2** with required extensions:
  - `mysqli`, `curl`, `json`, `mbstring`, `gd`, `fileinfo`
  - Verify with: `php -m | grep -E "mysqli|curl|json|mbstring|gd|fileinfo"`
- **MySQL / MariaDB Server** (version 5.7+ / 8.0+ / MariaDB 10.3+)
- **Node.js** (optional, for JS validation)

---

### 2. Database Setup

1. **Start MySQL Server**:
   ```bash
   sudo systemctl start mysql    # Linux systemd
   # Or start MySQL via XAMPP / MAMP Control Panel
   ```

2. **Create the Database**:
   ```bash
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS cloudkazico_buniafrika CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
   ```

3. **Import Initial Schema and Seed Data**:
   Import the provided database dump from the project root:
   ```bash
   mysql -u root -p cloudkazico_buniafrika < cloudkazico_buniafrika.sql
   ```

4. **Verify Imported Tables**:
   ```bash
   mysql -u root -p cloudkazico_buniafrika -e "SHOW TABLES;"
   ```
   You should see all 12 core tables: `activity_logs`, `admins`, `categories`, `contact_submissions`, `donations`, `gallery_items`, `login_attempts`, `mailing_list`, `news_articles`, `projects`, `registrations`, `site_settings`.

---

### 3. Configure Local Environment (`secrets/.env`)

Edit `/home/ACCOUNT/buniafrika-files/secrets/.env` with your local database credentials:

```env
DB_HOST=127.0.0.1
DB_USER=root
DB_PASS=your_local_mysql_password
DB_NAME=cloudkazico_buniafrika
DB_PORT=3306

SMTP_HOST=mail.buniafrika.co.ke
SMTP_PORT=465
SMTP_USERNAME=info@buniafrika.co.ke
SMTP_PASSWORD=your_smtp_password
SMTP_FROM=info@buniafrika.co.ke

# Local Development URLs
BACKEND_API_URL=http://localhost:8000
FRONTEND_URL=http://localhost:8000
```

> **Note**: In local development, keeping `BACKEND_API_URL=http://localhost:8000` routes API requests directly through the local server.

---

### 4. Starting the Local Web Server

Choose one of the following methods:

#### Method A: Built-in PHP Server (Fastest & Recommended)

From the project root (`buniafrika-files/`), run:

```bash
php -S localhost:8000 -t buniafrika/buniafrika
```

Once running, access the application in your browser:
- **Public Website**: [http://localhost:8000](http://localhost:8000)
- **About Us**: [http://localhost:8000/about.php](http://localhost:8000/about.php)
- **Projects**: [http://localhost:8000/projects.php](http://localhost:8000/projects.php)
- **Research & News**: [http://localhost:8000/news.php](http://localhost:8000/news.php)
- **Contact Us**: [http://localhost:8000/contact.php](http://localhost:8000/contact.php)
- **Admin Login**: [http://localhost:8000/login.php](http://localhost:8000/login.php)
- **Admin Dashboard**: [http://localhost:8000/admin/admin_dashboard.php](http://localhost:8000/admin/admin_dashboard.php)

#### Method B: Dual-Server Setup (Simulating Production Subdomain Architecture)

To test frontend and backend API communication across separate ports/subdomains:

1. **Terminal 1: Start Frontend Website** (Port 8000):
   ```bash
   php -S localhost:8000 -t buniafrika/buniafrika
   ```

2. **Terminal 2: Start Backend API** (Port 8001):
   ```bash
   php -S localhost:8001 -t buniafrika/buniafrika.co.ke/public
   ```

3. In `secrets/.env`, configure:
   ```env
   BACKEND_API_URL=http://localhost:8001
   FRONTEND_URL=http://localhost:8000
   ```

4. Verify backend health endpoint:
   ```bash
   curl -i http://localhost:8001/
   ```
   Expected response:
   ```json
   {
       "status": "online",
       "service": "BuniAfrika Backend API",
       "version": "1.0.0",
       "environment": "production"
   }
   ```

#### Method C: Apache / XAMPP / LAMP VirtualHost Setup

If using Apache, add VirtualHost blocks to your Apache configuration (`httpd-vhosts.conf` or `/etc/apache2/sites-available/`):

```apache
# Frontend Website
<VirtualHost *:80>
    ServerName buniafrika.test
    DocumentRoot "/path/to/buniafrika-files/buniafrika/buniafrika"
    <Directory "/path/to/buniafrika-files/buniafrika/buniafrika">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

# Backend API
<VirtualHost *:80>
    ServerName api.buniafrika.test
    DocumentRoot "/path/to/buniafrika-files/buniafrika/buniafrika.co.ke/public"
    <Directory "/path/to/buniafrika-files/buniafrika/buniafrika.co.ke/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `/etc/hosts` (or `C:\Windows\System32\drivers\etc\hosts`):
```text
127.0.0.1 buniafrika.test api.buniafrika.test
```

---

### 5. Pre-Deployment Verification

Before committing and deploying, run the automated verification suite to ensure all architectural rules, secrets isolation, and file permissions are sound:

```bash
php scripts/verify.php
```

Run PHP syntax linting across all files:

```bash
find buniafrika/ -name "*.php" -exec php -l {} +
```

Verify that no sensitive production credentials or `.env` files are tracked:

```bash
git status
find buniafrika/ -name ".env*"
```


