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

enforceRateLimit($conn, 'registration', 5, 900, 600);

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

$first_name   = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
$last_name    = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
$email        = isset($_POST['email']) ? trim($_POST['email']) : '';
$country_code = isset($_POST['country_code']) ? trim($_POST['country_code']) : '';
$phone_number = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$role         = isset($_POST['role']) ? trim($_POST['role']) : '';
$message      = isset($_POST['message']) ? trim($_POST['message']) : '';

$phone = '';
if (!empty($country_code) && !empty($phone_number)) {
    $clean_phone = preg_replace('/[^0-9]/', '', $phone_number);
    $phone = $country_code . ' ' . $clean_phone;
}

if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($role) || empty($message) ||
    strlen($first_name) > 100 || strlen($last_name) > 100 || strlen($email) > 255 || strlen($phone) > 32 || strlen($message) > 5000) {
    echo json_encode(['status' => 'error', 'message' => 'First Name, Last Name, Email, Phone, Role, and Message are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

if (!preg_match("/^[a-zA-Z\s'-]+$/", $first_name) || !preg_match("/^[a-zA-Z\s'-]+$/", $last_name)) {
    echo json_encode(['status' => 'error', 'message' => 'Names can only contain letters, spaces, hyphens, and apostrophes.']);
    exit;
}

if (!empty($phone) && !preg_match("/^[+0-9\s-]+$/", $phone)) {
    echo json_encode(['status' => 'error', 'message' => 'Phone number can only contain digits, spaces, hyphens, and + (after formatting).']);
    exit;
}

$words = preg_split('/\s+/', trim($message));
$word_count = count(array_filter($words));
if ($word_count > 200) {
    echo json_encode(['status' => 'error', 'message' => 'Your message exceeds the limit of 200 words. Please shorten your message.']);
    exit;
}

$allowed_roles = ['volunteer', 'partner'];
if (!in_array(strtolower($role), $allowed_roles)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid role specified.']);
    exit;
}

$ip_address = $_SERVER['REMOTE_ADDR'] ?? '';

$sql = "INSERT INTO registrations (first_name, last_name, email, phone, role, message, ip_address) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssssss", $first_name, $last_name, $email, $phone, $role, $message, $ip_address);

    if ($stmt->execute()) {
        require_once __DIR__ . '/includes/mailer.php';
        $mailSubject = "New User Registration: " . h($first_name) . " " . h($last_name);
        $mailBody = "<h3>New " . ucfirst(h($role)) . " Registration</h3>" .
                    "<p><strong>Name:</strong> " . h($first_name) . " " . h($last_name) . "</p>" .
                    "<p><strong>Email:</strong> " . h($email) . "</p>" .
                    "<p><strong>Phone:</strong> " . h($phone) . "</p>" .
                    "<p><strong>Role:</strong> " . h($role) . "</p>" .
                    "<p><strong>Message:</strong><br>" . nl2br(h($message)) . "</p>";
        $recipient = strtolower($role) === 'partner' ? $siteSettings['partnership_email'] : $siteSettings['general_email'];
        sendAdminEmail($mailSubject, $mailBody, $recipient);

        logActivity($conn, 'User Registration', 'Registration submitted for role: ' . ucfirst($role), 'user', null, 'Website visitor');

        $display_role = ucfirst($role);
        echo json_encode([
            'status' => 'success',
            'message' => 'Congratulations, ' . h($first_name) . '! Your application as a ' . $display_role . ' has been registered successfully.'
        ]);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to save registration. Please try again later.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Database error. Failed to prepare registration insert.']);
}

$conn->close();
?>
