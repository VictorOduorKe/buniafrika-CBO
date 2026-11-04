<?php
/**
 * Public Publications and Reports Library
 */
require_once __DIR__ . '/includes/db_connection.php';
require_once __DIR__ . '/includes/functions.php';

$filter_category = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
$publications = getPublications($conn, true, $filter_category ?: null);

// Collect distinct published categories
$categories_res = $conn->query("SELECT DISTINCT category FROM publications WHERE published = 1 ORDER BY category ASC");
$all_cats = [];
if ($categories_res) {
    while ($r = $categories_res->fetch_assoc()) {
        if (!empty($r['category'])) {
            $all_cats[] = $r['category'];
        }
    }
}

$seo_context = [
    'title' => (!empty($filter_category) ? ($filter_category . ' — ') : '') . 'Publications and Reports | BuniAfrika CBO',
    'meta_description' => 'Download research reports, policy briefs, and community baseline studies published by BuniAfrika CBO.',
    'canonical_url' => getCanonicalDomain($conn) . '/publications.php' . (!empty($filter_category) ? ('?category=' . rawurlencode($filter_category)) : ''),
    'publications' => $publications,
    'breadcrumb_title' => 'Publications & Reports',
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container">
        <p class="section-tag" style="color: var(--secondary-container);">Open Knowledge &amp; Evidence</p>
        <h1>Publications and Reports</h1>
        <p class="lead">Actionable research, open data toolkits, policy analyses, and community reports produced by BuniAfrika and collaborators.</p>
    </div>
</section>

<section class="section container">
    <!-- Category Filter Controls -->
    <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; margin-bottom: 32px; padding-bottom: 20px; border-bottom: 1px solid var(--outline-variant);">
        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
            <a href="publications.php" class="btn <?php echo empty($filter_category) ? 'btn-primary' : 'btn-outline'; ?>" style="padding: 8px 16px; font-size: 13px;">
                All Categories
            </a>
            <?php foreach ($all_cats as $cat): ?>
                <a href="publications.php?category=<?php echo urlencode($cat); ?>" class="btn <?php echo $filter_category === $cat ? 'btn-primary' : 'btn-outline'; ?>" style="padding: 8px 16px; font-size: 13px;">
                    <?php echo h($cat); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div style="position: relative; min-width: 260px;">
            <input type="text" id="publication-search" class="form-control" placeholder="Search by title or topic..." style="padding: 10px 14px 10px 38px; border-radius: var(--radius-full); width: 100%; font-size: 13px; border: 1px solid var(--outline-variant); background: var(--surface);">
            <span class="material-symbols-outlined" style="position: absolute; left: 12px; top: 10px; font-size: 18px; color: var(--on-surface-variant); pointer-events: none;">search</span>
        </div>
    </div>

    <!-- Publications Grid -->
    <?php if (!empty($publications)): ?>
        <div id="publications-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 28px;">
            <?php foreach ($publications as $pub): ?>
                <article class="bento-card pub-item" data-title="<?php echo h(strtolower($pub['title'])); ?>" data-category="<?php echo h(strtolower($pub['category'])); ?>" data-author="<?php echo h(strtolower($pub['author'])); ?>" style="display: flex; flex-direction: column; justify-content: space-between; padding: 24px;">
                    <div>
                        <div style="display: flex; gap: 16px; margin-bottom: 16px;">
                            <?php if (!empty($pub['cover_image'])): ?>
                                <img src="<?php echo h($pub['cover_image']); ?>" alt="Cover for <?php echo h($pub['title']); ?>" style="width: 72px; height: 96px; object-fit: cover; border-radius: var(--radius-sm); border: 1px solid var(--outline-variant); flex-shrink: 0; box-shadow: var(--shadow-sm);">
                            <?php else: ?>
                                <div style="width: 72px; height: 96px; border-radius: var(--radius-sm); background: var(--secondary-container); color: var(--on-secondary-container); display: flex; flex-direction: column; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <span class="material-symbols-outlined" style="font-size: 32px;">description</span>
                                    <span style="font-size: 10px; font-weight: 700; margin-top: 4px;">PDF</span>
                                </div>
                            <?php endif; ?>

                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                <span class="badge badge-info" style="align-self: flex-start;"><?php echo h($pub['category']); ?></span>
                                <span style="font-size: 12px; color: var(--on-surface-variant);">
                                    <?php echo date('F Y', strtotime($pub['published_date'])); ?>
                                </span>
                                <?php if (!empty($pub['file_size'])): ?>
                                    <span style="font-size: 11px; color: var(--on-surface-variant); font-family: monospace;">
                                        <?php echo h($pub['file_size']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <h2 style="font-size: 18px; margin: 0 0 10px 0; color: var(--primary); line-height: 1.35;">
                            <?php echo h($pub['title']); ?>
                        </h2>

                        <p class="section-desc" style="font-size: 14px; margin: 0 0 16px 0; line-height: 1.55;">
                            <?php echo h($pub['summary']); ?>
                        </p>

                        <?php if (!empty($pub['author'])): ?>
                            <div style="font-size: 12px; color: var(--on-surface-variant); margin-bottom: 20px;">
                                <strong>Author:</strong> <?php echo h($pub['author']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding-top: 16px; border-top: 1px solid var(--outline-variant); flex-wrap: wrap;">
                        <span style="font-size: 12px; color: var(--on-surface-variant); display: flex; align-items: center; gap: 4px;">
                            <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                            <span><?php echo number_format((int)$pub['download_count']); ?> downloads</span>
                        </span>

                        <div style="display: flex; gap: 8px;">
                            <a href="download-publication.php?id=<?php echo (int)$pub['id']; ?>&view=1" target="_blank" rel="noopener noreferrer" class="btn btn-outline" style="padding: 6px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-symbols-outlined" style="font-size: 15px;">visibility</span> Read
                            </a>
                            <a href="download-publication.php?id=<?php echo (int)$pub['id']; ?>" class="btn btn-primary" style="padding: 6px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                <span class="material-symbols-outlined" style="font-size: 15px;">download</span> Download
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="empty-state" style="text-align: center; padding: 64px 20px; background: var(--surface); border-radius: var(--radius-xl); border: 1px solid var(--outline-variant); box-shadow: var(--shadow-sm); max-width: 600px; margin: 0 auto;">
            <span class="material-symbols-outlined" style="font-size: 56px; color: var(--on-surface-variant); margin-bottom: 12px; display: block;" aria-hidden="true">menu_book</span>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">Content Not Available</h3>
            <p style="color: var(--on-surface-variant); font-size: 15px; max-width: 480px; margin: 0 auto; line-height: 1.6;">
                <?php echo !empty($filter_category) ? 'There are currently no reports listed under the "' . h($filter_category) . '" category. Please check back later.' : 'The information you are looking for is currently unavailable. Please check back later.'; ?>
            </p>
            <?php if (!empty($filter_category)): ?>
                <p style="margin-top: 20px;"><a href="publications.php" class="btn btn-outline">View All Publications</a></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('publication-search');
    const items = document.querySelectorAll('.pub-item');
    if (!searchInput || items.length === 0) return;

    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase().trim();
        items.forEach(item => {
            const title = item.getAttribute('data-title') || '';
            const cat = item.getAttribute('data-category') || '';
            const author = item.getAttribute('data-author') || '';
            const matches = title.includes(query) || cat.includes(query) || author.includes(query);
            item.style.display = matches ? 'flex' : 'none';
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

