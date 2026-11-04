<?php
/**
 * BuniAfrika Backend API - Translation Proxy Handler
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/functions.php';

sendCorsHeaders();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$body = json_decode(file_get_contents('php://input'), true);
$text = isset($body['text']) && is_string($body['text']) ? trim($body['text']) : '';
$target = isset($body['target']) && is_string($body['target']) ? trim($body['target']) : 'en';
$supportedLanguages = [
    'en', 'fr', 'sw', 'luo', 'sn', 'de', 'es', 'ar', 'zh-CN', 'en-ZA',
    'nl', 'pt', 'sv', 'ur', 'bn', 'ja', 'so', 'am', 'it', 'ru', 'ko',
    'hr', 'id', 'yo', 'pa',
];

if ($text === '') {
    echo json_encode(['translated' => '']);
    exit;
}
if (!in_array($target, $supportedLanguages, true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid target language']);
    exit;
}
if (strlen($text) > 12000) {
    http_response_code(413);
    echo json_encode(['error' => 'Translation text is too long']);
    exit;
}

$url = 'https://api.mymemory.translated.net/get?' . http_build_query([
    'q'        => $text,
    'langpair' => 'en|' . strtolower($target),
]);
$translated = null;

if (function_exists('curl_init')) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 8,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT      => 'BuniAfrika-i18n/1.0',
    ]);
    $response = curl_exec($ch);
    $err      = curl_error($ch);
    curl_close($ch);

    if ($err || $response === false) {
        http_response_code(502);
        echo json_encode(['error' => 'Translation service unreachable']);
        exit;
    }
} else {
    $ctx      = stream_context_create(['http' => ['timeout' => 8]]);
    $response = @file_get_contents($url, false, $ctx);
    if ($response === false) {
        http_response_code(502);
        echo json_encode(['error' => 'Translation service unreachable']);
        exit;
    }
}

$data = json_decode($response, true);

if (
    isset($data['responseStatus']) &&
    (int) $data['responseStatus'] === 200 &&
    isset($data['responseData']['translatedText']) &&
    is_string($data['responseData']['translatedText'])
) {
    $translated = $data['responseData']['translatedText'];
} else {
    http_response_code(502);
    echo json_encode(['error' => 'Translation provider could not translate this text']);
    exit;
}

echo json_encode(['translated' => $translated]);

