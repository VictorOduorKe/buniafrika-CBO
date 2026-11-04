<?php
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

$thematic_areas = getThematicAreas($conn, true);
$faqs = getFaqs($conn, true);

$seo_context = [
    'title' => 'Thematic Areas & Programmes | BuniAfrika CBO',
    'meta_description' => 'Explore BuniAfrika CBO’s database-driven thematic areas, research streams, community action programmes, and public FAQs.',
    'faqs' => $faqs,
    'breadcrumb_title' => 'Our Work & Thematic Areas',
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag">Our thematic areas</p>
        <h1>Research, technology and civic action</h1>
        <p class="lead">BuniAfrika CBO works across key thematic areas, connecting community knowledge with inclusive civic participation and responsible digital preservation.</p>
    </div>
</section>

<!-- Dynamic Thematic Areas Sections -->
<?php if (!empty($thematic_areas)): ?>
    <?php foreach ($thematic_areas as $index => $area): 
        $isEven = ($index % 2 === 1);
        $sectionClass = $isEven ? 'section section-bg-low' : 'section container';
        $anchorId = 'thematic-' . (!empty($area['slug']) ? $area['slug'] : $area['id']);
        $points = !empty($area['points']) ? explode("\n", str_replace("\r", "", $area['points'])) : [];
        $points = array_values(array_filter(array_map('trim', $points)));
    ?>
        <section class="<?php echo $sectionClass; ?>" id="<?php echo h($anchorId); ?>" aria-labelledby="<?php echo h($anchorId); ?>-heading">
            <div class="<?php echo $isEven ? 'container' : ''; ?>">
                <article class="programme-card">
                    <div>
                        <p class="section-tag">Programme area <?php echo str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT); ?></p>
                        <h2 id="<?php echo h($anchorId); ?>-heading" style="display: flex; align-items: center; gap: 10px;">
                            <?php if (!empty($area['image_url'])): ?>
                                <img src="<?php echo h($area['image_url']); ?>" alt="<?php echo h($area['title']); ?>" style="width: 36px; height: 36px; object-fit: cover; border-radius: var(--radius-sm);">
                            <?php else: ?>
                                <span class="material-symbols-outlined text-primary" aria-hidden="true"><?php echo h($area['icon'] ?: 'account_balance'); ?></span>
                            <?php endif; ?>
                            <span><?php echo h($area['title']); ?></span>
                        </h2>
                        <p><?php echo h($area['description'] ?? ($area['intro'] ?? '')); ?></p>
                    </div>
                    <?php if (!empty($points)): ?>
                        <ul>
                            <?php foreach ($points as $point): ?>
                                <li><?php echo h($point); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </article>
            </div>
        </section>
    <?php endforeach; ?>
<?php else: ?>
    <section class="section container">
        <div class="empty-state" style="text-align: center; padding: 64px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); max-width: 600px; margin: 0 auto;">
            <span class="material-symbols-outlined" style="font-size: 56px; color: var(--on-surface-variant); margin-bottom: 12px; display: block;" aria-hidden="true">category</span>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">Content Not Available</h3>
            <p style="color: var(--on-surface-variant); font-size: 15px; max-width: 480px; margin: 0 auto; line-height: 1.6;">Programme information is currently being updated. Please check back later.</p>
        </div>
    </section>
<?php endif; ?>

<!-- Database-Driven Frequently Asked Questions -->
<section class="section container" id="faqs">
    <div class="section-header">
        <p class="section-tag">Common questions</p>
        <h2 class="section-title">About BuniAfrika’s Work</h2>
    </div>
    <div class="faq-list">
        <?php if (!empty($faqs)): ?>
            <?php foreach ($faqs as $faq): ?>
                <details class="faq-item">
                    <summary class="faq-question"><strong><?php echo h($faq['question']); ?></strong></summary>
                    <div class="faq-answer-content">
                        <p class="faq-direct-answer" style="font-weight: 500;"><?php echo nl2br(h($faq['answer'])); ?></p>
                        <?php if (!empty($faq['detailed_answer'])): ?>
                            <p class="faq-detailed-answer" style="margin-top: 8px; color: var(--on-surface-variant); font-size: 0.95rem; line-height: 1.6;"><?php echo nl2br(h($faq['detailed_answer'])); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($faq['related_link_url'])): ?>
                            <p style="margin-top: 10px;"><a href="<?php echo h($faq['related_link_url']); ?>" class="faq-citation-link" style="display: inline-flex; align-items: center; gap: 4px; font-weight: 600; font-size: 0.9rem; color: var(--primary); text-decoration: underline;"><?php echo h(!empty($faq['related_link_text']) ? $faq['related_link_text'] : 'Learn more'); ?> &rarr;</a></p>
                        <?php endif; ?>
                    </div>
                </details>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state" style="text-align: center; padding: 48px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); max-width: 600px; margin: 0 auto;">
                <span class="material-symbols-outlined" style="font-size: 48px; color: var(--on-surface-variant); margin-bottom: 8px; display: block;" aria-hidden="true">help_outline</span>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Content Not Available</h3>
                <p style="color: var(--on-surface-variant); font-size: 14px; max-width: 480px; margin: 0 auto; line-height: 1.5;">Frequently asked questions are currently being updated. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
