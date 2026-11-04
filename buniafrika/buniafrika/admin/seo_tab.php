<?php
/**
 * SEO & Answer Engine Optimization (AEO) Management Tab
 * Adheres strictly to the Independent-Form Architecture:
 * - 1 entity = 1 form
 * - 1 Save button
 * - Isolated validation & state
 * - Dedicated CSRF token & user feedback
 */

$site_settings = getSiteSettings($conn);

// Fetch all registered pages in seo_page_metadata
$all_pages = [];
$res = $conn->query("SELECT * FROM seo_page_metadata ORDER BY id ASC");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $all_pages[$row['page_route']] = $row;
    }
}

// Determine active page being edited
$selected_page = isset($_GET['page']) && isset($all_pages[$_GET['page']]) ? $_GET['page'] : 'index.php';
$active_page_meta = $all_pages[$selected_page] ?? [
    'page_route' => $selected_page,
    'meta_title' => '',
    'meta_description' => '',
    'canonical_url' => '',
    'robots_index' => 1,
    'robots_follow' => 1,
    'og_title' => '',
    'og_description' => '',
    'og_image_url' => '',
    'og_type' => 'website',
    'twitter_card' => 'summary_large_image',
];

$canonical_domain = getCanonicalDomain($conn);
$sitemap_path = dirname(__DIR__) . '/sitemap.xml';
$sitemap_exists = file_exists($sitemap_path);
$sitemap_mtime = $sitemap_exists ? date('Y-m-d H:i:s', filemtime($sitemap_path)) : 'Never';
$sitemap_size = $sitemap_exists ? number_format(filesize($sitemap_path) / 1024, 1) . ' KB' : 'N/A';
?>

<div class="panel-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
    <div>
        <h2 style="font-size: 22px; color: var(--primary); margin: 0 0 6px 0;">Search &amp; Answer Engine Optimization (SEO + AEO)</h2>
        <p style="font-size: 13px; color: var(--on-surface-variant); margin: 0; max-width: 720px; line-height: 1.5;">
            Manage global search parameters, individual page metadata, Open Graph &amp; Twitter previews, dynamic sitemaps, and AI crawler discovery directives across the Buniafrika platform.
        </p>
    </div>
    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
        <a href="../sitemap.xml" target="_blank" rel="noopener" class="btn btn-outline" style="font-size: 12px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size: 16px;">open_in_new</span> View sitemap.xml
        </a>
        <a href="../robots.txt" target="_blank" rel="noopener" class="btn btn-outline" style="font-size: 12px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size: 16px;">smart_toy</span> View robots.txt
        </a>
        <a href="../llms.txt" target="_blank" rel="noopener" class="btn btn-outline" style="font-size: 12px; padding: 6px 12px; display: inline-flex; align-items: center; gap: 6px;">
            <span class="material-symbols-outlined" style="font-size: 16px;">psychology</span> View llms.txt
        </a>
    </div>
</div>

<div style="display: flex; flex-direction: column; gap: 32px;">

    <!-- ========================================================================= -->
    <!-- SECTION 1: GLOBAL SEO DEFAULTS FORM (INDEPENDENT)                        -->
    <!-- ========================================================================= -->
    <div class="card" style="background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); padding: 24px; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid var(--outline-variant); padding-bottom: 12px;">
            <span class="material-symbols-outlined" style="color: var(--primary); font-size: 24px;">public</span>
            <div>
                <h3 style="font-size: 17px; margin: 0; color: var(--primary);">1. Global SEO &amp; Schema Defaults</h3>
                <span style="font-size: 12px; color: var(--on-surface-variant);">Universal defaults applied across the website and in Schema.org Organization structured data.</span>
            </div>
        </div>

        <form method="POST" action="admin_dashboard.php?tab=seo" style="display: flex; flex-direction: column; gap: 18px;">
            <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="action" value="save_global_seo">

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="seo_canonical_domain" style="font-weight: 600; font-size: 13px;">Canonical Domain URL <span style="color:var(--error);">*</span></label>
                    <input type="url" id="seo_canonical_domain" name="global_seo[seo_canonical_domain]" class="form-control" value="<?php echo h($site_settings['seo_canonical_domain'] ?? 'https://buniafrika.co.ke'); ?>" required style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                    <small style="color: var(--on-surface-variant); font-size: 11px;">Production domain used to build absolute canonical URLs (e.g. <code>https://buniafrika.co.ke</code>).</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="seo_default_title" style="font-weight: 600; font-size: 13px;">Default Title Suffix / Template <span style="color:var(--error);">*</span></label>
                    <input type="text" id="seo_default_title" name="global_seo[seo_default_title]" class="form-control" value="<?php echo h($site_settings['seo_default_title'] ?? 'BuniAfrika CBO | Civic Technology, Heritage and Civic Action'); ?>" required style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                    <small style="color: var(--on-surface-variant); font-size: 11px;">Fallback title when a page lacks a specific custom title.</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="seo_twitter_handle" style="font-weight: 600; font-size: 13px;">Twitter / X Handle</label>
                    <input type="text" id="seo_twitter_handle" name="global_seo[seo_twitter_handle]" class="form-control" value="<?php echo h($site_settings['seo_twitter_handle'] ?? '@BuniAfrika'); ?>" placeholder="@BuniAfrika" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                    <small style="color: var(--on-surface-variant); font-size: 11px;">Attached to <code>twitter:site</code> meta tags on shared links.</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="seo_organization_type" style="font-weight: 600; font-size: 13px;">Schema.org Organization Type</label>
                    <select id="seo_organization_type" name="global_seo[seo_organization_type]" class="form-control" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%; background: var(--surface);">
                        <?php
                        $org_type = $site_settings['seo_organization_type'] ?? 'NGO';
                        $types = ['NGO' => 'NGO (Non-Governmental Organization)', 'NonprofitOrganization' => 'Nonprofit Organization', 'GovernmentOrganization' => 'Government Organization', 'Organization' => 'General Organization'];
                        foreach ($types as $key => $lbl): ?>
                            <option value="<?php echo h($key); ?>" <?php echo $org_type === $key ? 'selected' : ''; ?>><?php echo h($lbl); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="seo_org_founding_date" style="font-weight: 600; font-size: 13px;">Founding Year</label>
                    <input type="text" id="seo_org_founding_date" name="global_seo[seo_org_founding_date]" class="form-control" value="<?php echo h($site_settings['seo_org_founding_date'] ?? '2024'); ?>" placeholder="2024" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                </div>

                <div class="form-group">
                    <label class="form-label" for="seo_org_area_served" style="font-weight: 600; font-size: 13px;">Primary Region / Area Served</label>
                    <input type="text" id="seo_org_area_served" name="global_seo[seo_org_area_served]" class="form-control" value="<?php echo h($site_settings['seo_org_area_served'] ?? 'Kenya'); ?>" placeholder="Kenya" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="seo_default_description" style="font-weight: 600; font-size: 13px;">Global Default Meta Description <span style="color:var(--error);">*</span></label>
                <textarea id="seo_default_description" name="global_seo[seo_default_description]" class="form-control" rows="2" required style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;"><?php echo h($site_settings['seo_default_description'] ?? ''); ?></textarea>
                <small style="color: var(--on-surface-variant); font-size: 11px;">Target 140–155 characters for optimal snippet length in Google and Bing search results.</small>
            </div>

            <div class="form-group">
                <label class="form-label" for="seo_default_og_image" style="font-weight: 600; font-size: 13px;">Default Open Graph Social Banner (1200x630)</label>
                <input type="text" id="seo_default_og_image" name="global_seo[seo_default_og_image]" class="form-control" value="<?php echo h($site_settings['seo_default_og_image'] ?? 'assets/images/buniafrika-og-banner.png'); ?>" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                <small style="color: var(--on-surface-variant); font-size: 11px;">Relative or absolute path to the landscape banner (1200x630) used when links are shared on WhatsApp, LinkedIn, X, and Facebook.</small>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span> Save Global SEO Defaults
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- SECTION 2: PAGE-LEVEL METADATA EDITOR (INDEPENDENT)                      -->
    <!-- ========================================================================= -->
    <div class="card" style="background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); padding: 24px; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid var(--outline-variant); padding-bottom: 12px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 24px;">find_in_page</span>
                <div>
                    <h3 style="font-size: 17px; margin: 0; color: var(--primary);">2. Page-Specific SEO &amp; Social Metadata</h3>
                    <span style="font-size: 12px; color: var(--on-surface-variant);">Fine-tune titles, descriptions, canonical links, indexation flags, and social previews per page.</span>
                </div>
            </div>

            <!-- Page selector dropdown -->
            <div style="display: flex; align-items: center; gap: 8px;">
                <label for="page-selector" style="font-size: 13px; font-weight: 600; color: var(--on-surface);">Editing Page:</label>
                <select id="page-selector" onchange="window.location.href='admin_dashboard.php?tab=seo&page=' + encodeURIComponent(this.value);" style="padding: 8px 14px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant); font-size: 13px; font-weight: 600; background: var(--surface); color: var(--primary);">
                    <?php foreach ($all_pages as $route => $pData): ?>
                        <option value="<?php echo h($route); ?>" <?php echo $selected_page === $route ? 'selected' : ''; ?>>
                            <?php echo h($route); ?> <?php echo empty($pData['robots_index']) ? '[noindex]' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <form method="POST" action="admin_dashboard.php?tab=seo" style="display: flex; flex-direction: column; gap: 18px;">
            <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="action" value="save_page_seo">
            <input type="hidden" name="page_route" value="<?php echo h($selected_page); ?>">

            <div style="background: rgba(0, 32, 69, 0.03); border: 1px solid var(--outline-variant); padding: 12px 16px; border-radius: var(--radius-md); display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;">
                <div>
                    <span style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--on-surface-variant); font-weight: 700;">Selected Route</span>
                    <h4 style="margin: 2px 0 0 0; color: var(--primary); font-size: 16px;"><code><?php echo h($selected_page); ?></code></h4>
                </div>
                <div>
                    <a href="../<?php echo h($selected_page); ?>" target="_blank" rel="noopener" class="btn btn-outline" style="font-size: 12px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 4px;">
                        <span class="material-symbols-outlined" style="font-size: 14px;">visibility</span> Preview Live Page
                    </a>
                </div>
            </div>

            <!-- Title with character counter -->
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label class="form-label" for="page_meta_title" style="font-weight: 600; font-size: 13px; margin: 0;">Page Title (&lt;title&gt;) <span style="color:var(--error);">*</span></label>
                    <span id="title-counter" style="font-size: 11px; font-weight: 600; color: var(--on-surface-variant);">0 / 60 characters</span>
                </div>
                <input type="text" id="page_meta_title" name="page_seo[meta_title]" class="form-control" value="<?php echo h($active_page_meta['meta_title'] ?? ''); ?>" required maxlength="120" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%; font-size: 14px;" placeholder="Unique, descriptive title including primary keyword">
                <small style="color: var(--on-surface-variant); font-size: 11px;">Recommended length: <strong>50–60 characters</strong>. Avoid keyword stuffing.</small>
            </div>

            <!-- Meta description with character counter -->
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                    <label class="form-label" for="page_meta_description" style="font-weight: 600; font-size: 13px; margin: 0;">Meta Description <span style="color:var(--error);">*</span></label>
                    <span id="desc-counter" style="font-size: 11px; font-weight: 600; color: var(--on-surface-variant);">0 / 155 characters</span>
                </div>
                <textarea id="page_meta_description" name="page_seo[meta_description]" class="form-control" rows="3" required maxlength="300" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%; font-size: 14px;" placeholder="Concise, compelling summary of this page's content for search snippets..."><?php echo h($active_page_meta['meta_description'] ?? ''); ?></textarea>
                <small style="color: var(--on-surface-variant); font-size: 11px;">Recommended length: <strong>130–155 characters</strong>. Accurately reflect the page's intent.</small>
            </div>

            <!-- Canonical URL override & Robots Directives -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                <div class="form-group">
                    <label class="form-label" for="page_canonical_url" style="font-weight: 600; font-size: 13px;">Canonical URL Override</label>
                    <input type="url" id="page_canonical_url" name="page_seo[canonical_url]" class="form-control" value="<?php echo h($active_page_meta['canonical_url'] ?? ''); ?>" placeholder="<?php echo h($canonical_domain . '/' . ($selected_page === 'index.php' ? '' : $selected_page)); ?>" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                    <small style="color: var(--on-surface-variant); font-size: 11px;">Leave empty to automatically generate from the canonical domain.</small>
                </div>

                <div class="form-group" style="display: flex; flex-direction: column; justify-content: center; gap: 8px; padding-top: 4px;">
                    <span style="font-weight: 600; font-size: 13px;">Robots Indexation Directives</span>
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="page_seo[robots_index]" value="1" <?php echo !empty($active_page_meta['robots_index']) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                            <span>Allow Indexing (<code>index</code>)</span>
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; font-size: 13px; cursor: pointer; margin: 0;">
                            <input type="checkbox" name="page_seo[robots_follow]" value="1" <?php echo !empty($active_page_meta['robots_follow']) ? 'checked' : ''; ?> style="width: 18px; height: 18px;">
                            <span>Follow Links (<code>follow</code>)</span>
                        </label>
                    </div>
                    <small style="color: var(--on-surface-variant); font-size: 11px;">Uncheck to set <code>noindex</code> on private pages or duplicate forms.</small>
                </div>
            </div>

            <!-- Social Card Overrides -->
            <div style="border-top: 1px dashed var(--outline-variant); padding-top: 16px; margin-top: 4px;">
                <h4 style="font-size: 14px; color: var(--primary); margin: 0 0 12px 0;">Social Sharing Overrides (Open Graph &amp; Twitter)</h4>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                    <div class="form-group">
                        <label class="form-label" for="page_og_title" style="font-weight: 600; font-size: 13px;">Social Title (OG Title)</label>
                        <input type="text" id="page_og_title" name="page_seo[og_title]" class="form-control" value="<?php echo h($active_page_meta['og_title'] ?? ''); ?>" placeholder="Defaults to Page Title if empty" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="page_og_image_url" style="font-weight: 600; font-size: 13px;">Social Share Image URL</label>
                        <input type="text" id="page_og_image_url" name="page_seo[og_image_url]" class="form-control" value="<?php echo h($active_page_meta['og_image_url'] ?? ''); ?>" placeholder="assets/images/buniafrika-og-banner.png" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="page_twitter_card" style="font-weight: 600; font-size: 13px;">Twitter Card Format</label>
                        <select id="page_twitter_card" name="page_seo[twitter_card]" class="form-control" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%; background: var(--surface);">
                            <?php $tCard = $active_page_meta['twitter_card'] ?? 'summary_large_image'; ?>
                            <option value="summary_large_image" <?php echo $tCard === 'summary_large_image' ? 'selected' : ''; ?>>summary_large_image (Large landscape card)</option>
                            <option value="summary" <?php echo $tCard === 'summary' ? 'selected' : ''; ?>>summary (Square thumbnail card)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label class="form-label" for="page_og_description" style="font-weight: 600; font-size: 13px;">Social Description (OG Description)</label>
                    <textarea id="page_og_description" name="page_seo[og_description]" class="form-control" rows="2" placeholder="Defaults to Meta Description if empty" style="padding: 10px; border: 1px solid var(--outline-variant); border-radius: var(--radius-md); width: 100%;"><?php echo h($active_page_meta['og_description'] ?? ''); ?></textarea>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; margin-top: 8px;">
                <button type="submit" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;">
                    <span class="material-symbols-outlined" style="font-size: 18px;">save</span> Save Page SEO Metadata
                </button>
            </div>
        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- SECTION 3: DYNAMIC XML SITEMAP SYNC (INDEPENDENT)                        -->
    <!-- ========================================================================= -->
    <div class="card" style="background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); padding: 24px; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px; border-bottom: 1px solid var(--outline-variant); padding-bottom: 12px;">
            <span class="material-symbols-outlined" style="color: var(--primary); font-size: 24px;">account_tree</span>
            <div>
                <h3 style="font-size: 17px; margin: 0; color: var(--primary);">3. Dynamic XML Sitemap &amp; Crawler Sync</h3>
                <span style="font-size: 12px; color: var(--on-surface-variant);">Generate and synchronize search engine sitemaps from published pages and active leadership profiles.</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 20px;">
            <div style="background: rgba(0, 32, 69, 0.03); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--on-surface-variant); font-weight: 700;">Static Cache Status</span>
                <p style="margin: 4px 0 0 0; font-weight: 700; color: <?php echo $sitemap_exists ? 'var(--primary)' : 'var(--error)'; ?>;">
                    <?php echo $sitemap_exists ? 'Generated &amp; Active' : 'Missing File'; ?>
                </p>
                <small style="color: var(--on-surface-variant); font-size: 11px;">Size: <?php echo $sitemap_size; ?></small>
            </div>

            <div style="background: rgba(0, 32, 69, 0.03); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--on-surface-variant); font-weight: 700;">Last Cache Sync</span>
                <p style="margin: 4px 0 0 0; font-weight: 700; color: var(--on-surface);"><?php echo $sitemap_mtime; ?></p>
                <small style="color: var(--on-surface-variant); font-size: 11px;">Server filesystem timestamp</small>
            </div>

            <div style="background: rgba(0, 32, 69, 0.03); padding: 14px; border-radius: var(--radius-md); border: 1px solid var(--outline-variant);">
                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--on-surface-variant); font-weight: 700;">Live Endpoint</span>
                <p style="margin: 4px 0 0 0; font-weight: 700; color: var(--primary);">
                    <a href="../sitemap.php" target="_blank" rel="noopener" style="color: var(--primary); text-decoration: underline;">/sitemap.php</a>
                </p>
                <small style="color: var(--on-surface-variant); font-size: 11px;">Always real-time from DB</small>
            </div>
        </div>

        <form method="POST" action="admin_dashboard.php?tab=seo" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
            <input type="hidden" name="csrf_token" value="<?php echo h($_SESSION['csrf_token'] ?? ''); ?>">
            <input type="hidden" name="action" value="sync_sitemap">

            <p style="margin: 0; font-size: 13px; color: var(--on-surface-variant); max-width: 600px;">
                Clicking regenerate executes the dynamic generator and writes a refreshed <code>sitemap.xml</code> cache on disk, ensuring both modern rewrite servers and static crawlers receive identical, up-to-date entries.
            </p>

            <button type="submit" class="btn btn-outline" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px;">
                <span class="material-symbols-outlined" style="font-size: 18px;">sync</span> Sync &amp; Regenerate sitemap.xml
            </button>
        </form>
    </div>

    <!-- ========================================================================= -->
    <!-- SECTION 4: AEO & ANSWER ENGINE GUIDANCE                                  -->
    <!-- ========================================================================= -->
    <div class="card" style="background: var(--surface); border: 1px solid var(--outline-variant); border-radius: var(--radius-lg); padding: 24px; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; border-bottom: 1px solid var(--outline-variant); padding-bottom: 12px; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 24px;">psychology</span>
                <div>
                    <h3 style="font-size: 17px; margin: 0; color: var(--primary);">4. Answer Engine Optimization (AEO) Readiness</h3>
                    <span style="font-size: 12px; color: var(--on-surface-variant);">Best practices for formatting Q&amp;As and organizational content for AI search (ChatGPT, Perplexity, Claude).</span>
                </div>
            </div>
            <div>
                <a href="admin_dashboard.php?tab=faqs" class="btn btn-primary" style="font-size: 12px; padding: 8px 14px; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="material-symbols-outlined" style="font-size: 16px;">edit_note</span> Manage FAQs &amp; Answers
                </a>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px;">
            <div style="border-left: 3px solid var(--primary); padding-left: 12px;">
                <h5 style="margin: 0 0 4px 0; font-size: 14px; color: var(--primary);">Direct Answers First</h5>
                <p style="margin: 0; font-size: 12px; color: var(--on-surface-variant); line-height: 1.45;">
                    The first 1–2 sentences must provide a direct, unambiguous answer to the user question without rhetorical filler or preamble.
                </p>
            </div>
            <div style="border-left: 3px solid var(--secondary); padding-left: 12px;">
                <h5 style="margin: 0 0 4px 0; font-size: 14px; color: var(--secondary);">Detailed Context</h5>
                <p style="margin: 0; font-size: 12px; color: var(--on-surface-variant); line-height: 1.45;">
                    Follow the direct answer with rich, factual supporting context (e.g. operational procedures, eligibility criteria, locations).
                </p>
            </div>
            <div style="border-left: 3px solid #c59b44; padding-left: 12px;">
                <h5 style="margin: 0 0 4px 0; font-size: 14px; color: #8e6c1f;">Contextual Citations</h5>
                <p style="margin: 0; font-size: 12px; color: var(--on-surface-variant); line-height: 1.45;">
                    Include a relevant internal link with descriptive anchor text so AI engines cite Buniafrika pages as direct source references.
                </p>
            </div>
        </div>
    </div>

</div>

<!-- Interactive Character Counters -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var titleInput = document.getElementById('page_meta_title');
    var titleCounter = document.getElementById('title-counter');
    var descInput = document.getElementById('page_meta_description');
    var descCounter = document.getElementById('desc-counter');

    function updateTitleCount() {
        if (!titleInput || !titleCounter) return;
        var len = titleInput.value.length;
        titleCounter.textContent = len + ' / 60 characters';
        if (len >= 50 && len <= 60) {
            titleCounter.style.color = '#2e7d32'; // optimal green
        } else if (len > 60) {
            titleCounter.style.color = '#c62828'; // warning red
        } else {
            titleCounter.style.color = 'var(--on-surface-variant)';
        }
    }

    function updateDescCount() {
        if (!descInput || !descCounter) return;
        var len = descInput.value.length;
        descCounter.textContent = len + ' / 155 characters';
        if (len >= 130 && len <= 155) {
            descCounter.style.color = '#2e7d32'; // optimal green
        } else if (len > 155) {
            descCounter.style.color = '#c62828'; // warning red
        } else {
            descCounter.style.color = 'var(--on-surface-variant)';
        }
    }

    if (titleInput) {
        titleInput.addEventListener('input', updateTitleCount);
        updateTitleCount();
    }
    if (descInput) {
        descInput.addEventListener('input', updateDescCount);
        updateDescCount();
    }
});
</script>
