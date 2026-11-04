<?php
/**
 * BuniAfrika Navigation Configuration and Accessible Rendering Architecture
 * Modular, scalable hierarchical navigation structure for desktop and mobile devices.
 */

if (!function_exists('getNavigationStructure')) {
    /**
     * Centralized Navigation Menu Definition
     * New sections or pages can be added here without rewriting templates.
     *
     * @param string $base_path Relative root prefix (e.g. '../' for admin, '' for public)
     * @return array
     */
    function getNavigationStructure($base_path = '') {
        return [
            [
                'id' => 'home',
                'title' => 'Home',
                'url' => $base_path . 'index.php',
                'route' => 'index.php',
            ],
            [
                'id' => 'about',
                'title' => 'About Us',
                'url' => $base_path . 'about.php',
                'route' => 'about.php',
                'children' => [
                    [
                        'title' => 'Our Story',
                        'url' => $base_path . 'about.php#story',
                        'route' => 'about.php',
                    ],
                    [
                        'title' => 'Vision and Mission',
                        'url' => $base_path . 'mission-vision.php',
                        'route' => 'mission-vision.php',
                    ],
                    [
                        'title' => 'Objectives',
                        'url' => $base_path . 'mission-vision.php#objectives',
                        'route' => 'mission-vision.php',
                    ],
                    [
                        'title' => 'Core Values',
                        'url' => $base_path . 'about.php#values',
                        'route' => 'about.php',
                    ],
                    [
                        'title' => 'Leadership',
                        'url' => $base_path . 'about.php#leadership',
                        'route' => 'about.php',
                    ],
                ],
            ],
            [
                'id' => 'our-work',
                'title' => 'Our Work',
                'url' => $base_path . 'programmes.php',
                'route' => 'programmes.php',
                'children' => [
                    [
                        'title' => 'Thematic Areas',
                        'url' => $base_path . 'programmes.php',
                        'route' => 'programmes.php',
                    ],
                    [
                        'title' => 'Projects',
                        'url' => $base_path . 'projects.php',
                        'route' => 'projects.php',
                    ],
                    [
                        'title' => 'Achievements and Impact',
                        'url' => $base_path . 'index.php#achievements',
                        'route' => 'index.php',
                    ],
                    [
                        'title' => 'Partnerships',
                        'url' => $base_path . 'about.php#partners',
                        'route' => 'about.php',
                    ],
                ],
            ],
            [
                'id' => 'news-events',
                'title' => 'News and Events',
                'url' => $base_path . 'news.php',
                'route' => 'news.php',
                'children' => [
                    [
                        'title' => 'News',
                        'url' => $base_path . 'news.php',
                        'route' => 'news.php',
                    ],
                    [
                        'title' => 'Events',
                        'url' => $base_path . 'events.php',
                        'route' => 'events.php',
                    ],
                ],
            ],
            [
                'id' => 'resources',
                'title' => 'Resources',
                'url' => $base_path . 'publications.php',
                'route' => 'publications.php',
                'children' => [
                    [
                        'title' => 'Publications and Reports',
                        'url' => $base_path . 'publications.php',
                        'route' => 'publications.php',
                    ],
                    [
                        'title' => 'Media Gallery',
                        'url' => $base_path . 'gallery.php',
                        'route' => 'gallery.php',
                    ],
                    [
                        'title' => 'Testimonials',
                        'url' => $base_path . 'index.php#testimonials',
                        'route' => 'index.php',
                    ],
                    [
                        'title' => 'FAQs',
                        'url' => $base_path . 'programmes.php#faqs',
                        'route' => 'programmes.php',
                    ],
                ],
            ],
            [
                'id' => 'get-involved',
                'title' => 'Get Involved',
                'url' => $base_path . 'volunteer.php',
                'route' => 'volunteer.php',
                'children' => [
                    [
                        'title' => 'Volunteer Opportunities',
                        'url' => $base_path . 'volunteer.php',
                        'route' => 'volunteer.php',
                    ],
                    [
                        'title' => 'Donate and Support',
                        'url' => $base_path . 'donate.php',
                        'route' => 'donate.php',
                    ],
                ],
            ],
            [
                'id' => 'contact',
                'title' => 'Contact Us',
                'url' => $base_path . 'contact.php',
                'route' => 'contact.php',
                'is_cta' => true,
            ],
        ];
    }
}

if (!function_exists('isNavActive')) {
    /**
     * Determines whether a navigation item or one of its descendants matches the active route.
     *
     * @param array $item
     * @param string $current_page Current file basename (e.g. 'about.php')
     * @return bool
     */
    function isNavActive($item, $current_page) {
        if (!empty($item['route']) && $item['route'] === $current_page) {
            return true;
        }
        if (!empty($item['children']) && is_array($item['children'])) {
            foreach ($item['children'] as $child) {
                if (!empty($child['route']) && $child['route'] === $current_page) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('renderDesktopNavigation')) {
    /**
     * Renders Desktop Nav HTML with accessible keyboard & hover dropdowns
     *
     * @param array $navItems
     * @param string $current_page
     * @return string
     */
    function renderDesktopNavigation($navItems, $current_page) {
        $html = '<div class="nav-desktop-links" role="menubar" aria-label="Main navigation">';
        foreach ($navItems as $item) {
            $hasChildren = !empty($item['children']) && is_array($item['children']);
            $isActive = isNavActive($item, $current_page);
            $activeClass = $isActive ? ' active' : '';

            if ($hasChildren) {
                $html .= '<div class="nav-item-dropdown" data-nav-dropdown>';
                $html .= '<button type="button" class="nav-link nav-dropdown-toggle' . $activeClass . '" aria-expanded="false" aria-haspopup="true">';
                $html .= '<span>' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</span>';
                $html .= '<span class="nav-dropdown-icon" aria-hidden="true"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></span>';
                $html .= '</button>';
                $html .= '<div class="nav-dropdown-menu" role="menu" aria-label="' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . ' submenu">';
                $html .= '<a class="nav-dropdown-overview" href="' . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '" role="menuitem">';
                $html .= '<span>Overview: ' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</span>';
                $html .= '</a>';
                foreach ($item['children'] as $child) {
                    $childActive = ($current_page === $child['route']) ? ' active' : '';
                    $html .= '<a class="nav-dropdown-link' . $childActive . '" href="' . htmlspecialchars($child['url'], ENT_QUOTES, 'UTF-8') . '" role="menuitem">';
                    $html .= htmlspecialchars($child['title'], ENT_QUOTES, 'UTF-8');
                    $html .= '</a>';
                }
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $ctaClass = !empty($item['is_cta']) ? ' nav-contact-btn' : '';
                $html .= '<a class="nav-link' . $ctaClass . $activeClass . '" href="' . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '" role="menuitem">';
                $html .= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
                $html .= '</a>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}

if (!function_exists('renderMobileDrawerNavigation')) {
    /**
     * Renders Mobile Drawer Accordion Navigation HTML
     *
     * @param array $navItems
     * @param string $current_page
     * @return string
     */
    function renderMobileDrawerNavigation($navItems, $current_page) {
        $html = '<div class="drawer-nav-list" role="navigation" aria-label="Mobile navigation links">';
        foreach ($navItems as $item) {
            $hasChildren = !empty($item['children']) && is_array($item['children']);
            $isActive = isNavActive($item, $current_page);
            $activeClass = $isActive ? ' active' : '';

            if ($hasChildren) {
                $html .= '<div class="drawer-group' . ($isActive ? ' is-open' : '') . '">';
                $html .= '<button type="button" class="drawer-group-toggle js-drawer-toggle' . $activeClass . '" aria-expanded="' . ($isActive ? 'true' : 'false') . '">';
                $html .= '<span class="drawer-group-label">' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</span>';
                $html .= '<span class="drawer-group-icon" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg></span>';
                $html .= '</button>';
                $html .= '<div class="drawer-submenu' . ($isActive ? ' is-expanded' : '') . '"' . ($isActive ? '' : ' hidden') . '>';
                $html .= '<a class="drawer-sublink drawer-overview-link" href="' . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '">';
                $html .= '<span>Overview: ' . htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') . '</span>';
                $html .= '</a>';
                foreach ($item['children'] as $child) {
                    $childActive = ($current_page === $child['route']) ? ' active' : '';
                    $html .= '<a class="drawer-sublink' . $childActive . '" href="' . htmlspecialchars($child['url'], ENT_QUOTES, 'UTF-8') . '">';
                    $html .= htmlspecialchars($child['title'], ENT_QUOTES, 'UTF-8');
                    $html .= '</a>';
                }
                $html .= '</div>';
                $html .= '</div>';
            } else {
                $ctaClass = !empty($item['is_cta']) ? ' drawer-contact-link' : '';
                $html .= '<a class="drawer-link' . $ctaClass . $activeClass . '" href="' . htmlspecialchars($item['url'], ENT_QUOTES, 'UTF-8') . '">';
                $html .= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
                $html .= '</a>';
            }
        }
        $html .= '</div>';
        return $html;
    }
}
