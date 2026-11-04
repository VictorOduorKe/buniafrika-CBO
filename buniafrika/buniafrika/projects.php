<?php
$page_title = 'Projects | BuniAfrika CBO';
$meta_desc = 'Explore projects published by BuniAfrika CBO through its existing content management system.';

require_once __DIR__ . '/includes/header.php';

$selected_cat = isset($_GET['cat']) ? trim($_GET['cat']) : 'all';

$projects = getProjects($conn);

$categories = [];
$cat_result = $conn->query("SELECT DISTINCT c.name AS category FROM projects p JOIN categories c ON p.category_id = c.id WHERE p.published = 1 ORDER BY c.name ASC");
if ($cat_result && $cat_result->num_rows > 0) {
    while ($row = $cat_result->fetch_assoc()) {
        if (!empty($row['category'])) {
            $categories[] = $row['category'];
        }
    }
}
?>

<section class="hero">
    <div class="hero-overlay"></div>
    <img class="hero-bg" style="opacity: 0.8" src="<?php echo $base_path; ?>assets/lewa7.jpeg" alt="Community members working together"/>
    <div class="hero-content">
        <h1 class="hero-title poppins-bold">Our Active Projects</h1>
        <p class="hero-desc poppins-regular">Witness the real-time impact of transparency and compassion across global communities.</p>
    </div>
</section>

<section class="section-bg-low" style="padding: 16px 0; position: sticky; top: 64px; z-index: 40; border-bottom: 1px solid var(--outline-variant);">
    <div class="container" style="display: flex; justify-content: center; overflow-x: auto; white-space: nowrap; -webkit-overflow-scrolling: touch;">
        <div class="gallery-filter-bar" style="margin-bottom: 0;">
            <button class="poppins-bold gallery-filter-btn js-filter-btn <?php echo $selected_cat === 'all' ? 'active' : ''; ?>" onclick="filterProjects('all')">Projects</button>
            <?php foreach ($categories as $cat): ?>
                <button class="poppins-bold gallery-filter-btn js-filter-btn <?php echo $selected_cat === strtolower($cat) ? 'active' : ''; ?>" onclick="filterProjects('<?php echo h(strtolower($cat)); ?>')"><?php echo h($cat); ?></button>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section container">
    <h2 class="visually-hidden">Initiatives Portfolio</h2>
    <div class="projects-grid" id="projects-container">
        <?php if (empty($projects)): ?>
            <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 64px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm);">
                <span class="material-symbols-outlined" style="font-size: 56px; color: var(--on-surface-variant); margin-bottom: 12px; display: block;" aria-hidden="true">folder_off</span>
                <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">Content Not Available</h3>
                <p style="color: var(--on-surface-variant); font-size: 15px; max-width: 480px; margin: 0 auto; line-height: 1.6;">The information you are looking for is currently unavailable. Please check back later.</p>
            </div>
        <?php else: ?>
            <?php foreach ($projects as $proj): ?>
                <div class="project-card js-project-card" data-category="<?php echo h($proj['category']); ?>">
                    <div class="project-img-wrapper" style="background: #000; display: flex; align-items: center; justify-content: center; position: relative;">
                        <?php if (!empty($proj['image_url'])): ?>
                            <img class="project-img" src="<?php echo h($proj['image_url']); ?>" alt="<?php echo h($proj['title']); ?>"/>
                        <?php elseif (!empty($proj['video_url'])): ?>
                            <?php 
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $proj['video_url'], $match)): 
                                $video_id = $match[1];
                            ?>
                                <iframe src="https://www.youtube.com/embed/<?php echo h($video_id); ?>?controls=0&mute=1&autoplay=0" frameborder="0" style="width: 100%; height: 100%; object-fit: cover;"></iframe>
                            <?php elseif (preg_match('%vimeo\.com/(?:channels/(?:\w+\/)?|groups/([^/]*)/videos/|album/(\d+)/video/|video/|)(\d+)(?:$|\/|\?)%i', $proj['video_url'], $match)): 
                                $video_id = $match[3];
                            ?>
                                <iframe src="https://player.vimeo.com/video/<?php echo h($video_id); ?>?background=1&autoplay=0" frameborder="0" style="width: 100%; height: 100%; object-fit: cover;"></iframe>
                            <?php else: ?>
                                <video src="<?php echo h($proj['video_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;"></video>
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="font-size: 48px; color: var(--on-surface-variant); display: flex; align-items: center; justify-content: center;"><span class="material-symbols-outlined" style="font-size: 48px;">photo</span></div>
                        <?php endif; ?>
                    </div>
                    <div class="project-content">
                        <span class="project-category poppins-medium"><?php echo h($proj['category']); ?></span>
                        <h3 class="project-title poppins-bold"><?php echo h($proj['title']); ?></h3>
                        <div class="js-proj-block" style="margin-bottom: var(--spacing-sm); display: flex; flex-direction: column; gap: var(--spacing-sm);">
                            <p class="proj-excerpt poppins-regular" style="color:var(--on-surface-variant); font-size:14px; margin-bottom:var(--spacing-sm);"><?php echo h(getHtmlExcerpt($proj['description'], 140)); ?></p>
                            <div class="proj-full rich-content" style="display:none; font-size:14px; color:var(--on-surface); line-height:1.6; margin-bottom:var(--spacing-sm);">
                                <?php echo renderFrontendVideo($proj['video_url'] ?? ''); ?>
                                <?php echo safeHtml($proj['description']); ?>
                                <?php echo renderFeaturedMediaGallery($proj['featured_media'] ?? ''); ?>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-md); flex-wrap: wrap; gap: 12px;">
                                <button class="btn btn-outline" onclick="toggleProjectDesc(this)" style="padding:6px 16px; font-size:12px;">Full Description</button>
                                <div class="social-share" style="display: flex; gap: 12px; align-items: center;">
                                    <span style="font-size: 12px; color: var(--on-surface-variant); font-weight: 600;">Share:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?project=' . $proj['id']); ?>" target="_blank" style="color: #1877F2; display: flex; align-items: center;" title="Share on Facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Check out this project: ' . $proj['title'] . ' - http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?project=' . $proj['id']); ?>" target="_blank" style="color: #25D366; display: flex; align-items: center;" title="Share on WhatsApp">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.012 2c5.506 0 9.988 4.478 9.988 9.985 0 5.508-4.482 9.985-9.988 9.985-1.745 0-3.39-.447-4.836-1.233l-5.176 1.353 1.383-5.048A9.914 9.914 0 012.024 11.985C2.024 6.478 6.506 2 12.012 2zm0 1.666C7.432 3.666 3.69 7.412 3.69 11.985c0 1.802.57 3.518 1.624 4.935l.17.228-.823 3.003 3.082-.807.222.13c1.36.797 2.923 1.218 4.547 1.218 4.58 0 8.322-3.746 8.322-8.318 0-4.573-3.742-8.318-8.322-8.318zM8.884 7.62c-.22-.004-.467.08-.667.275-.54.526-.645 1.355-.262 2.054.49 1.01 1.272 1.95 2.152 2.662 1.487 1.2 2.983 2.015 4.567 2.37.525.118 1.07.054 1.488-.236.425-.295.66-.757.722-1.242.062-.486-.23-.9-.675-1.12l-1.95-.956c-.36-.176-.788-.08-1.05.234l-.608.73c-.15.18-.396.23-.604.126-1.15-.56-2.275-1.428-2.92-2.32-.113-.157-.09-.373.056-.51l.525-.518c.24-.236.31-.58.172-.888l-.75-1.897c-.174-.442-.647-.69-1.11-.635z"/></svg>
                                    </a>
                                    <a href="https://www.instagram.com/" target="_blank" style="color: #E1306C; display: flex; align-items: center;" title="Share on Instagram">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <a href="register.php?role=volunteer" class="btn btn-primary w-full text-center">Get Involved</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<section class="section" style="background-color: var(--primary-container); color: #ffffff;">
    <div class="container text-center" style="max-width: 800px;">
        <h2 class="mb-sm poppins-bold" style="color: #ffffff; font-size: 28px;">Receive updates from BuniAfrika</h2>
        <p class="section-desc mb-lg poppins-regular" style="color: var(--on-primary-container);">Subscribe to receive updates and public information shared by BuniAfrika CBO.</p>
        <form class="flex" style="flex-direction: column; gap: var(--spacing-sm); max-width: 500px; margin: 0 auto; width: 100%;" id="project-newsletter-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
            <div hidden aria-hidden="true" style="position:absolute; left:-10000px;">
                <label for="project_company_website">Leave this field empty</label>
                <input type="text" id="project_company_website" name="company_website" tabindex="-1" autocomplete="off">
            </div>
            <input class="form-control poppins-regular" type="email" name="email" placeholder="Enter your email address" required style="border-radius: var(--radius-full); text-align: center;"/>
            <button type="submit" class="btn btn-secondary w-full poppins-medium" style="border-radius: var(--radius-full);">Subscribe</button>
        </form>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="js/projects.js"></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
