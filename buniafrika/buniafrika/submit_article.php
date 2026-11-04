<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth_helper.php';

sendCorsHeaders();

if (!isset($conn)) {
    require_once __DIR__ . '/includes/db_connection.php';
}
$site_settings = getSiteSettings($conn);

$error = '';
$success = '';

if (isset($_GET['submitted']) && $_GET['submitted'] == 1) {
    $success = 'Thank you! Your article has been submitted successfully. It has been sent to our editorial team for review and approval.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    $rateCheck = checkRateLimit($conn, 'submit_article', 3, 1800, 900);
    if (!$rateCheck['allowed']) {
        $error = 'Rate limit exceeded. Please wait ' . ($rateCheck['retry_after'] ?? 60) . ' seconds before submitting another article.';
        http_response_code(429);
    } elseif (!verifyCsrfToken($csrf_token)) {
        $error = 'Security check failed. Please refresh the page and try again.';
        http_response_code(403);
    } elseif (!empty($_POST['company_website'] ?? '')) {
        $error = 'The submission could not be accepted.';
        http_response_code(400);
    } else {
        if (empty($error)) {
            $title = trim($_POST['title'] ?? '');
            $category = trim($_POST['category'] ?? '');
            $summary = trim($_POST['summary'] ?? '');
            $content = safeHtml(trim($_POST['content'] ?? ''));
            $author = trim($_POST['author'] ?? '');

            if (empty($author)) {
                $author = 'Guest Contributor';
            }

            // Basic Validations
            if (strlen($title) < 5) {
                $error = 'The article title is too short (must be at least 5 characters).';
            } elseif (strlen($title) > 255) {
                $error = 'The article title is too long.';
            } elseif (!in_array($category, [$site_settings['civictech_title'], $site_settings['heritage_title']], true)) {
                $error = 'Select one of the listed thematic areas.';
            } elseif (strlen($summary) < 10 || strlen($summary) > 500) {
                $error = 'Please enter a summary of at least 10 characters.';
            } elseif (strlen($author) > 100 || strlen($content) > 60000) {
                $error = 'The author name or article content is too long.';
            } elseif (empty($content) || $content === '<p><br></p>') {
                $error = 'The article body content cannot be empty.';
            } else {
                // Handle optional image upload
                $image_url = null;
                if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
                    require_once __DIR__ . '/includes/image_helper.php';
                    $upload_result = secureImageUploadAndResize($_FILES['image_file'], __DIR__ . '/assets/uploads', 'news');
                    if ($upload_result['status'] === 'success') {
                        $image_url = $upload_result['resized_path'];
                    } else {
                        $error = $upload_result['message'];
                    }
                }

                if (empty($error)) {
                    // Get or create category
                    $category_id = getOrCreateCategoryId($conn, $category);

                    if (!$category_id) {
                        $error = 'Failed to process the article category.';
                    } else {
                        // Insert into news_articles as published = 0 (Unpublished / Pending)
                        $stmt = $conn->prepare("INSERT INTO news_articles (title, category_id, category, summary, content, image_url, author, published_date, published) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(), 0)");
                        if ($stmt) {
                            $stmt->bind_param("sisssss", $title, $category_id, $category, $summary, $content, $image_url, $author);
                            if ($stmt->execute()) {
                                // Log the activity
                                logActivity($conn, 'Guest Article Submission', 'Guest submitted an article draft for editorial review.', 'user', null, 'Website visitor');

                                // Send Admin Alert Email
                                require_once __DIR__ . '/includes/mailer.php';
                                $mailSubject = "New Guest Article Pending Approval: " . $title;
                                $mailBody = "<h3>A new article has been submitted via the public portal and requires review.</h3>" .
                                            "<p><strong>Title:</strong> " . h($title) . "</p>" .
                                            "<p><strong>Author:</strong> " . h($author) . "</p>" .
                                            "<p><strong>Category:</strong> " . h($category) . "</p>" .
                                            "<p><strong>Summary:</strong> " . h($summary) . "</p>" .
                                            "<p>Please log in to the <a href='https://buniafrika.co.ke/admin/admin_dashboard.php?tab=news'>BuniAfrika admin dashboard</a> to review and publish this submission.</p>";
                                sendAdminEmail($mailSubject, $mailBody);

                                // Redirect to success screen
                                header("Location: submit_article.php?submitted=1");
                                exit;
                            } else {
                                $error = 'Failed to save submission. Please contact our system administrator.';
                            }
                            $stmt->close();
                        } else {
                            $error = 'Database preparation error. Failed to execute query.';
                        }
                    }
                }
            }
        }
    }
}

$dynamic_thematic = getThematicAreas($conn, true);
$categories = [];
if (!empty($dynamic_thematic)) {
    foreach ($dynamic_thematic as $ta) {
        $categories[] = $ta['title'];
    }
}
if (empty($categories)) {
    $categories = array_values(array_filter([$site_settings['civictech_title'] ?? '', $site_settings['heritage_title'] ?? '']));
}

$page_title = 'Submit an Article | BuniAfrika CBO';
$meta_desc = 'Submit a community article draft related to BuniAfrika’s two thematic areas for editorial review.';
require_once __DIR__ . '/includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
     <img class="hero-bg" style="opacity: 0.7" src="<?php echo $base_path; ?>assets/rahma.jpeg" alt="Office building" />
    <div class="hero-content">
        <span class="hero-tag">Community Submissions</span>
        <h1 class="hero-title poppins-bold">Submit Your Article</h1>
        <p class="hero-desc poppins-medium">Do you have a story, report, or update on community impact? Share it with us! All articles are reviewed by our team before publication.</p>
    </div>
</section>

<!-- Submission Container -->
<section class="section container" style="max-width: 800px;">
    <div class="bento-card" style="box-shadow: var(--shadow-lg); padding: var(--spacing-xl); background-color: var(--surface);">
        <h2 class="mb-xs poppins-bold" style="font-size: 24px; color: var(--primary);">Article Details</h2>
        <p class="section-desc mb-xl" style="font-size: 14px; color: var(--on-surface-variant);">Share factual, respectful, community-focused ideas related to <?php echo h($site_settings['civictech_title']); ?> or <?php echo h($site_settings['heritage_title']); ?>.</p>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success mb-lg" style="border-left: 4px solid var(--secondary); background-color: var(--secondary-container); color: var(--on-secondary-container); padding: 16px; border-radius: var(--radius-lg); font-weight: 500;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 24px; color: var(--secondary);">check_circle</span>
                    <span><?php echo h($success); ?></span>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.BuniAlert) {
                    window.BuniAlert.success('Article Submitted', <?php echo json_encode($success); ?>);
                }
            });
            </script>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mb-lg" style="border-left: 4px solid var(--error); background-color: var(--error-container); color: var(--error); padding: 16px; border-radius: var(--radius-lg); font-weight: 500;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 24px;">error</span>
                    <span><?php echo h($error); ?></span>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.BuniAlert) {
                    window.BuniAlert.error('Submission Issue', <?php echo json_encode($error); ?>);
                }
            });
            </script>
        <?php endif; ?>

        <?php if (empty($success) || !empty($error)): ?>
            <form id="submit-article-form" method="POST" action="submit_article.php" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: var(--spacing-md);">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">

                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label" for="title" style="font-weight: 700; color: var(--primary);">Article Title <span style="color: var(--error);">*</span></label>
                    <input class="form-control" type="text" id="title" name="title" value="<?php echo h($_POST['title'] ?? ''); ?>" required placeholder="e.g. Preserving Our Heritage Through Youth Engagement" style="padding: 12px; border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); background: var(--background); color: var(--on-background);" />
                </div>

                <div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-md);">
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="form-label" for="category-select" style="font-weight: 700; color: var(--primary);">Category <span style="color: var(--error);">*</span></label>
                        <select class="form-control" id="category-select" name="category" required style="padding: 12px; border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); background: var(--background); color: var(--on-background); cursor: pointer;">
                            <option value="" disabled <?php echo empty($_POST['category']) ? 'selected' : ''; ?>>Select a thematic area...</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo h($cat); ?>" <?php echo (($_POST['category'] ?? '') === $cat) ? 'selected' : ''; ?>><?php echo h($cat); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div hidden aria-hidden="true" style="position:absolute; left:-10000px;">
                        <label for="article_company_website">Leave this field empty</label>
                        <input type="text" id="article_company_website" name="company_website" tabindex="-1" autocomplete="off">
                    </div>
                    <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                        <label class="form-label" for="author" style="font-weight: 700; color: var(--primary);">Your Name / Author</label>
                        <input class="form-control" type="text" id="author" name="author" maxlength="100" value="<?php echo h($_POST['author'] ?? ''); ?>" placeholder="Anonymous or your name" style="padding: 12px; border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); background: var(--background); color: var(--on-background);" />
                    </div>
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label" for="summary" style="font-weight: 700; color: var(--primary);">Short Summary <span style="color: var(--error);">*</span></label>
                    <input class="form-control" type="text" id="summary" name="summary" maxlength="500" value="<?php echo h($_POST['summary'] ?? ''); ?>" required placeholder="A brief summary of the article..." style="padding: 12px; border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); background: var(--background); color: var(--on-background);" />
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label" style="font-weight: 700; color: var(--primary);">Cover Image</label>
                    <div id="image-upload-wrapper" style="border: 2px dashed var(--outline-variant); border-radius: var(--radius-xl); padding: var(--spacing-lg); text-align: center; background: var(--background); cursor: pointer; transition: border-color 0.2s, background-color 0.2s;" onclick="document.getElementById('image_file').click()">
                        <span class="material-symbols-outlined" style="font-size: 48px; color: var(--outline); margin-bottom: 8px;">image</span>
                        <p style="font-size: 14px; font-weight: 600; color: var(--on-surface-variant); margin-bottom: 4px;">Drag and drop or click to upload cover image</p>
                        <p style="font-size: 11px; color: var(--outline);">Supports JPG, PNG, and WebP. Maximum size 10MB.</p>
                        <input type="file" id="image_file" name="image_file" accept="image/*" style="display: none;" onchange="previewSelectedImage(this)" />
                        <div id="image-preview-container" style="display: none; margin-top: 16px; position: relative; border-radius: var(--radius-lg); overflow: hidden;">
                            <img id="image-preview" src="#" alt="Preview" style="max-height: 250px; max-width: 100%; object-fit: contain; border-radius: var(--radius-lg);" />
                            <button type="button" class="btn btn-outline" style="position: absolute; top: 12px; right: 12px; padding: 6px 12px; background: var(--surface); color: var(--error); border-color: var(--error); font-size: 11px;" onclick="removeSelectedImage(event)">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; gap: 6px;">
                    <label class="form-label" for="content" style="font-weight: 700; color: var(--primary);">Article Body Content <span style="color: var(--error);">*</span></label>
                    <textarea id="content" name="content" style="display: none;"><?php echo h(safeHtml($_POST['content'] ?? '')); ?></textarea>
                    <div id="news-content-editor" style="min-height: 300px; border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); background: var(--surface); color: var(--on-background);"></div>
                </div>

                <button type="submit" class="btn btn-primary w-full" style="padding: 14px 28px; font-size: 14px; border-radius: var(--radius-full); margin-top: var(--spacing-sm);">
                    Submit Draft for Review
                </button>
            </form>
        <?php else: ?>
            <div style="text-align: center; margin-top: var(--spacing-lg);">
                <a href="news.php" class="btn btn-primary" style="margin-right: 12px;">Browse Field Stories</a>
                <a href="submit_article.php" class="btn btn-outline">Submit Another Article</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quill Text Editor Assets & Logic -->
<link href="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>

<script>
function previewSelectedImage(input) {
    const wrapper = document.getElementById('image-upload-wrapper');
    const container = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
            wrapper.style.borderColor = 'var(--secondary)';
            wrapper.style.backgroundColor = 'rgba(59, 105, 52, 0.02)';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeSelectedImage(event) {
    event.stopPropagation();
    const input = document.getElementById('image_file');
    const wrapper = document.getElementById('image-upload-wrapper');
    const container = document.getElementById('image-preview-container');
    const preview = document.getElementById('image-preview');

    input.value = '';
    preview.src = '#';
    container.style.display = 'none';
    wrapper.style.borderColor = 'var(--outline-variant)';
    wrapper.style.backgroundColor = 'var(--background)';
}

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

    const newsEditorEl = document.getElementById('news-content-editor');
    if (newsEditorEl) {
        const newsTextarea = document.getElementById('content');
        const newsQuill = new Quill('#news-content-editor', {
            theme: 'snow',
            modules: { toolbar: toolbarOptions },
            placeholder: 'Write your full article body text here...'
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

<?php
require_once __DIR__ . '/includes/footer.php';
?>
