<?php

function resizeImage($sourcePath, $destinationPath, $maxWidth = 1200, $maxHeight = 1200, $quality = 85, $convertToWebP = false) {
    if (function_exists('imagecreatetruecolor')) {
        return resizeImageGD($sourcePath, $destinationPath, $maxWidth, $maxHeight, $quality, $convertToWebP);
    }

    if (class_exists('Imagick')) {
        return resizeImageImagick($sourcePath, $destinationPath, $maxWidth, $maxHeight, $quality, $convertToWebP);
    }

    $hasConvert = false;
    if (function_exists('exec')) {
        $test = [];
        exec('which convert 2>&1', $test, $code);
        if ($code === 0) {
            $hasConvert = true;
        }
    }
    if ($hasConvert) {
        return resizeImageCLI($sourcePath, $destinationPath, $maxWidth, $maxHeight, $quality, $convertToWebP);
    }

    if ($convertToWebP) {
        $dir = dirname($destinationPath);
        $filename = pathinfo($destinationPath, PATHINFO_FILENAME);
        $ext = pathinfo($sourcePath, PATHINFO_EXTENSION);
        $destinationPath = $dir . '/' . $filename . '.' . $ext;
    }
    if (copy($sourcePath, $destinationPath)) {
        return $destinationPath;
    }

    return false;
}


function resizeImageGD($sourcePath, $destinationPath, $maxWidth, $maxHeight, $quality, $convertToWebP) {
    if (!file_exists($sourcePath)) {
        return false;
    }

    $info = getimagesize($sourcePath);
    if ($info === false) {
        return false;
    }

    list($origWidth, $origHeight, $imageType) = $info;

    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            return false;
    }

    if (!$sourceImage) {
        return false;
    }

    $newWidth = $origWidth;
    $newHeight = $origHeight;

    if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        $newWidth = (int)round($origWidth * $ratio);
        $newHeight = (int)round($origHeight * $ratio);
    }

    $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
    if (!$destinationImage) {
        return false;
    }

    if ($imageType === IMAGETYPE_PNG || $imageType === IMAGETYPE_WEBP || $convertToWebP) {
        imagealphablending($destinationImage, false);
        imagesavealpha($destinationImage, true);
        $transparentColor = imagecolorallocatealpha($destinationImage, 0, 0, 0, 127);
        imagefill($destinationImage, 0, 0, $transparentColor);
    }

    if (!imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight)) {
        return false;
    }

    $success = false;
    if ($convertToWebP) {
        $dir = dirname($destinationPath);
        $filename = pathinfo($destinationPath, PATHINFO_FILENAME);
        $destinationPath = $dir . '/' . $filename . '.webp';
        $success = imagewebp($destinationImage, $destinationPath, $quality);
    } else {
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                $success = imagejpeg($destinationImage, $destinationPath, $quality);
                break;
            case IMAGETYPE_PNG:
                $pngQuality = (int)round((100 - $quality) / 10);
                $pngQuality = max(0, min(9, $pngQuality));
                $success = imagepng($destinationImage, $destinationPath, $pngQuality);
                break;
            case IMAGETYPE_WEBP:
                $success = imagewebp($destinationImage, $destinationPath, $quality);
                break;
        }
    }

    return $success ? $destinationPath : false;
}


function resizeImageImagick($sourcePath, $destinationPath, $maxWidth, $maxHeight, $quality, $convertToWebP) {
    try {
        $imagickClass = 'Imagick';
        $imagick = new $imagickClass($sourcePath);

        $imagick->stripImage();

        $origWidth = $imagick->getImageWidth();
        $origHeight = $imagick->getImageHeight();

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $filter = defined('Imagick::FILTER_LANCZOS') ? constant('Imagick::FILTER_LANCZOS') : 22;
            $imagick->resizeImage($maxWidth, $maxHeight, $filter, 1, true);
        }

        if ($convertToWebP) {
            $dir = dirname($destinationPath);
            $filename = pathinfo($destinationPath, PATHINFO_FILENAME);
            $destinationPath = $dir . '/' . $filename . '.webp';
            $imagick->setImageFormat('webp');
        }

        $imagick->setImageCompressionQuality($quality);

        $success = $imagick->writeImage($destinationPath);
        $imagick->clear();
        $imagick->destroy();

        return $success ? $destinationPath : false;
    } catch (Exception $e) {
        return false;
    }
}


function resizeImageCLI($sourcePath, $destinationPath, $maxWidth, $maxHeight, $quality, $convertToWebP) {
    if ($convertToWebP) {
        $dir = dirname($destinationPath);
        $filename = pathinfo($destinationPath, PATHINFO_FILENAME);
        $destinationPath = $dir . '/' . $filename . '.webp';
    }

    $src = escapeshellarg($sourcePath);
    $dest = escapeshellarg($destinationPath);
    $maxW = (int)$maxWidth;
    $maxH = (int)$maxHeight;
    $q = (int)$quality;

    $cmd = "convert $src -resize {$maxW}x{$maxH}\> -strip -quality $q $dest 2>&1";
    exec($cmd, $output, $returnCode);

    if ($returnCode === 0 && file_exists($destinationPath)) {
        return $destinationPath;
    }
    return false;
}


function secureImageUploadAndResize($file, $targetBaseDir, $folder, $config = []) {
    $result = ['status' => 'empty', 'original_path' => '', 'resized_path' => '', 'message' => ''];

    $maxWidth = isset($config['max_width']) ? (int)$config['max_width'] : 1200;
    $maxHeight = isset($config['max_height']) ? (int)$config['max_height'] : 1200;
    $quality = isset($config['quality']) ? (int)$config['quality'] : 85;
    $convertToWebP = isset($config['convert_webp']) ? (bool)$config['convert_webp'] : true; // WebP conversion enabled by default
    $maxFileSize = isset($config['max_file_size']) ? (int)$config['max_file_size'] : 10 * 1024 * 1024; // 10MB

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        if (isset($file) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE   => 'The uploaded image exceeds the server\'s upload limit (upload_max_filesize in php.ini). Please upload a smaller file or increase the server limit.',
                UPLOAD_ERR_FORM_SIZE  => 'The uploaded image exceeds the MAX_FILE_SIZE directive specified in the form.',
                UPLOAD_ERR_PARTIAL    => 'The image was only partially uploaded. Please try again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server configuration issue: Missing temporary upload folder.',
                UPLOAD_ERR_CANT_WRITE => 'Server storage issue: Failed to write image to disk.',
                UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the image upload.'
            ];
            $errorMessage = isset($uploadErrors[$file['error']]) ? $uploadErrors[$file['error']] : 'Unknown upload error (code: ' . $file['error'] . ').';
            $result = ['status' => 'error', 'message' => $errorMessage];
        }
        return $result;
    }

    if ($file['size'] > $maxFileSize) {
        $maxMb = $maxFileSize / (1024 * 1024);
        $uploadedMb = round($file['size'] / (1024 * 1024), 2);
        return ['status' => 'error', 'message' => "Uploaded file size ({$uploadedMb}MB) exceeds the maximum permitted limit of {$maxMb}MB."];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);

    $allowedMimeTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];

    if (!array_key_exists($mimeType, $allowedMimeTypes)) {
        return ['status' => 'error', 'message' => 'Invalid file type. Only JPG, PNG, and WebP images are allowed.'];
    }

    $detectedExt = $allowedMimeTypes[$mimeType];

    $uploadDir = rtrim($targetBaseDir, '/') . '/' . trim($folder, '/') . '/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ['status' => 'error', 'message' => 'Failed to create target upload directory. Check permissions.'];
        }
    }

    $uniqueId = bin2hex(random_bytes(16));
    $originalName = 'original_' . $uniqueId . '.' . $detectedExt;
    
    $resizedName = 'resized_' . $uniqueId . '.' . ($convertToWebP ? 'webp' : $detectedExt);

    $originalPath = $uploadDir . $originalName;
    $resizedPath = $uploadDir . $resizedName;

    if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
        return ['status' => 'error', 'message' => 'Failed to save uploaded original file. Check directory permissions.'];
    }

    $resizeResult = resizeImage($originalPath, $resizedPath, $maxWidth, $maxHeight, $quality, $convertToWebP);
    if ($resizeResult === false) {
        if (file_exists($originalPath)) {
            unlink($originalPath);
        }
        return ['status' => 'error', 'message' => 'Failed to process and resize image.'];
    }

    $relativeOriginal = 'assets/uploads/' . $folder . '/' . $originalName;
    $relativeResized = 'assets/uploads/' . $folder . '/' . basename($resizeResult);

    return [
        'status' => 'success',
        'original_path' => $relativeOriginal,
        'resized_path' => $relativeResized,
        'message' => 'Image uploaded and processed successfully.'
    ];
}


function secureVideoUpload($file, $targetBaseDir, $folder, $config = []) {
    $result = ['status' => 'empty', 'path' => '', 'message' => ''];

    // Default max size: 40MB (40 * 1024 * 1024)
    $maxFileSize = isset($config['max_file_size']) ? (int)$config['max_file_size'] : 40 * 1024 * 1024; 

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        if (isset($file) && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE   => 'The uploaded video exceeds the server\'s upload limit.',
                UPLOAD_ERR_FORM_SIZE  => 'The uploaded video exceeds the MAX_FILE_SIZE directive.',
                UPLOAD_ERR_PARTIAL    => 'The video was only partially uploaded. Please try again.',
                UPLOAD_ERR_NO_TMP_DIR => 'Server configuration issue: Missing temporary upload folder.',
                UPLOAD_ERR_CANT_WRITE => 'Server storage issue: Failed to write video to disk.',
                UPLOAD_ERR_EXTENSION  => 'A PHP extension blocked the video upload.'
            ];
            $errorMessage = isset($uploadErrors[$file['error']]) ? $uploadErrors[$file['error']] : 'Unknown upload error (code: ' . $file['error'] . ').';
            $result = ['status' => 'error', 'message' => $errorMessage];
        }
        return $result;
    }

    if ($file['size'] > $maxFileSize) {
        $maxMb = $maxFileSize / (1024 * 1024);
        $uploadedMb = round($file['size'] / (1024 * 1024), 2);
        return ['status' => 'error', 'message' => "Uploaded video size ({$uploadedMb}MB) exceeds the maximum permitted limit of {$maxMb}MB."];
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $file['tmp_name']);
    unset($finfo);

    $allowedMimeTypes = [
        'video/mp4'        => 'mp4',
        'video/webm'       => 'webm',
        'video/ogg'        => 'ogg',
        'video/quicktime'  => 'mov'
    ];

    if (!array_key_exists($mimeType, $allowedMimeTypes)) {
        return ['status' => 'error', 'message' => 'Invalid file type. Only MP4, WebM, OGG, and MOV videos are allowed.'];
    }

    $detectedExt = $allowedMimeTypes[$mimeType];

    $uploadDir = rtrim($targetBaseDir, '/') . '/' . trim($folder, '/') . '/';
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ['status' => 'error', 'message' => 'Failed to create target video upload directory. Check permissions.'];
        }
    }

    $uniqueId = bin2hex(random_bytes(16));
    $videoName = 'video_' . $uniqueId . '.' . $detectedExt;
    $targetPath = $uploadDir . $videoName;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['status' => 'error', 'message' => 'Failed to save uploaded video file. Check directory permissions.'];
    }

    $relativeVideoPath = 'assets/uploads/' . $folder . '/' . $videoName;

    return [
        'status' => 'success',
        'path' => $relativeVideoPath,
        'message' => 'Video uploaded successfully.'
    ];
}

function uploadFeaturedMedia($filesField, $folder) {
    if (!isset($filesField) || empty($filesField['name'][0])) {
        return ['status' => 'empty', 'paths' => []];
    }

    $paths = [];
    $errors = [];
    $totalFiles = count($filesField['name']);

    if ($totalFiles > 5) {
        return ['status' => 'error', 'message' => 'Maximum of 5 featured media files can be uploaded.'];
    }

    for ($i = 0; $i < $totalFiles; $i++) {
        if ($filesField['error'][$i] !== UPLOAD_ERR_OK) {
            if ($filesField['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            $errors[] = "File " . ($i + 1) . " upload error code: " . $filesField['error'][$i];
            continue;
        }

        // Determine type and size limit: 40MB for videos, 12MB for images
        $mime = $filesField['type'][$i];
        if (empty($mime) && !empty($filesField['tmp_name'][$i])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $filesField['tmp_name'][$i]);
            unset($finfo);
        }
        $isVideo = (strpos($mime, 'video/') === 0);
        $maxBytes = $isVideo ? 40 * 1024 * 1024 : 12 * 1024 * 1024;
        $limitLabel = $isVideo ? "40MB" : "12MB";
        if ($filesField['size'][$i] > $maxBytes) {
            $errors[] = "File " . ($i + 1) . " exceeds the {$limitLabel} size limit.";
            continue;
        }

        $singleFile = [
            'name' => $filesField['name'][$i],
            'type' => $filesField['type'][$i],
            'tmp_name' => $filesField['tmp_name'][$i],
            'error' => $filesField['error'][$i],
            'size' => $filesField['size'][$i],
        ];

        $mime = $singleFile['type'];
        if (empty($mime) && !empty($singleFile['tmp_name'])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $singleFile['tmp_name']);
            unset($finfo);
        }

        if (strpos($mime, 'image/') === 0) {
            $upload_result = secureImageUploadAndResize($singleFile, dirname(__DIR__, 2) . '/assets/uploads', $folder, ['max_file_size' => $maxBytes]);
            if ($upload_result['status'] === 'success') {
                $paths[] = $upload_result['resized_path'];
            } else {
                $errors[] = "Image " . ($i + 1) . " error: " . $upload_result['message'];
            }
        } elseif (strpos($mime, 'video/') === 0) {
            $upload_result = secureVideoUpload($singleFile, dirname(__DIR__, 2) . '/assets/uploads', $folder, ['max_file_size' => $maxBytes]);
            if ($upload_result['status'] === 'success') {
                $paths[] = $upload_result['path'];
            } else {
                $errors[] = "Video " . ($i + 1) . " error: " . $upload_result['message'];
            }
        } else {
            $errors[] = "File " . ($i + 1) . " has an unsupported file type ({$mime}). Only images and videos are allowed.";
        }
    }

    if (!empty($errors)) {
        return ['status' => 'error', 'message' => implode('<br>', $errors), 'paths' => $paths];
    }

    return ['status' => 'success', 'paths' => $paths];
}


