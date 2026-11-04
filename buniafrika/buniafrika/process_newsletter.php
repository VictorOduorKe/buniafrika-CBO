<?php

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed.']);
    exit;
}

require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/mailer.php';

sendCorsHeaders();

enforceRateLimit($conn, 'newsletter', 5, 900, 600);

$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!verifyCsrfToken($csrf_token)) {
    logActivity($conn, 'CSRF Token Missing', 'A form submission failed due to missing or invalid CSRF token from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''), 'system', null, 'System');
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Your form session has expired. Refresh the page and try again.']);
    exit;
}

if (!empty($_POST['company_website'] ?? '')) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'The submission could not be accepted.']);
    exit;
}

$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($email)) {
    echo json_encode(['status' => 'error', 'message' => 'Email address is required.']);
    exit;
}

if (strlen($email) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

try {
    $stmt = $conn->prepare("INSERT IGNORE INTO mailing_list (email) VALUES (?)");
    if (!$stmt) {
        throw new RuntimeException('Could not prepare subscription insert.');
    }
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->close();
} catch (Throwable $e) {
    error_log('Newsletter subscription could not be stored: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'We could not save your subscription. Please try again later.']);
    exit;
}

$mailSubject = "New Mailing List Subscription";
$mailBody = "<h3>New Newsletter Subscription</h3>" .
            "<p>A new user has subscribed to the mailing list.</p>" .
            "<p><strong>Email:</strong> " . h($email) . "</p>";

sendAdminEmail($mailSubject, $mailBody);

logActivity($conn, 'Newsletter Subscription', 'Subscription recorded from the public signup form.', 'user', null, 'Website visitor');

echo json_encode([
    'status' => 'success',
    'message' => 'Thank you for subscribing to our updates!'
]);
?>
