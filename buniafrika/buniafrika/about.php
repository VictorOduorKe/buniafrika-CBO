<?php
$page_title = 'About BuniAfrika CBO | Vision, mission and leadership';
$meta_desc = 'Learn about BuniAfrika CBO’s vision, mission, objectives, journey, core values, and confirmed public leadership.';
require_once __DIR__ . '/includes/header.php';

$leadership = getLeadershipProfiles($site_settings);
$objectives = getObjectives($conn, true);
$core_values = getCoreValues($conn, true);
$milestones = getOurStory($conn, true);
$thematic_areas = getThematicAreas($conn, true);
$partners = getPartners($conn, true);
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag" style="color: var(--secondary-container);">About the organisation</p>
        <h1>About BuniAfrika CBO</h1>
        <p class="lead">An organisation focused on informed citizenship, institutional accountability, innovation and inclusive progress.</p>
    </div>
</section>

<!-- Vision & Mission -->
<section class="section container" id="vision" aria-label="Vision and mission">
    <div class="bento-grid">
        <article class="bento-card col-span-6">
            <h2>Vision</h2>
            <p class="section-desc"><?php echo h($site_settings['vision']); ?></p>
        </article>
        <article class="bento-card col-span-6">
            <h2>Mission</h2>
            <p class="section-desc"><?php echo h($site_settings['mission']); ?></p>
        </article>
    </div>
</section>

<!-- Core Values -->
<?php if (!empty($core_values)): ?>
    <section class="section section-bg-low" id="values" aria-labelledby="values-heading">
        <div class="container">
            <div class="section-header">
                <p class="section-tag">Ethical foundation</p>
                <h2 class="section-title" id="values-heading">Our Core Values</h2>
                <p class="section-desc">The principles that guide our research methodology, community partnerships, and civic action.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 24px;">
                <?php foreach ($core_values as $val): ?>
                    <article class="bento-card" style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="width: 44px; height: 44px; border-radius: var(--radius-md); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center;">
                            <span class="material-symbols-outlined" style="font-size: 24px;"><?php echo h($val['icon'] ?: 'verified'); ?></span>
                        </div>
                        <h3 style="margin: 0; font-size: 18px; color: var(--primary);"><?php echo h($val['title']); ?></h3>
                        <p class="section-desc" style="margin: 0; font-size: 14px; line-height: 1.5;"><?php echo h($val['description']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Database-Driven Objectives -->
<section class="section <?php echo !empty($core_values) ? 'container' : 'section section-bg-low'; ?>" id="objectives">
    <div class="<?php echo empty($core_values) ? 'container' : ''; ?>">
        <div class="section-header">
            <p class="section-tag">Our objectives</p>
            <h2 class="section-title">What guides our work</h2>
            <p class="section-desc">Specific, actionable pillars driving our programmes across coastal communities and national civic networks.</p>
        </div>

        <?php if (!empty($objectives)): ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <?php foreach ($objectives as $idx => $obj): ?>
                    <div class="bento-card" style="display: flex; flex-direction: column; gap: 8px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="badge" style="background: var(--surface-variant); color: var(--primary); font-weight: 700;">
                                Pillar <?php echo str_pad((string)($idx + 1), 2, '0', STR_PAD_LEFT); ?>
                            </span>
                            <?php if (!empty($obj['category']) && $obj['category'] !== 'General'): ?>
                                <span class="badge badge-info"><?php echo h($obj['category']); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 style="font-size: 17px; margin-top: 4px;"><?php echo h($obj['title']); ?></h3>
                        <?php if (!empty($obj['description'])): ?>
                            <p class="section-desc" style="margin: 0; font-size: 14px;"><?php echo h($obj['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state" style="text-align: center; padding: 40px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl);">
                <span class="material-symbols-outlined" style="font-size: 40px; color: var(--on-surface-variant); margin-bottom: 8px; display: block;" aria-hidden="true">track_changes</span>
                <h3 style="font-size: 17px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Content Not Available</h3>
                <p style="color: var(--on-surface-variant); font-size: 14px; margin: 0;">Organisational objectives are currently being updated. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Our Story / Milestones Timeline -->
<?php if (!empty($milestones)): ?>
    <section class="section section-bg-low" id="story" aria-labelledby="story-heading">
        <div class="container">
            <div class="section-header">
                <p class="section-tag">Our journey</p>
                <h2 class="section-title" id="story-heading">Our Story &amp; Foundational Milestones</h2>
                <p class="section-desc">Key moments that shaped BuniAfrika from grassroots inception into an active civic technology and cultural repository.</p>
            </div>
            <div style="position: relative; padding-left: 24px; border-left: 3px solid var(--primary); margin: 32px 0 32px 16px; display: flex; flex-direction: column; gap: 32px;">
                <?php foreach ($milestones as $ms): ?>
                    <div style="position: relative;">
                        <!-- Dot on timeline -->
                        <div style="position: absolute; left: -33px; top: 4px; width: 16px; height: 16px; border-radius: 50%; background: var(--primary); border: 3px solid var(--surface); box-shadow: 0 0 0 2px var(--primary);"></div>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                            <span class="badge" style="background: var(--primary); color: #ffffff; font-weight: 700; font-size: 13px; align-self: flex-start;">
                                <?php echo h($ms['year_or_date'] ?? $ms['year'] ?? ''); ?>
                            </span>
                        </div>
                        <h3 style="font-size: 18px; margin: 8px 0 6px 0; color: var(--primary);"><?php echo h($ms['title']); ?></h3>
                        <p class="section-desc" style="margin: 0; font-size: 14px; max-width: 700px;"><?php echo h($ms['description']); ?></p>
                        <?php if (!empty($ms['image_url'])): ?>
                            <div style="margin-top: 12px;">
                                <img src="<?php echo h($ms['image_url']); ?>" alt="<?php echo h($ms['title']); ?>" style="max-height: 180px; border-radius: var(--radius-md); object-fit: cover; box-shadow: var(--shadow-sm);">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Dynamic Thematic Areas -->
<section class="section container">
    <div class="section-header">
        <p class="section-tag">Our work</p>
        <h2 class="section-title">Thematic Areas</h2>
        <p class="section-desc">BuniAfrika’s public programme and research information is organised around these core thematic areas.</p>
    </div>
    <div class="bento-grid">
        <?php if (!empty($thematic_areas)): ?>
            <?php foreach ($thematic_areas as $area): ?>
                <article class="bento-card col-span-6">
                    <?php if (!empty($area['image_url'])): ?>
                        <div style="margin-bottom: 12px;">
                            <img src="<?php echo h($area['image_url']); ?>" alt="<?php echo h($area['title']); ?>" style="width: 44px; height: 44px; object-fit: cover; border-radius: var(--radius-md);">
                        </div>
                    <?php else: ?>
                        <span class="material-symbols-outlined text-primary" aria-hidden="true" style="font-size: 28px; display: block; margin-bottom: 12px;"><?php echo h($area['icon'] ?: 'account_balance'); ?></span>
                    <?php endif; ?>
                    <h3><?php echo h($area['title']); ?></h3>
                    <p class="section-desc"><?php echo h($area['description'] ?? ($area['intro'] ?? '')); ?></p>
                    <a href="programmes.php#thematic-<?php echo h($area['slug'] ?? $area['id']); ?>">Read about <?php echo h($area['title']); ?> &rarr;</a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl);">
                <span class="material-symbols-outlined" style="font-size: 40px; color: var(--on-surface-variant); margin-bottom: 8px; display: block;" aria-hidden="true">category</span>
                <h3 style="font-size: 17px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Content Not Available</h3>
                <p style="color: var(--on-surface-variant); font-size: 14px; margin: 0;">Thematic areas are currently unavailable. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Public Leadership Profiles -->
<section class="section section-bg-low" id="leadership">
    <div class="container">
        <div class="section-header">
            <p class="section-tag">Public leadership</p>
            <h2 class="section-title">Leadership</h2>
            <p class="section-desc">Public roles and personal contact details are shown only when confirmed and added by an administrator.</p>
        </div>
        <div class="leadership-grid">
            <?php
            foreach ($leadership as $leader):
                $leaderImage = $leader['image'];
                $leaderRole = $leader['role'];
                $leaderRoleVerified = $leader['role_verified'] === '1';
                $leaderPhone = $leader['phone'];
                $leaderEmail = $leader['email'];
                $nameParts = preg_split('/\s+/', trim($leader['name']));
                $initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . (count($nameParts) > 1 ? substr($nameParts[count($nameParts) - 1], 0, 1) : ''));
            ?>
                <article class="leader-card">
                    <?php if ($leaderImage !== ''): ?>
                        <img class="leader-avatar" src="<?php echo h($leaderImage); ?>" alt="Portrait of <?php echo h($leader['name']); ?>" loading="lazy">
                    <?php else: ?>
                        <div class="leader-avatar leader-avatar-initials" aria-hidden="true"><?php echo h($initials); ?></div>
                    <?php endif; ?>
                    <h3><?php echo h($leader['name']); ?></h3>
                    <?php if ($leaderRole !== '' && $leaderRoleVerified): ?><p class="leader-role"><?php echo h($leaderRole); ?></p><?php endif; ?>
                    <?php if ($leaderPhone !== '' || $leaderEmail !== ''): ?>
                        <div class="leader-contact">
                            <?php if ($leaderPhone !== ''): ?><a href="tel:<?php echo h(phoneNumberHref($leaderPhone)); ?>"><span class="material-symbols-outlined" aria-hidden="true">call</span><?php echo h($leaderPhone); ?></a><?php endif; ?>
                            <?php if ($leaderEmail !== ''): ?><a href="mailto:<?php echo h($leaderEmail); ?>"><span class="material-symbols-outlined" aria-hidden="true">mail</span><?php echo h($leaderEmail); ?></a><?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty(trim($leader['description'] ?? ''))): ?>
                        <div class="leader-actions">
                            <a href="leader.php?id=<?php echo urlencode($leader['id']); ?>" class="leader-read-more-btn" data-leader-id="<?php echo h($leader['id']); ?>" aria-haspopup="dialog" aria-controls="leader-modal">
                                <span>Read More</span>
                                <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                            </a>
                        </div>
                    <?php endif; ?>
                </article>
            <?php endforeach; ?>
            <?php if (!$leadership): ?><p class="section-desc">Public leadership profiles will be listed here when added.</p><?php endif; ?>
        </div>
    </div>
</section>

<!-- Institutional Partnerships -->
<?php if (!empty($partners)): ?>
    <section class="section container" id="partners" aria-labelledby="partners-heading">
        <div class="section-header">
            <p class="section-tag">Alliances</p>
            <h2 class="section-title" id="partners-heading">Institutional &amp; Community Partners</h2>
            <p class="section-desc">Organizations partnering with BuniAfrika across civil society, academia, and open research.</p>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; align-items: center;">
            <?php foreach ($partners as $p): ?>
                <div style="background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); padding: 14px 20px; display: flex; align-items: center; gap: 10px; box-shadow: var(--shadow-sm);">
                    <?php if (!empty($p['logo_url'])): ?>
                        <img src="<?php echo h($p['logo_url']); ?>" alt="<?php echo h($p['name']); ?>" style="height: 32px; max-width: 90px; object-fit: contain;">
                    <?php else: ?>
                        <span class="material-symbols-outlined text-primary" aria-hidden="true">handshake</span>
                    <?php endif; ?>
                    <div>
                        <strong style="font-size: 13px; color: var(--on-surface);"><?php echo h($p['name']); ?></strong>
                        <?php if (!empty($p['website_url'])): ?>
                            <br><a href="<?php echo h($p['website_url']); ?>" target="_blank" rel="noopener noreferrer" style="font-size: 11px; color: var(--primary); text-decoration: underline;">Visit website &nearr;</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<!-- Leader Description Modal -->
<div id="leader-modal" class="leader-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="leader-modal-name" aria-hidden="true" style="display: none;">
    <div class="leader-modal-card">
        <button type="button" class="leader-modal-close" id="leader-modal-close-icon" aria-label="Close leader description">
            <span class="material-symbols-outlined" aria-hidden="true">close</span>
        </button>
        <div class="leader-modal-header">
            <div id="leader-modal-avatar-container"></div>
            <div class="leader-modal-meta">
                <h2 id="leader-modal-name" class="leader-modal-name"></h2>
                <div id="leader-modal-role" class="leader-modal-role"></div>
                <div id="leader-modal-contact" class="leader-modal-contact"></div>
            </div>
        </div>
        <div class="leader-modal-body">
            <h3 class="leader-modal-desc-heading">Detailed Description</h3>
            <div id="leader-modal-desc" class="leader-modal-desc-content"></div>
        </div>
        <div class="leader-modal-footer">
            <button type="button" class="btn btn-outline leader-modal-close-btn" id="leader-modal-close-footer">
                <span class="material-symbols-outlined" aria-hidden="true">close</span>
                <span>Close</span>
            </button>
        </div>
    </div>
</div>

<?php
$leadershipJsonData = [];
foreach ($leadership as $leader) {
    $nParts = preg_split('/\s+/', trim($leader['name']));
    $inits = strtoupper(substr($nParts[0] ?? '', 0, 1) . (count($nParts) > 1 ? substr($nParts[count($nameParts) - 1], 0, 1) : ''));
    $leadershipJsonData[$leader['id']] = [
        'id' => $leader['id'],
        'name' => $leader['name'],
        'role' => $leader['role'],
        'role_verified' => $leader['role_verified'] === '1',
        'image' => $leader['image'],
        'initials' => $inits,
        'phone' => $leader['phone'],
        'phone_href' => phoneNumberHref($leader['phone']),
        'email' => $leader['email'],
        'description' => $leader['description'],
    ];
}
?>
<script>
(function () {
    const leaderData = <?php echo json_encode($leadershipJsonData, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
    const modal = document.getElementById('leader-modal');
    if (!modal) return;

    const closeIcon = document.getElementById('leader-modal-close-icon');
    const closeFooter = document.getElementById('leader-modal-close-footer');
    const avatarContainer = document.getElementById('leader-modal-avatar-container');
    const nameEl = document.getElementById('leader-modal-name');
    const roleEl = document.getElementById('leader-modal-role');
    const contactEl = document.getElementById('leader-modal-contact');
    const descEl = document.getElementById('leader-modal-desc');

    let activeTrigger = null;

    function escapeHtml(str) {
        const p = document.createElement('p');
        p.textContent = str;
        return p.innerHTML;
    }

    function openLeaderModal(leaderId, triggerBtn) {
        const leader = leaderData[leaderId];
        if (!leader) return;

        activeTrigger = triggerBtn || null;

        // Avatar
        avatarContainer.innerHTML = '';
        if (leader.image) {
            const img = document.createElement('img');
            img.className = 'leader-modal-avatar';
            img.src = leader.image;
            img.alt = 'Portrait of ' + leader.name;
            avatarContainer.appendChild(img);
        } else {
            const div = document.createElement('div');
            div.className = 'leader-modal-avatar leader-modal-avatar-initials';
            div.setAttribute('aria-hidden', 'true');
            div.textContent = leader.initials;
            avatarContainer.appendChild(div);
        }

        // Name
        nameEl.textContent = leader.name;

        // Role/Title
        if (leader.role && leader.role_verified) {
            roleEl.innerHTML = '<span class="material-symbols-outlined" style="font-size: 1.1rem; color: var(--primary);" aria-hidden="true">verified</span> ' + escapeHtml(leader.role);
            roleEl.style.display = 'inline-flex';
        } else {
            roleEl.textContent = '';
            roleEl.style.display = 'none';
        }

        // Contact
        contactEl.innerHTML = '';
        if (leader.phone || leader.email) {
            if (leader.phone) {
                const aPhone = document.createElement('a');
                aPhone.href = 'tel:' + leader.phone_href;
                aPhone.innerHTML = '<span class="material-symbols-outlined" aria-hidden="true">call</span> ' + escapeHtml(leader.phone);
                contactEl.appendChild(aPhone);
            }
            if (leader.email) {
                const aEmail = document.createElement('a');
                aEmail.href = 'mailto:' + leader.email;
                aEmail.innerHTML = '<span class="material-symbols-outlined" aria-hidden="true">mail</span> ' + escapeHtml(leader.email);
                contactEl.appendChild(aEmail);
            }
            contactEl.style.display = 'flex';
        } else {
            contactEl.style.display = 'none';
        }

        // Detailed Description
        descEl.textContent = leader.description || 'No detailed description available.';

        // Show modal with animation
        modal.style.display = 'flex';
        modal.offsetHeight; // trigger reflow
        modal.classList.add('active');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';

        // URL hash
        if (window.history && history.replaceState) {
            history.replaceState(null, '', '#leader-' + encodeURIComponent(leaderId));
        }

        closeIcon.focus();
    }

    function closeLeaderModal() {
        modal.classList.remove('active');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';

        if (window.history && history.replaceState) {
            const cleanUrl = window.location.pathname + window.location.search;
            history.replaceState(null, '', cleanUrl);
        }

        setTimeout(function () {
            modal.style.display = 'none';
            if (activeTrigger) {
                activeTrigger.focus();
                activeTrigger = null;
            }
        }, 300);
    }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.leader-read-more-btn');
        if (btn) {
            e.preventDefault();
            const leaderId = btn.dataset.leaderId;
            openLeaderModal(leaderId, btn);
            return;
        }

        if (e.target === modal || e.target.closest('#leader-modal-close-icon') || e.target.closest('#leader-modal-close-footer')) {
            closeLeaderModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeLeaderModal();
        }
    });

    // Check hash on load
    window.addEventListener('DOMContentLoaded', function () {
        const hash = window.location.hash;
        if (hash && hash.indexOf('#leader-') === 0) {
            const id = decodeURIComponent(hash.substring(8));
            if (leaderData[id]) {
                openLeaderModal(id);
            }
        }
    });
}());
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
