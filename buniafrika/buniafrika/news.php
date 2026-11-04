<?php

$page_title = 'Research and News | BuniAfrika CBO';
$meta_desc = 'Read research, perspectives and updates published by BuniAfrika CBO.';

require_once __DIR__ . '/includes/header.php';

// Fetch articles
$articles = getNewsArticles($conn);
// Fetch upcoming events
$events = getUpcomingEvents($conn);
?>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-overlay"></div>
    <img class="hero-bg" style="opacity: 0.7" src="<?php echo $base_path; ?>assets/news0.jpeg" alt="Community gathering"/>
    <div class="hero-content">
        <h1 class="hero-title poppins-bold">News &amp; Field Stories</h1>
        <p class="hero-desc poppins-medium">Research, articles and updates shared by BuniAfrika CBO.</p>
        <div style="margin-top: 16px;">
            <a href="publications.php" class="btn btn-secondary" style="font-size: 13px; padding: 8px 18px; display: inline-flex; align-items: center; gap: 6px;">
                <span class="material-symbols-outlined" style="font-size: 16px;">description</span>
                Browse Publications &amp; Reports Library &rarr;
            </a>
        </div>
    </div>
</section>

<!-- Main News Feed Layout -->
<section class="section container">
    <div class="news-layout">
        <!-- Main News Column -->
        <div class="news-main" id="articles-container">
            <h2 class="visually-hidden">Latest Articles &amp; Updates</h2>
            <?php if (empty($articles)): ?>
                <div class="empty-state" style="text-align: center; padding: 64px 20px; background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-xl); box-shadow: var(--shadow-sm); margin-bottom: 32px;">
                    <span class="material-symbols-outlined" style="font-size: 56px; color: var(--on-surface-variant); margin-bottom: 12px; display: block;" aria-hidden="true">article</span>
                    <h3 style="font-size: 20px; font-weight: 700; color: var(--primary); margin: 0 0 8px 0;">Content Not Available</h3>
                    <p style="color: var(--on-surface-variant); font-size: 15px; max-width: 480px; margin: 0 auto; line-height: 1.6;">No research publications or news articles are currently available. Please check back later.</p>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                    <article class="news-card news-card-horizontal js-article-card poppins-medium"
                             data-title="<?php echo h(strtolower($article['title'])); ?>"
                             data-summary="<?php echo h(strtolower($article['summary'])); ?>"
                             data-category="<?php echo h(strtolower($article['category'])); ?>">
                        <div class="news-card-img-wrapper" style="background: #000; display: flex; align-items: center; justify-content: center; position: relative;">
                                
                            <?php if (!empty($article['image_url'])): ?>
                                <img class="news-card-img" src="<?php echo h($article['image_url']); ?>" alt="<?php echo h($article['title']); ?>" loading="lazy"/>
                            <?php elseif (!empty($article['video_url'])): ?>
                                <?php 
                                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $article['video_url'], $match)): 
                                    $video_id = $match[1];
                                ?>
                                    <iframe src="https://www.youtube.com/embed/<?php echo h($video_id); ?>?controls=0&mute=1&autoplay=0" frameborder="0" style="width: 100%; height: 100%; object-fit: cover;"></iframe>
                                <?php elseif (preg_match('%vimeo\.com/(?:channels/(?:\w+\/)?|groups/([^/]*)/videos/|album/(\d+)/video/|video/|)(\d+)(?:$|\/|\?)%i', $article['video_url'], $match)): 
                                    $video_id = $match[3];
                                ?>
                                    <iframe src="https://player.vimeo.com/video/<?php echo h($video_id); ?>?background=1&autoplay=0" frameborder="0" style="width: 100%; height: 100%; object-fit: cover;"></iframe>
                                <?php else: ?>
                                    <video src="<?php echo h($article['video_url']); ?>" style="width: 100%; height: 100%; object-fit: cover;"></video>
                                <?php endif; ?>
                            <?php else: ?>
                                <div style="font-size: 48px; color: var(--on-surface-variant); display: flex; align-items: center; justify-content: center;"><span class="material-symbols-outlined" style="font-size: 48px;">photo</span></div>
                            <?php endif; ?>
                        </div>
                        <div class="news-card-content">
                            <div class="news-meta">
                                <span class="news-cat"><?php echo h($article['category']); ?></span>
                                <span class="news-meta-dot"></span>
                                <time><?php echo date('M d, Y', strtotime($article['published_date'])); ?></time>
                                <span class="news-meta-dot"></span>
                                <span class="poppins-regular-italic">By <?php echo h($article['author']); ?></span>
                            </div>
                            <h3 class="news-title poppins-bold"><?php echo h($article['title']); ?></h3>
                            <p class="news-summary poppins-medium-italic" style="margin-bottom: var(--spacing-sm);">
                                <?php
                                $summary = $article['summary'];
                                $limit = 150;
                                
                                if (strlen($summary) > $limit) {
                                    $lastSentenceEnd = max(
                                        strrpos($summary, '.'),
                                        strrpos($summary, '!'),
                                        strrpos($summary, '?')
                                    );
                                    
                                    if ($lastSentenceEnd === false || $lastSentenceEnd < $limit) {
                                        $summary = substr($summary, 0, $limit) . '...';
                                    } else {
                                        $summary = substr($summary, 0, $lastSentenceEnd + 1);
                                    }
                                }
                                echo h($summary);
                                ?>
                            </p>
                            <div class="js-article-full-content rich-content" style="font-size: 14px; color: var(--on-surface); line-height: 1.6; display: none; padding: 12px 0 0;">
                                    <?php echo renderFrontendVideo($article['video_url'] ?? ''); ?>
                                    <?php echo safeHtml($article['content']); ?>
                                    <?php echo renderFeaturedMediaGallery($article['featured_media'] ?? ''); ?>
                                </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 12px; gap: 12px; flex-wrap: wrap;">
                                <button class="btn btn-outline js-read-more-btn" onclick="toggleReadMore(this)" style="padding: 6px 16px; font-size: 12px;">Read Full Story</button>
                                <div class="social-share" style="display: flex; gap: 12px; align-items: center;">
                                    <span style="font-size: 12px; color: var(--on-surface-variant); font-weight: 600;">Share:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?article=' . $article['id']); ?>" target="_blank" style="color: #1877F2; display: flex; align-items: center;" title="Share on Facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Check out this news: ' . $article['title'] . ' - http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?article=' . $article['id']); ?>" target="_blank" style="color: #25D366; display: flex; align-items: center;" title="Share on WhatsApp">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.012 2c5.506 0 9.988 4.478 9.988 9.985 0 5.508-4.482 9.985-9.988 9.985-1.745 0-3.39-.447-4.836-1.233l-5.176 1.353 1.383-5.048A9.914 9.914 0 012.024 11.985C2.024 6.478 6.506 2 12.012 2zm0 1.666C7.432 3.666 3.69 7.412 3.69 11.985c0 1.802.57 3.518 1.624 4.935l.17.228-.823 3.003 3.082-.807.222.13c1.36.797 2.923 1.218 4.547 1.218 4.58 0 8.322-3.746 8.322-8.318 0-4.573-3.742-8.318-8.322-8.318zM8.884 7.62c-.22-.004-.467.08-.667.275-.54.526-.645 1.355-.262 2.054.49 1.01 1.272 1.95 2.152 2.662 1.487 1.2 2.983 2.015 4.567 2.37.525.118 1.07.054 1.488-.236.425-.295.66-.757.722-1.242.062-.486-.23-.9-.675-1.12l-1.95-.956c-.36-.176-.788-.08-1.05.234l-.608.73c-.15.18-.396.23-.604.126-1.15-.56-2.275-1.428-2.92-2.32-.113-.157-.09-.373.056-.51l.525-.518c.24-.236.31-.58.172-.888l-.75-1.897c-.174-.442-.647-.69-1.11-.635z"/></svg>
                                    </a>
                                    <a href="https://www.instagram.com/" target="_blank" style="color: #E1306C; display: flex; align-items: center;" title="Share on Instagram">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                </div>
                            </div>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="news-sidebar">
            <h2 class="visually-hidden">Sidebar Resources</h2>
            <div class="widget">
                <h4 class="widget-title poppins-bold">Search Articles</h4>
                <div class="search-bar-wrapper">
                    <input class="search-bar-input" id="search-input" type="text" placeholder="Type keywords..." oninput="searchArticles()"/>
                    <span class="material-symbols-outlined search-bar-icon">search</span>
                </div>
            </div>

            <div class="widget" style="background: linear-gradient(135deg, var(--primary) 0%, var(--primary-container) 100%); color: #ffffff; border: none; box-shadow: var(--shadow-md);">
                <h4 class="widget-title poppins-bold" style="color: #ffffff; display: flex; align-items: center; gap: 8px; margin-bottom: var(--spacing-sm);">
                    <span class="material-symbols-outlined" style="color: var(--secondary-container);">edit_note</span>
                    Share Your Story
                </h4>
                <p style="font-size: 13px; opacity: 0.95; margin-bottom: var(--spacing-md); line-height: 1.5; font-family: var(--font-body);">
                    Have a field report, community update, or positive story to share? Submit your article draft to be reviewed and featured on our portal!
                </p>
                <a href="<?php echo $base_path; ?>submit_article.php" class="btn btn-secondary w-full" style="text-align: center; display: block; font-size: 12px; padding: 10px 16px; border-radius: var(--radius-full); text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700;">
                    Write & Submit Article
                </a>
            </div>

            <div class="widget" id="events" style="background-color: var(--surface-container-low);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-md); flex-wrap: wrap; gap: 8px;">
                    <h4 class="widget-title poppins-bold" style="display: flex; align-items: center; gap: 8px; margin: 0;">
                        <span class="material-symbols-outlined" style="color: var(--primary);">event</span>
                        <a href="events.php" style="color: inherit; text-decoration: none;">Upcoming Events</a>
                    </h4>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <a href="events.php" class="btn btn-outline" style="padding: 4px 10px; font-size: 11px; text-decoration: none;">All Events &rarr;</a>
                        <?php if (!empty($events)): ?>
                            <div class="view-toggle-container">
                                <button id="btn-list-view" class="view-toggle-btn active" onclick="switchEventView('list')">List</button>
                                <button id="btn-calendar-view" class="view-toggle-btn" onclick="switchEventView('calendar')">Calendar</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (empty($events)): ?>
                    <p style="font-size: 13px; color: var(--on-surface-variant); font-style: italic;">No upcoming events scheduled at the moment.</p>
                <?php else: ?>
                    <div id="events-list-view" style="display: flex; flex-direction: column; gap: 16px;">
                        <?php foreach ($events as $event): ?>
                            <div style="border-left: 3px solid var(--primary); padding-left: 12px;">
                                <h5 class="poppins-bold" style="font-size: 14px; margin-bottom: 4px;"><?php echo h($event['title']); ?></h5>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--on-surface-variant); margin-bottom: 4px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">calendar_today</span>
                                    <span><?php echo date('M d, Y - h:i A T', strtotime($event['event_date'])); ?></span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--on-surface-variant); margin-bottom: 6px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">location_on</span>
                                    <span>
                                        <?php if (!empty($event['location_link'])): ?>
                                            <a href="<?php echo h($event['location_link']); ?>" target="_blank" style="color: inherit; text-decoration: underline; font-weight: 600;"><?php echo h($event['location']); ?></a>
                                        <?php else: ?>
                                            <?php echo h($event['location']); ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <?php if (!empty($event['meeting_link'])): ?>
                                <div style="display: flex; align-items: center; gap: 4px; font-size: 12px; color: var(--on-surface-variant); margin-bottom: 6px;">
                                    <span class="material-symbols-outlined" style="font-size: 14px;">link</span>
                                    <a href="<?php echo h($event['meeting_link']); ?>" target="_blank" style="color: var(--primary); text-decoration: underline; font-weight: 600;">Join Meeting</a>
                                </div>
                                <?php endif; ?>
                               <?php
$shortDesc = mb_strlen($event['description']) > 120
    ? mb_substr($event['description'], 0, 120) . '...'
    : $event['description'];
?>

<p style="font-size:13px; color:var(--on-surface); line-height:1.4; margin:0;">
    <?php echo h($shortDesc); ?>
</p>
<button
    class="btn btn-outline"
    style="margin-top:10px; padding:6px 14px; font-size:12px;"
    onclick='showEventDetails(
        <?php echo json_encode($event["title"]); ?>,
        <?php echo json_encode(date("M d, Y - h:i A", strtotime($event["event_date"]))); ?>,
        <?php echo json_encode($event["location"]); ?>,
        <?php echo json_encode($event["location_link"]); ?>,
        <?php echo json_encode($event["meeting_link"]); ?>,
        <?php echo json_encode($event["description"]); ?>
    )'>
    Read More
</button>
                                <div class="social-share" style="display: flex; gap: 10px; align-items: center; margin-top: 8px;">
                                    <span style="font-size: 11px; color: var(--on-surface-variant); font-weight: 600;">Share:</span>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?event=' . $event['id']); ?>" target="_blank" style="color: #1877F2; display: flex; align-items: center;" title="Share on Facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode('Check out this event: ' . $event['title'] . ' - http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?event=' . $event['id']); ?>" target="_blank" style="color: #25D366; display: flex; align-items: center;" title="Share on WhatsApp">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12.012 2c5.506 0 9.988 4.478 9.988 9.985 0 5.508-4.482 9.985-9.988 9.985-1.745 0-3.39-.447-4.836-1.233l-5.176 1.353 1.383-5.048A9.914 9.914 0 012.024 11.985C2.024 6.478 6.506 2 12.012 2zm0 1.666C7.432 3.666 3.69 7.412 3.69 11.985c0 1.802.57 3.518 1.624 4.935l.17.228-.823 3.003 3.082-.807.222.13c1.36.797 2.923 1.218 4.547 1.218 4.58 0 8.322-3.746 8.322-8.318 0-4.573-3.742-8.318-8.322-8.318zM8.884 7.62c-.22-.004-.467.08-.667.275-.54.526-.645 1.355-.262 2.054.49 1.01 1.272 1.95 2.152 2.662 1.487 1.2 2.983 2.015 4.567 2.37.525.118 1.07.054 1.488-.236.425-.295.66-.757.722-1.242.062-.486-.23-.9-.675-1.12l-1.95-.956c-.36-.176-.788-.08-1.05.234l-.608.73c-.15.18-.396.23-.604.126-1.15-.56-2.275-1.428-2.92-2.32-.113-.157-.09-.373.056-.51l.525-.518c.24-.236.31-.58.172-.888l-.75-1.897c-.174-.442-.647-.69-1.11-.635z"/></svg>
                                    </a>
                                    <a href="https://www.instagram.com/" target="_blank" style="color: #E1306C; display: flex; align-items: center;" title="Share on Instagram">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="events-calendar-view" style="display: none; margin-top: 8px;">
                        <div id="calendar"></div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="widget">
                <h4 class="widget-title poppins-bold">Join Our Mailing List</h4>
                <p class="section-desc mb-md" style="font-size: 13px;">Receive updates when BuniAfrika shares news and information.</p>
                <form id="sidebar-newsletter-form" style="display: flex; flex-direction: column; gap: var(--spacing-xs);">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
                    <div hidden aria-hidden="true" style="position:absolute; left:-10000px;">
                        <label for="sidebar_company_website">Leave this field empty</label>
                        <input type="text" id="sidebar_company_website" name="company_website" tabindex="-1" autocomplete="off">
                    </div>
                    <input class="form-control" type="email" name="email" placeholder="email@domain.com" required style="font-size: 14px;"/>
                    <button type="submit" class="btn btn-secondary w-full" style="font-size: 12px; padding: 10px;">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Event Detail Modal (Premium Design) -->
<div id="event-detail-modal" class="event-detail-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="event-modal-title" style="display: none;">
    <div class="event-detail-modal-card">
        <button class="event-detail-modal-close" onclick="closeEventModal()" aria-label="Close modal">
            <span class="material-symbols-outlined">close</span>
        </button>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; align-items: flex-start; gap: 8px; color: var(--primary); padding-right: 24px;">
                <span class="material-symbols-outlined" style="font-size: 24px; margin-top: 2px;">event</span>
                <h3 id="event-modal-title" class="poppins-bold" style="margin: 0; font-size: 16px; line-height: 1.3;">Event Title</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 8px; border-top: 1px solid var(--outline-variant); border-bottom: 1px solid var(--outline-variant); padding: 12px 0;">
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--on-surface-variant);">
                    <span class="material-symbols-outlined" style="font-size: 16px; color: var(--primary);">calendar_today</span>
                    <span id="event-modal-date" style="font-weight: 600;">Date & Time</span>
                </div>
                <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--on-surface-variant);">
                    <span class="material-symbols-outlined" style="font-size: 16px; color: var(--primary);">location_on</span>
                    <span id="event-modal-location" style="font-weight: 600;">Location</span>
                </div>
                <div id="event-modal-meeting-container" style="display: none; align-items: center; gap: 8px; font-size: 13px; color: var(--on-surface-variant);">
                    <span class="material-symbols-outlined" style="font-size: 16px; color: var(--primary);">link</span>
                    <a id="event-modal-meeting-link" href="#" target="_blank" style="font-weight: 600; color: var(--primary); text-decoration: underline;">Join Meeting</a>
                </div>
            </div>
            <div>
                <p id="event-modal-desc" style="font-size: 13px; color: var(--on-surface); line-height: 1.5; margin: 0; white-space: pre-wrap;">Event Description</p>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: var(--spacing-xs); flex-wrap: wrap; gap: 12px;">
                <div class="social-share" style="display: flex; gap: 12px; align-items: center;">
                    <span style="font-size: 12px; color: var(--on-surface-variant); font-weight: 600;">Share:</span>
                    <a id="event-share-fb" href="#" target="_blank" style="color: #1877F2; display: flex; align-items: center;" title="Share on Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </a>
                    <a id="event-share-wa" href="#" target="_blank" style="color: #25D366; display: flex; align-items: center;" title="Share on WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12.012 2c5.506 0 9.988 4.478 9.988 9.985 0 5.508-4.482 9.985-9.988 9.985-1.745 0-3.39-.447-4.836-1.233l-5.176 1.353 1.383-5.048A9.914 9.914 0 012.024 11.985C2.024 6.478 6.506 2 12.012 2zm0 1.666C7.432 3.666 3.69 7.412 3.69 11.985c0 1.802.57 3.518 1.624 4.935l.17.228-.823 3.003 3.082-.807.222.13c1.36.797 2.923 1.218 4.547 1.218 4.58 0 8.322-3.746 8.322-8.318 0-4.573-3.742-8.318-8.322-8.318zM8.884 7.62c-.22-.004-.467.08-.667.275-.54.526-.645 1.355-.262 2.054.49 1.01 1.272 1.95 2.152 2.662 1.487 1.2 2.983 2.015 4.567 2.37.525.118 1.07.054 1.488-.236.425-.295.66-.757.722-1.242.062-.486-.23-.9-.675-1.12l-1.95-.956c-.36-.176-.788-.08-1.05.234l-.608.73c-.15.18-.396.23-.604.126-1.15-.56-2.275-1.428-2.92-2.32-.113-.157-.09-.373.056-.51l.525-.518c.24-.236.31-.58.172-.888l-.75-1.897c-.174-.442-.647-.69-1.11-.635z"/></svg>
                    </a>
                    <a id="event-share-ig" href="https://www.instagram.com/" target="_blank" style="color: #E1306C; display: flex; align-items: center;" title="Share on Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                    </a>
                </div>
                <button onclick="closeEventModal()" class="btn btn-outline" style="padding: 6px 18px; font-size: 12px;">Close</button>
            </div>
        </div>
    </div>
</div>

<?php
$js_events = [];
foreach ($events as $event) {
    $js_events[] = [
        'id' => (int)$event['id'],
        'title' => $event['title'],
        'start' => $event['event_date'],
        'extendedProps' => [
            'description' => $event['description'],
            'location' => $event['location'],
            'location_link' => $event['location_link'] ?? '',
            'meeting_link' => $event['meeting_link'] ?? '',
            'formatted_date' => date('M d, Y - h:i A T', strtotime($event['event_date']))
        ]
    ];
}
?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
const eventsData = <?php echo json_encode($js_events); ?>;
let calendar;
let calendarInitialized = false;

function switchEventView(viewType) {
    const listView = document.getElementById('events-list-view');
    const calendarView = document.getElementById('events-calendar-view');
    const btnList = document.getElementById('btn-list-view');
    const btnCalendar = document.getElementById('btn-calendar-view');

    if (!listView || !calendarView || !btnList || !btnCalendar) return;

    if (viewType === 'list') {
        listView.style.display = 'flex';
        calendarView.style.display = 'none';
        btnList.classList.add('active');
        btnCalendar.classList.remove('active');
    } else {
        listView.style.display = 'none';
        calendarView.style.display = 'block';
        btnList.classList.remove('active');
        btnCalendar.classList.add('active');

        if (!calendarInitialized) {
            initCalendar();
            calendarInitialized = true;
        } else {
            calendar.updateSize();
        }
    }
}

function initCalendar() {
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        events: eventsData,
        eventClick: function(info) {
            showEventModal(info.event);
        },
        height: 'auto',
        contentHeight: 'auto',
        aspectRatio: 1.2
    });
    calendar.render();
}

function showEventModal(event) {
    const modal = document.getElementById('event-detail-modal');
    if (!modal) return;
    
    document.getElementById('event-modal-title').textContent = event.title;
    document.getElementById('event-modal-date').textContent = event.extendedProps.formatted_date;
    
    const locEl = document.getElementById('event-modal-location');
    if (event.extendedProps.location_link) {
        locEl.innerHTML = `<a href="${event.extendedProps.location_link}" target="_blank" style="color: inherit; text-decoration: underline;">${event.extendedProps.location}</a>`;
    } else {
        locEl.textContent = event.extendedProps.location;
    }

    const meetingContainer = document.getElementById('event-modal-meeting-container');
    const meetingLink = document.getElementById('event-modal-meeting-link');
    if (event.extendedProps.meeting_link) {
        meetingLink.href = event.extendedProps.meeting_link;
        meetingContainer.style.display = 'flex';
    } else {
        meetingContainer.style.display = 'none';
    }

    document.getElementById('event-modal-desc').textContent = event.extendedProps.description;
    
    // Setup sharing links for the event
    const shareUrl = window.location.href.split('?')[0] + '?event=' + event.id;
    const fbShare = document.getElementById('event-share-fb');
    const waShare = document.getElementById('event-share-wa');
    if (fbShare) fbShare.href = 'https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(shareUrl);
    if (waShare) waShare.href = 'https://api.whatsapp.com/send?text=' + encodeURIComponent('Check out this event: ' + event.title + ' - ' + shareUrl);
    
    modal.style.display = 'flex';
    // Trigger reflow
    modal.offsetHeight;
    modal.classList.add('active');
}

function closeEventModal() {
    const modal = document.getElementById('event-detail-modal');
    if (!modal) return;
    
    modal.classList.remove('active');
    setTimeout(() => {
        modal.style.display = 'none';
    }, 300);
}

window.addEventListener('click', function(event) {
    const modal = document.getElementById('event-detail-modal');
    if (modal && event.target === modal) {
        closeEventModal();
    }
});
</script>

<script src="js/news.js"></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
