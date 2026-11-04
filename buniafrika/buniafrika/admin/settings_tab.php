<?php
if (!defined('LOC_PROJECTS')) {
    exit('Direct access not permitted.');
}

$admins_res = $conn->query("SELECT id, username, email, created_at FROM admins ORDER BY created_at ASC");
$admins = [];
if ($admins_res && $admins_res->num_rows > 0) {
    while ($row = $admins_res->fetch_assoc()) {
        $admins[] = $row;
    }
}
$site_settings = getSiteSettings($conn);
$leadership = getLeadershipProfiles($site_settings, true);

// Load existing thematic areas, or migrate the two existing areas
// into the new dynamic format without losing their content.
$thematic_areas = [];

if (!empty($site_settings['thematic_areas'])) {
    $decoded_areas = json_decode($site_settings['thematic_areas'], true);

    if (is_array($decoded_areas)) {
        $thematic_areas = $decoded_areas;
    }
}

if (empty($thematic_areas)) {
    $thematic_areas = [
        [
            'title' => $site_settings['civictech_title'] ?? 'CivicTech Awareness',
            'intro' => $site_settings['civictech_intro'] ?? '',
            'points' => preg_split('/\r\n|\r|\n/', $site_settings['civictech_points'] ?? ''),
        ],
        [
            'title' => $site_settings['heritage_title'] ?? 'Culture and Heritage',
            'intro' => $site_settings['heritage_intro'] ?? '',
            'points' => preg_split('/\r\n|\r|\n/', $site_settings['heritage_points'] ?? ''),
        ],
    ];
}
$settings_groups = [
    'Organisation and contact' => [
        'site_name' => ['Organisation name', 'text'],
        'website_url' => ['Website URL', 'url'],
        'general_phone' => ['Public phone number', 'tel'],
        'whatsapp_number' => ['WhatsApp number with country code, digits only', 'tel'],
        'general_email' => ['General enquiries email', 'email'],
        'partnership_email' => ['Partnerships email', 'email'],
        'finance_email' => ['Donations and finance email', 'email'],
        'location_text' => ['Location', 'text'],
        'location_map_url' => ['Google Maps URL', 'url'],
    ],
    'Social media' => [
        'social_facebook' => ['Facebook URL', 'url'],
        'social_instagram' => ['Instagram URL', 'url'],
        'social_linkedin' => ['LinkedIn URL', 'url'],
        'social_youtube' => ['YouTube URL', 'url'],
        'social_x' => ['X URL', 'url'],
        'social_tiktok' => ['TikTok URL', 'url'],
        'social_whatsapp_channel' => ['WhatsApp Channel URL (optional)', 'url'],
    ],
    'Vision, mission and objectives' => [
        'vision' => ['Vision', 'textarea'],
        'mission' => ['Mission', 'textarea'],
        'objectives' => ['Objectives (one per line)', 'textarea'],
    ],
   
];
?>
<h2 style="font-size: 20px; margin-bottom: var(--spacing-md); color: var(--primary);">System Settings</h2>
<div style="display: grid; grid-template-columns: 1fr; gap: var(--spacing-lg);">

    <div class="bento-card site-settings-panel">
        <div>
            <h3 class="site-settings-heading"><span class="material-symbols-outlined" aria-hidden="true">tune</span>Website settings</h3>
            <p class="site-settings-intro">These values are shared with public pages, contact details, the footer, search metadata, and leadership cards. Personal roles, portraits, and contact details should be added only after verification.</p>
        </div>
        <?php if (empty($GLOBALS['site_settings_available'])): ?>
            <p class="alert alert-danger" role="alert">The site settings table is not installed. Apply the additive migration at <code>database/migrations/20261020_create_site_settings.sql</code> before saving settings.</p>
        <?php endif; ?>
        <?php if (!empty($session_error) && $current_tab === 'settings'): ?>
            <p class="alert alert-danger" role="alert"><?php echo h($session_error); ?></p>
        <?php elseif (!empty($session_success) && $current_tab === 'settings'): ?>
            <p class="alert alert-success" role="status"><?php echo h($session_success); ?></p>
        <?php endif; ?>
        <style>
            .site-settings-section-form {
                margin-bottom: var(--spacing-lg);
            }
            .site-settings-section-form:last-of-type {
                margin-bottom: 0;
            }
            .settings-feedback:empty {
                display: none;
            }
            .settings-feedback .alert {
                margin-bottom: var(--spacing-sm);
                padding: 10px 14px;
                border-radius: var(--radius-md);
                font-size: 14px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            .spin {
                display: inline-block;
                animation: spin 1s linear infinite;
            }
        </style>

        <!-- 1. Organisation and Contact -->
        <form method="post" action="admin_dashboard.php?tab=settings" class="site-settings-form site-settings-section-form" id="form-settings-organization">
            <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
            <input type="hidden" name="action" value="save_settings_organization">
            <div class="settings-feedback" id="feedback-settings-organization" role="status" aria-live="polite"></div>
            <fieldset class="site-settings-group" id="section-organization">
                <legend>Organisation and contact</legend>
                <div class="site-settings-fields">
                    <?php foreach ($settings_groups['Organisation and contact'] as $key => [$label, $type]): ?>
                        <label class="site-setting-field">
                            <span><?php echo h($label); ?></span>
                            <?php if ($type === 'textarea'): ?>
                                <textarea class="form-control" name="site_settings[<?php echo h($key); ?>]" rows="3" required><?php echo h($site_settings[$key]); ?></textarea>
                            <?php else: ?>
                                <input class="form-control" type="<?php echo h($type); ?>" name="site_settings[<?php echo h($key); ?>]" value="<?php echo h($site_settings[$key]); ?>" required>
                            <?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="settings-action-row" style="margin-top: var(--spacing-md); display: flex; justify-content: flex-end;">
                    <button class="btn btn-primary settings-submit-btn" type="submit">Save Organisation & Contact</button>
                </div>
            </fieldset>
        </form>

        <!-- 2. Social Media -->
        <form method="post" action="admin_dashboard.php?tab=settings" class="site-settings-form site-settings-section-form" id="form-settings-social">
            <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
            <input type="hidden" name="action" value="save_settings_social">
            <div class="settings-feedback" id="feedback-settings-social" role="status" aria-live="polite"></div>
            <fieldset class="site-settings-group" id="section-social">
                <legend>Social media</legend>
                <div class="site-settings-fields">
                    <?php foreach ($settings_groups['Social media'] as $key => [$label, $type]): ?>
                        <label class="site-setting-field">
                            <span><?php echo h($label); ?></span>
                            <input class="form-control" type="<?php echo h($type); ?>" name="site_settings[<?php echo h($key); ?>]" value="<?php echo h($site_settings[$key]); ?>" <?php echo $key === 'social_whatsapp_channel' ? '' : ''; ?>>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="settings-action-row" style="margin-top: var(--spacing-md); display: flex; justify-content: flex-end;">
                    <button class="btn btn-primary settings-submit-btn" type="submit">Save Social Media</button>
                </div>
            </fieldset>
        </form>

        <!-- 3. Vision, Mission and Objectives -->
        <form method="post" action="admin_dashboard.php?tab=settings" class="site-settings-form site-settings-section-form" id="form-settings-vision">
            <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
            <input type="hidden" name="action" value="save_settings_vision">
            <div class="settings-feedback" id="feedback-settings-vision" role="status" aria-live="polite"></div>
            <fieldset class="site-settings-group" id="section-vision">
                <legend>Vision, mission and objectives</legend>
                <div class="site-settings-fields">
                    <?php foreach ($settings_groups['Vision, mission and objectives'] as $key => [$label, $type]): ?>
                        <label class="site-setting-field">
                            <span><?php echo h($label); ?></span>
                            <textarea class="form-control" name="site_settings[<?php echo h($key); ?>]" rows="<?php echo $key === 'objectives' ? '6' : '3'; ?>" required><?php echo h($site_settings[$key]); ?></textarea>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="settings-action-row" style="margin-top: var(--spacing-md); display: flex; justify-content: flex-end;">
                    <button class="btn btn-primary settings-submit-btn" type="submit">Save Vision, Mission & Objectives</button>
                </div>
            </fieldset>
        </form>

        <!-- 4. Thematic Areas -->
        <form method="post" action="admin_dashboard.php?tab=settings" class="site-settings-form site-settings-section-form" id="form-settings-thematic">
            <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
            <input type="hidden" name="action" value="save_settings_thematic">
            <div class="settings-feedback" id="feedback-settings-thematic" role="status" aria-live="polite"></div>
            <fieldset class="site-settings-group" id="section-thematic">
                <legend>Thematic Areas</legend>

                <p class="site-settings-intro">
                    Manage the organisation's thematic areas. Add new areas, edit descriptions,
                    manage focus points, and change their display order.
                </p>

                <input
                    type="hidden"
                    name="site_settings[thematic_areas]"
                    id="thematic-areas-json"
                    value="<?php echo h(json_encode($thematic_areas, JSON_UNESCAPED_UNICODE)); ?>"
                >

                <div id="thematic-areas-list" class="leadership-settings-list">

                    <?php foreach ($thematic_areas as $index => $area): ?>
                        <fieldset class="leadership-settings-card thematic-area-card">

                            <legend><?php echo h($area['title'] ?? 'Thematic Area'); ?></legend>

                            <div class="leader-order-controls">
                                <span class="leader-order-label"></span>

                                <button type="button" class="btn btn-outline thematic-move-button" data-move="up">
                                    <span class="material-symbols-outlined">arrow_upward</span>
                                    Move earlier
                                </button>

                                <button type="button" class="btn btn-outline thematic-move-button" data-move="down">
                                    <span class="material-symbols-outlined">arrow_downward</span>
                                    Move later
                                </button>
                            </div>

                            <div class="leadership-settings-fields">

                                <label class="site-setting-field site-setting-field-full">
                                    <span>Thematic area title</span>
                                    <input class="form-control thematic-title"
                                           type="text"
                                           maxlength="180"
                                           value="<?php echo h($area['title'] ?? ''); ?>"
                                           required>
                                </label>

                                <label class="site-setting-field site-setting-field-full">
                                    <span>Introduction</span>
                                    <textarea class="form-control thematic-intro"
                                              rows="4"
                                              required><?php echo h($area['intro'] ?? ''); ?></textarea>
                                </label>

                                <div class="site-setting-field site-setting-field-full">
                                    <span>Focus areas</span>

                                    <div class="thematic-points-list">
                                        <?php foreach (($area['points'] ?? []) as $point): ?>
                                            <?php if (trim((string)$point) !== ''): ?>
                                                <div class="thematic-point-row">
                                                    <input class="form-control thematic-point"
                                                           type="text"
                                                           value="<?php echo h($point); ?>"
                                                           placeholder="Enter a focus area">

                                                    <button type="button"
                                                            class="btn btn-outline thematic-remove-point"
                                                            aria-label="Remove focus point">
                                                        <span class="material-symbols-outlined">delete</span>
                                                    </button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>

                                    <button type="button" class="btn btn-outline thematic-add-point">
                                        <span class="material-symbols-outlined">add</span>
                                        Add focus point
                                    </button>
                                </div>

                            </div>

                            <button type="button" class="btn btn-outline thematic-remove-area">
                                <span class="material-symbols-outlined">delete</span>
                                Remove thematic area
                            </button>

                        </fieldset>
                    <?php endforeach; ?>

                </div>

                <div style="margin-top: var(--spacing-sm); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <button type="button" class="btn btn-outline" id="add-thematic-area">
                        <span class="material-symbols-outlined">add_circle</span>
                        Add thematic area
                    </button>
                    <button class="btn btn-primary settings-submit-btn" type="submit">Save Thematic Areas</button>
                </div>
            </fieldset>
        </form>

        <!-- 5. Public Leadership Profiles -->
        <form method="post" action="admin_dashboard.php?tab=settings" class="site-settings-form site-settings-section-form" id="form-settings-leadership" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">
            <input type="hidden" name="action" value="save_settings_leadership">
            <div class="settings-feedback" id="feedback-settings-leadership" role="status" aria-live="polite"></div>
            <fieldset class="site-settings-group leadership-settings-group" id="section-leadership">
                <legend>Public leadership profiles</legend>
                <p class="site-settings-intro">Add or remove public profiles, upload portraits, and publish roles only after they have been verified.</p>
                <div id="leadership-profiles" class="leadership-settings-list">
                    <?php foreach ($leadership as $leader): ?>
                        <?php $leaderId = $leader['id']; ?>
                        <fieldset class="leadership-settings-card" data-leader-id="<?php echo h($leaderId); ?>">
                            <legend><?php echo h($leader['name']); ?></legend>
                            <div class="leader-order-controls" aria-label="Change display order">
                                <span class="leader-order-label"></span>
                                <button class="btn btn-outline leader-move-button" type="button" data-move="up" aria-label="Move leader earlier"><span class="material-symbols-outlined" aria-hidden="true">arrow_upward</span>Move earlier</button>
                                <button class="btn btn-outline leader-move-button" type="button" data-move="down" aria-label="Move leader later"><span class="material-symbols-outlined" aria-hidden="true">arrow_downward</span>Move later</button>
                            </div>
                            <div class="leadership-settings-fields">
                                <div class="leader-upload-preview">
                                    <?php if ($leader['image'] !== ''): ?>
                                        <img class="leader-upload-image" src="<?php echo h(getImageDisplayPath($leader['image'])); ?>" alt="Current portrait for <?php echo h($leader['name']); ?>">
                                    <?php else: ?>
                                        <div class="leader-upload-image leader-upload-placeholder" aria-hidden="true"><span class="material-symbols-outlined">person</span></div>
                                    <?php endif; ?>
                                </div>
                                <label class="site-setting-field">
                                    <span>Name</span>
                                    <input class="form-control" type="text" name="leaders[<?php echo h($leaderId); ?>][name]" value="<?php echo h($leader['name']); ?>" maxlength="150" required>
                                </label>
                                <label class="site-setting-field">
                                    <span>Public role/title (optional)</span>
                                    <input class="form-control" type="text" name="leaders[<?php echo h($leaderId); ?>][role]" value="<?php echo h($leader['role']); ?>" maxlength="150">
                                </label>
                                <label class="site-setting-field">
                                    <span>Role/title verified?</span>
                                    <select class="form-control" name="leaders[<?php echo h($leaderId); ?>][role_verified]" required>
                                        <option value="0" <?php echo $leader['role_verified'] === '0' ? 'selected' : ''; ?>>No - hide this role publicly</option>
                                        <option value="1" <?php echo $leader['role_verified'] === '1' ? 'selected' : ''; ?>>Yes - approved and verified</option>
                                    </select>
                                </label>
                                <label class="site-setting-field">
                                    <span>Display this profile publicly?</span>
                                    <select class="form-control" name="leaders[<?php echo h($leaderId); ?>][public_visible]" required>
                                        <option value="0" <?php echo $leader['public_visible'] === '0' ? 'selected' : ''; ?>>No - keep it in settings only</option>
                                        <option value="1" <?php echo $leader['public_visible'] === '1' ? 'selected' : ''; ?>>Yes - show on the website</option>
                                    </select>
                                </label>
                                <label class="site-setting-field">
                                    <span>Public phone (optional)</span>
                                    <input class="form-control" type="tel" name="leaders[<?php echo h($leaderId); ?>][phone]" value="<?php echo h($leader['phone']); ?>">
                                </label>
                                <label class="site-setting-field">
                                    <span>Public email (optional)</span>
                                    <input class="form-control" type="email" name="leaders[<?php echo h($leaderId); ?>][email]" value="<?php echo h($leader['email']); ?>">
                                </label>
                                <label class="site-setting-field">
                                    <span>Upload portrait (JPG, PNG, or WebP)</span>
                                    <input class="form-control leader-image-input" type="file" name="leader_images[<?php echo h($leaderId); ?>]" accept="image/jpeg,image/png,image/webp">
                                </label>
                                <label class="site-setting-field site-setting-field-full">
                                    <span>Full description (optional)</span>
                                    <textarea class="form-control" name="leaders[<?php echo h($leaderId); ?>][description]" rows="5" placeholder="Detailed background, biography, education, experience, and leadership profile..."><?php echo h($leader['description'] ?? ''); ?></textarea>
                                </label>
                                <input type="hidden" name="leaders[<?php echo h($leaderId); ?>][image]" value="<?php echo h($leader['image']); ?>">
                            </div>
                            <button class="btn btn-outline leader-remove-button" type="button">Remove leader</button>
                        </fieldset>
                    <?php endforeach; ?>
                </div>
                <div style="margin-top: var(--spacing-sm); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                    <button class="btn btn-outline" id="add-leader-button" type="button"><span class="material-symbols-outlined" aria-hidden="true">person_add</span>Add leader</button>
                    <button class="btn btn-primary settings-submit-btn" type="submit">Save Leadership Profiles</button>
                </div>
            </fieldset>
        </form>

        <script>
        (function () {
            function escapeHtml(text) {
                if (!text) return '';
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Leadership Profiles Management
            const list = document.getElementById('leadership-profiles');
            const addButton = document.getElementById('add-leader-button');

            function updateLeaderPositions() {
                if (!list) return;
                const cards = Array.from(list.querySelectorAll('.leadership-settings-card'));
                cards.forEach(function (card, index) {
                    const label = card.querySelector('.leader-order-label');
                    if (label) {
                        label.textContent = 'Display position: ' + (index + 1) + (index === 0 ? ' (first)' : index === cards.length - 1 ? ' (last)' : '');
                    }
                    const upBtn = card.querySelector('[data-move="up"]');
                    const downBtn = card.querySelector('[data-move="down"]');
                    if (upBtn) upBtn.disabled = index === 0;
                    if (downBtn) downBtn.disabled = index === cards.length - 1;
                });
            }

            if (list) {
                list.addEventListener('click', function (event) {
                    const removeButton = event.target.closest('.leader-remove-button');
                    if (removeButton) {
                        removeButton.closest('.leadership-settings-card').remove();
                        updateLeaderPositions();
                        return;
                    }
                    const moveButton = event.target.closest('.leader-move-button');
                    if (!moveButton) return;
                    const card = moveButton.closest('.leadership-settings-card');
                    if (moveButton.dataset.move === 'up' && card.previousElementSibling) {
                        list.insertBefore(card, card.previousElementSibling);
                    } else if (moveButton.dataset.move === 'down' && card.nextElementSibling) {
                        list.insertBefore(card.nextElementSibling, card);
                    }
                    updateLeaderPositions();
                });

                list.addEventListener('change', function (event) {
                    if (!event.target.matches('.leader-image-input') || !event.target.files[0]) return;
                    const reader = new FileReader();
                    reader.onload = function () {
                        const preview = event.target.closest('.leadership-settings-card').querySelector('.leader-upload-image');
                        if (!preview) return;
                        if (preview.tagName === 'IMG') {
                            preview.src = reader.result;
                            return;
                        }
                        const image = document.createElement('img');
                        image.className = 'leader-upload-image';
                        image.alt = 'Selected leader portrait preview';
                        image.src = reader.result;
                        preview.replaceWith(image);
                    };
                    reader.readAsDataURL(event.target.files[0]);
                });
            }

            if (addButton) {
                addButton.addEventListener('click', function () {
                    const id = 'custom_' + (window.crypto && crypto.randomUUID ? crypto.randomUUID().replace(/-/g, '') : Math.random().toString(36).slice(2));
                    const card = document.createElement('fieldset');
                    card.className = 'leadership-settings-card';
                    card.dataset.leaderId = id;
                    card.innerHTML = `<legend>New leader</legend>
                        <div class="leader-order-controls" aria-label="Change display order"><span class="leader-order-label"></span><button class="btn btn-outline leader-move-button" type="button" data-move="up" aria-label="Move leader earlier"><span class="material-symbols-outlined" aria-hidden="true">arrow_upward</span>Move earlier</button><button class="btn btn-outline leader-move-button" type="button" data-move="down" aria-label="Move leader later"><span class="material-symbols-outlined" aria-hidden="true">arrow_downward</span>Move later</button></div>
                        <div class="leadership-settings-fields">
                            <div class="leader-upload-preview"><div class="leader-upload-image leader-upload-placeholder" aria-hidden="true"><span class="material-symbols-outlined">person</span></div></div>
                            <label class="site-setting-field"><span>Name</span><input class="form-control" type="text" name="leaders[${id}][name]" maxlength="150" required></label>
                            <label class="site-setting-field"><span>Public role/title (optional)</span><input class="form-control" type="text" name="leaders[${id}][role]" maxlength="150"></label>
                            <label class="site-setting-field"><span>Role/title verified?</span><select class="form-control" name="leaders[${id}][role_verified]" required><option value="0" selected>No - hide this role publicly</option><option value="1">Yes - approved and verified</option></select></label>
                            <label class="site-setting-field"><span>Display this profile publicly?</span><select class="form-control" name="leaders[${id}][public_visible]" required><option value="0">No - keep it in settings only</option><option value="1" selected>Yes - show on the website</option></select></label>
                            <label class="site-setting-field"><span>Public phone (optional)</span><input class="form-control" type="tel" name="leaders[${id}][phone]"></label>
                            <label class="site-setting-field"><span>Public email (optional)</span><input class="form-control" type="email" name="leaders[${id}][email]"></label>
                            <label class="site-setting-field"><span>Upload portrait (JPG, PNG, or WebP)</span><input class="form-control leader-image-input" type="file" name="leader_images[${id}]" accept="image/jpeg,image/png,image/webp"></label>
                            <label class="site-setting-field site-setting-field-full"><span>Full description (optional)</span><textarea class="form-control" name="leaders[${id}][description]" rows="5" placeholder="Detailed background, biography, education, experience, and leadership profile..."></textarea></label>
                            <input type="hidden" name="leaders[${id}][image]" value="">
                        </div>
                        <button class="btn btn-outline leader-remove-button" type="button">Remove leader</button>`;
                    list.appendChild(card);
                    updateLeaderPositions();
                    const nameInput = card.querySelector('input[name^="leaders"][name$="[name]"]');
                    if (nameInput) nameInput.focus();
                });
            }

            updateLeaderPositions();

            // Thematic Areas Management
            const thematicList = document.getElementById('thematic-areas-list');
            const addThematicButton = document.getElementById('add-thematic-area');
            const thematicJson = document.getElementById('thematic-areas-json');

            function updateThematicPositions() {
                if (!thematicList) return;
                const cards = Array.from(thematicList.querySelectorAll('.thematic-area-card'));
                cards.forEach(function(card, index) {
                    const label = card.querySelector('.leader-order-label');
                    if (label) {
                        label.textContent = 'Display position: ' + (index + 1);
                    }
                    const upBtn = card.querySelector('[data-move="up"]');
                    const downBtn = card.querySelector('[data-move="down"]');
                    if (upBtn) upBtn.disabled = index === 0;
                    if (downBtn) downBtn.disabled = index === cards.length - 1;
                });
            }

            function addThematicPoint(container, value) {
                const val = typeof value === 'string' ? value : '';
                const row = document.createElement('div');
                row.className = 'thematic-point-row';

                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'form-control thematic-point';
                input.placeholder = 'Enter a focus area';
                input.value = val;

                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'btn btn-outline thematic-remove-point';
                remove.setAttribute('aria-label', 'Remove focus point');
                remove.innerHTML = '<span class="material-symbols-outlined">delete</span>';

                row.appendChild(input);
                row.appendChild(remove);
                container.appendChild(row);
            }

            function createThematicCard() {
                const card = document.createElement('fieldset');
                card.className = 'leadership-settings-card thematic-area-card';

                card.innerHTML = `
                    <legend>New thematic area</legend>

                    <div class="leader-order-controls">
                        <span class="leader-order-label"></span>
                        <button type="button" class="btn btn-outline thematic-move-button" data-move="up">
                            Move earlier
                        </button>
                        <button type="button" class="btn btn-outline thematic-move-button" data-move="down">
                            Move later
                        </button>
                    </div>

                    <div class="leadership-settings-fields">
                        <label class="site-setting-field site-setting-field-full">
                            <span>Thematic area title</span>
                            <input class="form-control thematic-title" type="text"
                                   maxlength="180" required>
                        </label>

                        <label class="site-setting-field site-setting-field-full">
                            <span>Introduction</span>
                            <textarea class="form-control thematic-intro"
                                      rows="4" required></textarea>
                        </label>

                        <div class="site-setting-field site-setting-field-full">
                            <span>Focus areas</span>
                            <div class="thematic-points-list"></div>
                            <button type="button" class="btn btn-outline thematic-add-point">
                                Add focus point
                            </button>
                        </div>
                    </div>

                    <button type="button" class="btn btn-outline thematic-remove-area">
                        Remove thematic area
                    </button>
                `;

                addThematicPoint(card.querySelector('.thematic-points-list'));
                return card;
            }

            if (thematicList) {
                thematicList.addEventListener('click', function(event) {
                    const removeArea = event.target.closest('.thematic-remove-area');
                    if (removeArea) {
                        removeArea.closest('.thematic-area-card').remove();
                        updateThematicPositions();
                        return;
                    }

                    const addPoint = event.target.closest('.thematic-add-point');
                    if (addPoint) {
                        addThematicPoint(addPoint.closest('.site-setting-field').querySelector('.thematic-points-list'));
                        return;
                    }

                    const removePoint = event.target.closest('.thematic-remove-point');
                    if (removePoint) {
                        removePoint.closest('.thematic-point-row').remove();
                        return;
                    }

                    const moveButton = event.target.closest('.thematic-move-button');
                    if (!moveButton) return;

                    const card = moveButton.closest('.thematic-area-card');
                    if (moveButton.dataset.move === 'up' && card.previousElementSibling) {
                        thematicList.insertBefore(card, card.previousElementSibling);
                    } else if (moveButton.dataset.move === 'down' && card.nextElementSibling) {
                        thematicList.insertBefore(card.nextElementSibling, card);
                    }

                    updateThematicPositions();
                });
            }

            if (addThematicButton && thematicList) {
                addThematicButton.addEventListener('click', function() {
                    const card = createThematicCard();
                    thematicList.appendChild(card);
                    updateThematicPositions();
                    const titleEl = card.querySelector('.thematic-title');
                    if (titleEl) titleEl.focus();
                });
            }

            updateThematicPositions();

            function serializeThematicAreas() {
                if (!thematicList || !thematicJson) return true;
                const cards = Array.from(thematicList.querySelectorAll('.thematic-area-card'));
                const feedback = document.getElementById('feedback-settings-thematic');

                if (cards.length === 0) {
                    const msg = 'Provide at least one thematic area.';
                    if (feedback) {
                        feedback.innerHTML = '<div class="alert alert-danger" role="alert"><span class="material-symbols-outlined" style="font-size: 18px;">error</span> ' + msg + '</div>';
                    } else {
                        alert(msg);
                    }
                    return false;
                }

                const areas = [];
                for (let i = 0; i < cards.length; i++) {
                    const card = cards[i];
                    const titleInput = card.querySelector('.thematic-title');
                    const introInput = card.querySelector('.thematic-intro');
                    const title = titleInput ? titleInput.value.trim() : '';
                    const intro = introInput ? introInput.value.trim() : '';

                    if (!title || !intro) {
                        const msg = 'Every thematic area must have a title and introduction.';
                        if (feedback) {
                            feedback.innerHTML = '<div class="alert alert-danger" role="alert"><span class="material-symbols-outlined" style="font-size: 18px;">error</span> ' + msg + '</div>';
                        } else {
                            alert(msg);
                        }
                        if (!title && titleInput) titleInput.focus();
                        else if (!intro && introInput) introInput.focus();
                        return false;
                    }

                    const points = Array.from(card.querySelectorAll('.thematic-point'))
                        .map(input => input.value.trim())
                        .filter(Boolean);

                    areas.push({
                        title: title,
                        intro: intro,
                        points: points
                    });
                }

                thematicJson.value = JSON.stringify(areas);
                return true;
            }

            // Independent AJAX Submissions for All 5 Categories
            const formsConfig = [
                {
                    formId: 'form-settings-organization',
                    feedbackId: 'feedback-settings-organization',
                    validate: null
                },
                {
                    formId: 'form-settings-social',
                    feedbackId: 'feedback-settings-social',
                    validate: null
                },
                {
                    formId: 'form-settings-vision',
                    feedbackId: 'feedback-settings-vision',
                    validate: null
                },
                {
                    formId: 'form-settings-thematic',
                    feedbackId: 'feedback-settings-thematic',
                    validate: serializeThematicAreas
                },
                {
                    formId: 'form-settings-leadership',
                    feedbackId: 'feedback-settings-leadership',
                    validate: null
                }
            ];

            formsConfig.forEach(function (cfg) {
                const form = document.getElementById(cfg.formId);
                const feedback = document.getElementById(cfg.feedbackId);
                if (!form || !feedback) return;

                form.addEventListener('submit', function (event) {
                    if (typeof cfg.validate === 'function') {
                        const isValid = cfg.validate();
                        if (!isValid) {
                            event.preventDefault();
                            return;
                        }
                    }

                    if (window.fetch && window.FormData) {
                        event.preventDefault();
                        const submitBtn = form.querySelector('.settings-submit-btn');
                        const origHtml = submitBtn ? submitBtn.innerHTML : '';
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.innerHTML = '<span class="material-symbols-outlined spin" style="font-size: 18px; vertical-align: middle;">sync</span> Saving...';
                        }
                        feedback.innerHTML = '';

                        const formData = new FormData(form);
                        formData.append('ajax', '1');

                        fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(function (res) {
                            return res.json().catch(function () {
                                return {
                                    status: res.ok ? 'success' : 'error',
                                    message: res.ok ? 'Settings saved successfully.' : 'The server encountered an error.'
                                };
                            });
                        })
                        .then(function (data) {
                            if (data.status === 'success') {
                                feedback.innerHTML = '<div class="alert alert-success" role="status"><span class="material-symbols-outlined" style="font-size: 18px;">check_circle</span> ' + escapeHtml(data.message) + '</div>';
                            } else {
                                feedback.innerHTML = '<div class="alert alert-danger" role="alert"><span class="material-symbols-outlined" style="font-size: 18px;">error</span> ' + escapeHtml(data.message || 'Settings could not be saved.') + '</div>';
                            }
                            feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        })
                        .catch(function (err) {
                            feedback.innerHTML = '<div class="alert alert-danger" role="alert"><span class="material-symbols-outlined" style="font-size: 18px;">error</span> A network error occurred while saving. Please try again.</div>';
                            feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        })
                        .finally(function () {
                            if (submitBtn) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = origHtml;
                            }
                        });
                    }
                });
            });
        }());
        </script>
    </div>

    <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md);">
        <div style="margin-bottom: var(--spacing-sm);">
            <h3 style="font-size: 18px; color: var(--primary); display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined">lock_reset</span>Password Management
            </h3>
            <p style="font-size: 14px; color: var(--on-surface-variant); margin-top: 8px;">
                Send a password reset link to the administrator email address.
            </p>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--outline-variant); margin: 16px 0;">

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <p style="font-size: 13px; color: var(--on-surface-variant); line-height: 1.5;">
                Clicking the button below will generate a secure password reset link and email it to the configured administrator email address.
            </p>
            
            <form method="POST" action="admin_dashboard.php">
                <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                <input type="hidden" name="action" value="send_reset_link">
                <button type="submit" class="btn" style="margin-top: 12px; padding: 12px 24px; background-color: var(--primary); color: #fff; border: none; font-weight: 600; cursor: pointer; align-self: flex-start; border-radius: var(--radius-lg); display: flex; align-items: center; gap: 8px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">mail</span>Send Reset Link
                </button>
            </form>
        </div>
    </div>

    <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md); display:none;">
        <div style="margin-bottom: var(--spacing-sm);">
            <h3 style="font-size: 18px; color: var(--error); display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined">warning</span>Danger Zone
            </h3>
            <p style="font-size: 14px; color: var(--on-surface-variant); margin-top: 8px;">
                Perform system-level destructive actions. Please proceed with caution.
            </p>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--outline-variant); margin: 16px 0;">

        <div style="display: flex; flex-direction: column; gap: 12px;">
            <h4 style="font-size: 15px; font-weight: 700;">Reset Database to Default Seed</h4>
            <p style="font-size: 13px; color: var(--on-surface-variant); line-height: 1.5;">
                This action will delete all existing data in the database, including:
            </p>
            <ul style="font-size: 13px; color: var(--on-surface-variant); padding-left: 20px; line-height: 1.6;">
                <li>All active <strong>Projects</strong></li>
                <li>All uploaded <strong>Gallery Catalog Items</strong></li>
                <li>All published <strong>News Articles & Publications</strong></li>
                <li>All received <strong>Contact Messages</strong></li>
                <li>All user and member <strong>Registrations</strong></li>
                <li>All recorded <strong>Login Attempts & History</strong></li>
            </ul>
            <p style="font-size: 13px; color: var(--on-surface-variant); line-height: 1.5; font-weight: 600;">
                Once reset, only the default administrator account (<strong>Username: admin</strong>, <strong>Password: admin123</strong>) will be restored to allow system access.
            </p>

            <button type="button" class="btn" onclick="showResetModal()" style="margin-top: 12px; padding: 12px 24px; background-color: var(--error); color: #fff; border: none; font-weight: 600; cursor: pointer; align-self: flex-start; border-radius: var(--radius-lg); display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">restart_alt</span>Reset Database Now
            </button>
        </div>
    </div>
    <div class="bento-card" style="box-shadow: none; border-radius: var(--radius-lg); padding: var(--spacing-md); grid-column: 1 / -1;">
        <div style="margin-bottom: var(--spacing-sm);">
            <h3 style="font-size: 18px; color: var(--primary); display: flex; align-items: center; gap: 8px;">
                <span class="material-symbols-outlined">manage_accounts</span>Administrator Management
            </h3>
            <p style="font-size: 14px; color: var(--on-surface-variant); margin-top: 8px;">
                Manage system administrators, edit their details, or remove access.
            </p>
        </div>

        <hr style="border: 0; border-top: 1px solid var(--outline-variant); margin: 16px 0;">

        <div class="table-responsive" style="margin-top: var(--spacing-md);">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Created</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($admins as $admin): ?>
                    <tr>
                        <td><?php echo h($admin['username']); ?></td>
                        <td><?php echo h($admin['email']); ?></td>
                        <td><?php echo date('M j, Y', strtotime($admin['created_at'])); ?></td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <button onclick="openEditAdminModal(<?php echo $admin['id']; ?>, '<?php echo h($admin['username']); ?>', '<?php echo h($admin['email']); ?>')" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; display: flex; align-items: center; gap: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">edit</span> Edit
                                </button>
                                <?php if ($admin['id'] != $_SESSION['admin_id']): ?>
                                <button onclick="openDeleteAdminModal(<?php echo $admin['id']; ?>, '<?php echo h($admin['username']); ?>')" class="btn btn-outline" style="padding: 6px 12px; font-size: 12px; display: flex; align-items: center; gap: 4px; color: var(--error); border-color: var(--error);">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">delete</span> Delete
                                </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="reset-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; padding: 20px;">
    <div style="background-color: var(--surface); color: var(--on-surface); border-radius: var(--radius-xl); padding: var(--spacing-lg); max-width: 500px; width: 100%; box-shadow: var(--shadow-xl); border: 1px solid var(--outline-variant); animation: modalFadeIn 0.3s ease-out;">
        
        <div style="display: flex; align-items: center; gap: 12px; color: var(--error); margin-bottom: var(--spacing-sm);">
            <span class="material-symbols-outlined" style="font-size: 32px;">warning</span>
            <h3 style="font-size: 20px; color: var(--error); margin: 0;">Confirm Database Reset</h3>
        </div>

        <p style="font-size: 14px; margin-bottom: 16px; line-height: 1.5;">
            Are you absolutely sure you want to proceed? This will <strong>permanently delete</strong> all records from the database.
        </p>

        <div style="background-color: var(--error-container); color: var(--error); padding: var(--spacing-sm); border-radius: var(--radius-lg); font-size: 13px; font-weight: 600; line-height: 1.5; margin-bottom: var(--spacing-md); border-left: 4px solid var(--error);">
            WARNING: This operation is destructive and cannot be undone. Only the default administrator account credentials (admin / admin123) will be preserved.
        </div>

        <form method="POST" action="admin_dashboard.php">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <input type="hidden" name="action" value="reset_database">
            
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="hideResetModal()" class="btn btn-outline" style="padding: 10px 20px; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer;">
                    Cancel
                </button>
                <button type="submit" class="btn" style="padding: 10px 20px; background-color: var(--error); color: #fff; border: none; font-weight: 600; cursor: pointer; border-radius: var(--radius-lg);">
                    Yes, Reset Database
                </button>
            </div>
        </form>
    </div>
</div>

<div id="edit-admin-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; padding: 20px;">
    <div style="background-color: var(--surface); color: var(--on-surface); border-radius: var(--radius-xl); padding: var(--spacing-lg); max-width: 500px; width: 100%; box-shadow: var(--shadow-xl); border: 1px solid var(--outline-variant); animation: modalFadeIn 0.3s ease-out;">
        <div style="display: flex; align-items: center; gap: 12px; color: var(--primary); margin-bottom: var(--spacing-sm);">
            <span class="material-symbols-outlined" style="font-size: 32px;">edit</span>
            <h3 style="font-size: 20px; color: var(--on-surface); margin: 0;">Edit Administrator</h3>
        </div>
        <form id="edit-admin-form" method="POST" action="admin_dashboard.php">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <input type="hidden" name="action" value="admin_edit">
            <input type="hidden" name="admin_id" id="edit-admin-id">
            
            <div class="form-group" style="margin-bottom: 16px; text-align: left;">
                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Username</label>
                <input type="text" name="admin_username" id="edit-admin-username" required style="width: 100%; padding: 10px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
            </div>
            <div class="form-group" style="margin-bottom: 24px; text-align: left;">
                <label style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px;">Email</label>
                <input type="email" name="admin_email" id="edit-admin-email" required style="width: 100%; padding: 10px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
            </div>
            
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="closeEditAdminModal()" class="btn btn-outline" style="padding: 10px 20px; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="button" onclick="confirmAdminSave()" class="btn" style="padding: 10px 20px; background-color: var(--primary); color: #fff; border: none; font-weight: 600; cursor: pointer; border-radius: var(--radius-lg);">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<div id="confirm-save-modal" style="display: none; position: fixed; z-index: 10000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; padding: 20px;">
    <div style="background-color: var(--surface); color: var(--on-surface); border-radius: var(--radius-xl); padding: var(--spacing-lg); max-width: 400px; width: 100%; box-shadow: var(--shadow-xl); border: 1px solid var(--outline-variant); text-align: center; animation: modalFadeIn 0.3s ease-out;">
        <span class="material-symbols-outlined" style="font-size: 48px; color: var(--primary); margin-bottom: 16px;">info</span>
        <h3 style="font-size: 20px; margin-bottom: 12px;">Confirm Changes</h3>
        <p style="font-size: 14px; margin-bottom: 24px; color: var(--on-surface-variant);">Are you sure you want to save these details?</p>
        <div style="display: flex; justify-content: center; gap: 12px;">
            <button type="button" onclick="closeConfirmSaveModal()" class="btn btn-outline" style="padding: 10px 20px; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer;">Cancel</button>
            <button type="button" onclick="submitEditAdminForm()" class="btn" style="padding: 10px 20px; background-color: var(--primary); color: #fff; border: none; font-weight: 600; cursor: pointer; border-radius: var(--radius-lg);">Yes, Save</button>
        </div>
    </div>
</div>

<div id="delete-admin-modal" style="display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0, 0, 0, 0.5); justify-content: center; align-items: center; padding: 20px;">
    <div style="background-color: var(--surface); color: var(--on-surface); border-radius: var(--radius-xl); padding: var(--spacing-lg); max-width: 400px; width: 100%; box-shadow: var(--shadow-xl); border: 1px solid var(--outline-variant); text-align: center; animation: modalFadeIn 0.3s ease-out;">
        <span class="material-symbols-outlined" style="font-size: 48px; color: var(--error); margin-bottom: 16px;">warning</span>
        <h3 style="font-size: 20px; margin-bottom: 12px; color: var(--error);">Delete Administrator</h3>
        <p style="font-size: 14px; margin-bottom: 24px; color: var(--on-surface-variant);">Are you sure you want to delete <strong id="delete-admin-name"></strong>? This action cannot be undone.</p>
        <form method="POST" action="admin_dashboard.php">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <input type="hidden" name="action" value="delete_admin">
            <input type="hidden" name="admin_id" id="delete-admin-id">
            <div style="display: flex; justify-content: center; gap: 12px;">
                <button type="button" onclick="closeDeleteAdminModal()" class="btn btn-outline" style="padding: 10px 20px; border-radius: var(--radius-lg); font-weight: 600; cursor: pointer;">Cancel</button>
                <button type="submit" class="btn" style="padding: 10px 20px; background-color: var(--error); color: #fff; border: none; font-weight: 600; cursor: pointer; border-radius: var(--radius-lg);">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
</style>

<script>
function showResetModal() {
    document.getElementById('reset-modal').style.display = 'flex';
}

function hideResetModal() {
    document.getElementById('reset-modal').style.display = 'none';
}

function openEditAdminModal(id, username, email) {
    document.getElementById('edit-admin-id').value = id;
    document.getElementById('edit-admin-username').value = username;
    document.getElementById('edit-admin-email').value = email;
    document.getElementById('edit-admin-modal').style.display = 'flex';
}
function closeEditAdminModal() {
    document.getElementById('edit-admin-modal').style.display = 'none';
}
function confirmAdminSave() {
    document.getElementById('confirm-save-modal').style.display = 'flex';
}
function closeConfirmSaveModal() {
    document.getElementById('confirm-save-modal').style.display = 'none';
}
function submitEditAdminForm() {
    document.getElementById('edit-admin-form').submit();
}

function openDeleteAdminModal(id, username) {
    document.getElementById('delete-admin-id').value = id;
    document.getElementById('delete-admin-name').innerText = username;
    document.getElementById('delete-admin-modal').style.display = 'flex';
}
function closeDeleteAdminModal() {
    document.getElementById('delete-admin-modal').style.display = 'none';
}

window.addEventListener('click', function(event) {
    var modal = document.getElementById('reset-modal');
    if (event.target == modal) {
        hideResetModal();
    }
    var editModal = document.getElementById('edit-admin-modal');
    if (event.target == editModal) {
        closeEditAdminModal();
    }
    var confirmModal = document.getElementById('confirm-save-modal');
    if (event.target == confirmModal) {
        closeConfirmSaveModal();
    }
    var deleteModal = document.getElementById('delete-admin-modal');
    if (event.target == deleteModal) {
        closeDeleteAdminModal();
    }
});
</script>
