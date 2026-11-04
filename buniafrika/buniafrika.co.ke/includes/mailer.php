<?php
require_once __DIR__ . '/bootstrap.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$phpMailerDir = (defined('BUNIAFRIKA_PRIVATE_DIR') && is_dir(BUNIAFRIKA_PRIVATE_DIR . '/PHPMailer'))
    ? BUNIAFRIKA_PRIVATE_DIR . '/PHPMailer'
    : (is_dir(__DIR__ . '/../PHPMailer') ? __DIR__ . '/../PHPMailer' : dirname(__DIR__, 3) . '/buniafrika.co.ke/PHPMailer');

require_once $phpMailerDir . '/Exception.php';
require_once $phpMailerDir . '/PHPMailer.php';
require_once $phpMailerDir . '/SMTP.php';

function sendAdminEmail($subject, $bodyHTML, $recipient = null) {
    if ($recipient === null) {
        $settings = $GLOBALS['site_settings'] ?? null;
        if (!is_array($settings) || empty($settings['general_email'])) {
            global $conn;
            require_once __DIR__ . '/functions.php';
            $settings = getSiteSettings($conn);
        }
        $recipient = $settings['general_email'];
    }
    return sendEmailViaSMTP($recipient, $subject, $bodyHTML);
}

function sendGeneralEmail($toEmail, $subject, $bodyHTML) {
    return sendEmailViaSMTP($toEmail, $subject, $bodyHTML);
}

function sendEmailViaSMTP($toEmail, $subject, $bodyHTML) {
    $smtpUsername = $_ENV['SMTP_USERNAME'] ?? '';
    $smtpPassword = $_ENV['SMTP_PASSWORD'] ?? '';
    $smtpHost = $_ENV['SMTP_HOST'] ?? '';
    $fromName = preg_replace('/[\r\n]+/', ' ', (string)($GLOBALS['site_settings']['site_name'] ?? 'BuniAfrika CBO'));

    if (!filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        error_log('Email notification not sent: invalid recipient address.');
        return false;
    }

    if ($smtpUsername === '' || $smtpPassword === '' || $smtpHost === '') {
        error_log('Email notification not sent: SMTP is not configured.');
        return false;
    }

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $smtpHost;
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpUsername;
        $mail->Password   = $smtpPassword;

        $port = (int)($_ENV['SMTP_PORT'] ?? 465);
        $mail->Port       = $port;

        if ($port === 465) {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } else {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $fromEmail = $_ENV['SMTP_FROM'] ?? $smtpUsername;
        $mail->setFrom($fromEmail, $fromName);
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHTML;

        return $mail->send();
    } catch (Exception $e) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: {$fromName} <{$fromEmail}>\r\n";
        $headers .= "Reply-To: {$fromEmail}\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        $sent = mail($toEmail, $subject, $bodyHTML, $headers);
        if (!$sent) {
            error_log('Native mail fallback failed for BuniAfrika notification.');
        }
        return $sent;
    }
}
