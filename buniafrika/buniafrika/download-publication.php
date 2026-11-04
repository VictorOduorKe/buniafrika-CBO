<?php
/**
 * BuniAfrika Public Publication Download Handler
 * Securely delivers published PDF documents and increments download statistics.
 */

require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    http_response_code(400);
    die('Invalid publication ID.');
}

$publication = getPublicationById($conn, $id);
if (!$publication) {
    http_response_code(404);
    die('Publication not found.');
}

// Check if publication is published (unless logged-in admin)
$isAdmin = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
if (empty($publication['published']) && !$isAdmin) {
    http_response_code(404);
    die('Publication not available.');
}

$rawPath = trim($publication['file_path'] ?? ($publication['file_url'] ?? ''));
if (empty($rawPath)) {
    http_response_code(404);
    die('No document file attached to this publication.');
}

// Resolve uploads base directory
$frontendDir = __DIR__;
$targetBaseDir = realpath($frontendDir . '/assets/uploads/publications');
if (!$targetBaseDir) {
    $targetBaseDir = $frontendDir . '/assets/uploads/publications';
}

// Check relative or direct path
$fileName = basename($rawPath);
$fullPath = $targetBaseDir . '/' . $fileName;

$realPath = realpath($fullPath);
if (!$realPath || !file_exists($realPath) || !is_file($realPath)) {
    // Also try resolving relative to frontendDir
    $altPath = realpath($frontendDir . '/' . ltrim($rawPath, '/'));
    if ($altPath && file_exists($altPath) && is_file($altPath)) {
        $realPath = $altPath;
    }
}

// Security: Prevent directory traversal - ensure realPath starts within allowed assets/uploads
$allowedPrefix = realpath($frontendDir . '/assets/uploads');
if (!$realPath || !$allowedPrefix || strpos($realPath, $allowedPrefix) !== 0) {
    http_response_code(403);
    die('Access denied: Invalid file location.');
}

// Verify extension and MIME
$ext = strtolower(pathinfo($realPath, PATHINFO_EXTENSION));
if ($ext !== 'pdf') {
    http_response_code(403);
    die('Access denied: File is not a PDF.');
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mimeType = $finfo->file($realPath);

if ($mimeType !== 'application/pdf') {
    http_response_code(403);
    die('Access denied: Invalid document MIME type.');
}

// Increment download count
incrementPublicationDownload($conn, $id);

// Prepare download filename
$cleanTitle = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $publication['title']);
$downloadName = trim($cleanTitle, '_') . '.pdf';
if (empty(trim($cleanTitle, '_'))) {
    $downloadName = 'Buniafrika_Publication_' . $id . '.pdf';
}

$viewInline = isset($_GET['view']) && $_GET['view'] === '1';
$disposition = $viewInline ? 'inline' : 'attachment';

// Clear any buffered output
if (ob_get_level()) {
    ob_end_clean();
}

header('Content-Type: application/pdf');
header('Content-Disposition: ' . $disposition . '; filename="' . $downloadName . '"');
header('Content-Length: ' . filesize($realPath));
header('Content-Transfer-Encoding: binary');
header('Cache-Control: private, max-age=0, must-revalidate');
header('Pragma: public');
header('Expires: 0');

readfile($realPath);
exit;

