<?php
/**
 * Dynamic XML Sitemap Generator for BuniAfrika CBO
 * Dynamically queries database for indexable public pages and leadership profiles.
 * Serves valid XML compliant with sitemaps.org protocol schema 0.9.
 */

// Initialize backend connection and SEO helper
require_once __DIR__ . '/../buniafrika.co.ke/includes/functions.php';

// Disable caching for live crawler freshness
if (!headers_sent()) {
    header('Content-Type: application/xml; charset=utf-8');
    header('X-Robots-Tag: noindex, follow');
}

$canonicalDomain = getCanonicalDomain($conn);
$siteSettings = getSiteSettings($conn);

// Map priorities and frequencies for core routes
$routePriorities = [
    'index.php' => ['priority' => '1.0', 'changefreq' => 'weekly'],
    'programmes.php' => ['priority' => '0.9', 'changefreq' => 'monthly'],
    'about.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'publications.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'volunteer.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'contact.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'projects.php' => ['priority' => '0.8', 'changefreq' => 'monthly'],
    'news.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'events.php' => ['priority' => '0.8', 'changefreq' => 'weekly'],
    'mission-vision.php' => ['priority' => '0.7', 'changefreq' => 'monthly'],
    'gallery.php' => ['priority' => '0.6', 'changefreq' => 'monthly'],
    'donate.php' => ['priority' => '0.6', 'changefreq' => 'monthly'],
    'privacy.php' => ['priority' => '0.3', 'changefreq' => 'yearly'],
];

// Query indexable pages from seo_page_metadata
$pages = [];
$stmt = $conn->prepare("SELECT page_route, updated_at FROM seo_page_metadata WHERE robots_index = 1 ORDER BY id ASC");
if ($stmt) {
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $route = $row['page_route'];
        // Skip private or non-canonical duplicate routes
        if (in_array($route, ['login.php', 'register.php', 'reset_password.php', 'submit_article.php'], true)) {
            continue;
        }
        $pages[$route] = $row['updated_at'] ? date('Y-m-d', strtotime($row['updated_at'])) : date('Y-m-d');
    }
    $stmt->close();
}

// Fallback if seo_page_metadata is empty
if (empty($pages)) {
    foreach (array_keys($routePriorities) as $route) {
        $pages[$route] = date('Y-m-d');
    }
}

// Fetch active leadership profiles for dynamic URLs
$leaders = getLeadershipProfiles($siteSettings, true);

// Begin XML output
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Render core public pages
foreach ($pages as $route => $lastmod) {
    $loc = ($route === 'index.php') ? $canonicalDomain . '/' : $canonicalDomain . '/' . $route;
    $prio = $routePriorities[$route]['priority'] ?? '0.5';
    $freq = $routePriorities[$route]['changefreq'] ?? 'monthly';

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
    echo "    <lastmod>" . htmlspecialchars($lastmod, ENT_XML1, 'UTF-8') . "</lastmod>\n";
    echo "    <changefreq>" . htmlspecialchars($freq, ENT_XML1, 'UTF-8') . "</changefreq>\n";
    echo "    <priority>" . htmlspecialchars($prio, ENT_XML1, 'UTF-8') . "</priority>\n";
    echo "  </url>\n";
}

// Render dynamic public leadership profiles
foreach ($leaders as $leader) {
    if (empty($leader['id'])) {
        continue;
    }
    $loc = $canonicalDomain . '/leader.php?id=' . rawurlencode($leader['id']);
    $lastmod = date('Y-m-d');

    echo "  <url>\n";
    echo "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n";
    echo "    <lastmod>" . htmlspecialchars($lastmod, ENT_XML1, 'UTF-8') . "</lastmod>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.7</priority>\n";
    echo "  </url>\n";
}

echo '</urlset>' . "\n";
