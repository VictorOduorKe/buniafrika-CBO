<?php
header('Content-Type: application/json; charset=utf-8');

function contactResponse($status, $message, $httpStatus = 200) {
    http_response_code($httpStatus);
    echo json_encode(['status' => $status, 'message' => $message], JSON_UNESCAPED_UNICODE);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    contactResponse('error', 'Method not allowed.', 405);
}

require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

sendCorsHeaders();

enforceRateLimit($conn, 'contact', 5, 600, 300);

if (!verifyCsrfToken($_POST['csrf_token'] ?? '')) {
    contactResponse('error', 'Your form session has expired. Refresh the page and try again.', 403);
}

if (!empty($_POST['company_website'] ?? '')) {
    contactResponse('error', 'The submission could not be accepted.', 400);
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$inquiryType = trim($_POST['inquiry_type'] ?? 'general');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');
$siteSettings = getSiteSettings($conn);
$recipients = [
    'general' => $siteSettings['general_email'],
    'partnership' => $siteSettings['partnership_email'],
    'finance' => $siteSettings['finance_email'],
];

if ($name === '' || strlen($name) > 255 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255 ||
    $subject === '' || strlen($subject) > 255 || $message === '' || strlen($message) > 5000 ||
    strlen($phone) > 30 || !isset($recipients[$inquiryType])) {
    contactResponse('error', 'Check the required fields and their length, then try again.', 422);
}

if ($phone !== '' && !preg_match('/^[+0-9()\s.-]{5,30}$/', $phone)) {
    contactResponse('error', 'Enter a valid phone number or leave the phone field empty.', 422);
}

try {
    $stmt = $conn->prepare('INSERT INTO contact_submissions (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);
    $stmt->execute();
    $stmt->close();
} catch (Throwable $e) {
    error_log('Contact submission could not be stored: ' . $e->getMessage());
    contactResponse('error', 'We could not save your enquiry. Please try again later.', 500);
}

require_once __DIR__ . '/includes/mailer.php';
$mailSubject = $siteSettings['site_name'] . ' enquiry: ' . $subject;
$mailBody = '<h3>New ' . h($siteSettings['site_name']) . ' enquiry</h3>' .
    '<p><strong>Type:</strong> ' . h($inquiryType) . '</p>' .
    '<p><strong>Name:</strong> ' . h($name) . '</p>' .
    '<p><strong>Email:</strong> ' . h($email) . '</p>' .
    '<p><strong>Phone:</strong> ' . h($phone) . '</p>' .
    '<p><strong>Subject:</strong> ' . h($subject) . '</p>' .
    '<p><strong>Message:</strong><br>' . nl2br(h($message)) . '</p>';
$emailSent = sendEmailViaSMTP($recipients[$inquiryType], $mailSubject, $mailBody);

logActivity($conn, 'Contact Submission', 'Website enquiry submitted (' . $inquiryType . ')', 'user', null, 'Website visitor');
$conn->close();

if (!$emailSent) {
    contactResponse('warning', 'Your enquiry was saved, but the email notification could not be sent. You can reach the team directly at ' . $recipients[$inquiryType] . '.');
}

contactResponse('success', 'Thank you. Your enquiry was received and sent to the appropriate BuniAfrika team.');
