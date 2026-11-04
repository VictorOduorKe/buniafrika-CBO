<?php
/**
 * BuniAfrika SEO & AEO Centralized Service
 * Generates verified, database-driven metadata, canonical tags, Open Graph cards,
 * Twitter cards, and Schema.org JSON-LD graphs across all routes.
 */

/**
 * Resolves the verified, production canonical domain.
 * Prevents host header poisoning or untrusted user domains.
 *
 * @param mysqli $conn
 * @return string E.g. 'https://buniafrika.co.ke'
 */
function getCanonicalDomain($conn) {
    $settings = function_exists('getSiteSettings') ? getSiteSettings($conn) : [];
    $domain = $settings['seo_canonical_domain'] ?? ($settings['website_url'] ?? '');
    if ($domain === '') {
        $domain = getenv('FRONTEND_URL') ?: (getenv('BACKEND_API_URL') ?: 'https://buniafrika.co.ke');
    }
    $parts = parse_url($domain);
    $scheme = $parts['scheme'] ?? 'https';
    $host = $parts['host'] ?? 'buniafrika.co.ke';
    $port = isset($parts['port']) && !in_array($parts['port'], [80, 443]) ? ':' . $parts['port'] : '';
    return rtrim($scheme . '://' . $host . $port, '/');
}

/**
 * Fetches unified, database-driven SEO & AEO metadata for a given route.
 *
 * @param mysqli $conn
 * @param string $route E.g. 'about.php', 'index.php', 'leader.php'
 * @param array $dynamicContext Optional dynamic overrides from controller
 * @return array
 */
function getSeoMetadata($conn, $route, $dynamicContext = []) {
        $settings = function_exists('getSiteSettings') ? getSiteSettings($conn) : [];
        $siteName = $settings['site_name'] ?? 'BuniAfrika CBO';
        $canonicalDomain = getCanonicalDomain($conn);

        // Global defaults from site_settings or fallback
        $defaultTitle = $settings['seo_default_title'] ?? ($siteName . ' | Civic Technology, Heritage and Civic Action');
        $defaultDesc = $settings['seo_default_description'] ?? ($settings['mission'] ?? 'BuniAfrika CBO bridges research, civic technology and cultural heritage preservation in Kenya.');
        $defaultOgImage = $settings['seo_default_og_image'] ?? 'assets/images/buniafrika-og-banner.png';
        $twitterHandle = $settings['seo_twitter_handle'] ?? '@BuniAfrika';

        // Check for page-level record in seo_page_metadata
        $pageRecord = null;
        if ($conn instanceof mysqli) {
            $stmt = $conn->prepare("SELECT * FROM `seo_page_metadata` WHERE `page_route` = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param('s', $route);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res && $res->num_rows > 0) {
                    $pageRecord = $res->fetch_assoc();
                }
                $stmt->close();
            }
        }

        // 1. Resolve Title
        if (!empty($dynamicContext['title'])) {
            $title = $dynamicContext['title'];
        } elseif (!empty($pageRecord['meta_title'])) {
            $title = $pageRecord['meta_title'];
        } else {
            $title = $defaultTitle;
        }
        // Ensure site name appears in title if not already present
        if (strpos($title, 'BuniAfrika') === false) {
            $title .= ' | ' . $siteName;
        }

        // 2. Resolve Description
        if (!empty($dynamicContext['meta_description'])) {
            $description = $dynamicContext['meta_description'];
        } elseif (!empty($pageRecord['meta_description'])) {
            $description = $pageRecord['meta_description'];
        } else {
            $description = $defaultDesc;
        }

        // 3. Resolve Canonical URL
        if (!empty($dynamicContext['canonical_url'])) {
            $canonicalUrl = $dynamicContext['canonical_url'];
        } elseif (!empty($pageRecord['canonical_url'])) {
            $canonicalUrl = $pageRecord['canonical_url'];
        } else {
            if ($route === 'index.php') {
                $canonicalUrl = $canonicalDomain . '/';
            } elseif (!empty($dynamicContext['query_string'])) {
                $canonicalUrl = $canonicalDomain . '/' . $route . '?' . $dynamicContext['query_string'];
            } else {
                $canonicalUrl = $canonicalDomain . '/' . rawurlencode($route);
            }
        }

        // 4. Resolve Robots Directive
        if (!empty($dynamicContext['robots'])) {
            $robots = $dynamicContext['robots'];
        } elseif ($pageRecord !== null) {
            $index = !empty($pageRecord['robots_index']) ? 'index' : 'noindex';
            $follow = !empty($pageRecord['robots_follow']) ? 'follow' : 'nofollow';
            $robots = "$index, $follow";
        } else {
            $privateRoutes = ['login.php', 'reset_password.php', 'register.php', 'submit_article.php', 'download-publication.php', 'download-gallery.php'];
            if (in_array($route, $privateRoutes, true) || strpos($route, 'admin/') !== false) {
                $robots = 'noindex, nofollow';
            } else {
                $robots = $settings['seo_default_robots'] ?? 'index, follow';
            }
        }

        // 5. Resolve Open Graph & Twitter Social Metadata
        $ogTitle = $dynamicContext['og_title'] ?? ($pageRecord['og_title'] ?? $title);
        $ogDescription = $dynamicContext['og_description'] ?? ($pageRecord['og_description'] ?? $description);
        $ogType = $dynamicContext['og_type'] ?? ($pageRecord['og_type'] ?? 'website');

        $rawOgImage = $dynamicContext['og_image'] ?? ($pageRecord['og_image_url'] ?? $defaultOgImage);
        if (preg_match('#^https?://#i', $rawOgImage)) {
            $ogImage = $rawOgImage;
        } else {
            $ogImage = $canonicalDomain . '/' . ltrim($rawOgImage, '/');
        }

        $twitterCard = $dynamicContext['twitter_card'] ?? ($pageRecord['twitter_card'] ?? 'summary_large_image');

        return [
            'route' => $route,
            'title' => $title,
            'meta_description' => $description,
            'canonical_url' => $canonicalUrl,
            'robots' => $robots,
            'og_title' => $ogTitle,
            'og_description' => $ogDescription,
            'og_image' => $ogImage,
            'og_type' => $ogType,
            'og_url' => $canonicalUrl,
            'twitter_card' => $twitterCard,
            'twitter_title' => $ogTitle,
            'twitter_description' => $ogDescription,
            'twitter_image' => $ogImage,
            'twitter_site' => $twitterHandle,
            'site_name' => $siteName,
            'canonical_domain' => $canonicalDomain,
        ];
    }

/**
 * Renders standard, safely escaped SEO and social meta head tags.
 *
 * @param array $seo Unified SEO metadata from getSeoMetadata()
 * @return string
 */
function renderSeoHeadTags($seo) {
    $out = [];
    $out[] = '<title>' . htmlspecialchars($seo['title'], ENT_QUOTES, 'UTF-8') . '</title>';
    $out[] = '<meta name="description" content="' . htmlspecialchars($seo['meta_description'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta name="robots" content="' . htmlspecialchars($seo['robots'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<link rel="canonical" href="' . htmlspecialchars($seo['canonical_url'], ENT_QUOTES, 'UTF-8') . '">';

    // Open Graph
    $out[] = '<meta property="og:site_name" content="' . htmlspecialchars($seo['site_name'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta property="og:type" content="' . htmlspecialchars($seo['og_type'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta property="og:url" content="' . htmlspecialchars($seo['og_url'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta property="og:title" content="' . htmlspecialchars($seo['og_title'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta property="og:description" content="' . htmlspecialchars($seo['og_description'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta property="og:image" content="' . htmlspecialchars($seo['og_image'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta property="og:image:alt" content="' . htmlspecialchars($seo['og_title'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta property="og:locale" content="en_KE">';

    // Twitter Cards
    $out[] = '<meta name="twitter:card" content="' . htmlspecialchars($seo['twitter_card'], ENT_QUOTES, 'UTF-8') . '">';
    if (!empty($seo['twitter_site'])) {
        $out[] = '<meta name="twitter:site" content="' . htmlspecialchars($seo['twitter_site'], ENT_QUOTES, 'UTF-8') . '">';
    }
    $out[] = '<meta name="twitter:title" content="' . htmlspecialchars($seo['twitter_title'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta name="twitter:description" content="' . htmlspecialchars($seo['twitter_description'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta name="twitter:image" content="' . htmlspecialchars($seo['twitter_image'], ENT_QUOTES, 'UTF-8') . '">';
    $out[] = '<meta name="twitter:image:alt" content="' . htmlspecialchars($seo['twitter_title'], ENT_QUOTES, 'UTF-8') . '">';

    return implode("\n    ", $out) . "\n";
}

/**
 * Constructs comprehensive Schema.org JSON-LD graph matching page contents.
 *
 * @param mysqli $conn
 * @param string $route
 * @param array $seo
 * @param array $dynamicContext
 * @return string Valid JSON-LD script block
 */
function buildSchemaJsonLd($conn, $route, $seo, $dynamicContext = []) {
        $settings = function_exists('getSiteSettings') ? getSiteSettings($conn) : [];
        $siteUrl = $seo['canonical_domain'];
        $canonicalUrl = $seo['canonical_url'];
        $siteName = $seo['site_name'];
        $logoUrl = $siteUrl . '/assets/buniafrika%20favicon/android-chrome-512x512.png';

        $phoneHref = function_exists('phoneNumberHref') ? phoneNumberHref($settings['general_phone'] ?? '') : '';
        $socialUrls = array_values(array_filter([
            $settings['social_facebook'] ?? '',
            $settings['social_instagram'] ?? '',
            $settings['social_linkedin'] ?? '',
            $settings['social_youtube'] ?? '',
            $settings['social_x'] ?? '',
            $settings['social_tiktok'] ?? '',
            $settings['social_whatsapp_channel'] ?? '',
        ]));

        $orgId = $siteUrl . '/#organization';
        $websiteId = $siteUrl . '/#website';

        $graph = [];

        // 1. Organization Entity
        $graph[] = [
            '@type' => $settings['seo_organization_type'] ?? 'NGO',
            '@id' => $orgId,
            'name' => $siteName,
            'url' => $siteUrl . '/',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logoUrl,
                'width' => 512,
                'height' => 512,
            ],
            'description' => $settings['mission'] ?? ($seo['meta_description'] ?? ''),
            'email' => $settings['general_email'] ?? '',
            'telephone' => $phoneHref ?: '+254700000000',
            'foundingDate' => $settings['seo_org_founding_date'] ?? '2024',
            'areaServed' => [
                '@type' => 'Country',
                'name' => $settings['seo_org_area_served'] ?? 'Kenya',
            ],
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $settings['location_text'] ?? 'Mombasa',
                'addressCountry' => 'KE',
            ],
            'sameAs' => $socialUrls,
            'contactPoint' => [
                [
                    '@type' => 'ContactPoint',
                    'contactType' => 'general enquiries',
                    'email' => $settings['general_email'] ?? '',
                    'telephone' => $phoneHref ?: '+254700000000',
                ],
                [
                    '@type' => 'ContactPoint',
                    'contactType' => 'partnerships',
                    'email' => $settings['partnership_email'] ?? '',
                ],
                [
                    '@type' => 'ContactPoint',
                    'contactType' => 'donations and finance',
                    'email' => $settings['finance_email'] ?? '',
                ],
            ],
        ];

        // 2. WebSite Entity
        $graph[] = [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $siteUrl . '/',
            'name' => $siteName,
            'publisher' => ['@id' => $orgId],
            'inLanguage' => 'en',
        ];

        // 3. Page-Specific Subtype
        $pageType = 'WebPage';
        if ($route === 'about.php') {
            $pageType = 'AboutPage';
        } elseif ($route === 'contact.php') {
            $pageType = 'ContactPage';
        } elseif ($route === 'publications.php') {
            $pageType = 'CollectionPage';
        }

        $pageEntity = [
            '@type' => $pageType,
            '@id' => $canonicalUrl . '#webpage',
            'url' => $canonicalUrl,
            'name' => $seo['title'],
            'description' => $seo['meta_description'],
            'isPartOf' => ['@id' => $websiteId],
            'about' => ['@id' => $orgId],
            'inLanguage' => 'en',
        ];

        // 4. Person Schema on leader.php
        if ($route === 'leader.php' && !empty($dynamicContext['leader'])) {
            $leader = $dynamicContext['leader'];
            $personId = $canonicalUrl . '#person';
            $graph[] = [
                '@type' => 'Person',
                '@id' => $personId,
                'name' => $leader['name'],
                'jobTitle' => $leader['role'] ?? 'Leader',
                'description' => $leader['description'] ?? '',
                'image' => !empty($leader['image']) ? $siteUrl . '/' . ltrim($leader['image'], '/') : $logoUrl,
                'worksFor' => ['@id' => $orgId],
                'sameAs' => array_values(array_filter([
                    !empty($leader['email']) ? 'mailto:' . $leader['email'] : null,
                    !empty($leader['phone']) ? 'tel:' . $leader['phone'] : null,
                ])),
            ];
            $pageEntity['mainEntity'] = ['@id' => $personId];
        }

        // 5. FAQPage Schema on programmes.php
        if ($route === 'programmes.php' && !empty($dynamicContext['faqs']) && is_array($dynamicContext['faqs'])) {
            $faqEntities = [];
            foreach ($dynamicContext['faqs'] as $faq) {
                if (!empty($faq['question']) && !empty($faq['answer'])) {
                    $faqEntities[] = [
                        '@type' => 'Question',
                        'name' => $faq['question'],
                        'acceptedAnswer' => [
                            '@type' => 'Answer',
                            'text' => !empty($faq['detailed_answer']) ? $faq['detailed_answer'] : $faq['answer'],
                        ],
                    ];
                }
            }
            if (!empty($faqEntities)) {
                $graph[] = [
                    '@type' => 'FAQPage',
                    '@id' => $canonicalUrl . '#faq',
                    'isPartOf' => ['@id' => $canonicalUrl . '#webpage'],
                    'mainEntity' => $faqEntities,
                ];
            }
        }

        // 6. Publications Collection on publications.php
        if ($route === 'publications.php' && !empty($dynamicContext['publications']) && is_array($dynamicContext['publications'])) {
            $pubEntities = [];
            foreach (array_slice($dynamicContext['publications'], 0, 10) as $pub) {
                $pubUrl = $siteUrl . '/download-publication.php?id=' . (int)$pub['id'];
                $pubEntities[] = [
                    '@type' => 'DigitalDocument',
                    'name' => $pub['title'],
                    'description' => $pub['description'] ?? '',
                    'author' => ['@id' => $orgId],
                    'datePublished' => $pub['publication_date'] ?? date('Y-m-d'),
                    'fileFormat' => 'application/pdf',
                    'url' => $pubUrl,
                ];
            }
            if (!empty($pubEntities)) {
                $pageEntity['hasPart'] = $pubEntities;
            }
        }

        $graph[] = $pageEntity;

        // 7. BreadcrumbList
        $breadcrumbs = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $siteUrl . '/'],
        ];
        if ($route !== 'index.php') {
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => $dynamicContext['breadcrumb_title'] ?? $seo['title'],
                'item' => $canonicalUrl,
            ];
        }
        $graph[] = [
            '@type' => 'BreadcrumbList',
            '@id' => $canonicalUrl . '#breadcrumb',
            'itemListElement' => $breadcrumbs,
        ];

        $schema = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];

        $flags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
        return '<script type="application/ld+json">' . json_encode($schema, $flags) . '</script>';
    }
