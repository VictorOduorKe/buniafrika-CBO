# BUNIAFRIKA CBO — COMPREHENSIVE SEO & AEO AUDIT

**Date:** November 4, 2026  
**Audited Target:** Buniafrika Public Web Application (`buniafrika/buniafrika/`) & Backend API (`buniafrika/buniafrika.co.ke/`)  
**Auditor:** Antigravity Engineering Agent  
**Baseline Standard:** Google Search Essentials, Schema.org Standards, Bing Webmaster Guidelines, Modern Answer Engine Optimization (AEO) Principles.

---

## 1. Executive Summary

A comprehensive architectural and content audit was conducted across all 17 public routes, database tables, metadata configurations, server responses, and crawler access controls of the Buniafrika application.

The application possesses a solid server-side rendered (SSR) foundation in PHP, ensuring that primary page markup is delivered directly in initial HTTP responses without reliance on client-side JavaScript rendering. However, several critical technical SEO and AEO deficiencies prevent optimal discovery, ranking, indexing, and citation by modern search and AI answer engines:

1. **Critical Robots.txt Asset Blocking**: `robots.txt` explicitly disallows `/assets/uploads/`, completely blocking Googlebot, Bingbot, and social sharing scrapers from accessing publication cover images, leadership portraits, gallery media, and partner logos.
2. **Static & Stale Sitemap**: `sitemap.xml` is a hardcoded static file last updated in October 2026. It omits core dynamic pages (`publications.php`, `volunteer.php`, dynamic leadership profiles, published news/thematic routes), lacks `<lastmod>` timestamps, and incorrectly includes internal utility endpoints (`register.php`).
3. **Canonical URL Flattening**: Canonical generation in `header.php` strips query parameters globally, collapsing all dynamic leadership profiles (`leader.php?id=rahma`, `leader.php?id=kennedy`) onto a single canonical URL (`/leader.php`), causing duplicate content cannibalization in search engines.
4. **Social Sharing Degradation**: Open Graph (`og:image`) is hardcoded to a 512×512 square emblem (`android-chrome-512x512.png`), causing distorted or cropped link previews on Facebook, LinkedIn, and X/Twitter. Dynamic routes cannot define dedicated landscape share images (1200×630).
5. **Incomplete Structured Data**: JSON-LD output is confined to generic `Organization`, `WebSite`, `WebPage`, and `BreadcrumbList`. High-value entity schemas—including `AboutPage`, `ContactPage`, `Person` (leadership), `DigitalDocument`/`Report` (publications), and `FAQPage`—are completely absent.
6. **AEO Content Structuring Gaps**: Although organizational content is stored in the database, pages lack concise, direct question-and-answer summaries optimized for AI citation and extraction by search generative experiences (SGE) and LLM crawlers.

---

## 2. Route-by-Route SEO & AEO Audit Matrix

| Route | Title Status | Meta Description | Canonical URL | Robots | Schema.org | Heading Hierarchy | Content in Initial HTML | Severity |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| `index.php` | Static PHP string | Present (150 chars) | Correct (`/`) | `index, follow` | Org, WebSite | H1: 1, H2: 8, H3: 7 | Yes (Full SSR) | Medium |
| `about.php` | Static PHP string | Present (118 chars) | Correct (`/about.php`) | `index, follow` | WebPage only (Missing `AboutPage`) | H1: 1, H2: 9, H3: 8 | Yes (Full SSR) | Medium |
| `mission-vision.php` | Static PHP string | Present (89 chars) | Correct (`/mission-vision.php`) | `index, follow` | WebPage only | H1: 1, H2: 3, H3: 2 | Yes (Full SSR) | Low |
| `programmes.php` | Static PHP string | Present (118 chars) | Correct (`/programmes.php`) | `index, follow` | WebPage only (Missing `FAQPage`) | H1: 1, H2: 2, H3: 2 | Yes (Full SSR) | High |
| `projects.php` | Static PHP string | Present (99 chars) | Correct (`/projects.php`) | `index, follow` | WebPage only | H1: 1, H2: 1, H3: 2 | Yes (Full SSR) | Medium |
| `news.php` | Static PHP string | Present (81 chars) | Correct (`/news.php`) | `index, follow` | WebPage only (Missing `Article`) | H1: 1, H2: 0, H3: 3 (Gap) | Yes (Full SSR) | Medium |
| `publications.php` | Static PHP string | Present (107 chars) | Correct (`/publications.php`) | `index, follow` | WebPage only (Missing `DigitalDocument`) | H1: 1, H2: 1, H3: 1 | Yes (Full SSR) | High |
| `volunteer.php` | Static PHP string | Present (124 chars) | Correct (`/volunteer.php`) | `index, follow` | WebPage only | H1: 1, H2: 2, H3: 2 | Yes (Full SSR) | Medium |
| `gallery.php` | Static PHP string | Present (65 chars) | Correct (`/gallery.php`) | `index, follow` | WebPage only | H1: 1, H2: 0, H3: 1 (Gap) | Yes (Full SSR) | Low |
| `contact.php` | Static PHP string | Present (117 chars) | Correct (`/contact.php`) | `index, follow` | WebPage only (Missing `ContactPage`) | H1: 1, H2: 2, H3: 2 | Yes (Full SSR) | Medium |
| `donate.php` | Static PHP string | Present (75 chars) | Correct (`/donate.php`) | `index, follow` | WebPage only | H1: 1, H2: 0, H3: 4 (Gap) | Yes (Full SSR) | Low |
| `leader.php` (Dynamic) | Static fallback on error | Static fallback | Broken (Flattens to `/leader.php`) | Emits `index, follow` on 404! | WebPage only (Missing `Person`) | Dynamic H1 | Yes (Full SSR) | **Critical** |
| `privacy.php` | Static PHP string | Present (121 chars) | Correct (`/privacy.php`) | `index, follow` | WebPage only | H1: 1, H2: 5, H3: 0 | Yes (Full SSR) | Low |
| `login.php` | Static PHP string | Admin login text | `/login.php` | `noindex, nofollow` | Excluded from JSON-LD | H1: 1 | N/A | Pass |
| `register.php` | Static PHP string | Partner registration text | `/register.php` | `index, follow` (Should be noindex) | Excluded | H1: 1 | Form SSR | High |
| `reset_password.php` | Static PHP string | Password reset text | `/reset_password.php` | `noindex, nofollow` | Excluded | H1: 1 | Form SSR | Pass |
| `submit_article.php` | Static PHP string | Community draft text | `/submit_article.php` | `index, follow` (Should be noindex) | Excluded | H1: 1 | Form SSR | Medium |

---

## 3. Detailed Audit Findings by Technical Domain

### 3.1 Metadata & Title Architecture
- **Finding 1.1 (Severity: Medium)**: Page titles and meta descriptions are hardcoded in individual page controllers. Administrators cannot adjust title tags, meta descriptions, or social preview summaries without code modifications.
- **Finding 1.2 (Severity: Medium)**: When administrators change the organization name in Website Settings, the application uses `str_replace('BuniAfrika CBO', $site_settings['site_name'], $page_title)`. If a custom title lacks the exact substring `'BuniAfrika CBO'`, the organization name is omitted or improperly concatenated.
- **Finding 1.3 (Severity: High)**: Dynamic content records (leadership profiles, thematic areas, publications, news) do not generate unique, context-rich titles or descriptions automatically.

### 3.2 Canonical URLs & Indexing Control
- **Finding 2.1 (Severity: Critical)**: `header.php` line 25 strips all URL query strings unconditionally (`rawurlencode($current_page)`). Dynamic profile pages such as `leader.php?id=rahma` and `leader.php?id=kennedy` share the identical canonical tag (`https://buniafrika.co.ke/leader.php`). Search engines will index only one profile and drop the remainder as duplicate content.
- **Finding 2.2 (Severity: High)**: When `leader.php` is called with an invalid or non-existent profile ID (e.g. `leader.php?id=unknown`), it outputs "Profile Not Found" but serves HTTP 200 and `<meta name="robots" content="index, follow">`. It must emit HTTP 404 and `noindex, nofollow`.
- **Finding 2.3 (Severity: Medium)**: Internal transactional and submission utility forms (`register.php`, `submit_article.php`) are marked `index, follow` and expose internal intake workflows to search engine indexation.

### 3.3 Robots.txt & Sitemap Infrastructure
- **Finding 3.1 (Severity: Critical)**: `buniafrika/buniafrika/robots.txt` contains:
  ```text
  Disallow: /assets/uploads/
  ```
  This directive prohibits search engine image crawlers (Googlebot-Image, BingPreview) and social media scrapers from fetching any uploaded media. As a result, publication covers, team portraits, partner logos, and story photographs cannot appear in Google Images, Knowledge Graph panels, or social cards.
- **Finding 3.2 (Severity: Critical)**: `sitemap.xml` is a static XML file lacking dynamic generation. It fails to list `publications.php`, `volunteer.php`, or dynamic URLs, contains no `<lastmod>` timestamps or `<changefreq>` hints, and mistakenly lists the internal `register.php` endpoint.
- **Finding 3.3 (Severity: Medium)**: Neither `robots.txt` nor any discovery endpoint provides structured guidance for AI answer engine crawlers (PerplexityBot, GPTBot, ClaudeBot, Google-Extended).

### 3.4 Open Graph & Social Sharing
- **Finding 4.1 (Severity: High)**: Open Graph image (`og:image`) is hardcoded to a 512×512 square emblem (`android-chrome-512x512.png`). Standard Open Graph image specifications require a 1200×630 landscape image (1.91:1 aspect ratio). Square icons render with letterboxing or heavy cropping on Facebook and LinkedIn feeds.
- **Finding 4.2 (Severity: High)**: Twitter card metadata defines `twitter:card` as `summary`, lacks `twitter:image`, and does not bind `twitter:site` to Buniafrika's verified social handle (`@BuniAfrika`).
- **Finding 4.3 (Severity: Medium)**: Shared links for specific publications or leaders cannot display the publication cover or leader portrait as their social preview.

### 3.5 Structured Data (Schema.org JSON-LD)
- **Finding 5.1 (Severity: High)**: Missing specialized Schema.org subtypes:
  - `AboutPage` on `about.php`.
  - `ContactPage` on `contact.php`.
  - `Person` with job title, affiliation, and verified credentials on `leader.php`.
  - `DigitalDocument` / `Report` with author, publication date, and download URL on `publications.php`.
  - `FAQPage` with structured `Question` and `Answer` entities on `programmes.php`.
- **Finding 5.2 (Severity: Low)**: Organization schema telephone references `$schema_phone` directly without validating international E.164 formatting (`+254...`).

### 3.6 Heading Hierarchy & Semantic HTML
- **Finding 6.1 (Severity: Low)**: `news.php`, `gallery.php`, and `donate.php` skip `<h2>` heading levels, jumping directly from `<h1>` to `<h3>` within bento cards.
- **Finding 6.2 (Severity: Low)**: Dynamic leadership modal headers and card headers should utilize semantic `<article>` and `<header>` tags rather than generic `<div>` containers.

### 3.7 Answer Engine Optimization (AEO) Readiness
- **Finding 7.1 (Severity: High)**: Content architecture lacks concise, direct answer summaries at the top of key sections. AI systems extracting quick answers regarding Buniafrika's mandate, geographic scope, and programmatic focus must parse long descriptive paragraphs rather than direct factual statements.
- **Finding 7.2 (Severity: Medium)**: The `faqs` database table stores questions and answers but lacks fields for direct short answers versus expanded explanations, and lacks explicit page associations.

---

## 4. Remediation Plan & Target Specifications

| Action Item | Technical Remedy | Target Phase |
| :--- | :--- | :--- |
| **Centralized SEO Service** | Build `buniafrika.co.ke/includes/seo_helper.php` exporting modular metadata generation functions. | Phase 2 |
| **Database SEO Management** | Create migration `20261104_create_seo_tables.sql` defining `seo_page_metadata` and adding SEO columns to `site_settings`. | Phase 3 |
| **Dynamic Sitemap Generator** | Build dynamic XML sitemap controller `sitemap.php` (with `.htaccess` rewrite to `/sitemap.xml`) including all published pages, leaders, publications, and accurate `<lastmod>`. | Phase 5 |
| **Robots.txt Correction** | Remove `/assets/uploads/` disallow, retain `/admin/` and private form blocks, add sitemap pointer and clean AI crawler policy. | Phase 5 |
| **Canonical Fix** | Update `canonical_url` builder to support query parameters (`leader.php?id=...`) and custom database overrides. | Phase 2 & 5 |
| **Open Graph Card Assets** | Provision a dedicated 1200×630 landscape social banner (`assets/images/buniafrika-og-banner.png`) and support record-level preview images. | Phase 7 |
| **Schema.org Expansion** | Implement modular JSON-LD generators for `AboutPage`, `ContactPage`, `Person`, `DigitalDocument`, and `FAQPage`. | Phase 6 |
| **AEO Q&A Enhancement** | Enhance `faqs` table with `direct_answer`, `detailed_answer`, and `page_slug`. Render crawlable semantic accordions. | Phase 9 & 10 |
| **Admin SEO/AEO Tab** | Add an independent "SEO & Metadata" management tab to `admin_dashboard.php` adhering to the independent-form principle. | Phase 13 |
| **AI Crawler Discovery** | Author a verified, factual `llms.txt` and `llms-full.txt` discovery document adhering to published specifications. | Phase 12 |

---

## 5. Verification Gate Criteria
The remediation will be verified against:
1. Valid XML sitemap syntax, valid HTTPS URLs, and zero private route leakage.
2. HTTP 200, 404, and robots meta tag compliance across all 17 public routes.
3. Clean JSON-LD serialization passing Schema.org and Google Rich Results validators with 0 errors.
4. DevTools responsive rendering and horizontal scroll audit across 320px–1440px viewports.
5. 100% pass rate in the permanent regression suite (`php scripts/verify.php`).
