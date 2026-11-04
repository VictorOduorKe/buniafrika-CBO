# BuniAfrika CBO — SEO & AEO Verification & Test Report

**Date**: 2026-11-04  
**Environment**: Production Structure Local QA (`buniafrika-files`)  
**Test Suite**: `scripts/test_seo_e2e.php` & `scripts/verify.php`  
**Overall Status**: **PASSED (100% Pass Rate)**

---

## 1. Test Execution Summary

| Test Category | Total Tests | Passed | Failed | Status |
| :--- | :---: | :---: | :---: | :---: |
| **Robots.txt Configuration** | 8 | 8 | 0 | **PASS** |
| **AI Discovery (`llms.txt`)** | 5 | 5 | 0 | **PASS** |
| **Dynamic XML Sitemap** | 12 | 12 | 0 | **PASS** |
| **Public Pages Metadata & Schema** | 180 | 180 | 0 | **PASS** |
| **Dynamic Routes & 404 Error SEO** | 6 | 6 | 0 | **PASS** |
| **AEO Q&A Content & FAQPage Schema** | 3 | 3 | 0 | **PASS** |
| **Admin SEO Suite & CSRF Integrity** | 11 | 11 | 0 | **PASS** |
| **Total Automated Assertions** | **225** | **225** | **0** | **PASS** |

In addition, the project regression test suite (`scripts/verify.php`) ran with **143 passed, 0 failed**, and the form security suite (`scripts/test_all_forms_e2e.php`) ran with **47 passed, 0 failed**.

---

## 2. Detailed Test Results by Category

### 2.1 Robots.txt Access Policy Audit
- **Uploads Permitted**: Verified that `Disallow: /assets/uploads/` was completely removed and `Allow: /assets/uploads/` is present, unblocking publication covers and leader portraits for image search indexing.
- **Admin & Private Handlers Blocked**: Verified that `/admin/`, `/includes/`, `/database/`, `/.env`, `/login.php`, `/register.php`, and AJAX form processing endpoints are disallowed from indexing.
- **AI Crawler Rules**: Explicitly verified policies for `GPTBot`, `PerplexityBot`, `ClaudeBot`, and `Applebot-Extended`.
- **Sitemap References**: Verified reference to `https://buniafrika.co.ke/sitemap.xml` and `sitemap.php`.

### 2.2 LLM & Answer Engine Discovery Files
- Verified that `/llms.txt` and `/llms-full.txt` exist and are accessible.
- Verified factual accuracy: includes BuniAfrika CBO legal status, headquarters in Mombasa, Kenya, strategic objectives, thematic programmes (CivicTech Awareness & Culture and Heritage), and verified leadership team without fabricated data.

### 2.3 Dynamic XML Sitemap Validation
- Endpoint `sitemap.php` serves standard XML 0.9 declaration and valid `<urlset>`.
- Verified inclusion of all 12 core indexable public routes.
- Verified dynamic discovery and inclusion of all active public leadership profiles:
  - `https://buniafrika.co.ke/leader.php?id=rahma`
  - `https://buniafrika.co.ke/leader.php?id=brodox`
  - `https://buniafrika.co.ke/leader.php?id=kennedy`
  - `https://buniafrika.co.ke/leader.php?id=custom_586a1dd422a8453282692f80fc838b58`
- Verified exclusion of private administrative and authentication routes (`login.php`, `register.php`, `reset_password.php`, `submit_article.php`).
- Verified synchronization of the physical on-disk cache `sitemap.xml`.

### 2.4 Public Route Metadata Matrix

Every public route was audited for `<title>`, `<meta name="description">`, `<link rel="canonical">`, `<meta name="robots">`, Open Graph tags, Twitter Card tags, and Schema.org JSON-LD:

| Page Route | Title Tag Present & Branded | Meta Description Length | Canonical URL Valid (HTTPS) | Robots Directive | Schema.org Entities Verified |
| :--- | :---: | :---: | :---: | :---: | :--- |
| `index.php` | YES | 149 chars | `https://buniafrika.co.ke/` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `about.php` | YES | 114 chars | `https://buniafrika.co.ke/about.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `mission-vision.php` | YES | 89 chars | `https://buniafrika.co.ke/mission-vision.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `programmes.php` | YES | 129 chars | `https://buniafrika.co.ke/programmes.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `FAQPage`, `BreadcrumbList` |
| `projects.php` | YES | 122 chars | `https://buniafrika.co.ke/projects.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `publications.php` | YES | 137 chars | `https://buniafrika.co.ke/publications.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `news.php` | YES | 114 chars | `https://buniafrika.co.ke/news.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `volunteer.php` | YES | 139 chars | `https://buniafrika.co.ke/volunteer.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `gallery.php` | YES | 108 chars | `https://buniafrika.co.ke/gallery.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `contact.php` | YES | 109 chars | `https://buniafrika.co.ke/contact.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `donate.php` | YES | 76 chars | `https://buniafrika.co.ke/donate.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |
| `privacy.php` | YES | 112 chars | `https://buniafrika.co.ke/privacy.php` | `index, follow` | `NGO`, `WebSite`, `WebPage`, `BreadcrumbList` |

### 2.5 Dynamic Leader Profiles & 404 Error State Verification
- Valid Leader Profile (`leader.php?id=kennedy`):
  - Emits custom title: `Kennedy Muuo — Chief Executive Officer | Leadership Profile | BuniAfrika CBO`.
  - Canonical URL correctly preserves query string parameter: `https://buniafrika.co.ke/leader.php?id=kennedy`.
  - Schema.org graph embeds a `Person` entity referencing `Kennedy Muuo` as CEO working for the BuniAfrika `NGO` entity.
- Invalid Leader Profile (`leader.php?id=nonexistent`):
  - Emits HTTP 404 response code.
  - Emits `<meta name="robots" content="noindex, nofollow">` to prevent indexation of non-existent profiles.
  - Structured data schema is strictly omitted.

### 2.6 AEO (Answer Engine Optimization) Q&A Verification
- On `programmes.php`, verified rendering of the two-part response structure:
  - `<div class="faq-direct-answer">`: Direct 1-2 sentence response for featured snippets and voice search.
  - `<div class="faq-detailed-answer">`: Detailed operational and policy context.
  - Schema.org `FAQPage` entity is present with Question and Answer nodes.

### 2.7 Admin Dashboard SEO & Mutation Verification
- **CSRF Protection**: Verified rejection of requests submitted with invalid or missing CSRF tokens with error message `An errror has occured in form submission. Refresh page and try again`.
- **Global SEO Updates**: Verified mutation of `site_settings` through `save_global_seo`.
- **Page-Level SEO Updates**: Verified mutation of `seo_page_metadata` through `save_page_seo`.
- **Sitemap Sync Action**: Verified that clicking "Sync & Regenerate sitemap.xml" regenerates the static cache file.

---

## 3. Heading Hierarchy Audit Summary

All audited public templates strictly enforce a linear semantic heading hierarchy:
- Each page features exactly **one `<h1>`** tag describing the primary topic.
- Main sections are marked with **`<h2>`** tags (including accessible visually hidden headings for semantic container grouping where visually omitted).
- Subsection items, cards, and accordion items use **`<h3>`** tags.
- Zero skipped heading levels (e.g. no direct jumps from `h1` to `h3` or `h2` to `h4`).
- All below-the-fold images include `loading="lazy"` and meaningful `alt` attributes.
