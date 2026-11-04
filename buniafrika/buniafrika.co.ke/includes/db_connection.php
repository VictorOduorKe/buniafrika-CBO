<?php

require_once __DIR__ . '/bootstrap.php';

if (isset($conn) && is_object($conn)) {
    return;
}

try {
    $db_host = $_ENV['DB_HOST'] ?? '';
    $db_user = $_ENV['DB_USER'] ?? '';
    $db_pass = $_ENV['DB_PASS'] ?? '';
    $db_name = $_ENV['DB_NAME'] ?? '';
    $db_port = (int)($_ENV['DB_PORT'] ?? 3306);
    $db_socket = !empty($_ENV['DB_SOCKET']) ? $_ENV['DB_SOCKET'] : (file_exists('/tmp/mysql.sock') ? '/tmp/mysql.sock' : null);

    if ($db_host === '' || $db_user === '' || $db_name === '') {
        throw new RuntimeException('Database configuration is incomplete.');
    }

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port, $db_socket);
    $conn->set_charset('utf8mb4');
    $username = $db_user;
    $password = $db_pass;
} catch (Throwable $e) {
    error_log('Database connection failed: ' . $e->getMessage());
    http_response_code(500);
    exit('The website is temporarily unavailable. Please try again later.');
}
