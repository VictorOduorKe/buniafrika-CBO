<?php
/**
 * BuniAfrika Frontend API Gateway Bridge
 * Forwards requests to the private backend API router.
 */

require_once __DIR__ . '/includes/bootstrap.php';

$backendApi = rtrim($privateDir ?? '', '/') . '/public/api.php';
if (file_exists($backendApi)) {
    require $backendApi;
} else {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['status' => 'error', 'message' => 'Backend API gateway unavailable.']);
}

