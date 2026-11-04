# BuniAfrika CBO — SEO & AEO Implementation Architecture

## 1. Executive Summary

This document describes the production-ready Search Engine Optimization (SEO) and Answer Engine Optimization (AEO) system implemented across the BuniAfrika Community Based Organisation (CBO) web application.

The primary objectives achieved:
1. **Discoverability & Indexation**: Traditional search engines (Google, Bing) and AI answer engines (OpenAI ChatGPT Search / GPTBot, PerplexityBot, ClaudeBot, Applebot) can discover, parse, index, and contextualize all public organizational assets.
2. **Dynamic Database Single Source of Truth**: All page-level titles, meta descriptions, canonical URLs, robots directives, and Open Graph previews are stored in the database (`seo_page_metadata` and `site_settings`), completely editable by administrators via the admin dashboard without code modifications.
3. **Structured Data (Schema.org JSON-LD)**: Rich multi-entity schema graph injected server-side into every public page, declaring `Organization` (NGO subtype), `WebSite`, `WebPage`, `BreadcrumbList`, `Person` (leaders), `FAQPage` (AEO Q&A), and `DigitalDocument` (research publications).
4. **Independent Content Management Architecture**: The Admin SEO module strictly conforms to the independent-form principle (1 entity = 1 form, 1 save button, isolated validation, dedicated CSRF, feedback).
5. **AEO Content Grounding**: Clear Q&A formatting with direct concise answers, detailed supporting explanations, and contextual citations, complemented by machine-readable `llms.txt` and `llms-full.txt` discovery manifests.

---

## 2. Database Schema & Architecture

### 2.1 Table: `seo_page_metadata`
Stores route-specific metadata and robots indexation controls:

```sql
CREATE TABLE IF NOT EXISTS `seo_page_metadata` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `page_route` VARCHAR(100) NOT NULL UNIQUE,
    `meta_title` VARCHAR(255) NOT NULL,
    `meta_description` VARCHAR(500) NOT NULL,
    `canonical_url` VARCHAR(500) NULL DEFAULT NULL,
    `robots_index` TINYINT(1) NOT NULL DEFAULT 1,
    `robots_follow` TINYINT(1) NOT NULL DEFAULT 1,
    `og_title` VARCHAR(255) NULL DEFAULT NULL,
    `og_description` VARCHAR(500) NULL DEFAULT NULL,
    `og_image_url` VARCHAR(500) NULL DEFAULT NULL,
    `og_type` VARCHAR(50) NOT NULL DEFAULT 'website',
    `twitter_card` VARCHAR(50) NOT NULL DEFAULT 'summary_large_image',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_page_route` (`page_route`),
    INDEX `idx_robots_index` (`robots_index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 2.2 Global SEO Keys in `site_settings`
- `seo_default_title`: Default title suffix and fallback title.
- `seo_default_description`: Default meta description for public pages.
- `seo_default_og_image`: Default 1200×630 landscape social banner (`assets/images/buniafrika-og-banner.png`).
- `seo_canonical_domain`: Production domain (`https://buniafrika.co.ke`).
- `seo_default_robots`: Default robots directive (`index, follow`).
- `seo_twitter_handle`: Twitter / X handle (`@BuniAfrika`).
- `seo_organization_type`: Schema.org organization subtype (`NGO`).
- `seo_org_founding_date`: Founding year (`2024`).
- `seo_org_area_served`: Geographic focus (`Kenya`).

### 2.3 Table Enhancements: `faqs`
The `faqs` table was enhanced with fields to support Answer Engine Optimization (AEO):
- `detailed_answer` (TEXT): In-depth supporting explanation following the concise direct answer.
- `page_slug` (VARCHAR(100)): Target page placement (`programmes`, `about`, `volunteer`, `contact`, `all`).
- `related_link_url` (VARCHAR(500)): Citation link URL (e.g. `publications.php`).
- `related_link_text` (VARCHAR(255)): Anchor text for the citation link (e.g. `Read our Open Data Policy`).

---

## 3. Centralized SEO Helper Service (`seo_helper.php`)

The centralized service is located in `buniafrika/buniafrika.co.ke/includes/seo_helper.php` and bridged into `functions.php`:

### Key Functions:
1. `getCanonicalDomain(mysqli $conn): string`
   - Retrieves the canonical domain setting or detects the host with HTTPS protocol fallback.
2. `getSeoMetadata(mysqli $conn, string $route, array $context = []): array`
   - Resolves unified SEO parameters:
     - Pulls route defaults from `seo_page_metadata`.
     - Merges dynamic overrides from `$context`.
     - Automatically crafts absolute canonical URLs and Open Graph image URLs.
     - Enforces `noindex, nofollow` on private admin and authentication pages.
3. `renderSeoHeadTags(array $seo): string`
   - Renders HTML `<title>`, `<meta name="description">`, `<meta name="robots">`, `<link rel="canonical">`, Open Graph tags, and Twitter Card tags.
4. `buildSchemaJsonLd(mysqli $conn, string $route, array $seo, array $dynamicContext = []): string`
   - Generates interconnected Schema.org JSON-LD `@graph`:
     - `NGO` / `Organization` with logo, contact point, address, and social links.
     - `WebSite` with site name and publisher link.
     - `WebPage` with canonical URL, description, and breadcrumbs.
     - `BreadcrumbList` for hierarchical search breadcrumbs.
     - `Person` for individual leadership profiles on `leader.php?id=...`.
     - `FAQPage` for community questions on `programmes.php`.
     - `DigitalDocument` collection for reports on `publications.php`.
5. `savePageSeoMetadata(mysqli $conn, array $data): array`
   - Safely updates or creates a page-level SEO record in `seo_page_metadata`.
6. `saveGlobalSeoSettings(mysqli $conn, array $data): array`
   - Updates global SEO defaults in `site_settings`.

---

## 4. Technical SEO Infrastructure

### 4.1 Dynamic XML Sitemap (`sitemap.php` & `sitemap.xml`)
- Endpoint: `https://buniafrika.co.ke/sitemap.php` (and rewritten from `/sitemap.xml`).
- Standards: Compliant with standard Sitemaps.org protocol schema 0.9.
- Dynamic Inclusion:
  - Automatically queries all active indexable routes from `seo_page_metadata`.
  - Queries active leadership profiles (`/leader.php?id={id}`).
  - Formats accurate `<lastmod>`, `<changefreq>`, and `<priority>`.
  - Excludes all administrative, authentication, and form processing routes.
- Web Server Rewrite (`.htaccess`):
  ```apache
  <IfModule mod_rewrite.c>
      RewriteEngine On
      RewriteRule ^sitemap\.xml$ sitemap.php [L]
  </IfModule>
  ```
- File Sync: Static `sitemap.xml` cache is synchronized on deployment and whenever changes are saved in the admin dashboard.

### 4.2 Crawler Access Policies (`robots.txt`)
- File: `buniafrika/buniafrika/robots.txt`
- Media Ingestion:
  - Removed old `Disallow: /assets/uploads/`.
  - Added explicit `Allow: /assets/uploads/` so Google Images, Bing, and social card scrapers can index publication covers, leadership portraits, and certificates.
- Protected Areas:
  - Disallows `/admin/`, `/includes/`, `/database/`, `/.env`, and form submission handlers (`process_contact.php`, `process_donation.php`, etc.).
- Dedicated AI Crawler Directives:
  - Configured allow rules for `GPTBot`, `PerplexityBot`, `ClaudeBot`, and `Applebot-Extended`.
- Sitemap Directives:
  - References `https://buniafrika.co.ke/sitemap.xml` and `https://buniafrika.co.ke/sitemap.php`.

### 4.3 AI & LLM Discovery Files (`llms.txt` & `llms-full.txt`)
- Files: `/llms.txt` and `/llms-full.txt`.
- Adheres to the emerging standard for LLM crawler discovery and context extraction.
- Factual grounding: Contains strictly verified organizational facts, mandate, leadership bios, contact details, and thematic areas without fabrication.

---

## 5. Admin Dashboard SEO & AEO Management Suite

The dedicated "SEO & AEO" tab is available at `admin_dashboard.php?tab=seo`.
It strictly implements the **independent-form principle**:

### Form 1: Global SEO & Search Defaults
- Action: `save_global_seo`
- Fields: Canonical Domain URL, Title Suffix/Template, Twitter Handle, Schema.org Organization Type, Founding Year, Area Served, Global Default Meta Description, and Default Social Banner (1200x630).
- Dedicated submit button: `Save Global SEO Defaults`.

### Form 2: Page-Specific SEO & Social Metadata
- Action: `save_page_seo`
- Interactive route selector dropdown for all registered public routes.
- Fields: Page Title (with live 50-60 character counter), Meta Description (with live 130-155 character counter), Canonical URL override, Robots Index & Follow checkboxes, Open Graph Title, Open Graph Description, Open Graph Share Image URL, and Twitter Card format.
- Dedicated submit button: `Save Page SEO Metadata`.

### Form 3: Dynamic XML Sitemap & Technical Controls
- Action: `sync_sitemap`
- Status displays: static cache status, file size, filesystem timestamp, and link to dynamic live endpoint.
- Dedicated action button: `Sync & Regenerate sitemap.xml`.

### Form 4: FAQ & AEO Editor (in `faqs_tab.php`)
- Enhanced FAQ editing fields:
  - `answer`: Direct concise answer (1-2 sentences for AI answer engines and featured snippets).
  - `detailed_answer`: Detailed supporting explanation with operational context.
  - `page_slug`: Target placement page.
  - `related_link_url`: Citation reference URL.
  - `related_link_text`: Anchor text for citation link.

---

## 6. Social Sharing Assets

- Banner Created: `buniafrika/buniafrika/assets/images/buniafrika-og-banner.png`
- Dimensions: 1200 × 630 pixels (standard Open Graph / Twitter summary_large_image aspect ratio 1.91:1).
- Palette: Official BuniAfrika deep green (`#153b2d`, `#1d4b38`) with golden ochre accents (`#c59b44`).
- Weight: ~67.5 KB (optimized for instant fetching by WhatsApp, Telegram, Facebook, and LinkedIn card scrapers).
- Fallback hierarchy:
  1. Record-level portrait image (on `leader.php?id=...`).
  2. Page-level custom `og_image_url` (from `seo_page_metadata`).
  3. Global default `seo_default_og_image` (`assets/images/buniafrika-og-banner.png`).
