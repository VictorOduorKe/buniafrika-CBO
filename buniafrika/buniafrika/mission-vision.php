<?php
$page_title = 'Vision, Mission and Objectives | BuniAfrika CBO';
$meta_desc = 'Read BuniAfrika CBO’s official vision, mission and strategic organisational objectives.';
require_once __DIR__ . '/includes/header.php';

$objectives = getObjectives($conn, true);
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag" style="color: var(--secondary-container);">Our purpose</p>
        <h1>Vision, mission and objectives</h1>
        <p class="lead">The statements below express BuniAfrika CBO’s organisational purpose and priorities.</p>
    </div>
</section>

<section class="section container">
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

<section class="section section-bg-low" id="objectives">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Strategic Objectives</h2>
            <p class="section-desc">Actionable commitments that guide our interventions and community engagement.</p>
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
            <div class="empty-state" style="text-align: center; padding: 48px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); max-width: 600px; margin: 0 auto;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: var(--on-surface-variant); margin-bottom: 8px; display: block;" aria-hidden="true">track_changes</span>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Content Not Available</h3>
                <p style="color: var(--on-surface-variant); font-size: 14px; margin: 0;">Strategic objectives are currently being updated. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
