<?php

// ==========================================
// Secure watermarked image download.
// ==========================================

// Folder users are allowed to download from
$allowedFolder = realpath(__DIR__ . '/assets/uploads/gallery');

if (!isset($_GET['file'])) {
    die("No file specified.");
}

$file = urldecode($_GET['file']);

$fullPath = realpath(__DIR__ . '/' . ltrim($file, '/'));

if (!$allowedFolder || !$fullPath || !is_file($fullPath) ||
    strpos($fullPath, $allowedFolder . DIRECTORY_SEPARATOR) !== 0) {
    http_response_code(403);
    die("Access denied.");
}

if (!file_exists($fullPath)) {
    die("Image not found.");
}

$extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

switch ($extension) {

    case "jpg":
    case "jpeg":
        $image = imagecreatefromjpeg($fullPath);
        break;

    case "png":
        $image = imagecreatefrompng($fullPath);
        imagesavealpha($image, true);
        break;

    case "webp":
        $image = imagecreatefromwebp($fullPath);
        break;

    default:
        die("Unsupported image type.");
}

if (!$image) {
    die("Unable to load image.");
}

$width  = imagesx($image);
$height = imagesy($image);

// ============================
// WATERMARK SETTINGS
// ============================

$text1 = "© BuniAfrika CBO";
$text2 = "https://buniafrika.co.ke";

// Built-in font (1-5)
$font = 5;

// White with transparency
$color = imagecolorallocatealpha($image, 255, 255, 255, 90);

// Shadow
$shadow = imagecolorallocatealpha($image, 0, 0, 0, 105);

// Font sizing by image width
if ($width > 3500) {

    $font = 5;

} elseif ($width > 2000) {

    $font = 5;

} elseif ($width > 1000) {

    $font = 4;

} else {

    $font = 3;
}

$textWidth1 = imagefontwidth($font) * strlen($text1);
$textWidth2 = imagefontwidth($font) * strlen($text2);

$x1 = ($width - $textWidth1) / 2;
$x2 = ($width - $textWidth2) / 2;

$y1 = $height - 45;
$y2 = $height - 25;

// Shadow
imagestring($image,$font,$x1+1,$y1+1,$text1,$shadow);
imagestring($image,$font,$x2+1,$y2+1,$text2,$shadow);

// Main text
imagestring($image,$font,$x1,$y1,$text1,$color);
imagestring($image,$font,$x2,$y2,$text2,$color);

// ============================
// DOWNLOAD
// ============================

$filename = pathinfo($fullPath, PATHINFO_FILENAME);

header("Content-Type: image/jpeg");
header("Content-Disposition: attachment; filename=\"{$filename}-BuniAfrika.jpg\"");

imagejpeg($image, null, 95);


exit;