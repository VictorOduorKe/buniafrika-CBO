<?php
$page_title = 'BuniAfrika CBO | Civic technology, heritage and civic action';
$meta_desc = 'BuniAfrika CBO bridges research, technology and civic action to support public accountability, inclusive participation and African cultural heritage.';
require_once __DIR__ . '/includes/header.php';

$thematic_areas = getThematicAreas($conn, true);
$achievements = getAchievements($conn, true);
$testimonials = getTestimonials($conn, true);
$partners = getPartners($conn, true);
$featured_projects = array_slice(getProjects($conn), 0, 3);
$latest_articles = array_slice(getNewsArticles($conn), 0, 3);
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag" style="color: var(--secondary-container);">Research. Technology. Civic action.</p>
        <h1><?php echo h($site_settings['site_name']); ?></h1>
        <p class="lead"><?php echo h($site_settings['mission']); ?></p>
        <div class="flex gap-sm" style="flex-wrap: wrap; margin-top: var(--spacing-lg);">
            <a class="btn btn-secondary" href="programmes.php">Explore our thematic areas</a>
            <a class="btn btn-outline" href="contact.php" style="border-color: #fff; color: #fff;">Contact BuniAfrika</a>
        </div>
    </div>
</section>

<!-- Database-Driven Thematic Areas -->
<section class="section container" aria-labelledby="focus-heading">
    <div class="section-header">
        <p class="section-tag">What we work on</p>
        <h2 class="section-title" id="focus-heading">Our Thematic Areas</h2>
        <p class="section-desc">Our work focuses on civic participation, digital accountability, and the responsible preservation of cultural knowledge.</p>
    </div>
    <div class="bento-grid">
        <?php if (!empty($thematic_areas)): ?>
            <?php foreach ($thematic_areas as $area): ?>
                <article class="bento-card col-span-6">
                    <?php if (!empty($area['image_url'])): ?>
                        <div style="margin-bottom: 12px;">
                            <img src="<?php echo h($area['image_url']); ?>" alt="<?php echo h($area['title']); ?>" style="width: 48px; height: 48px; object-fit: cover; border-radius: var(--radius-md);">
                        </div>
                    <?php else: ?>
                        <span class="material-symbols-outlined text-primary" aria-hidden="true" style="font-size: 32px; display: block; margin-bottom: 12px;"><?php echo h($area['icon'] ?: 'account_balance'); ?></span>
                    <?php endif; ?>
                    <h3><?php echo h($area['title']); ?></h3>
                    <p class="section-desc"><?php echo h($area['intro'] ?? $area['description'] ?? ''); ?></p>
                    <a class="footer-link-new" href="programmes.php#thematic-<?php echo h($area['slug'] ?? $area['id'] ?? ''); ?>">Explore <?php echo h($area['title']); ?> &rarr;</a>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 48px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm);">
                <span class="material-symbols-outlined" style="font-size: 48px; color: var(--on-surface-variant); margin-bottom: 8px; display: block;" aria-hidden="true">category</span>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--primary); margin: 0 0 6px 0;">Content Not Available</h3>
                <p style="color: var(--on-surface-variant); font-size: 14px; max-width: 440px; margin: 0 auto;">Thematic areas are currently being updated. Please check back later.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Achievements and Verifiable Impact Metrics -->
<?php if (!empty($achievements)): ?>
    <section class="section section-bg-low" id="achievements" aria-labelledby="impact-heading">
        <div class="container">
            <div class="section-header">
                <p class="section-tag">Measurable change</p>
                <h2 class="section-title" id="impact-heading">Achievements &amp; Impact</h2>
                <p class="section-desc">Documented progress across our community interventions, open data tools, and cultural research.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px;">
                <?php foreach ($achievements as $ach): ?>
                    <div class="bento-card" style="text-align: center; padding: 32px 20px;">
                        <div style="font-size: 38px; font-weight: 800; color: var(--primary); line-height: 1.1; margin-bottom: 8px;">
                            <?php echo h($ach['metric_value']); ?>
                            <?php if (!empty($ach['unit'])): ?>
                                <span style="font-size: 16px; font-weight: 600; color: var(--secondary);"><?php echo h($ach['unit']); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 style="font-size: 16px; margin-bottom: 6px;"><?php echo h($ach['label']); ?></h3>
                        <?php if (!empty($ach['reporting_period'])): ?>
                            <span class="badge badge-info" style="margin-bottom: 8px;"><?php echo h($ach['reporting_period']); ?></span>
                        <?php endif; ?>
                        <?php if (!empty($ach['description'])): ?>
                            <p class="section-desc" style="font-size: 13px; margin: 0;"><?php echo h($ach['description']); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="section <?php echo !empty($achievements) ? '' : 'section-bg-low'; ?>">
    <div class="container">
        <div class="section-header">
            <p class="section-tag">Our purpose</p>
            <h2 class="section-title">Informed citizenship and inclusive progress</h2>
        </div>
        <div class="bento-grid">
            <article class="bento-card col-span-6">
                <h3>Our vision</h3>
                <p class="section-desc"><?php echo h($site_settings['vision']); ?></p>
            </article>
            <article class="bento-card col-span-6">
                <h3>Our mission</h3>
                <p class="section-desc"><?php echo h($site_settings['mission']); ?></p>
            </article>
        </div>
    </div>
</section>

<?php if ($featured_projects): ?>
    <section class="section container" aria-labelledby="projects-heading">
        <div class="section-header">
            <p class="section-tag">From our content library</p>
            <h2 class="section-title" id="projects-heading">Published projects</h2>
        </div>
        <div class="projects-grid">
            <?php foreach ($featured_projects as $project): ?>
                <article class="bento-card">
                    <?php if (!empty($project['image_url'])): ?>
                        <img src="<?php echo h($project['image_url']); ?>" alt="<?php echo h($project['title']); ?>" loading="lazy" style="width: 100%; height: 220px; object-fit: cover; border-radius: var(--radius-lg);">
                    <?php endif; ?>
                    <h3><?php echo h($project['title']); ?></h3>
                    <p class="section-desc"><?php echo h(getHtmlExcerpt($project['description'] ?? '', 180)); ?></p>
                </article>
            <?php endforeach; ?>
        </div>
        <p style="margin-top: var(--spacing-md);"><a class="btn btn-outline" href="projects.php">View all projects</a></p>
    </section>
<?php endif; ?>

<!-- Testimonials from Community & Partners -->
<?php if (!empty($testimonials)): ?>
    <section class="section section-bg-low" id="testimonials" aria-labelledby="testimonials-heading">
        <div class="container">
            <div class="section-header">
                <p class="section-tag">Voices of the community</p>
                <h2 class="section-title" id="testimonials-heading">What People Say</h2>
                <p class="section-desc">Perspectives from community researchers, civic innovators, and partners working alongside BuniAfrika.</p>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
                <?php foreach ($testimonials as $t): ?>
                    <article class="bento-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                        <blockquote style="font-size: 15px; font-style: italic; color: var(--on-surface); line-height: 1.6; margin: 0 0 20px 0;">
                            &ldquo;<?php echo h($t['quote']); ?>&rdquo;
                        </blockquote>
                        <div style="display: flex; align-items: center; gap: 12px; margin-top: auto; padding-top: 16px; border-top: 1px solid var(--outline-variant);">
                            <?php if (!empty($t['author_image'])): ?>
                                <img src="<?php echo h($t['author_image']); ?>" alt="<?php echo h($t['author_name']); ?>" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 44px; height: 44px; border-radius: 50%; background: var(--secondary-container); color: var(--on-secondary-container); display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                    <?php echo h(strtoupper(substr($t['author_name'], 0, 1))); ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <strong style="font-size: 14px; display: block; color: var(--primary);"><?php echo h($t['author_name']); ?></strong>
                                <span style="font-size: 12px; color: var(--on-surface-variant);">
                                    <?php echo h($t['author_role']); ?>
                                    <?php if (!empty($t['author_organization'])): ?>
                                        &bull; <?php echo h($t['author_organization']); ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Institutional & Community Partnerships -->
<?php if (!empty($partners)): ?>
    <section class="section container" aria-labelledby="partners-heading">
        <div class="section-header">
            <p class="section-tag">Collaboration</p>
            <h2 class="section-title" id="partners-heading">Our Partners &amp; Allies</h2>
            <p class="section-desc">Working collaboratively with grassroots institutions, technology networks, and research collectives.</p>
        </div>
        <div style="display: flex; flex-wrap: wrap; gap: 24px; justify-content: center; align-items: center;">
            <?php foreach ($partners as $p): ?>
                <div style="background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); padding: 16px 24px; display: flex; align-items: center; gap: 12px; box-shadow: var(--shadow-sm); min-width: 180px;">
                    <?php if (!empty($p['logo_url'])): ?>
                        <img src="<?php echo h($p['logo_url']); ?>" alt="<?php echo h($p['name']); ?>" style="height: 38px; max-width: 100px; object-fit: contain;">
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

<?php if ($latest_articles): ?>
    <section class="section section-bg-low">
        <div class="container">
            <div class="section-header">
                <p class="section-tag">Updates and research</p>
                <h2 class="section-title">Latest publications</h2>
            </div>
            <div class="projects-grid">
                <?php foreach ($latest_articles as $article): ?>
                    <article class="bento-card">
                        <?php if (!empty($article['image_url'])): ?>
                            <img src="<?php echo h($article['image_url']); ?>" alt="<?php echo h($article['title']); ?>" loading="lazy" style="width: 100%; height: 220px; object-fit: cover; border-radius: var(--radius-lg);">
                        <?php endif; ?>
                        <p class="section-tag"><?php echo h($article['category'] ?? ''); ?></p>
                        <h3><?php echo h($article['title']); ?></h3>
                        <p class="section-desc"><?php echo h(getHtmlExcerpt($article['summary'] ?? '', 180)); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="flex gap-sm" style="margin-top: var(--spacing-md); flex-wrap: wrap;">
                <a class="btn btn-outline" href="news.php">View all updates</a>
                <a class="btn btn-secondary" href="publications.php">Browse Research Library</a>
            </div>
        </div>
    </section>
<?php endif; ?>

<section class="section container">
    <div class="programme-card">
        <div>
            <p class="section-tag">Work with us</p>
            <h2>Connect with BuniAfrika</h2>
            <p>For general enquiries, partnership discussions, or volunteer opportunities, connect with our team.</p>
        </div>
        <div class="flex gap-sm" style="flex-wrap: wrap;">
            <a class="btn btn-primary" href="contact.php">Send an enquiry</a>
            <a class="btn btn-secondary" href="volunteer.php">Volunteer with us</a>
            <a class="btn btn-outline" href="register.php?role=partner">Partnership enquiries</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
