<?php
/**
 * BuniAfrika Dynamic Content Management REST API Router
 * Supports public read endpoints and authenticated administrative operations.
 */

require_once dirname(__DIR__) . '/includes/bootstrap.php';
require_once dirname(__DIR__) . '/includes/db_connection.php';
require_once dirname(__DIR__) . '/includes/auth_helper.php';
require_once dirname(__DIR__) . '/includes/functions.php';

sendCorsHeaders();

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Parse endpoint path from $_GET['endpoint'], PATH_INFO or REQUEST_URI
$endpoint = $_GET['endpoint'] ?? '';
if (empty($endpoint)) {
    $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $uriPath = preg_replace('~^/(?:buniafrika\.co\.ke/public|api\.php)?/?~', '', $uriPath);
    if (strpos($uriPath, 'api/') === 0) {
        $endpoint = substr($uriPath, 4);
    } else {
        $endpoint = $uriPath;
    }
}
$endpoint = trim($endpoint, '/');

// Read JSON input if sent as raw body
$input = [];
$rawBody = file_get_contents('php://input');
if (!empty($rawBody)) {
    $decoded = json_decode($rawBody, true);
    if (is_array($decoded)) {
        $input = $decoded;
    }
}
$input = array_merge($_POST, $input);

function apiSuccess($data = [], $message = 'Success', $extra = []) {
    $res = array_merge([
        'status' => 'success',
        'message' => $message,
        'data' => $data,
        'count' => is_array($data) ? count($data) : (empty($data) ? 0 : 1),
    ], $extra);
    echo json_encode($res, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function apiError($message, $code = 400, $errors = []) {
    http_response_code($code);
    echo json_encode([
        'status' => 'error',
        'message' => $message,
        'errors' => $errors,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function requireAdminAuth() {
    if (!isAdminLoggedIn()) {
        apiError('Unauthorized. Administrator authentication required.', 401);
    }
}

// ------------------------------------------------------------------------------
// ROUTING
// ------------------------------------------------------------------------------

switch ($endpoint) {

    // ==========================================
    // PUBLIC READ ENDPOINTS
    // ==========================================

    case 'public/thematic-areas':
    case 'public/thematic_areas':
        $data = getThematicAreas($conn, true);
        apiSuccess($data, 'Published thematic areas retrieved.');

    case 'public/objectives':
        $data = getObjectives($conn, true);
        apiSuccess($data, 'Published organisational objectives retrieved.');

    case 'public/publications':
        $cat = $_GET['category'] ?? null;
        $data = getPublications($conn, true, $cat);
        apiSuccess($data, 'Published reports and publications retrieved.');

    case 'public/testimonials':
        $data = getTestimonials($conn, true);
        apiSuccess($data, 'Published testimonials retrieved.');

    case 'public/faqs':
        $cat = $_GET['category'] ?? null;
        $data = getFaqs($conn, true, $cat);
        apiSuccess($data, 'Published FAQs retrieved.', ['categories' => getFaqCategories($conn)]);

    case 'public/partnerships':
    case 'public/partners':
        $data = getPartners($conn, true);
        apiSuccess($data, 'Published partners retrieved.');

    case 'public/volunteer-opportunities':
        $data = getVolunteerOpportunities($conn, true);
        apiSuccess($data, 'Active volunteer opportunities retrieved.');

    case 'public/volunteer-applications':
        if ($method !== 'POST') {
            apiError('Method not allowed. Use POST to submit applications.', 405);
        }
        enforceRateLimit($conn, 'volunteer_application', 3, 1800, 900);
        requireValidCsrfToken($conn);
        try {
            $appId = submitVolunteerApplication($conn, $input);
            apiSuccess(['application_id' => $appId], 'Thank you! Your volunteer application has been submitted successfully.');
        } catch (InvalidArgumentException $e) {
            apiError($e->getMessage(), 422);
        } catch (Throwable $e) {
            error_log('Volunteer app error: ' . $e->getMessage());
            apiError('Could not process application. Please check your information and try again.', 500);
        }

    case 'public/our-story':
    case 'public/story':
        $data = getOurStory($conn, true);
        apiSuccess($data, 'Organisation story milestones retrieved.');

    case 'public/core-values':
    case 'public/values':
        $data = getCoreValues($conn, true);
        apiSuccess($data, 'Published core values retrieved.');

    case 'public/achievements':
    case 'public/impact':
        $data = getAchievements($conn, true);
        apiSuccess($data, 'Published achievements and impact metrics retrieved.');

    // ==========================================
    // PROTECTED ADMINISTRATIVE CRUD ENDPOINTS
    // ==========================================

    case 'admin/thematic-areas':
    case 'admin/thematic_areas':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getThematicAreaById($conn, (int)$id);
                if (!$item) apiError('Thematic area not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getThematicAreas($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveThematicAreaRecord($conn, $input);
                apiSuccess(['id' => $id], 'Thematic area saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing thematic area ID for deletion.', 400);
            deleteThematicAreaRecord($conn, $id);
            apiSuccess([], 'Thematic area deleted successfully.');
        }
        break;

    case 'admin/objectives':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getObjectiveById($conn, (int)$id);
                if (!$item) apiError('Objective not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getObjectives($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveObjectiveRecord($conn, $input);
                apiSuccess(['id' => $id], 'Objective saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing objective ID for deletion.', 400);
            deleteObjectiveRecord($conn, $id);
            apiSuccess([], 'Objective deleted successfully.');
        }
        break;

    case 'admin/publications':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getPublicationById($conn, (int)$id);
                if (!$item) apiError('Publication not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getPublications($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = savePublicationRecord($conn, $input);
                apiSuccess(['id' => $id], 'Publication report saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing publication ID for deletion.', 400);
            deletePublicationRecord($conn, $id);
            apiSuccess([], 'Publication deleted successfully.');
        }
        break;

    case 'admin/testimonials':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getTestimonialById($conn, (int)$id);
                if (!$item) apiError('Testimonial not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getTestimonials($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveTestimonialRecord($conn, $input);
                apiSuccess(['id' => $id], 'Testimonial saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing testimonial ID for deletion.', 400);
            deleteTestimonialRecord($conn, $id);
            apiSuccess([], 'Testimonial deleted successfully.');
        }
        break;

    case 'admin/faqs':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getFaqById($conn, (int)$id);
                if (!$item) apiError('FAQ not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getFaqs($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveFaqRecord($conn, $input);
                apiSuccess(['id' => $id], 'FAQ saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing FAQ ID for deletion.', 400);
            deleteFaqRecord($conn, $id);
            apiSuccess([], 'FAQ deleted successfully.');
        }
        break;

    case 'admin/partnerships':
    case 'admin/partners':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getPartnerById($conn, (int)$id);
                if (!$item) apiError('Partner not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getPartners($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = savePartnerRecord($conn, $input);
                apiSuccess(['id' => $id], 'Partner saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing partner ID for deletion.', 400);
            deletePartnerRecord($conn, $id);
            apiSuccess([], 'Partner deleted successfully.');
        }
        break;

    case 'admin/volunteer-opportunities':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getVolunteerOpportunityById($conn, (int)$id);
                if (!$item) apiError('Volunteer opportunity not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getVolunteerOpportunities($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveVolunteerOpportunityRecord($conn, $input);
                apiSuccess(['id' => $id], 'Volunteer opportunity saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing volunteer opportunity ID for deletion.', 400);
            deleteVolunteerOpportunityRecord($conn, $id);
            apiSuccess([], 'Volunteer opportunity deleted successfully.');
        }
        break;

    case 'admin/volunteer-applications':
        requireAdminAuth();
        if ($method === 'GET') {
            $oppId = $_GET['opportunity_id'] ?? null;
            $status = $_GET['status'] ?? null;
            apiSuccess(getVolunteerApplications($conn, $oppId, $status));
        } elseif ($method === 'POST') {
            $id = (int)($input['id'] ?? 0);
            $status = $input['status'] ?? '';
            if (!$id || !$status) apiError('Missing application ID or status.', 400);
            try {
                updateVolunteerApplicationStatus($conn, $id, $status);
                apiSuccess([], 'Volunteer application status updated.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing application ID for deletion.', 400);
            deleteVolunteerApplicationRecord($conn, $id);
            apiSuccess([], 'Volunteer application deleted successfully.');
        }
        break;

    case 'admin/our-story':
    case 'admin/story':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getStoryMilestoneById($conn, (int)$id);
                if (!$item) apiError('Milestone not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getOurStory($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveStoryMilestoneRecord($conn, $input);
                apiSuccess(['id' => $id], 'Story milestone saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing milestone ID for deletion.', 400);
            deleteStoryMilestoneRecord($conn, $id);
            apiSuccess([], 'Milestone deleted successfully.');
        }
        break;

    case 'admin/core-values':
    case 'admin/values':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getCoreValueById($conn, (int)$id);
                if (!$item) apiError('Core value not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getCoreValues($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveCoreValueRecord($conn, $input);
                apiSuccess(['id' => $id], 'Core value saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing core value ID for deletion.', 400);
            deleteCoreValueRecord($conn, $id);
            apiSuccess([], 'Core value deleted successfully.');
        }
        break;

    case 'admin/achievements':
    case 'admin/impact':
        requireAdminAuth();
        if ($method === 'GET') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                $item = getAchievementById($conn, (int)$id);
                if (!$item) apiError('Achievement not found.', 404);
                apiSuccess($item);
            }
            apiSuccess(getAchievements($conn, false));
        } elseif ($method === 'POST') {
            try {
                $id = saveAchievementRecord($conn, $input);
                apiSuccess(['id' => $id], 'Achievement saved successfully.');
            } catch (InvalidArgumentException $e) {
                apiError($e->getMessage(), 422);
            } catch (Throwable $e) {
                apiError($e->getMessage(), 500);
            }
        } elseif ($method === 'DELETE') {
            $id = (int)($_GET['id'] ?? ($input['id'] ?? 0));
            if (!$id) apiError('Missing achievement ID for deletion.', 400);
            deleteAchievementRecord($conn, $id);
            apiSuccess([], 'Achievement deleted successfully.');
        }
        break;

    default:
        apiError("Invalid API endpoint: $endpoint", 404);
}

