<?php

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed.']);
    exit;
}

require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

sendCorsHeaders();
$siteSettings = getSiteSettings($conn);

enforceRateLimit($conn, 'donation', 10, 600, 300);

$csrf_token = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';
if (!verifyCsrfToken($csrf_token)) {
    logActivity($conn, 'CSRF Token Missing', 'A donation form submission failed due to missing or invalid CSRF token from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''), 'system', null, 'System');
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'An error has occurred in form submission. Refresh page and try again.']);
    exit;
}

if (!empty($_POST['company_website'] ?? '')) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'The submission could not be accepted.']);
    exit;
}

$name                  = isset($_POST['name']) ? trim($_POST['name']) : '';
$email                 = isset($_POST['email']) ? trim($_POST['email']) : '';
$country_code          = isset($_POST['country_code']) ? trim($_POST['country_code']) : '';
$phone_number          = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$amount                = isset($_POST['amount']) ? trim($_POST['amount']) : '';
$currency              = isset($_POST['currency']) ? trim($_POST['currency']) : 'KES';
$payment_method        = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
$transaction_reference = isset($_POST['transaction_reference']) ? trim($_POST['transaction_reference']) : '';
$message               = isset($_POST['message']) ? trim($_POST['message']) : '';

$phone = '';
if (!empty($country_code) && !empty($phone_number)) {
    $clean_phone = preg_replace('/[^0-9]/', '', $phone_number);
    $phone = $country_code . ' ' . $clean_phone;
}

if (empty($name) || empty($email) || empty($amount) || empty($payment_method)) {
    echo json_encode(['status' => 'error', 'message' => 'Name, Email, Amount, and Payment Method are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

if (!is_numeric($amount) || floatval($amount) <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid positive donation amount.']);
    exit;
}

$amount_val = floatval($amount);

$sql = "INSERT INTO donations (name, email, phone, amount, currency, payment_method, transaction_reference, message, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssdssss", $name, $email, $phone, $amount_val, $currency, $payment_method, $transaction_reference, $message);

    if ($stmt->execute()) {
        require_once __DIR__ . '/includes/mailer.php';
        $mailSubject = "New Donation Submission from: " . $name;
        $mailBody = "<h3>New Donation Received (Pending Verification)</h3>" .
                    "<p><strong>Name:</strong> " . h($name) . "</p>" .
                    "<p><strong>Email:</strong> " . h($email) . "</p>" .
                    "<p><strong>Phone:</strong> " . h($phone) . "</p>" .
                    "<p><strong>Amount:</strong> " . h($currency) . " " . number_format($amount_val, 2) . "</p>" .
                    "<p><strong>Payment Method:</strong> " . h($payment_method) . "</p>" .
                    "<p><strong>Transaction Reference:</strong> " . h($transaction_reference) . "</p>" .
                    "<p><strong>Message / Notes:</strong><br>" . nl2br(h($message)) . "</p>";
        sendAdminEmail($mailSubject, $mailBody, $siteSettings['finance_email']);

        logActivity($conn, 'Donation Submission', 'Donation submission received for verification.', 'user', null, 'Website visitor');

        echo json_encode([
            'status' => 'success',
            'message' => 'Thank you, ' . h($name) . '! Your donation submission has been received for verification.'
        ]);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save donation record. Please try again later.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error. Failed to prepare donation insert.']);
}

$conn->close();
?>
