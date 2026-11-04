<?php
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

$leaderId = trim((string)($_GET['id'] ?? ''));
$site_settings = getSiteSettings($conn);
$leadership = getLeadershipProfiles($site_settings, false);

$currentLeader = null;
foreach ($leadership as $leader) {
    if ($leader['id'] === $leaderId) {
        $currentLeader = $leader;
        break;
    }
}

if (!$currentLeader) {
    http_response_code(404);
    $seo_context = [
        'title' => 'Profile Not Found | BuniAfrika CBO',
        'meta_description' => 'The requested leadership profile could not be found.',
        'robots' => 'noindex, nofollow',
    ];
    require_once __DIR__ . '/includes/header.php';
    ?>
    <section class="section" style="min-height: 50vh; display: flex; align-items: center; justify-content: center; text-align: center; padding: 80px 20px;">
        <div class="container" style="max-width: 540px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); padding: 48px 32px; box-shadow: var(--shadow-sm);">
            <span class="material-symbols-outlined" style="font-size: 64px; color: var(--on-surface-variant); margin-bottom: 16px;">person_off</span>
            <h1 style="font-size: 26px; font-weight: 700; margin-bottom: 12px; color: var(--primary);">Profile Not Found</h1>
            <p style="color: var(--on-surface-variant); font-size: 15px; line-height: 1.6; margin-bottom: 28px;">The leadership profile you are looking for does not exist or is currently unavailable.</p>
            <a href="about.php#leadership" class="btn btn-primary">&larr; Return to Leadership Team</a>
        </div>
    </section>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$leaderImage = $currentLeader['image'];
$leaderRole = $currentLeader['role'];
$leaderRoleVerified = $currentLeader['role_verified'] === '1';
$leaderPhone = $currentLeader['phone'];
$leaderEmail = $currentLeader['email'];
$leaderDescription = trim($currentLeader['description'] ?? '');

$nameParts = preg_split('/\s+/', trim($currentLeader['name']));
$initials = strtoupper(substr($nameParts[0] ?? '', 0, 1) . (count($nameParts) > 1 ? substr($nameParts[count($nameParts) - 1], 0, 1) : ''));

$cleanDesc = !empty($leaderDescription) ? mb_substr(strip_tags($leaderDescription), 0, 155) : ('Profile and confirmed organizational leadership details for ' . $currentLeader['name'] . ' at BuniAfrika CBO.');

$seo_context = [
    'title' => $currentLeader['name'] . ($leaderRole !== '' && $leaderRoleVerified ? ' — ' . $leaderRole : '') . ' | Leadership Profile',
    'meta_description' => $cleanDesc,
    'canonical_url' => getCanonicalDomain($conn) . '/leader.php?id=' . rawurlencode($leaderId),
    'og_title' => $currentLeader['name'] . ($leaderRole !== '' && $leaderRoleVerified ? ' — ' . $leaderRole : '') . ' | BuniAfrika Leadership',
    'og_description' => $cleanDesc,
    'og_image' => !empty($leaderImage) ? $leaderImage : 'assets/images/buniafrika-og-banner.png',
    'og_type' => 'profile',
    'breadcrumb_title' => $currentLeader['name'],
    'leader' => $currentLeader,
];

require_once __DIR__ . '/includes/header.php';
?>

    <section class="section leader-page-section">
        <div class="container">
            <article class="leader-page-card">
                <header class="leader-page-hero">
                    <a href="about.php#leadership" class="btn btn-outline leader-page-close-top" aria-label="Close and return to leadership">
                        <span class="material-symbols-outlined" aria-hidden="true">close</span>
                    </a>
                    <?php if ($leaderImage !== ''): ?>
                        <img class="leader-page-avatar" src="<?php echo h($leaderImage); ?>" alt="Portrait of <?php echo h($currentLeader['name']); ?>">
                    <?php else: ?>
                        <div class="leader-page-avatar leader-page-avatar-initials" aria-hidden="true"><?php echo h($initials); ?></div>
                    <?php endif; ?>
                    <div class="leader-page-info">
                        <h1 class="leader-page-name"><?php echo h($currentLeader['name']); ?></h1>
                        <?php if ($leaderRole !== '' && $leaderRoleVerified): ?>
                            <p class="leader-page-role">
                                <span class="material-symbols-outlined" style="font-size: 1.1rem; vertical-align: middle; color: var(--primary);" aria-hidden="true">verified</span>
                                <?php echo h($leaderRole); ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($leaderPhone !== '' || $leaderEmail !== ''): ?>
                            <div class="leader-contact" style="margin-top: 8px;">
                                <?php if ($leaderPhone !== ''): ?>
                                    <a href="tel:<?php echo h(phoneNumberHref($leaderPhone)); ?>">
                                        <span class="material-symbols-outlined" aria-hidden="true">call</span><?php echo h($leaderPhone); ?>
                                    </a>
                                <?php endif; ?>
                                <?php if ($leaderEmail !== ''): ?>
                                    <a href="mailto:<?php echo h($leaderEmail); ?>">
                                        <span class="material-symbols-outlined" aria-hidden="true">mail</span><?php echo h($leaderEmail); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="leader-page-body">
                    <h2 class="leader-modal-desc-heading" style="font-size: 0.9rem; margin-bottom: 14px;">Detailed Description & Biography</h2>
                    <?php if ($leaderDescription !== ''): ?>
                        <div class="leader-modal-desc-content"><?php echo h($leaderDescription); ?></div>
                    <?php else: ?>
                        <p style="color: var(--on-surface-variant); font-style: italic;">Detailed biography and leadership description will be published soon.</p>
                    <?php endif; ?>
                </div>

                <footer class="leader-page-footer">
                    <a href="about.php#leadership" class="btn btn-primary leader-page-close-btn">
                        <span class="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                        <span>Close and return</span>
                    </a>
                </footer>
            </article>
        </div>
    </section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
