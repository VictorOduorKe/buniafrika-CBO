<?php
/**
 * BuniAfrika Database Migration Runner
 * Executes additive migrations safely against the configured database.
 */

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/db_connection.php';

echo "=== Running BuniAfrika Database Migrations ===\n";

$migrationsDir = __DIR__ . '/migrations';
$migrationFiles = [
    '20261020_create_missing_content_tables.sql',
    '20261020_create_site_settings.sql',
    '20261021_add_leader_description_settings.sql',
    '20261103_create_dynamic_cms_tables.sql',
    '20261104_create_seo_and_aeo_tables.sql',
];

$executed = 0;
$skipped = 0;

foreach ($migrationFiles as $file) {
    $path = "$migrationsDir/$file";
    if (!file_exists($path)) {
        echo " [WARN] Migration file not found: $file\n";
        continue;
    }

    echo " Applying: $file ... ";
    $sql = file_get_contents($path);

    // Split SQL into individual statements
    $queries = preg_split('/;\s*[\r\n]+/', $sql);
    $errors = 0;

    foreach ($queries as $query) {
        $trimmed = trim($query);
        // Strip leading SQL comments
        $trimmed = trim(preg_replace('/^(\s*--[^\r\n]*[\r\n]+)+/', '', $trimmed));
        $trimmed = trim(preg_replace('/^(\s*\/\*.*?\*\/[\r\n]*)+/s', '', $trimmed));

        if ($trimmed === '') {
            continue;
        }

        try {
            $conn->query($trimmed);
        } catch (Throwable $e) {
            // Ignore duplicate key or existing index errors if table already exists
            if (strpos($e->getMessage(), 'Duplicate key') === false && strpos($e->getMessage(), 'already exists') === false) {
                echo "\n  [ERROR] " . $e->getMessage() . " in query:\n  " . substr($trimmed, 0, 80) . "...\n";
                $errors++;
            }
        }
    }

    if ($errors === 0) {
        echo "[OK]\n";
        $executed++;
    } else {
        echo "[COMPLETED WITH $errors WARNINGS]\n";
    }
}

echo "=== Migration complete: $executed files processed ===\n";

