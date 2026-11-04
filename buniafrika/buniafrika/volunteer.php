<?php
/**
 * Public Volunteer Opportunities and Application Portal
 */
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

sendCorsHeaders();

$opportunities = getVolunteerOpportunities($conn, true);
$selected_opp_id = isset($_GET['opportunity']) ? (int)$_GET['opportunity'] : 0;

$form_success = '';
$form_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_volunteer') {
    $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false);

    $rateCheck = checkRateLimit($conn, 'volunteer_application', 3, 1800, 900);
    if (!$rateCheck['allowed']) {
        $retryAfter = $rateCheck['retry_after'] ?? 60;
        if ($isAjax) {
            http_response_code(429);
            header('Retry-After: ' . $retryAfter);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'status' => 'error',
                'code' => 'RATE_LIMIT_EXCEEDED',
                'retry_after' => $retryAfter,
                'message' => "Too many attempts from your network. Please wait {$retryAfter} seconds before trying again."
            ]);
            exit;
        }
        $form_error = "Too many attempts. Please wait {$retryAfter} seconds before submitting again.";
    }

    $csrf = $_POST['csrf_token'] ?? '';
    if (empty($form_error) && !verifyCsrfToken($csrf)) {
        if ($isAjax) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['status' => 'error', 'message' => 'Invalid submission security token. Please refresh and try again.']);
            exit;
        }
        $form_error = 'Invalid submission security token. Please refresh and try again.';
    } elseif (empty($form_error)) {
        $data = [
            'opportunity_id' => !empty($_POST['opportunity_id']) ? (int)$_POST['opportunity_id'] : null,
            'applicant_name' => trim($_POST['applicant_name'] ?? ''),
            'applicant_email' => trim($_POST['applicant_email'] ?? ''),
            'applicant_phone' => trim($_POST['applicant_phone'] ?? ''),
            'skills' => trim($_POST['skills'] ?? ''),
            'motivation' => trim($_POST['motivation'] ?? ''),
        ];

        if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
            $resume = $_FILES['resume'];
            $maxBytes = 10 * 1024 * 1024; // 10MB
            $ext = strtolower(pathinfo($resume['name'], PATHINFO_EXTENSION));
            if ($resume['size'] <= $maxBytes && in_array($ext, ['pdf', 'doc', 'docx'], true)) {
                $uploadDir = __DIR__ . '/assets/uploads/volunteers';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $safeName = 'cv_' . bin2hex(random_bytes(16)) . '.' . $ext;
                $target = $uploadDir . '/' . $safeName;
                if (move_uploaded_file($resume['tmp_name'], $target)) {
                    $data['resume_path'] = 'assets/uploads/volunteers/' . $safeName;
                }
            }
        }

        try {
            $appId = submitVolunteerApplication($conn, $data);
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Thank you! Your volunteer application has been submitted successfully. Our team will review your details and be in touch soon.',
                    'application_id' => $appId
                ]);
                exit;
            }
            $form_success = 'Thank you! Your volunteer application has been submitted successfully. Our team will review your details and be in touch soon.';
        } catch (Throwable $e) {
            $errMessage = $e->getMessage() ?: 'Unable to submit application. Please verify the required fields.';
            if ($isAjax) {
                http_response_code(422);
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode(['status' => 'error', 'message' => $errMessage]);
                exit;
            }
            $form_error = $errMessage;
        }
    }
}

$page_title = 'Volunteer & Get Involved | BuniAfrika CBO';
$meta_desc = 'Join BuniAfrika as a volunteer or community fellow to advance civic technology, digital rights, and African heritage.';
require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag" style="color: var(--secondary-container);">Community Engagement</p>
        <h1>Volunteer with BuniAfrika</h1>
        <p class="lead">Contribute your skills, enthusiasm, and perspective to projects advancing open democracy, community research, and cultural heritage.</p>
    </div>
</section>

<section class="section container">
    <div class="section-header">
        <p class="section-tag">Open callouts</p>
        <h2 class="section-title">Current Opportunities</h2>
        <p class="section-desc">Explore active roles for researchers, community organizers, coders, and cultural knowledge custodians.</p>
    </div>

    <?php if ($form_success): ?>
        <div style="background: #e6f4ea; color: #137333; padding: 18px 24px; border-radius: var(--radius-lg); margin-bottom: 32px; border: 1px solid #ceead6; display: flex; align-items: center; gap: 12px;">
            <span class="material-symbols-outlined" style="font-size: 28px;">check_circle</span>
            <p style="margin: 0; font-size: 15px; font-weight: 600;"><?php echo h($form_success); ?></p>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.BuniAlert) {
                window.BuniAlert.success('Application Submitted', <?php echo json_encode($form_success); ?>);
            }
        });
        </script>
    <?php endif; ?>

    <?php if ($form_error): ?>
        <div style="background: #fce8e6; color: #c5221f; padding: 18px 24px; border-radius: var(--radius-lg); margin-bottom: 32px; border: 1px solid #fad2cf; display: flex; align-items: center; gap: 12px;">
            <span class="material-symbols-outlined" style="font-size: 28px;">error</span>
            <p style="margin: 0; font-size: 15px; font-weight: 600;"><?php echo h($form_error); ?></p>
        </div>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.BuniAlert) {
                window.BuniAlert.error('Submission Issue', <?php echo json_encode($form_error); ?>);
            }
        });
        </script>
    <?php endif; ?>

    <!-- Opportunities Grid -->
    <?php if (!empty($opportunities)): ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px; margin-bottom: 48px;">
            <?php foreach ($opportunities as $opp): ?>
                <article class="bento-card" style="display: flex; flex-direction: column; justify-content: space-between; padding: 24px;">
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 8px; margin-bottom: 12px;">
                            <span class="badge badge-info"><?php echo h($opp['department']); ?></span>
                            <span class="badge" style="background: var(--surface-variant); color: var(--on-surface);">
                                <?php echo (int)$opp['slots']; ?> <?php echo (int)$opp['slots'] === 1 ? 'slot' : 'slots'; ?>
                            </span>
                        </div>

                        <h3 style="font-size: 18px; color: var(--primary); margin: 0 0 8px 0;"><?php echo h($opp['title']); ?></h3>

                        <div style="display: flex; flex-wrap: wrap; gap: 12px; font-size: 12px; color: var(--on-surface-variant); margin-bottom: 14px;">
                            <span style="display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-symbols-outlined" style="font-size: 14px;">location_on</span>
                                <?php echo h($opp['location']); ?>
                            </span>
                            <span style="display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-symbols-outlined" style="font-size: 14px;">schedule</span>
                                <?php echo h($opp['commitment_type']); ?>
                            </span>
                            <?php if (!empty($opp['deadline'])): ?>
                                <span style="display: inline-flex; align-items: center; gap: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">event</span>
                                    Deadline: <?php echo date('M d, Y', strtotime($opp['deadline'])); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <p class="section-desc" style="font-size: 14px; margin: 0 0 16px 0; line-height: 1.55;">
                            <?php echo h($opp['description']); ?>
                        </p>

                        <?php if (!empty($opp['requirements'])): ?>
                            <div style="background: var(--surface-variant); padding: 12px 14px; border-radius: var(--radius-md); margin-bottom: 20px;">
                                <strong style="font-size: 12px; color: var(--primary); display: block; margin-bottom: 4px;">Requirements:</strong>
                                <p style="font-size: 12px; color: var(--on-surface-variant); margin: 0; line-height: 1.45;">
                                    <?php echo nl2br(h($opp['requirements'])); ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <a href="#volunteer-application-form" onclick="selectOpportunity(<?php echo (int)$opp['id']; ?>, '<?php echo addslashes(h($opp['title'])); ?>')" class="btn btn-outline" style="align-self: flex-start; padding: 8px 18px; font-size: 13px;">
                        Apply for this Role &rarr;
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state" style="text-align: center; padding: 48px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); max-width: 600px; margin: 0 auto 48px auto;">
            <span class="material-symbols-outlined" style="font-size: 48px; color: var(--on-surface-variant); margin-bottom: 8px; display: block;" aria-hidden="true">volunteer_activism</span>
            <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">No Specific Vacancies Posted</h3>
            <p style="color: var(--on-surface-variant); font-size: 14px; max-width: 480px; margin: 0 auto; line-height: 1.5;">Specific role listings are currently unavailable, but we warmly welcome proactive community collaborators. You can submit a general application using the form below!</p>
        </div>
    <?php endif; ?>

    <!-- Application Form Container -->
    <div id="volunteer-application-form" style="background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); padding: 36px 32px; box-shadow: var(--shadow-sm); max-width: 760px; margin: 0 auto;">
        <h2 style="font-size: 22px; color: var(--primary); margin: 0 0 8px 0;">Volunteer Application Form</h2>
        <p style="font-size: 14px; color: var(--on-surface-variant); margin: 0 0 24px 0;">Tell us about yourself, your background, and how you would like to collaborate with BuniAfrika.</p>

        <form method="POST" action="volunteer.php#volunteer-application-form" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
            <input type="hidden" name="action" value="submit_volunteer">

            <div style="display: flex; flex-direction: column; gap: 20px;">
                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label" for="app-opp" style="font-weight: 600; font-size: 13px;">Role of Interest</label>
                    <select id="app-opp" name="opportunity_id" class="form-control" style="padding: 12px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); font-size: 14px;">
                        <option value="">General Volunteer Pool (All Areas)</option>
                        <?php foreach ($opportunities as $op): ?>
                            <option value="<?php echo (int)$op['id']; ?>" <?php echo $selected_opp_id === (int)$op['id'] ? 'selected' : ''; ?>>
                                <?php echo h($op['title']); ?> (<?php echo h($op['department']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="form-label" for="app-name" style="font-weight: 600; font-size: 13px;">Full Name <span style="color: var(--error);">*</span></label>
                        <input type="text" id="app-name" name="applicant_name" class="form-control" required style="padding: 12px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); font-size: 14px;" placeholder="e.g. Zena Omar">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="form-label" for="app-email" style="font-weight: 600; font-size: 13px;">Email Address <span style="color: var(--error);">*</span></label>
                        <input type="email" id="app-email" name="applicant_email" class="form-control" required style="padding: 12px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); font-size: 14px;" placeholder="name@example.org">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="form-label" for="app-phone" style="font-weight: 600; font-size: 13px;">Phone Number</label>
                        <input type="tel" id="app-phone" name="applicant_phone" class="form-control" style="padding: 12px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); font-size: 14px;" placeholder="+254 700 000 000">
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="form-label" for="app-skills" style="font-weight: 600; font-size: 13px;">Key Skills &amp; Focus</label>
                        <input type="text" id="app-skills" name="skills" class="form-control" style="padding: 12px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); font-size: 14px;" placeholder="e.g. Research, Python, Translation, Social Media">
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label" for="app-motivation" style="font-weight: 600; font-size: 13px;">Motivation &amp; Community Interests <span style="color: var(--error);">*</span></label>
                    <textarea id="app-motivation" name="motivation" class="form-control" rows="4" required style="padding: 12px; border-radius: var(--radius-lg); border: 1px solid var(--outline-variant); background: var(--background); color: var(--on-background); font-size: 14px;" placeholder="Share why you would like to volunteer with BuniAfrika and what you hope to achieve or learn..."></textarea>
                </div>

                <div style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label" for="app-resume" style="font-weight: 600; font-size: 13px;">CV / Resume (Optional, PDF or DOCX up to 10MB)</label>
                    <input type="file" id="app-resume" name="resume" accept=".pdf,.doc,.docx" class="form-control" style="padding: 10px; font-size: 13px;">
                </div>

                <button type="submit" class="btn btn-primary" style="padding: 14px 28px; font-size: 15px; margin-top: 8px;">
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</section>

<script>
function selectOpportunity(id, title) {
    const select = document.getElementById('app-opp');
    if (select) {
        select.value = id;
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

