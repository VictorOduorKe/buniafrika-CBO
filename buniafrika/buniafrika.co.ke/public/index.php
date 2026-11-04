<?php
/**
 * BuniAfrika Backend API - Main Entry Point
 */

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/functions.php';

sendCorsHeaders();

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$endpoint = basename($uri);

// Route /api/* requests to api.php router
if (strpos($uri, '/api/') !== false || $endpoint === 'api.php' || $endpoint === 'api') {
    require __DIR__ . '/api.php';
    exit;
}

// Route to specific handler if requested via index or router
switch ($endpoint) {
    case 'download-publication.php':
    case 'download-publication':
        require __DIR__ . '/download-publication.php';
        exit;
    case 'process_contact.php':
    case 'contact':
        require __DIR__ . '/process_contact.php';
        exit;
    case 'process_registration.php':
    case 'register':
        require __DIR__ . '/process_registration.php';
        exit;
    case 'process_donation.php':
    case 'donate':
        require __DIR__ . '/process_donation.php';
        exit;
    case 'process_newsletter.php':
    case 'newsletter':
        require __DIR__ . '/process_newsletter.php';
        exit;
    case 'translate.php':
    case 'translate':
        require __DIR__ . '/translate.php';
        exit;
    case 'health':
    case 'health.php':
    default:
        echo json_encode([
            'status' => 'online',
            'service' => 'BuniAfrika Backend API',
            'version' => '1.0.0',
            'environment' => 'production',
            'timestamp' => date('c'),
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        exit;
}

