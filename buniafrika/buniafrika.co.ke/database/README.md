# Database layout and safety

The plain-PHP application connects to the existing local MySQL/MariaDB database
`buniafrika` through the project-root `.env`. The root `includes/db_connection.php`
is the shared connection entry point; the admin include delegates to it.

## Existing data and schema

Before this work, `buniafrika` contained:

- `admins`: 1 row
- `activity_logs`: 214 rows
- `donations`: 0 rows
- `login_attempts`: 0 rows
- `mailing_list`: 2 rows

The application also expected seven content tables that were absent. The
additive migration `migrations/20261020_create_missing_content_tables.sql`
created only those missing tables (`categories`, `contact_submissions`,
`gallery_items`, `news_articles`, `projects`, `registrations`, and
`upcoming_events`). It did not import dump contents or update/delete existing
records. The original five table row counts were checked after migration and
were unchanged.

The additive migration `migrations/20261020_create_site_settings.sql` creates
the `site_settings` table and inserts the BuniAfrika public defaults with
`INSERT IGNORE`. It was applied after a fresh source/database backup. The new
table contains editable site identity, contact, location, social, programme,
and leadership settings; no existing records were changed. Leadership profile
IDs and each profile's fields are stored as key/value rows in this table, so
adding or removing profiles does not require a schema migration. Uploaded
portraits are stored under `assets/uploads/leadership/`; the profile setting
stores the resulting relative asset path.

The older `migrations/legacy_migrate_categories.sql` and SQL files in
`legacy-archives/` are retained without importing them. They may contain
historical records and old organisation details. Do not run or import them as
part of setup. The archived exports were moved from the project root and admin
folder without changing their contents; the `database/` directory is blocked
from direct web access by `.htaccess`.

## Backups and rollback

Pre-change copies were saved outside the project tree in the session files
directory:

- Source: `BuniAfrika-pre-change-source.zip`
- Database: `buniafrika-pre-change.sql`

Before any later schema change or deployment, export and verify a fresh backup.
The additive migrations have no destructive down script. Rolling back PHP files
does not require restoring the database, but the site settings features require
their new table. If restoring the pre-change database becomes necessary, first
export the current database and confirm the impact: an older export will erase
database changes made after it was created.

The latest pre-change backup for site settings and layout work is
`BuniAfrika-before-site-settings-20261020-181511.zip` and
`buniafrika-before-site-settings-20261020-181511.sql` in the session files
directory, outside the web root. The SQL export was verified to contain all 12
existing table definitions before the additive settings migration was applied.

Direct web requests to this folder are denied by its `.htaccess`; SQL exports
must never be placed in a publicly accessible directory.
