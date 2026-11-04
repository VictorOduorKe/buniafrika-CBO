<?php

$page_title = 'Gallery | BuniAfrika CBO';
$meta_desc = 'Browse public media and stories shared by BuniAfrika CBO.';

require_once __DIR__ . '/includes/header.php';
        
$items = getGalleryItems($conn);
$gallery_categories = [];
foreach ($items as $item) {
    if (!empty($item['category'])) {
        $gallery_categories[$item['category']] = $item['category'];
    }
}
?>

<section class="hero">
    <div class="hero-overlay"></div>
    <img class="hero-bg" style="opacity: 0.7" src="<?php echo $base_path; ?>assets/lewa4.jpeg" alt="Landscape photograph"/>
    <div class="hero-content">
        <h1 class="hero-title poppins-bold">Gallery</h1>
        <p class="hero-desc poppins-regular">Browse media shared by BuniAfrika CBO.</p>
    </div>
</section>

<section class="section-bg-low" style="padding: 16px 0; position: sticky; top: 64px; z-index: 40; border-bottom: 1px solid var(--outline-variant);">
    <div class="container" style="display: flex; justify-content: center; overflow-x: auto; white-space: nowrap;">
        <div class="gallery-filter-bar" style="margin-bottom: 0;">
            <button type="button" class="gallery-filter-btn js-gallery-filter active poppins-bold" data-gallery-category="all">All Media</button>
            <?php foreach ($gallery_categories as $category): ?>
                <button type="button" class="gallery-filter-btn js-gallery-filter poppins-bold" data-gallery-category="<?php echo h($category); ?>"><?php echo h($category); ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section container">
    <h2 class="visually-hidden">Photo &amp; Video Documentation</h2>
    <?php if (empty($items)): ?>
        <div class="gallery-empty-state" style="text-align: center; padding: 64px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); max-width: 600px; margin: 40px auto;">
            <span class="material-symbols-outlined" style="font-size: 56px; color: var(--on-surface-variant); margin-bottom: 12px; display: block;" aria-hidden="true">photo_library</span>
            <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">Content Not Available</h3>
            <p style="color: var(--on-surface-variant); font-size: 15px; max-width: 480px; margin: 0 auto; line-height: 1.6;">The media gallery is currently being curated. Please check back later.</p>
        </div>
    <?php else: ?>
        <div class="masonry-grid" id="gallery-container">
            <?php foreach ($items as $item): ?>
                <div class="masonry-item js-gallery-item js-gallery-card"
                     data-category="<?php echo h($item['category']); ?>"
                     data-caption="<?php echo h($item['caption']); ?>">
                    
                    <?php if (!empty($item['video_url'])): ?>
                        <div class="masonry-video-container" style="position: relative; width: 100%; height: auto; overflow: hidden; background: #000; border-radius: var(--radius-lg);">
                            <video class="masonry-img" src="<?php echo h($item['video_url']); ?>" controls style="width: 100%; display: block; border-radius: var(--radius-lg);"></video>
                        </div>
                    <?php else: ?>
                        <img class="masonry-img" src="<?php echo h($item['image_url']); ?>" alt="<?php echo h($item['title']); ?>" loading="lazy"/>
                    <?php endif; ?>
                    
                    <div class="masonry-overlay" style="display: flex; flex-direction: column; justify-content: flex-end; pointer-events: none;">
                        <span class="masonry-cat bold" style="color: var(--secondary-container); font-size: 11px; display: block; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.05em; pointer-events: auto;"><?php echo h($item['category']); ?></span>
                        <h3 style="color: #ffffff; font-size: 16px; margin-bottom: 4px; line-height: 1.3; font-weight: 700; pointer-events: auto;"><?php echo h($item['title']); ?></h3>
                        <p style="color: rgba(255,255,255,0.85); font-size: 12px; line-height: 1.4; margin-bottom: 12px; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; pointer-events: auto;">
                            <?php echo h($item['caption']); ?>
                        </p>
                        <?php if (!empty($item['video_url'])): ?>
                            <a href="<?php echo h($item['video_url']); ?>" download="<?php echo h($item['title']); ?>.mp4" class="btn btn-outline" style="padding: 6px 12px; font-size: 11px; display: inline-flex; align-items: center; gap: 4px; border-color: rgba(255,255,255,0.5); color: #fff; border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; align-self: flex-start; pointer-events: auto;">
                                <span class="material-symbols-outlined" style="font-size: 14px;">download</span>Download Video
                            </a>
                        <?php else: ?>
 <a href="download-gallery.php?file=<?php echo urlencode($item['image_url']); ?>"
       class="btn btn-outline"
       style="padding: 6px 12px; font-size: 11px; display: inline-flex; align-items: center; gap: 4px; border-color: rgba(255,255,255,0.5); color: #fff; border-radius: var(--radius-sm); font-weight: 600; text-decoration: none; align-self: flex-start; pointer-events: auto;">
        <span class="material-symbols-outlined" style="font-size: 14px;">download</span>
        Download
    </a>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<script src="<?php echo h($base_path); ?>js/gallery.js"></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
