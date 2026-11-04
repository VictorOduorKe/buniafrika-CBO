<?php



require_once dirname(__DIR__) . '/includes/auth_helper.php';
require_once dirname(__DIR__) . '/includes/db_connection.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/image_helper.php';
require_once dirname(__DIR__) . '/includes/seo_helper.php';

requireAdminLogin();

define('ACTION_ADD', '&action=add');
define('LOC_PROJECTS', 'Location: admin_dashboard.php?tab=projects');
define('LOC_GALLERY', 'Location: admin_dashboard.php?tab=gallery');
define('LOC_NEWS', 'Location: admin_dashboard.php?tab=news');
define('DATE_FORMAT_YMD_HI', 'Y-m-d H:i');
define('ACTION_EDIT_ID', '&action=edit&id=');

$session_success = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : '';
$session_error = isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : '';
unset($_SESSION['success_msg'], $_SESSION['error_msg']);

$allowed_tabs = [
    'overview', 'contacts', 'registrations', 'projects', 'gallery', 'news', 'events', 'settings', 'logs', 'donations',
    'thematic', 'objectives', 'publications', 'testimonials', 'faqs', 'partners', 'volunteers', 'story', 'values', 'achievements',
    'seo'
];
$current_tab = isset($_GET['tab']) && in_array($_GET['tab'], $allowed_tabs) ? $_GET['tab'] : 'overview';
$action = isset($_GET['action']) ? trim($_GET['action']) : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$mutation_action = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_POST['_mutation_action'])) {
    $mutation_action = trim($_POST['_mutation_action']);
    $action = $mutation_action;
    $id = (int)($_POST['_mutation_id'] ?? 0);
}

if ($current_tab === 'logs' && $action === 'download') {
    logActivity($conn, 'Download Logs', 'Admin downloaded activity logs');
    $result = $conn->query("SELECT id, user_type, user_id, username, activity, details, ip_address, created_at FROM activity_logs ORDER BY created_at DESC");
    if (ob_get_level()) {
        ob_end_clean();
    }
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=activity_logs_' . date('Y-m-d_H-i-s') . '.csv');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Log ID', 'User Type', 'User ID', 'Username', 'Activity', 'Details', 'IP Address', 'Timestamp']);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $row['created_at'] = date('Y-m-d H:i:s T', strtotime($row['created_at']));
            fputcsv($output, $row);
        }
    }
    fclose($output);
    exit;
}


function uploadAdminImage($file, $folder) {
    $nestedAdmin = preg_match('~/admin/admin$~', str_replace('\\', '/', __DIR__));
    $projectRoot = $nestedAdmin ? dirname(dirname(__DIR__)) : dirname(__DIR__);
    $targetBaseDir = $projectRoot . '/assets/uploads';
    $upload_result = secureImageUploadAndResize($file, $targetBaseDir, $folder);
    if ($upload_result['status'] === 'success') {
        return ['status' => 'success', 'path' => $upload_result['resized_path']];
    }
    return $upload_result;
}


function uploadAdminVideo($file, $folder) {
    $targetBaseDir = dirname(__DIR__) . '/assets/uploads';
    return secureVideoUpload($file, $targetBaseDir, $folder);
}
function getImageDisplayPath($url) {
    if (empty($url)) {
        return '';
    }
    if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 || strpos($url, '//') === 0) {
        return $url;
    }
    $prefix = preg_match('~/admin/admin/[^/]+$~', str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '')) ? '../../' : '../';
    return $prefix . $url;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCsrfToken($csrf_token)) {
        logActivity($conn, 'CSRF Token Missing', 'A form submission failed due to missing or invalid CSRF token from IP: ' . ($_SERVER['REMOTE_ADDR'] ?? ''), 'system', null, 'System');
        $_SESSION['error_msg'] = 'An errror has occured in form submission. Refresh page and try again';
        header('Location: admin_dashboard.php?tab=' . $current_tab);
        exit;
    }

    $submit_action = isset($_POST['action']) ? trim($_POST['action']) : '';

    $settingsActions = [
        'save_settings_organization',
        'save_settings_social',
        'save_settings_vision',
        'save_settings_thematic',
        'save_settings_leadership',
        'save_site_settings',
    ];

    if (in_array($submit_action, $settingsActions, true)) {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
            || (isset($_POST['ajax']) && $_POST['ajax'] === '1');

        $anchor = 'section-organization';
        $successMsg = 'Settings saved successfully.';
        $activityTitle = 'Update Website Settings';
        $savedData = [];

        try {
            if ($submit_action === 'save_settings_organization') {
                $anchor = 'section-organization';
                if (!isset($_POST['site_settings']) || !is_array($_POST['site_settings'])) {
                    throw new InvalidArgumentException('No organisation settings were submitted.');
                }
                $savedData = saveOrganizationSettings($conn, $_POST['site_settings']);
                $successMsg = 'Organisation & Contact settings saved successfully.';
                $activityTitle = 'Update Organisation Settings';
            } elseif ($submit_action === 'save_settings_social') {
                $anchor = 'section-social';
                if (!isset($_POST['site_settings']) || !is_array($_POST['site_settings'])) {
                    throw new InvalidArgumentException('No social media settings were submitted.');
                }
                $savedData = saveSocialSettings($conn, $_POST['site_settings']);
                $successMsg = 'Social Media settings saved successfully.';
                $activityTitle = 'Update Social Media Settings';
            } elseif ($submit_action === 'save_settings_vision') {
                $anchor = 'section-vision';
                if (!isset($_POST['site_settings']) || !is_array($_POST['site_settings'])) {
                    throw new InvalidArgumentException('No vision or mission settings were submitted.');
                }
                $savedData = saveVisionSettings($conn, $_POST['site_settings']);
                $successMsg = 'Vision, Mission & Objectives saved successfully.';
                $activityTitle = 'Update Vision & Mission Settings';
            } elseif ($submit_action === 'save_settings_thematic') {
                $anchor = 'section-thematic';
                $thematicInput = $_POST['site_settings']['thematic_areas'] ?? ($_POST['thematic_areas'] ?? ($_POST['site_settings'] ?? null));
                if (empty($thematicInput)) {
                    throw new InvalidArgumentException('No thematic areas were submitted.');
                }
                $savedData = saveThematicSettings($conn, $thematicInput);
                $successMsg = 'Thematic Areas saved successfully.';
                $activityTitle = 'Update Thematic Areas';
            } elseif ($submit_action === 'save_settings_leadership') {
                $anchor = 'section-leadership';
                $leaders = $_POST['leaders'] ?? [];
                if (!is_array($leaders)) {
                    throw new InvalidArgumentException('Leadership profiles were submitted in an invalid format.');
                }

                foreach ($leaders as $leaderId => &$leader) {
                    if (!is_array($leader)) {
                        throw new InvalidArgumentException('A leadership profile was submitted in an invalid format.');
                    }

                    $uploadError = $_FILES['leader_images']['error'][$leaderId] ?? UPLOAD_ERR_NO_FILE;
                    if ($uploadError === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }

                    $upload = [
                        'name'     => $_FILES['leader_images']['name'][$leaderId] ?? '',
                        'type'     => $_FILES['leader_images']['type'][$leaderId] ?? '',
                        'tmp_name' => $_FILES['leader_images']['tmp_name'][$leaderId] ?? '',
                        'error'    => $uploadError,
                        'size'     => $_FILES['leader_images']['size'][$leaderId] ?? 0,
                    ];

                    $uploadResult = uploadAdminImage($upload, 'leadership');
                    if (($uploadResult['status'] ?? '') !== 'success') {
                        throw new InvalidArgumentException(
                            $uploadResult['message'] ?? 'The leader portrait could not be uploaded.'
                        );
                    }
                    $leader['image'] = $uploadResult['path'];
                }
                unset($leader);

                $savedData = saveLeadershipSettings($conn, $leaders);
                $successMsg = 'Public leadership profiles saved successfully.';
                $activityTitle = 'Update Public Leadership Profiles';
            } elseif ($submit_action === 'save_site_settings') {
                if (!isset($_POST['site_settings']) || !is_array($_POST['site_settings'])) {
                    throw new InvalidArgumentException('No website settings were submitted.');
                }

                $leaders = $_POST['leaders'] ?? [];
                if (!is_array($leaders)) {
                    throw new InvalidArgumentException('Leadership profiles were submitted in an invalid format.');
                }

                foreach ($leaders as $leaderId => &$leader) {
                    if (!is_array($leader)) {
                        throw new InvalidArgumentException('A leadership profile was submitted in an invalid format.');
                    }

                    $uploadError = $_FILES['leader_images']['error'][$leaderId] ?? UPLOAD_ERR_NO_FILE;
                    if ($uploadError === UPLOAD_ERR_NO_FILE) {
                        continue;
                    }

                    $upload = [
                        'name'     => $_FILES['leader_images']['name'][$leaderId] ?? '',
                        'type'     => $_FILES['leader_images']['type'][$leaderId] ?? '',
                        'tmp_name' => $_FILES['leader_images']['tmp_name'][$leaderId] ?? '',
                        'error'    => $uploadError,
                        'size'     => $_FILES['leader_images']['size'][$leaderId] ?? 0,
                    ];

                    $uploadResult = uploadAdminImage($upload, 'leadership');
                    if (($uploadResult['status'] ?? '') !== 'success') {
                        throw new InvalidArgumentException(
                            $uploadResult['message'] ?? 'The leader portrait could not be uploaded.'
                        );
                    }
                    $leader['image'] = $uploadResult['path'];
                }
                unset($leader);

                $settingsToSave = $_POST['site_settings'];
                $thematicAreas = [];
                if (!empty($settingsToSave['thematic_areas'])) {
                    $decodedAreas = json_decode($settingsToSave['thematic_areas'], true);
                    if (is_array($decodedAreas)) {
                        $thematicAreas = $decodedAreas;
                    }
                }
                if (!empty($thematicAreas)) {
                    $settingsToSave['civictech_title'] = $thematicAreas[0]['title'] ?? '';
                    $settingsToSave['civictech_intro'] = $thematicAreas[0]['intro'] ?? '';
                    $settingsToSave['civictech_points'] = implode("\n", $thematicAreas[0]['points'] ?? []);
                    $settingsToSave['heritage_title'] = $thematicAreas[1]['title'] ?? '';
                    $settingsToSave['heritage_intro'] = $thematicAreas[1]['intro'] ?? '';
                    $settingsToSave['heritage_points'] = implode("\n", $thematicAreas[1]['points'] ?? []);
                }

                $savedData = saveSiteSettings($conn, $settingsToSave, $leaders);
                $successMsg = 'Website settings saved successfully.';
                $activityTitle = 'Update Website Settings';
            }

            logActivity($conn, $activityTitle, 'Administrator updated ' . $activityTitle);

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'success',
                    'message' => $successMsg,
                    'data' => $savedData,
                ]);
                exit;
            }

            $_SESSION['success_msg'] = $successMsg;
            header('Location: admin_dashboard.php?tab=settings#' . $anchor);
            exit;
        } catch (InvalidArgumentException $e) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => $e->getMessage(),
                ]);
                exit;
            }
            $_SESSION['error_msg'] = $e->getMessage();
            header('Location: admin_dashboard.php?tab=settings#' . $anchor);
            exit;
        } catch (Throwable $e) {
            error_log('Settings update error (' . $submit_action . '): ' . $e->getMessage());
            $errorMsg = 'Settings could not be saved. Please review your input and try again.';
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(500);
                echo json_encode([
                    'status' => 'error',
                    'message' => $errorMsg,
                ]);
                exit;
            }
            $_SESSION['error_msg'] = $errorMsg;
            header('Location: admin_dashboard.php?tab=settings#' . $anchor);
            exit;
        }
    } elseif ($submit_action === 'project_add' || $submit_action === 'project_edit') {
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        if ($category === '__other__' && !empty($_POST['category_custom'])) {
            $category = trim($_POST['category_custom']);
        }
        $category_id = getOrCreateCategoryId($conn, $category);
        $description = trim($_POST['description']);
        $image_url = trim($_POST['image_url']);
        $video_url = trim($_POST['video_url']);

        $upload_result = uploadAdminImage($_FILES['image_file'] ?? null, 'projects');
        if ($upload_result['status'] === 'success') {
            $image_url = $upload_result['path'];
        } elseif ($upload_result['status'] === 'error') {
            $_SESSION['error_msg'] = $upload_result['message'];
            header(LOC_PROJECTS . ($submit_action === 'project_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        $video_upload = uploadAdminVideo($_FILES['video_file'] ?? null, 'projects');
        if ($video_upload['status'] === 'success') {
            $video_url = $video_upload['path'];
        } elseif ($video_upload['status'] === 'error') {
            $_SESSION['error_msg'] = $video_upload['message'];
            header(LOC_PROJECTS . ($submit_action === 'project_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        if (empty($title) || empty($category) || empty($description) || (empty($image_url) && empty($video_url))) {
            $_SESSION['error_msg'] = 'Title, Category, Description, and either an Image or a Video are required.';
            header(LOC_PROJECTS . ($submit_action === 'project_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        $featured_media = null;
        if (isset($_POST['enable_featured_media'])) {
            $existing_media = $_POST['existing_featured_media'] ?? [];
            if (!is_array($existing_media)) {
                $existing_media = [];
            }
            
            $new_files = $_FILES['featured_media_files'] ?? null;
            $new_count = (isset($new_files['name']) && !empty($new_files['name'][0])) ? count($new_files['name']) : 0;
            
            if (count($existing_media) + $new_count > 5) {
                $_SESSION['error_msg'] = 'You can only have up to 5 featured media files in total.';
                header(LOC_PROJECTS . ($submit_action === 'project_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
                exit;
            }
            
            $uploaded_paths = [];
            if ($new_count > 0) {
                $upload_result = uploadFeaturedMedia($new_files, 'featured');
                if ($upload_result['status'] === 'success') {
                    $uploaded_paths = $upload_result['paths'];
                } elseif ($upload_result['status'] === 'error') {
                    $_SESSION['error_msg'] = $upload_result['message'];
                    header(LOC_PROJECTS . ($submit_action === 'project_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
                    exit;
                }
            }
            
            $final_media = array_merge($existing_media, $uploaded_paths);
            if (count($final_media) > 5) {
                $final_media = array_slice($final_media, 0, 5);
            }
            $featured_media = !empty($final_media) ? json_encode(array_values($final_media)) : null;
        }

        if ($submit_action === 'project_add') {
           $stmt = $conn->prepare("
    INSERT INTO projects
    (title, category, category_id, description, image_url, video_url, featured_media)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssissss",
    $title,
    $category,
    $category_id,
    $description,
    $image_url,
    $video_url,
    $featured_media
);
            if ($stmt->execute()) {
                $_SESSION['success_msg'] = 'Project added successfully.';
                logActivity($conn, 'Create Project', "Created project: " . $title);
            } else {
                $_SESSION['error_msg'] = 'Failed to add project: ' . $conn->error;
            }
            $stmt->close();
        } else {
           $stmt = $conn->prepare("
    UPDATE projects
    SET
        title = ?,
        category = ?,
        category_id = ?,
        description = ?,
        image_url = ?,
        video_url = ?,
        featured_media = ?
    WHERE id = ?
");

$stmt->bind_param(
    "ssissssi",
    $title,
    $category,
    $category_id,
    $description,
    $image_url,
    $video_url,
    $featured_media,
    $id
);
            if ($stmt->execute()) {
                $_SESSION['success_msg'] = 'Project updated successfully.';
                logActivity($conn, 'Update Project', "Updated project (ID: " . $id . "): " . $title);
            } else {
                $_SESSION['error_msg'] = 'Failed to update project: ' . $conn->error;
            }
            $stmt->close();
        }
        header(LOC_PROJECTS);
        exit;
    }

    if ($submit_action === 'gallery_add' || $submit_action === 'gallery_edit') {
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        if ($category === '__other__' && !empty($_POST['category_custom'])) {
            $category = trim($_POST['category_custom']);
        }
        $category_id = getOrCreateCategoryId($conn, $category);
        $caption = trim($_POST['caption']);
        $image_url = trim($_POST['image_url'] ?? '');
        $video_url = trim($_POST['video_url'] ?? '');
        $media_type = isset($_POST['media_type']) ? trim($_POST['media_type']) : 'image';

        if ($media_type === 'image') {
            $upload_result = uploadAdminImage($_FILES['image_file'] ?? null, 'gallery');
            if ($upload_result['status'] === 'success') {
                $image_url = $upload_result['path'];
            } elseif ($upload_result['status'] === 'error') {
                $_SESSION['error_msg'] = $upload_result['message'];
                header(LOC_GALLERY . ($submit_action === 'gallery_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
                exit;
            }
            $video_url = '';    
        } else {
            $video_upload = uploadAdminVideo($_FILES['video_file'] ?? null, 'gallery');
            if ($video_upload['status'] === 'success') {
                $video_url = $video_upload['path'];
            } elseif ($video_upload['status'] === 'error') {
                $_SESSION['error_msg'] = $video_upload['message'];
                header(LOC_GALLERY . ($submit_action === 'gallery_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
                exit;
            }
            $image_url = '';
        }

        if (empty($title) || empty($category) || empty($caption) || ($media_type === 'image' && empty($image_url)) || ($media_type === 'video' && empty($video_url))) {
            $_SESSION['error_msg'] = 'Title, Category, Caption, and the selected Media File/URL are required.';
            header(LOC_GALLERY . ($submit_action === 'gallery_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        if ($submit_action === 'gallery_add') {
            $stmt = $conn->prepare("INSERT INTO gallery_items (title, category_id, image_url, video_url, caption) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sisss", $title, $category_id, $image_url, $video_url, $caption);
            if ($stmt->execute()) {
                $_SESSION['success_msg'] = 'Gallery item added successfully.';
                logActivity($conn, 'Create Gallery Item', "Created gallery item: " . $title);
            } else {
                $_SESSION['error_msg'] = 'Failed to add gallery item: ' . $conn->error;
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("UPDATE gallery_items SET title = ?, category_id = ?, image_url = ?, video_url = ?, caption = ? WHERE id = ?");
            $stmt->bind_param("sisssi", $title, $category_id, $image_url, $video_url, $caption, $id);
            if ($stmt->execute()) {
                $_SESSION['success_msg'] = 'Gallery item updated successfully.';
                logActivity($conn, 'Update Gallery Item', "Updated gallery item (ID: " . $id . "): " . $title);
            } else {
                $_SESSION['error_msg'] = 'Failed to update gallery item: ' . $conn->error;
            }
            $stmt->close();
        }
        header(LOC_GALLERY);
        exit;
    }

    if ($submit_action === 'news_add' || $submit_action === 'news_edit') {
        $title = trim($_POST['title']);
        $category = trim($_POST['category']);
        if ($category === '__other__' && !empty($_POST['category_custom'])) {
            $category = trim($_POST['category_custom']);
        }
        $category_id = getOrCreateCategoryId($conn, $category);
        $summary = trim($_POST['summary']);
        $content = trim($_POST['content']);
        $published_date = trim($_POST['published_date']);
        $author = trim($_POST['author'] ?: 'BuniAfrika CBO');
        $image_url = trim($_POST['image_url']);
        $video_url = trim($_POST['video_url']);

        $upload_result = uploadAdminImage($_FILES['image_file'] ?? null, 'news');
        if ($upload_result['status'] === 'success') {
            $image_url = $upload_result['path'];
        } elseif ($upload_result['status'] === 'error') {
            $_SESSION['error_msg'] = $upload_result['message'];
            header(LOC_NEWS . ($submit_action === 'news_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        $video_upload = uploadAdminVideo($_FILES['video_file'] ?? null, 'news');
        if ($video_upload['status'] === 'success') {
            $video_url = $video_upload['path'];
        } elseif ($video_upload['status'] === 'error') {
            $_SESSION['error_msg'] = $video_upload['message'];
            header(LOC_NEWS . ($submit_action === 'news_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        if (empty($title) || empty($category) || empty($summary) || empty($content) || empty($published_date) || (empty($image_url) && empty($video_url))) {
            $_SESSION['error_msg'] = 'Title, Category, Summary, Content, Date, and either an Image or a Video are required.';
            header(LOC_NEWS . ($submit_action === 'news_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        $featured_media = null;
        if (isset($_POST['enable_featured_media'])) {
            $existing_media = $_POST['existing_featured_media'] ?? [];
            if (!is_array($existing_media)) {
                $existing_media = [];
            }
            
            $new_files = $_FILES['featured_media_files'] ?? null;
            $new_count = (isset($new_files['name']) && !empty($new_files['name'][0])) ? count($new_files['name']) : 0;
            
            if (count($existing_media) + $new_count > 5) {
                $_SESSION['error_msg'] = 'You can only have up to 5 featured media files in total.';
                header(LOC_NEWS . ($submit_action === 'news_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
                exit;
            }
            
            $uploaded_paths = [];
            if ($new_count > 0) {
                $upload_result = uploadFeaturedMedia($new_files, 'featured');
                if ($upload_result['status'] === 'success') {
                    $uploaded_paths = $upload_result['paths'];
                } elseif ($upload_result['status'] === 'error') {
                    $_SESSION['error_msg'] = $upload_result['message'];
                    header(LOC_NEWS . ($submit_action === 'news_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
                    exit;
                }
            }
            
            $final_media = array_merge($existing_media, $uploaded_paths);
            if (count($final_media) > 5) {
                $final_media = array_slice($final_media, 0, 5);
            }
            $featured_media = !empty($final_media) ? json_encode(array_values($final_media)) : null;
        }

        if ($submit_action === 'news_add') {
            $stmt = $conn->prepare("INSERT INTO news_articles (title, category_id, category, summary, content, image_url, video_url, author, published_date, featured_media) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sissssssss", $title, $category_id, $category, $summary, $content, $image_url, $video_url, $author, $published_date, $featured_media);
            if ($stmt->execute()) {
                $_SESSION['success_msg'] = 'News article published successfully and notifications sent.';
                logActivity($conn, 'Create News Article', "Published news article: " . $title);

                require_once dirname(__DIR__) . '/includes/mailer.php';
                $subject = "New Article: $title";
                $body = "<h3>$title</h3><p><strong>Category:</strong> $category</p><p>$summary</p>";
                sendAdminEmail($subject, $body);

                $res = $conn->query("SELECT email FROM registrations WHERE email IS NOT NULL AND email != ''");
                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        sendGeneralEmail($row['email'], $subject, $body);
                    }
                }
                $res2 = $conn->query("SELECT email FROM mailing_list WHERE email IS NOT NULL AND email != ''");
                if ($res2 && $res2->num_rows > 0) {
                    while ($row2 = $res2->fetch_assoc()) {
                        sendGeneralEmail($row2['email'], $subject, $body);
                    }
                }
            } else {
                $_SESSION['error_msg'] = 'Failed to publish news article: ' . $conn->error;
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("UPDATE news_articles SET title = ?, category_id = ?, category = ?, summary = ?, content = ?, image_url = ?, video_url = ?, author = ?, published_date = ?, featured_media = ? WHERE id = ?");
            $stmt->bind_param("sissssssssi", $title, $category_id, $category, $summary, $content, $image_url, $video_url, $author, $published_date, $featured_media, $id);
            if ($stmt->execute()) {
                 $_SESSION['success_msg'] = 'News article updated successfully and notifications sent.';
                 logActivity($conn, 'Update News Article', "Updated news article (ID: " . $id . "): " . $title);

                 require_once dirname(__DIR__) . '/includes/mailer.php';
                 $subject = "Updated Article: $title";
                 $body = "<h3>$title</h3><p><strong>Category:</strong> $category</p><p>$summary</p>";
                 sendAdminEmail($subject, $body);
 
                 $res = $conn->query("SELECT email FROM registrations WHERE email IS NOT NULL AND email != ''");
                 if ($res && $res->num_rows > 0) {
                     while ($row = $res->fetch_assoc()) {
                         sendGeneralEmail($row['email'], $subject, $body);
                     }
                 }
                 $res2 = $conn->query("SELECT email FROM mailing_list WHERE email IS NOT NULL AND email != ''");
                 if ($res2 && $res2->num_rows > 0) {
                     while ($row2 = $res2->fetch_assoc()) {
                         sendGeneralEmail($row2['email'], $subject, $body);
                     }
                 }
             } else {
                 $_SESSION['error_msg'] = 'Failed to update news article: ' . $conn->error;
             }
             $stmt->close();
         }
         header(LOC_NEWS);
         exit;
    }

    if ($submit_action === 'event_add' || $submit_action === 'event_edit') {
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $event_date = trim($_POST['event_date']);
        $location = trim($_POST['location']);
        $location_link = trim($_POST['location_link'] ?? '');
        $meeting_link = trim($_POST['meeting_link'] ?? '');

        if (empty($title) || empty($event_date) || empty($location)) {
            $_SESSION['error_msg'] = 'Title, Event Date, and Location are required.';
            header("Location: admin_dashboard.php?tab=events" . ($submit_action === 'event_edit' ? ACTION_EDIT_ID . $id : ACTION_ADD));
            exit;
        }

        require_once dirname(__DIR__) . '/includes/mailer.php';

        if ($submit_action === 'event_add') {
            $stmt = $conn->prepare("INSERT INTO upcoming_events (title, description, event_date, location, location_link, meeting_link) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $title, $description, $event_date, $location, $location_link, $meeting_link);
            if ($stmt->execute()) {
                $_SESSION['success_msg'] = 'Event added successfully and notifications sent.';
                logActivity($conn, 'Create Event', "Created event: " . $title);

                $location_display = $location_link ? "<a href='$location_link'>$location</a>" : $location;
                $subject = "New Upcoming Event: $title";
                $body = "<h3>$title</h3><p><strong>Date:</strong> " . date('F j, Y, g:i a T', strtotime($event_date)) . "</p><p><strong>Location:</strong> $location_display</p>";
                if ($meeting_link) $body .= "<p><strong>Meeting Link:</strong> <a href='$meeting_link'>$meeting_link</a></p>";
                $body .= "<p>$description</p>";

                sendAdminEmail($subject, $body);

                $res = $conn->query("SELECT email FROM registrations WHERE email IS NOT NULL AND email != ''");
                if ($res && $res->num_rows > 0) {
                    while ($row = $res->fetch_assoc()) {
                        sendGeneralEmail($row['email'], $subject, $body);
                    }
                }
                $res2 = $conn->query("SELECT email FROM mailing_list WHERE email IS NOT NULL AND email != ''");
                if ($res2 && $res2->num_rows > 0) {
                    while ($row2 = $res2->fetch_assoc()) {
                        sendGeneralEmail($row2['email'], $subject, $body);
                    }
                }
            } else {
                $_SESSION['error_msg'] = 'Failed to add event: ' . $conn->error;
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("UPDATE upcoming_events SET title = ?, description = ?, event_date = ?, location = ?, location_link = ?, meeting_link = ? WHERE id = ?");
            $stmt->bind_param("ssssssi", $title, $description, $event_date, $location, $location_link, $meeting_link, $id);
            if ($stmt->execute()) {
                 $_SESSION['success_msg'] = 'Event updated successfully and notifications sent.';
                 logActivity($conn, 'Update Event', "Updated event (ID: " . $id . "): " . $title);

                 $location_display = $location_link ? "<a href='$location_link'>$location</a>" : $location;
                 $subject = "Updated Event: $title";
                 $body = "<h3>$title</h3><p><strong>Date:</strong> " . date('F j, Y, g:i a T', strtotime($event_date)) . "</p><p><strong>Location:</strong> $location_display</p>";
                 if ($meeting_link) $body .= "<p><strong>Meeting Link:</strong> <a href='$meeting_link'>$meeting_link</a></p>";
                 $body .= "<p>$description</p>";
 
                 sendAdminEmail($subject, $body);
 
                 $res = $conn->query("SELECT email FROM registrations WHERE email IS NOT NULL AND email != ''");
                 if ($res && $res->num_rows > 0) {
                     while ($row = $res->fetch_assoc()) {
                         sendGeneralEmail($row['email'], $subject, $body);
                     }
                 }
                 $res2 = $conn->query("SELECT email FROM mailing_list WHERE email IS NOT NULL AND email != ''");
                 if ($res2 && $res2->num_rows > 0) {
                     while ($row2 = $res2->fetch_assoc()) {
                         sendGeneralEmail($row2['email'], $subject, $body);
                     }
                 }
             } else {
                 $_SESSION['error_msg'] = 'Failed to update event: ' . $conn->error;
             }
             $stmt->close();
         }
         header("Location: admin_dashboard.php?tab=events");
         exit;

    // --- Thematic Areas ---
    } elseif ($submit_action === 'thematic_add' || $submit_action === 'thematic_edit') {
        $thematic_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $thematic_id,
            'title' => trim($_POST['title'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'intro' => trim($_POST['intro'] ?? ''),
            'points' => trim($_POST['points'] ?? ''),
            'icon' => trim($_POST['icon'] ?? 'account_balance'),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = uploadAdminImage($_FILES['image'], 'thematic');
            if ($up['status'] === 'success') {
                $data['image_url'] = $up['path'];
            }
        }
        try {
            $thematic_id = saveThematicAreaRecord($conn, $data);
            $_SESSION['success_msg'] = 'Thematic area saved successfully.';
            logActivity($conn, 'Save Thematic Area', "Saved thematic area: {$data['title']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save thematic area: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=thematic");
        exit;

    // --- Objectives ---
    } elseif ($submit_action === 'objective_add' || $submit_action === 'objective_edit') {
        $obj_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $obj_id,
            'title' => trim($_POST['title'] ?? ''),
            'category' => trim($_POST['category'] ?? 'General'),
            'description' => trim($_POST['description'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        try {
            $obj_id = saveObjectiveRecord($conn, $data);
            $_SESSION['success_msg'] = 'Objective saved successfully.';
            logActivity($conn, 'Save Objective', "Saved objective: {$data['title']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save objective: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=objectives");
        exit;

    // --- Publications & Reports ---
    } elseif ($submit_action === 'publication_add' || $submit_action === 'publication_edit') {
        $pub_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $pub_id,
            'title' => trim($_POST['title'] ?? ''),
            'category' => trim($_POST['category'] ?? 'Research Report'),
            'summary' => trim($_POST['summary'] ?? ''),
            'author' => trim($_POST['author'] ?? 'BuniAfrika Research Team'),
            'published_date' => trim($_POST['published_date'] ?? date('Y-m-d')),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];

        // Retain existing files if editing
        if ($pub_id > 0) {
            $existing = getPublicationById($conn, $pub_id);
            if ($existing) {
                $data['file_path'] = $existing['file_path'] ?? ($existing['file_url'] ?? '');
                $data['file_url'] = $data['file_path'];
                $data['file_size'] = $existing['file_size'] ?? '';
                $data['file_type'] = $existing['file_type'] ?? 'PDF';
                $data['cover_image'] = $existing['cover_image'] ?? ($existing['cover_image_url'] ?? '');
                $data['cover_image_url'] = $data['cover_image'];
            }
        }

        // Support hidden fallback inputs
        if (empty($data['file_path']) && !empty($_POST['existing_file_path'])) {
            $data['file_path'] = trim($_POST['existing_file_path']);
            $data['file_url'] = $data['file_path'];
        }
        if (empty($data['file_size']) && !empty($_POST['existing_file_size'])) {
            $data['file_size'] = trim($_POST['existing_file_size']);
        }
        if (empty($data['cover_image']) && !empty($_POST['existing_cover_image'])) {
            $data['cover_image'] = trim($_POST['existing_cover_image']);
            $data['cover_image_url'] = $data['cover_image'];
        }

        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] === UPLOAD_ERR_OK) {
            $upPdf = uploadAdminPublicationPdf($_FILES['pdf_file']);
            if ($upPdf['status'] === 'success') {
                $data['file_path'] = $upPdf['file_path'] ?? ($upPdf['file_url'] ?? '');
                $data['file_url'] = $data['file_path'];
                $data['file_size'] = $upPdf['file_size'] ?? '';
                $data['file_type'] = $upPdf['file_type'] ?? 'PDF';
            } else {
                $_SESSION['error_msg'] = 'PDF Upload error: ' . $upPdf['message'];
                header("Location: admin_dashboard.php?tab=publications" . ($pub_id > 0 ? ACTION_EDIT_ID . $pub_id : ACTION_ADD));
                exit;
            }
        } elseif (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $phpUploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'The uploaded document exceeds server upload limit (upload_max_filesize).',
                UPLOAD_ERR_FORM_SIZE => 'The uploaded document exceeds form limit.',
                UPLOAD_ERR_PARTIAL => 'The uploaded document was only partially uploaded.',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write document to disk on server.',
                UPLOAD_ERR_EXTENSION => 'A server extension stopped document upload.',
            ];
            $errCode = $_FILES['pdf_file']['error'];
            $errMsg = $phpUploadErrors[$errCode] ?? "Upload failed with error code $errCode.";
            $_SESSION['error_msg'] = 'PDF Upload error: ' . $errMsg;
            header("Location: admin_dashboard.php?tab=publications" . ($pub_id > 0 ? ACTION_EDIT_ID . $pub_id : ACTION_ADD));
            exit;
        }

        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
            $upCover = uploadAdminImage($_FILES['cover_image'], 'publications');
            if ($upCover['status'] === 'success') {
                $data['cover_image'] = $upCover['path'];
                $data['cover_image_url'] = $upCover['path'];
            }
        }

        try {
            $pub_id = savePublicationRecord($conn, $data);
            $_SESSION['success_msg'] = 'Publication saved successfully.';
            logActivity($conn, 'Save Publication', "Saved publication: {$data['title']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save publication: ' . $e->getMessage();
            if ($pub_id > 0) {
                header("Location: admin_dashboard.php?tab=publications" . ACTION_EDIT_ID . $pub_id);
                exit;
            } else {
                header("Location: admin_dashboard.php?tab=publications" . ACTION_ADD);
                exit;
            }
        }
        header("Location: admin_dashboard.php?tab=publications");
        exit;

    // --- Testimonials ---
    } elseif ($submit_action === 'testimonial_add' || $submit_action === 'testimonial_edit') {
        $test_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $test_id,
            'author_name' => trim($_POST['author_name'] ?? ''),
            'author_role' => trim($_POST['author_role'] ?? ''),
            'author_organization' => trim($_POST['author_organization'] ?? ''),
            'quote' => trim($_POST['quote'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = uploadAdminImage($_FILES['image'], 'testimonials');
            if ($up['status'] === 'success') {
                $data['author_image'] = $up['path'];
            }
        }
        try {
            $test_id = saveTestimonialRecord($conn, $data);
            $_SESSION['success_msg'] = 'Testimonial saved successfully.';
            logActivity($conn, 'Save Testimonial', "Saved testimonial from {$data['author_name']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save testimonial: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=testimonials");
        exit;

    // --- FAQs ---
    } elseif ($submit_action === 'faq_add' || $submit_action === 'faq_edit') {
        $faq_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $faq_id,
            'question' => trim($_POST['question'] ?? ''),
            'answer' => trim($_POST['answer'] ?? ''),
            'detailed_answer' => trim($_POST['detailed_answer'] ?? ''),
            'page_slug' => trim($_POST['page_slug'] ?? ''),
            'related_link_url' => trim($_POST['related_link_url'] ?? ''),
            'related_link_text' => trim($_POST['related_link_text'] ?? ''),
            'category' => trim($_POST['category'] ?? 'General'),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        try {
            $faq_id = saveFaqRecord($conn, $data);
            $_SESSION['success_msg'] = 'FAQ saved successfully.';
            logActivity($conn, 'Save FAQ', "Saved FAQ: {$data['question']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save FAQ: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=faqs");
        exit;

    // --- Partnerships ---
    } elseif ($submit_action === 'partner_add' || $submit_action === 'partner_edit') {
        $part_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $part_id,
            'name' => trim($_POST['name'] ?? ''),
            'partner_type' => trim($_POST['partner_type'] ?? 'Institutional'),
            'website_url' => trim($_POST['website_url'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
            $up = uploadAdminImage($_FILES['logo'], 'partners');
            if ($up['status'] === 'success') {
                $data['logo_url'] = $up['path'];
            }
        }
        try {
            $part_id = savePartnerRecord($conn, $data);
            $_SESSION['success_msg'] = 'Partner saved successfully.';
            logActivity($conn, 'Save Partner', "Saved partner: {$data['name']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save partner: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=partners");
        exit;

    // --- Volunteer Opportunities & Applications ---
    } elseif ($submit_action === 'volunteer_opp_add' || $submit_action === 'volunteer_opp_edit') {
        $opp_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $opp_id,
            'title' => trim($_POST['title'] ?? ''),
            'department' => trim($_POST['department'] ?? 'Civic Technology'),
            'location' => trim($_POST['location'] ?? 'Mombasa / Remote'),
            'commitment_type' => trim($_POST['commitment_type'] ?? 'Flexible'),
            'slots' => (int)($_POST['slots'] ?? 1),
            'deadline' => trim($_POST['deadline'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'requirements' => trim($_POST['requirements'] ?? ''),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        try {
            $opp_id = saveVolunteerOpportunityRecord($conn, $data);
            $_SESSION['success_msg'] = 'Volunteer role saved successfully.';
            logActivity($conn, 'Save Volunteer Role', "Saved role: {$data['title']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save volunteer role: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=volunteers&subview=opportunities");
        exit;

    } elseif ($submit_action === 'volunteer_app_status') {
        $app_id = (int)($_POST['id'] ?? 0);
        $new_status = trim($_POST['status'] ?? 'pending');
        try {
            $res = updateVolunteerApplicationStatus($conn, $app_id, $new_status);
            if ($res) {
                $_SESSION['success_msg'] = "Application status updated to " . ucfirst($new_status) . ".";
                logActivity($conn, 'Update Volunteer Application', "Updated application ID {$app_id} status to {$new_status}");
            } else {
                $_SESSION['error_msg'] = 'Failed to update application status.';
            }
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to update application status: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=volunteers&subview=applications");
        exit;

    // --- Our Story / Milestones ---
    } elseif ($submit_action === 'story_add' || $submit_action === 'story_edit') {
        $story_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $story_id,
            'year' => trim($_POST['year'] ?? date('Y')),
            'milestone_date' => trim($_POST['milestone_date'] ?? date('Y-m-d')),
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $up = uploadAdminImage($_FILES['image'], 'story');
            if ($up['status'] === 'success') {
                $data['image_url'] = $up['path'];
            }
        }
        try {
            $story_id = saveStoryMilestoneRecord($conn, $data);
            $_SESSION['success_msg'] = 'Story milestone saved successfully.';
            logActivity($conn, 'Save Milestone', "Saved story milestone: {$data['title']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save milestone: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=story");
        exit;

    // --- Core Values ---
    } elseif ($submit_action === 'value_add' || $submit_action === 'value_edit') {
        $val_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $val_id,
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'icon' => trim($_POST['icon'] ?? 'verified'),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        try {
            $val_id = saveCoreValueRecord($conn, $data);
            $_SESSION['success_msg'] = 'Core value saved successfully.';
            logActivity($conn, 'Save Core Value', "Saved core value: {$data['title']}");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save core value: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=values");
        exit;

    // --- Achievements & Impact ---
    } elseif ($submit_action === 'achievement_add' || $submit_action === 'achievement_edit') {
        $ach_id = (int)($_POST['id'] ?? 0);
        $data = [
            'id' => $ach_id,
            'metric_value' => trim($_POST['metric_value'] ?? ''),
            'label' => trim($_POST['label'] ?? ''),
            'unit' => trim($_POST['unit'] ?? ''),
            'reporting_period' => trim($_POST['reporting_period'] ?? date('Y')),
            'description' => trim($_POST['description'] ?? ''),
            'display_order' => (int)($_POST['display_order'] ?? 0),
            'published' => isset($_POST['published']) ? 1 : 0,
        ];
        try {
            $ach_id = saveAchievementRecord($conn, $data);
            $_SESSION['success_msg'] = 'Impact metric saved successfully.';
            logActivity($conn, 'Save Achievement', "Saved achievement metric: {$data['label']} ({$data['metric_value']})");
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save impact metric: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=achievements");
        exit;

    // --- SEO & AEO Management ---
    } elseif ($submit_action === 'save_global_seo') {
        try {
            $data = $_POST['global_seo'] ?? [];
            list($ok, $msg) = saveGlobalSeoSettings($conn, $data);
            if ($ok) {
                $_SESSION['success_msg'] = $msg;
                logActivity($conn, 'Update Global SEO', 'Admin updated global SEO & AEO configuration defaults');
            } else {
                $_SESSION['error_msg'] = $msg;
            }
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save global SEO: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=seo");
        exit;

    } elseif ($submit_action === 'save_page_seo') {
        $route = trim($_POST['page_route'] ?? '');
        try {
            $data = $_POST['page_seo'] ?? [];
            $data['page_route'] = $route;
            list($ok, $msg) = savePageSeoMetadata($conn, $data);
            if ($ok) {
                $_SESSION['success_msg'] = $msg;
                logActivity($conn, 'Update Page SEO', "Admin updated SEO metadata for route: {$route}");
            } else {
                $_SESSION['error_msg'] = $msg;
            }
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Failed to save page SEO: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=seo&page=" . urlencode($route));
        exit;

    } elseif ($submit_action === 'sync_sitemap') {
        try {
            $sitemapFile = dirname(__DIR__) . '/sitemap.xml';
            ob_start();
            require dirname(__DIR__) . '/sitemap.php';
            $xmlContent = ob_get_clean();
            if (!empty($xmlContent) && strpos($xmlContent, '<?xml') !== false) {
                file_put_contents($sitemapFile, $xmlContent);
                $_SESSION['success_msg'] = 'Sitemap synchronized and updated successfully (' . strlen($xmlContent) . ' bytes).';
                logActivity($conn, 'Sync Sitemap', 'Admin regenerated static sitemap.xml cache from dynamic generator');
            } else {
                $_SESSION['error_msg'] = 'Failed to generate valid XML sitemap.';
            }
        } catch (Throwable $e) {
            $_SESSION['error_msg'] = 'Sitemap synchronization error: ' . $e->getMessage();
        }
        header("Location: admin_dashboard.php?tab=seo");
        exit;

    } elseif ($submit_action === 'send_reset_link') {
         require_once dirname(__DIR__) . '/includes/mailer.php';
         
         $admin_id = $_SESSION['admin_id'] ?? null;
         if ($admin_id) {
             $reset_token = bin2hex(random_bytes(32));
             
             $stmt = $conn->prepare("UPDATE admins SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = ?");
             if ($stmt) {
                 $stmt->bind_param("si", $reset_token, $admin_id);
                 $stmt->execute();
                 $stmt->close();
                 
                 $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
                 $host = $_SERVER['HTTP_HOST'];
                 $base_dir = dirname($_SERVER['SCRIPT_NAME'], 2); 
                 $reset_link = $protocol . '://' . $host . $base_dir . '/reset_password.php?token=' . $reset_token;
                 $subject = "Admin Password Reset Request";
                 $body = "<h2>Password Reset</h2><p>You have requested to reset your admin password. Click the link below to set a new password:</p><p><a href=\"{$reset_link}\">{$reset_link}</a></p><p>This link will expire in 1 hour.</p>";
                 
                 if (sendAdminEmail($subject, $body)) {
                     $_SESSION['success_msg'] = 'Password reset link has been sent to the admin email.';
                      logActivity($conn, 'Request Password Reset', 'Admin requested password reset link');
                 } else {
                     $_SESSION['error_msg'] = 'Failed to send password reset email. Please check the mailer configuration.';
                 }
             } else {
                 $_SESSION['error_msg'] = 'Database error while generating reset token.';
             }
         } else {
             $_SESSION['error_msg'] = 'You must be logged in to request a password reset.';
         }
         
         header("Location: admin_dashboard.php?tab=settings");
         exit;
     } elseif ($submit_action === 'reset_database') {
         logActivity($conn, 'Reset Database', 'Admin triggered a full database reset');
          $conn->query("SET FOREIGN_KEY_CHECKS = 0;");
         $conn->query("TRUNCATE TABLE `contact_submissions`;");
         $conn->query("TRUNCATE TABLE `registrations`;");
         $conn->query("TRUNCATE TABLE `projects`;");
         $conn->query("TRUNCATE TABLE `gallery_items`;");
         $conn->query("TRUNCATE TABLE `news_articles`;");
         $conn->query("TRUNCATE TABLE `login_attempts`;");
         $conn->query("TRUNCATE TABLE `admins`;");
         $conn->query("SET FOREIGN_KEY_CHECKS = 1;");

          $username = 'admin';
          $password = 'admin123';
          $password_hashed = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $conn->prepare("INSERT INTO `admins` (`username`, `password`) VALUES (?, ?)");
          if ($stmt) {
              $stmt->bind_param("ss", $username, $password_hashed);
              $stmt->execute();
              $stmt->close();
          }

          $_SESSION = [];
          $_SESSION['success_msg'] = 'Database has been successfully reset. All data is cleared and only default admin seeds remain.';
          header("Location: ../login.php");
          exit;
     } elseif ($submit_action === 'admin_edit') {
         $admin_id = (int)($_POST['admin_id'] ?? 0);
         $admin_username = trim($_POST['admin_username'] ?? '');
         $admin_email = trim($_POST['admin_email'] ?? '');

         if ($admin_id > 0 && $admin_username !== '') {
             $stmt = $conn->prepare("UPDATE admins SET username = ?, email = ? WHERE id = ?");
             $stmt->bind_param("ssi", $admin_username, $admin_email, $admin_id);
             if ($stmt->execute()) {
                 $_SESSION['success_msg'] = 'Admin details updated successfully.';
                 logActivity($conn, 'Update Admin', "Updated details for admin ID {$admin_id}");
             } else {
                 $_SESSION['error_msg'] = 'Failed to update admin details.';
             }
             $stmt->close();
         } else {
             $_SESSION['error_msg'] = 'Invalid input for admin details.';
         }
         header("Location: admin_dashboard.php?tab=settings");
         exit;
     } elseif ($submit_action === 'delete_admin') {
         $admin_id = (int)($_POST['admin_id'] ?? 0);
         if ($admin_id > 0 && $admin_id != $_SESSION['admin_id']) {
             $stmt = $conn->prepare("DELETE FROM admins WHERE id = ?");
             $stmt->bind_param("i", $admin_id);
             if ($stmt->execute()) {
                 $_SESSION['success_msg'] = 'Admin deleted successfully.';
                 logActivity($conn, 'Delete Admin', "Deleted admin ID {$admin_id}");
             } else {
                 $_SESSION['error_msg'] = 'Failed to delete admin.';
             }
             $stmt->close();
         } else {
             $_SESSION['error_msg'] = 'Invalid request or you cannot delete your own active session account.';
         }
         header("Location: admin_dashboard.php?tab=settings");
         exit;
     }
 }

$isMutation = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $mutation_action !== '') {
    $isMutation = true;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($action) && !in_array($action, ['add', 'edit', 'download'], true)) {
    $isMutation = true;
}

if ($isMutation) {
    $allowed_mutations = [
        'toggle_project_publish', 'toggle_gallery_publish', 'delete_project',
        'delete_gallery', 'toggle_news_publish', 'delete_news',
        'toggle_event_publish', 'delete_event', 'delete_contact',
        'delete_registration', 'complete_donation', 'fail_donation',
        'delete_donation', 'delete_log', 'delete_all_logs',
        'thematic_toggle_publish', 'thematic_delete',
        'objective_toggle_publish', 'objective_delete',
        'publication_toggle_publish', 'publication_delete',
        'testimonial_toggle_publish', 'testimonial_delete',
        'faq_toggle_publish', 'faq_delete',
        'partner_toggle_publish', 'partner_delete',
        'volunteer_opp_toggle_publish', 'volunteer_opp_delete', 'volunteer_app_delete',
        'story_toggle_publish', 'story_delete',
        'value_toggle_publish', 'value_delete',
        'achievement_toggle_publish', 'achievement_delete',
    ];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && (!in_array($mutation_action, $allowed_mutations, true) || !verifyCsrfToken($_POST['csrf_token'] ?? ''))) {
        http_response_code(403);
        exit('The requested action could not be verified.');
    }

    if ($action === 'toggle_project_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE projects SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Project visibility toggled successfully.';
            logActivity($conn, 'Toggle Project Visibility', 'Admin toggled project visibility (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to toggle project visibility.';
        }
        $stmt->close();
        header(LOC_PROJECTS);
        exit;
    }

    if ($action === 'toggle_gallery_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE gallery_items SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Gallery item visibility toggled successfully.';
            logActivity($conn, 'Toggle Gallery Visibility', 'Admin toggled gallery visibility (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to toggle gallery item visibility.';
        }
        $stmt->close();
        header(LOC_GALLERY);
        exit;
    }

    if ($action === 'delete_project' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Project deleted successfully.';
            logActivity($conn, 'Delete Project', 'Admin deleted project (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete project.';
        }
        $stmt->close();
        header(LOC_PROJECTS);
        exit;
    }

    if ($action === 'delete_gallery' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM gallery_items WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Gallery item deleted successfully.';
            logActivity($conn, 'Delete Gallery Item', 'Admin deleted gallery item (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete gallery item.';
        }
        $stmt->close();
        header(LOC_GALLERY);
        exit;
    }

    if ($action === 'toggle_news_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE news_articles SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'News article visibility toggled successfully.';
            logActivity($conn, 'Toggle News Visibility', 'Admin toggled news visibility (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to toggle news article visibility.';
        }
        $stmt->close();
        header(LOC_NEWS);
        exit;
    }

    if ($action === 'delete_news' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM news_articles WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'News article deleted successfully.';
            logActivity($conn, 'Delete News Article', 'Admin deleted news article (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete news article.';
        }
        $stmt->close();
        header(LOC_NEWS);
        exit;
    }

    if ($action === 'toggle_event_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE upcoming_events SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Event visibility toggled successfully.';
            logActivity($conn, 'Toggle Event Visibility', 'Admin toggled event visibility (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to toggle event visibility.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=events");
        exit;
    }

    if ($action === 'delete_event' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM upcoming_events WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Event deleted successfully.';
            logActivity($conn, 'Delete Event', 'Admin deleted event (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete event.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=events");
        exit;
    }

    if ($action === 'delete_contact' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM contact_submissions WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Contact message record deleted successfully.';
            logActivity($conn, 'Delete Contact', 'Admin deleted contact record (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete contact record.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=contacts");
        exit;
    }

    if ($action === 'delete_registration' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM registrations WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Registration record deleted successfully.';
            logActivity($conn, 'Delete Registration', 'Admin deleted registration record (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete registration record.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=registrations");
        exit;
    }

    if ($action === 'complete_donation' && $id > 0) {
        $stmt = $conn->prepare("UPDATE donations SET status = 'completed' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Donation marked as completed successfully.';
            logActivity($conn, 'Approve Donation', 'Admin marked donation as completed (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to update donation status.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=donations");
        exit;
    }

    if ($action === 'fail_donation' && $id > 0) {
        $stmt = $conn->prepare("UPDATE donations SET status = 'failed' WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Donation marked as failed.';
            logActivity($conn, 'Reject Donation', 'Admin marked donation as failed (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to update donation status.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=donations");
        exit;
    }

    if ($action === 'delete_donation' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM donations WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Donation record deleted successfully.';
            logActivity($conn, 'Delete Donation', 'Admin deleted donation record (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete donation record.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=donations");
        exit;
    }

    if ($action === 'delete_log' && $id > 0) {
        $stmt = $conn->prepare("DELETE FROM activity_logs WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Activity log entry deleted successfully.';
            logActivity($conn, 'Delete Log', 'Admin deleted a specific activity log (ID: ' . $id . ')');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete activity log entry.';
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=logs");
        exit;
    }

    if ($action === 'delete_all_logs') {
        if ($conn->query("TRUNCATE TABLE activity_logs")) {
            $_SESSION['success_msg'] = 'All activity logs deleted successfully.';
            logActivity($conn, 'Delete Logs', 'Admin deleted all activity logs');
        } else {
            $_SESSION['error_msg'] = 'Failed to delete all activity logs.';
        }
        header("Location: admin_dashboard.php?tab=logs");
        exit;
    }

    // Thematic Areas mutations
    if ($action === 'thematic_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE thematic_areas SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Thematic area visibility toggled.';
            logActivity($conn, 'Toggle Thematic Area', "Admin toggled thematic area visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=thematic");
        exit;
    }
    if ($action === 'thematic_delete' && $id > 0) {
        deleteThematicAreaRecord($conn, $id);
        $_SESSION['success_msg'] = 'Thematic area deleted successfully.';
        logActivity($conn, 'Delete Thematic Area', "Admin deleted thematic area (ID: {$id})");
        header("Location: admin_dashboard.php?tab=thematic");
        exit;
    }

    // Objectives mutations
    if ($action === 'objective_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE objectives SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Objective visibility toggled.';
            logActivity($conn, 'Toggle Objective', "Admin toggled objective visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=objectives");
        exit;
    }
    if ($action === 'objective_delete' && $id > 0) {
        deleteObjectiveRecord($conn, $id);
        $_SESSION['success_msg'] = 'Objective deleted successfully.';
        logActivity($conn, 'Delete Objective', "Admin deleted objective (ID: {$id})");
        header("Location: admin_dashboard.php?tab=objectives");
        exit;
    }

    // Publications mutations
    if ($action === 'publication_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE publications SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Publication visibility toggled.';
            logActivity($conn, 'Toggle Publication', "Admin toggled publication visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=publications");
        exit;
    }
    if ($action === 'publication_delete' && $id > 0) {
        deletePublicationRecord($conn, $id);
        $_SESSION['success_msg'] = 'Publication deleted successfully.';
        logActivity($conn, 'Delete Publication', "Admin deleted publication (ID: {$id})");
        header("Location: admin_dashboard.php?tab=publications");
        exit;
    }

    // Testimonials mutations
    if ($action === 'testimonial_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE testimonials SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Testimonial visibility toggled.';
            logActivity($conn, 'Toggle Testimonial', "Admin toggled testimonial visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=testimonials");
        exit;
    }
    if ($action === 'testimonial_delete' && $id > 0) {
        deleteTestimonialRecord($conn, $id);
        $_SESSION['success_msg'] = 'Testimonial deleted successfully.';
        logActivity($conn, 'Delete Testimonial', "Admin deleted testimonial (ID: {$id})");
        header("Location: admin_dashboard.php?tab=testimonials");
        exit;
    }

    // FAQs mutations
    if ($action === 'faq_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE faqs SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'FAQ visibility toggled.';
            logActivity($conn, 'Toggle FAQ', "Admin toggled FAQ visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=faqs");
        exit;
    }
    if ($action === 'faq_delete' && $id > 0) {
        deleteFaqRecord($conn, $id);
        $_SESSION['success_msg'] = 'FAQ deleted successfully.';
        logActivity($conn, 'Delete FAQ', "Admin deleted FAQ (ID: {$id})");
        header("Location: admin_dashboard.php?tab=faqs");
        exit;
    }

    // Partnerships mutations
    if ($action === 'partner_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE partners SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Partner visibility toggled.';
            logActivity($conn, 'Toggle Partner', "Admin toggled partner visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=partners");
        exit;
    }
    if ($action === 'partner_delete' && $id > 0) {
        deletePartnerRecord($conn, $id);
        $_SESSION['success_msg'] = 'Partner deleted successfully.';
        logActivity($conn, 'Delete Partner', "Admin deleted partner (ID: {$id})");
        header("Location: admin_dashboard.php?tab=partners");
        exit;
    }

    // Volunteer opportunities & applications mutations
    if ($action === 'volunteer_opp_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE volunteer_opportunities SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Volunteer opportunity status toggled.';
            logActivity($conn, 'Toggle Volunteer Role', "Admin toggled volunteer role status (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=volunteers&subview=opportunities");
        exit;
    }
    if ($action === 'volunteer_opp_delete' && $id > 0) {
        deleteVolunteerOpportunityRecord($conn, $id);
        $_SESSION['success_msg'] = 'Volunteer opportunity deleted successfully.';
        logActivity($conn, 'Delete Volunteer Role', "Admin deleted volunteer role (ID: {$id})");
        header("Location: admin_dashboard.php?tab=volunteers&subview=opportunities");
        exit;
    }
    if ($action === 'volunteer_app_delete' && $id > 0) {
        deleteVolunteerApplicationRecord($conn, $id);
        $_SESSION['success_msg'] = 'Volunteer application deleted successfully.';
        logActivity($conn, 'Delete Volunteer Application', "Admin deleted volunteer application (ID: {$id})");
        header("Location: admin_dashboard.php?tab=volunteers&subview=applications");
        exit;
    }

    // Our Story mutations
    if ($action === 'story_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE our_story SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Milestone visibility toggled.';
            logActivity($conn, 'Toggle Milestone', "Admin toggled milestone visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=story");
        exit;
    }
    if ($action === 'story_delete' && $id > 0) {
        deleteStoryMilestoneRecord($conn, $id);
        $_SESSION['success_msg'] = 'Story milestone deleted successfully.';
        logActivity($conn, 'Delete Milestone', "Admin deleted milestone (ID: {$id})");
        header("Location: admin_dashboard.php?tab=story");
        exit;
    }

    // Core Values mutations
    if ($action === 'value_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE core_values SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Core value visibility toggled.';
            logActivity($conn, 'Toggle Core Value', "Admin toggled value visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=values");
        exit;
    }
    if ($action === 'value_delete' && $id > 0) {
        deleteCoreValueRecord($conn, $id);
        $_SESSION['success_msg'] = 'Core value deleted successfully.';
        logActivity($conn, 'Delete Core Value', "Admin deleted core value (ID: {$id})");
        header("Location: admin_dashboard.php?tab=values");
        exit;
    }

    // Achievements mutations
    if ($action === 'achievement_toggle_publish' && $id > 0) {
        $stmt = $conn->prepare("UPDATE achievements SET published = 1 - published WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = 'Achievement visibility toggled.';
            logActivity($conn, 'Toggle Achievement', "Admin toggled achievement visibility (ID: {$id})");
        }
        $stmt->close();
        header("Location: admin_dashboard.php?tab=achievements");
        exit;
    }
    if ($action === 'achievement_delete' && $id > 0) {
        deleteAchievementRecord($conn, $id);
        $_SESSION['success_msg'] = 'Achievement deleted successfully.';
        logActivity($conn, 'Delete Achievement', "Admin deleted achievement (ID: {$id})");
        header("Location: admin_dashboard.php?tab=achievements");
        exit;
    }
}


$cnt_contacts = $conn->query("SELECT COUNT(*) FROM contact_submissions")->fetch_row()[0] ?? 0;
$cnt_registrations = $conn->query("SELECT COUNT(*) FROM registrations")->fetch_row()[0] ?? 0;
$cnt_projects = $conn->query("SELECT COUNT(*) FROM projects")->fetch_row()[0] ?? 0;
$cnt_gallery = $conn->query("SELECT COUNT(*) FROM gallery_items")->fetch_row()[0] ?? 0;
$cnt_news = $conn->query("SELECT COUNT(*) FROM news_articles")->fetch_row()[0] ?? 0;
$cnt_events = $conn->query("SELECT COUNT(*) FROM upcoming_events")->fetch_row()[0] ?? 0;
$cnt_donations = $conn->query("SELECT COUNT(*) FROM donations")->fetch_row()[0] ?? 0;

$cnt_thematic = tableExists($conn, 'thematic_areas') ? ($conn->query("SELECT COUNT(*) FROM thematic_areas")->fetch_row()[0] ?? 0) : 0;
$cnt_objectives = tableExists($conn, 'objectives') ? ($conn->query("SELECT COUNT(*) FROM objectives")->fetch_row()[0] ?? 0) : 0;
$cnt_publications = tableExists($conn, 'publications') ? ($conn->query("SELECT COUNT(*) FROM publications")->fetch_row()[0] ?? 0) : 0;
$cnt_testimonials = tableExists($conn, 'testimonials') ? ($conn->query("SELECT COUNT(*) FROM testimonials")->fetch_row()[0] ?? 0) : 0;
$cnt_faqs = tableExists($conn, 'faqs') ? ($conn->query("SELECT COUNT(*) FROM faqs")->fetch_row()[0] ?? 0) : 0;
$cnt_partners = tableExists($conn, 'partners') ? ($conn->query("SELECT COUNT(*) FROM partners")->fetch_row()[0] ?? 0) : 0;
$cnt_volunteers = tableExists($conn, 'volunteer_applications') ? ($conn->query("SELECT COUNT(*) FROM volunteer_applications WHERE status = 'pending'")->fetch_row()[0] ?? 0) : 0;
$cnt_story = tableExists($conn, 'our_story') ? ($conn->query("SELECT COUNT(*) FROM our_story")->fetch_row()[0] ?? 0) : 0;
$cnt_values = tableExists($conn, 'core_values') ? ($conn->query("SELECT COUNT(*) FROM core_values")->fetch_row()[0] ?? 0) : 0;
$cnt_achievements = tableExists($conn, 'achievements') ? ($conn->query("SELECT COUNT(*) FROM achievements")->fetch_row()[0] ?? 0) : 0;
$cnt_seo = tableExists($conn, 'seo_page_metadata') ? ($conn->query("SELECT COUNT(*) FROM seo_page_metadata WHERE robots_index = 1")->fetch_row()[0] ?? 0) : 0;

$total_donations_kes = $conn->query("SELECT SUM(amount) FROM donations WHERE status = 'completed' AND currency = 'KES'")->fetch_row()[0] ?? 0;
$total_donations_usd = $conn->query("SELECT SUM(amount) FROM donations WHERE status = 'completed' AND currency = 'USD'")->fetch_row()[0] ?? 0;

$page_title = 'Admin Control Panel | BuniAfrika CBO';
require_once dirname(__DIR__) . '/includes/header.php';
?>

<link rel="stylesheet" href="../css/admin.css">

<section class="section container" style="padding-top: 24px;">

    <div class="admin-header-bar">
        <div class="admin-title-group">
            <h1>Control Panel Dashboard</h1>
            <span style="font-size: 12px; opacity: 0.85;">CBO Core Management Suite</span>
        </div>
        <div class="admin-user-info">
            <span class="material-symbols-outlined" style="color: var(--secondary-container);">account_circle</span>
            <span>Welcome, <strong><?php echo h($_SESSION['admin_username']); ?></strong></span>
            <a href="../logout.php" class="btn btn-outline" style="color: #ffffff; border-color: #ffffff; padding: 6px 14px; font-size: 11px; margin-left: 12px;">Log Out</a>
        </div>
    </div>

    <?php if (!empty($session_success)) { ?>
        <div class="alert alert-success" style="display: flex; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined">check_circle</span>
            <span><?php echo h($session_success); ?></span>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.BuniAlert) {
                window.BuniAlert.success('Operation Successful', <?php echo json_encode($session_success); ?>);
            }
        });
        </script>
    <?php } ?>
    <?php if (!empty($session_error)) { ?>
        <div class="alert alert-danger" style="display: flex; align-items: center; gap: 8px;">
            <span class="material-symbols-outlined">error</span>
            <span><?php echo h($session_error); ?></span>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.BuniAlert) {
                window.BuniAlert.error('Submission Issue', <?php echo json_encode($session_error); ?>);
            }
        });
        </script>
    <?php } ?>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-symbols-outlined">mail</span>
            </div>
            <div class="stat-info">
                <span class="stat-label">Submissions</span>
                <span class="stat-value"><?php echo $cnt_contacts; ?></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-symbols-outlined">groups</span>
            </div>
            <div class="stat-info">
                <span class="stat-label">Involvements</span>
                <span class="stat-value"><?php echo $cnt_registrations; ?></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-symbols-outlined">rss_feed</span>
            </div>
            <div class="stat-info">
                <span class="stat-label">News Updates</span>
                <span class="stat-value"><?php echo $cnt_news; ?></span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <span class="material-symbols-outlined">volunteer_activism</span>
            </div>
            <div class="stat-info">
                <span class="stat-label">Donations</span>
                <span class="stat-value"><?php echo $cnt_donations; ?></span>
                <span style="font-size: 11px; font-weight: 700; color: var(--primary); margin-top: 2px;">
                    KES <?php echo number_format($total_donations_kes, 0); ?> / $<?php echo number_format($total_donations_usd, 0); ?>
                </span>
            </div>
        </div>
    </div>

    <style>
    .admin-layout-container {
        display: flex;
        flex-direction: column;
        gap: var(--spacing-xl);
    }
    .admin-sidebar {
        display: none;
    }
    .admin-mobile-nav {
        display: block;
        margin-bottom: var(--spacing-xl);
    }
    .admin-nav-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .admin-nav-list li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: var(--radius-md);
        color: var(--on-surface-variant);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .admin-nav-list li a,
    .admin-nav-list li a:hover,
    .admin-nav-list li a:focus-visible {
        text-decoration: none !important;
    }
    .admin-nav-list li a:hover {
        background: rgba(0, 32, 69, 0.05);
        color: var(--primary);
    }
    .admin-nav-list li a.active {
        background: var(--primary);
        color: #ffffff;
    }
    .admin-nav-list li a .material-symbols-outlined {
        font-size: 20px;
    }

    @media (min-width: 1024px) {
        .admin-layout-container {
            display: grid;
            grid-template-columns: 260px 1fr;
            align-items: start;
        }
        .admin-mobile-nav {
            display: none;
        }
        .admin-sidebar {
            display: block;
            background-color: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--outline-variant);
            padding: var(--spacing-md);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 24px;
        }
    }
    </style>

    <div class="admin-layout-container">
        <div class="admin-mobile-nav">
            <label for="admin-nav-select" style="display: block; font-size: 12px; font-weight: 700; color: var(--on-surface-variant); margin-bottom: 8px; text-transform: uppercase;">Navigate To:</label>
            <select id="admin-nav-select" class="form-control" style="width: 100%; max-width: 350px; padding: 14px; font-weight: 700; font-size: 15px; border-radius: var(--radius-lg); border: 2px solid var(--outline-variant); background-color: var(--surface); color: var(--primary); cursor: pointer; box-shadow: var(--shadow-sm);" onchange="window.location.href=this.value">
                <optgroup label="Main & Inquiries">
                    <option value="admin_dashboard.php?tab=overview" <?php echo $current_tab === 'overview' ? 'selected' : ''; ?>>Overview</option>
                    <option value="admin_dashboard.php?tab=contacts" <?php echo $current_tab === 'contacts' ? 'selected' : ''; ?>>Messages (<?php echo $cnt_contacts; ?>)</option>
                    <option value="admin_dashboard.php?tab=registrations" <?php echo $current_tab === 'registrations' ? 'selected' : ''; ?>>Registrants (<?php echo $cnt_registrations; ?>)</option>
                    <option value="admin_dashboard.php?tab=donations" <?php echo $current_tab === 'donations' ? 'selected' : ''; ?>>Donations (<?php echo $cnt_donations; ?>)</option>
                </optgroup>
                <optgroup label="Website Content">
                    <option value="admin_dashboard.php?tab=thematic" <?php echo $current_tab === 'thematic' ? 'selected' : ''; ?>>Thematic Areas (<?php echo $cnt_thematic; ?>)</option>
                    <option value="admin_dashboard.php?tab=objectives" <?php echo $current_tab === 'objectives' ? 'selected' : ''; ?>>Objectives (<?php echo $cnt_objectives; ?>)</option>
                    <option value="admin_dashboard.php?tab=values" <?php echo $current_tab === 'values' ? 'selected' : ''; ?>>Core Values (<?php echo $cnt_values; ?>)</option>
                    <option value="admin_dashboard.php?tab=story" <?php echo $current_tab === 'story' ? 'selected' : ''; ?>>Our Story (<?php echo $cnt_story; ?>)</option>
                    <option value="admin_dashboard.php?tab=achievements" <?php echo $current_tab === 'achievements' ? 'selected' : ''; ?>>Achievements (<?php echo $cnt_achievements; ?>)</option>
                    <option value="admin_dashboard.php?tab=projects" <?php echo $current_tab === 'projects' ? 'selected' : ''; ?>>Projects (<?php echo $cnt_projects; ?>)</option>
                    <option value="admin_dashboard.php?tab=gallery" <?php echo $current_tab === 'gallery' ? 'selected' : ''; ?>>Gallery (<?php echo $cnt_gallery; ?>)</option>
                    <option value="admin_dashboard.php?tab=news" <?php echo $current_tab === 'news' ? 'selected' : ''; ?>>News (<?php echo $cnt_news; ?>)</option>
                    <option value="admin_dashboard.php?tab=events" <?php echo $current_tab === 'events' ? 'selected' : ''; ?>>Events (<?php echo $cnt_events; ?>)</option>
                </optgroup>
                <optgroup label="Engagement & Resources">
                    <option value="admin_dashboard.php?tab=publications" <?php echo $current_tab === 'publications' ? 'selected' : ''; ?>>Publications (<?php echo $cnt_publications; ?>)</option>
                    <option value="admin_dashboard.php?tab=testimonials" <?php echo $current_tab === 'testimonials' ? 'selected' : ''; ?>>Testimonials (<?php echo $cnt_testimonials; ?>)</option>
                    <option value="admin_dashboard.php?tab=faqs" <?php echo $current_tab === 'faqs' ? 'selected' : ''; ?>>FAQs (<?php echo $cnt_faqs; ?>)</option>
                    <option value="admin_dashboard.php?tab=partners" <?php echo $current_tab === 'partners' ? 'selected' : ''; ?>>Partnerships (<?php echo $cnt_partners; ?>)</option>
                    <option value="admin_dashboard.php?tab=volunteers" <?php echo $current_tab === 'volunteers' ? 'selected' : ''; ?>>Volunteers (<?php echo $cnt_volunteers; ?>)</option>
                </optgroup>
                <optgroup label="System">
                    <option value="admin_dashboard.php?tab=logs" <?php echo $current_tab === 'logs' ? 'selected' : ''; ?>>Activity Logs</option>
                    <option value="admin_dashboard.php?tab=settings" <?php echo $current_tab === 'settings' ? 'selected' : ''; ?>>Settings</option>
                    <option value="admin_dashboard.php?tab=seo" <?php echo $current_tab === 'seo' ? 'selected' : ''; ?>>SEO &amp; AEO (<?php echo $cnt_seo; ?>)</option>
                </optgroup>
            </select>
        </div>

        <aside class="admin-sidebar">
            <h3 style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--on-surface-variant); margin: 8px 0 10px 8px;">Main</h3>
            <ul class="admin-nav-list">
                <li><a href="admin_dashboard.php?tab=overview" class="<?php echo $current_tab === 'overview' ? 'active' : ''; ?>"><span class="material-symbols-outlined">dashboard</span> Overview</a></li>
                <li><a href="admin_dashboard.php?tab=contacts" class="<?php echo $current_tab === 'contacts' ? 'active' : ''; ?>"><span class="material-symbols-outlined">mail</span> Messages (<?php echo $cnt_contacts; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=registrations" class="<?php echo $current_tab === 'registrations' ? 'active' : ''; ?>"><span class="material-symbols-outlined">groups</span> Registrants (<?php echo $cnt_registrations; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=donations" class="<?php echo $current_tab === 'donations' ? 'active' : ''; ?>"><span class="material-symbols-outlined">volunteer_activism</span> Donations (<?php echo $cnt_donations; ?>)</a></li>
            </ul>

            <h3 style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--on-surface-variant); margin: 18px 0 10px 8px;">Website Content</h3>
            <ul class="admin-nav-list">
                <li><a href="admin_dashboard.php?tab=thematic" class="<?php echo $current_tab === 'thematic' ? 'active' : ''; ?>"><span class="material-symbols-outlined">category</span> Thematic Areas (<?php echo $cnt_thematic; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=objectives" class="<?php echo $current_tab === 'objectives' ? 'active' : ''; ?>"><span class="material-symbols-outlined">track_changes</span> Objectives (<?php echo $cnt_objectives; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=values" class="<?php echo $current_tab === 'values' ? 'active' : ''; ?>"><span class="material-symbols-outlined">verified</span> Core Values (<?php echo $cnt_values; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=story" class="<?php echo $current_tab === 'story' ? 'active' : ''; ?>"><span class="material-symbols-outlined">auto_stories</span> Our Story (<?php echo $cnt_story; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=achievements" class="<?php echo $current_tab === 'achievements' ? 'active' : ''; ?>"><span class="material-symbols-outlined">query_stats</span> Achievements (<?php echo $cnt_achievements; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=projects" class="<?php echo $current_tab === 'projects' ? 'active' : ''; ?>"><span class="material-symbols-outlined">work</span> Projects (<?php echo $cnt_projects; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=gallery" class="<?php echo $current_tab === 'gallery' ? 'active' : ''; ?>"><span class="material-symbols-outlined">photo_library</span> Gallery (<?php echo $cnt_gallery; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=news" class="<?php echo $current_tab === 'news' ? 'active' : ''; ?>"><span class="material-symbols-outlined">article</span> News (<?php echo $cnt_news; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=events" class="<?php echo $current_tab === 'events' ? 'active' : ''; ?>"><span class="material-symbols-outlined">event</span> Events (<?php echo $cnt_events; ?>)</a></li>
            </ul>

            <h3 style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--on-surface-variant); margin: 18px 0 10px 8px;">Engagement &amp; Resources</h3>
            <ul class="admin-nav-list">
                <li><a href="admin_dashboard.php?tab=publications" class="<?php echo $current_tab === 'publications' ? 'active' : ''; ?>"><span class="material-symbols-outlined">description</span> Publications (<?php echo $cnt_publications; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=testimonials" class="<?php echo $current_tab === 'testimonials' ? 'active' : ''; ?>"><span class="material-symbols-outlined">reviews</span> Testimonials (<?php echo $cnt_testimonials; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=faqs" class="<?php echo $current_tab === 'faqs' ? 'active' : ''; ?>"><span class="material-symbols-outlined">help</span> FAQs (<?php echo $cnt_faqs; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=partners" class="<?php echo $current_tab === 'partners' ? 'active' : ''; ?>"><span class="material-symbols-outlined">handshake</span> Partnerships (<?php echo $cnt_partners; ?>)</a></li>
                <li><a href="admin_dashboard.php?tab=volunteers" class="<?php echo $current_tab === 'volunteers' ? 'active' : ''; ?>"><span class="material-symbols-outlined">volunteer_activism</span> Volunteers (<?php echo $cnt_volunteers; ?>)</a></li>
            </ul>

            <h3 style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: var(--on-surface-variant); margin: 18px 0 10px 8px;">System</h3>
            <ul class="admin-nav-list">
                <li><a href="admin_dashboard.php?tab=logs" class="<?php echo $current_tab === 'logs' ? 'active' : ''; ?>"><span class="material-symbols-outlined">history</span> Activity Logs</a></li>
                <li><a href="admin_dashboard.php?tab=settings" class="<?php echo $current_tab === 'settings' ? 'active' : ''; ?>"><span class="material-symbols-outlined">settings</span> Settings</a></li>
                <li><a href="admin_dashboard.php?tab=seo" class="<?php echo $current_tab === 'seo' ? 'active' : ''; ?>"><span class="material-symbols-outlined">travel_explore</span> SEO &amp; AEO (<?php echo $cnt_seo; ?>)</a></li>
            </ul>
        </aside>

        <div style="background-color: var(--surface); padding: var(--spacing-lg); border-radius: var(--radius-xl); border: 1px solid var(--outline-variant); box-shadow: var(--shadow-sm); min-height: 400px; margin-bottom: 40px; overflow-x: auto;">
            <?php
            if ($current_tab === 'overview') {
                require __DIR__ . '/overview_tab.php';
            } elseif ($current_tab === 'contacts') {
                require __DIR__ . '/contacts_tab.php';
            } elseif ($current_tab === 'registrations') {
                require __DIR__ . '/registrations_tab.php';
            } elseif ($current_tab === 'projects') {
                require __DIR__ . '/projects_tab.php';
            } elseif ($current_tab === 'gallery') {
                require __DIR__ . '/gallery_tab.php';
            } elseif ($current_tab === 'news') {
                require __DIR__ . '/news_tab.php';
            } elseif ($current_tab === 'events') {
                require __DIR__ . '/events_tab.php';
            } elseif ($current_tab === 'donations') {
                require __DIR__ . '/donations_tab.php';
            } elseif ($current_tab === 'logs') {
                require __DIR__ . '/logs_tab.php';
            } elseif ($current_tab === 'settings') {
                require __DIR__ . '/settings_tab.php';
            } elseif ($current_tab === 'thematic') {
                require __DIR__ . '/thematic_tab.php';
            } elseif ($current_tab === 'objectives') {
                require __DIR__ . '/objectives_tab.php';
            } elseif ($current_tab === 'publications') {
                require __DIR__ . '/publications_tab.php';
            } elseif ($current_tab === 'testimonials') {
                require __DIR__ . '/testimonials_tab.php';
            } elseif ($current_tab === 'faqs') {
                require __DIR__ . '/faqs_tab.php';
            } elseif ($current_tab === 'partners') {
                require __DIR__ . '/partnerships_tab.php';
            } elseif ($current_tab === 'volunteers') {
                require __DIR__ . '/volunteer_tab.php';
            } elseif ($current_tab === 'story') {
                require __DIR__ . '/story_tab.php';
            } elseif ($current_tab === 'values') {
                require __DIR__ . '/values_tab.php';
            } elseif ($current_tab === 'achievements') {
                require __DIR__ . '/achievements_tab.php';
            } elseif ($current_tab === 'seo') {
                require __DIR__ . '/seo_tab.php';
            }
            ?>
        </div>
    </div>
</section>

<div id="confirm-modal" class="confirm-modal-overlay" aria-hidden="true" role="dialog" aria-labelledby="confirm-modal-title">
    <div class="confirm-modal-card">
        <div class="confirm-modal-header">
            <span class="material-symbols-outlined confirm-modal-icon">warning</span>
            <h3 id="confirm-modal-title" class="confirm-modal-heading">Confirm Deletion</h3>
        </div>
        <div class="confirm-modal-body">
            <p id="confirm-modal-message">Are you sure you want to delete this record?</p>
        </div>
        <div class="confirm-modal-actions">
            <button id="confirm-modal-cancel" class="btn btn-outline" style="padding: 8px 20px; font-size: 12px;">Cancel</button>
            <a id="confirm-modal-confirm" href="#" class="btn btn-primary btn-danger-action" style="padding: 8px 20px; font-size: 12px;">Delete</a>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>

<script>
(function () {
    const toolbarOptions = [
        [{ 'header': [1, 2, 3, false] }],
        ['bold', 'italic', 'underline', 'strike'],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
        [{ 'indent': '-1' }, { 'indent': '+1' }],
        ['blockquote', 'link'],
        ['clean']
    ];

    const projectEditorEl = document.getElementById('project-description-editor');
    if (projectEditorEl) {
        const projectTextarea = document.getElementById('description');
        const projectQuill = new Quill('#project-description-editor', {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
            placeholder: 'Write project description here…'
        });

        if (projectTextarea.value.trim()) {
            projectQuill.root.innerHTML = projectTextarea.value;
        }

        projectEditorEl.closest('form').addEventListener('submit', function () {
            projectTextarea.value = projectQuill.getSemanticHTML();
        });
    }

    const newsEditorEl = document.getElementById('news-content-editor');
    if (newsEditorEl) {
        const newsTextarea = document.getElementById('content');
        const newsQuill = new Quill('#news-content-editor', {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
            placeholder: 'Write full article body text…'
        });

        if (newsTextarea.value.trim()) {
            newsQuill.root.innerHTML = newsTextarea.value;
        }

        newsEditorEl.closest('form').addEventListener('submit', function () {
            newsTextarea.value = newsQuill.getSemanticHTML();
        });
    }
})();
</script>

<script src="../js/admin.js"></script>
<?php
require_once dirname(__DIR__) . '/includes/footer.php';
?>
