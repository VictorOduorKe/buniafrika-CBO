# Buniafrika Development Guidelines & Architectural Principles

## 1. Independent Content Management Principle

> [!IMPORTANT]
> **Mandatory Rule**: Never combine multiple independently managed content categories into one shared submission form or save operation.

### Rationale
In administrative interfaces and multi-part content management modules (such as Website Settings, Content Blocks, Profile Management, and Configuration), combining disparate data models into a single monolithic form causes:
1. **Validation Interference**: A validation rule in Category A (e.g., portrait image requirement for a leadership profile) blocks the administrator from saving completely unrelated content in Category B (e.g., Thematic Areas or Contact Details).
2. **Accidental Stale Overwriting**: Submitting all fields in a single payload risks overwriting unsaved changes, blanking out optional fields, or submitting stale cached data for unedited sections.
3. **Fragile User Experience**: When an entire page resets or reloads on submission, unsaved work across multiple sections is wiped out, and administrators cannot pinpoint the section responsible for validation errors.

---

### Core Architectural Rules

#### A. Architectural Isolation
Each logical content category must operate as a distinct, self-contained unit:
- **Dedicated Form**: Every section must have its own `<form>` element with an independent DOM hierarchy, unique `id`, CSRF token, and explicit `action` routing.
- **Dedicated Submit Trigger**: Every section must have its own dedicated Save Changes button (e.g., `Save Organisation & Contact`, `Save Thematic Areas`). Never create a global "Save All" button.
- **Dedicated Handler**: The backend must expose a distinct operation or action for each category (e.g., `saveOrganizationSettings`, `saveThematicSettings`).
- **Dedicated Feedback**: Success notifications, warning alerts, and validation error messages must render within that section's local feedback container.

#### B. Validation Isolation
- Validation schemas and server-side validation functions must be scoped strictly to the fields belonging to that specific category.
- Submitting Category A must never execute validation logic for Category B.
- Validating image uploads, portrait requirements, URL formats, or required text fields must only apply to the section being saved.
- An unchanged existing record (such as a leadership portrait) must never be re-validated or required to be re-uploaded.

#### C. Data & Transaction Isolation
- Each save operation must update only its corresponding database records or setting keys.
- Never write `NULL`, empty strings, or defaults over keys belonging to other categories.
- Operations that touch multiple rows (such as updating multiple key-value pairs or synchronizing lists with deletions) must execute inside an ACID database transaction (`$conn->begin_transaction()`, `$conn->commit()`, `$conn->rollback()`).
- Database schema changes should be minimized; use existing tables (e.g., `site_settings` key-value store) with partitioned keys.

#### D. Frontend State & User Experience Isolation
- Form state must never be shared across independent sections.
- When saving an individual section:
  - Only the active section's submit button enters a loading state (`disabled = true`, spinner / `Saving...`).
  - Buttons for other sections must remain enabled and interactive.
  - Form fields in other sections must remain untouched and their unsaved edits preserved.
  - Submissions should leverage asynchronous JavaScript (`fetch` / AJAX) with JSON response handling to avoid disruptive full-page reloads.
  - Progressive enhancement: When JavaScript is unavailable, the fallback standard POST submission must redirect back to the specific section anchor (e.g., `#section-thematic`) with session flash feedback.

#### E. Backward Compatibility
- Existing public-facing consumers and queries (e.g., `getSiteSettings()`, `getLeadershipProfiles()`) must continue functioning without breaking changes.
- Legacy endpoints or monolithic update wrappers (e.g., `saveSiteSettings()`) must be preserved as backward-compatible adapters that route to the new modular save operations.

---

## 2. Buniafrika Website Settings Structure

The Buniafrika Website Settings module is organized into 5 independent categories:

| Section | Form ID | POST Action | Dedicated Function | Submit Button Label |
| :--- | :--- | :--- | :--- | :--- |
| **1. Organisation & Contact** | `form-settings-organization` | `save_settings_organization` | `saveOrganizationSettings()` | `Save Organisation & Contact` |
| **2. Social Media** | `form-settings-social` | `save_settings_social` | `saveSocialSettings()` | `Save Social Media` |
| **3. Vision, Mission & Objectives** | `form-settings-vision` | `save_settings_vision` | `saveVisionSettings()` | `Save Vision, Mission & Objectives` |
| **4. Thematic Areas** | `form-settings-thematic` | `save_settings_thematic` | `saveThematicSettings()` | `Save Thematic Areas` |
| **5. Public Leadership Profiles** | `form-settings-leadership` | `save_settings_leadership` | `saveLeadershipSettings()` | `Save Leadership Profiles` |

---

## 3. Checklist for Adding or Modifying Administrative Settings

When introducing a new settings category or modifying an existing one:

- [ ] Does the category have its own `<form>` element and unique `id`?
- [ ] Does the section have its own dedicated submit button with a clear label?
- [ ] Is there an isolated `<div class="settings-feedback" ...>` container?
- [ ] Does the server-side validator check *only* the fields belonging to this section?
- [ ] Does the database update touch *only* the keys belonging to this section?
- [ ] Are transactions used for multi-record updates?
- [ ] Is the asynchronous handler configured to leave all other sections' DOM and values intact?
- [ ] Is backward compatibility maintained for existing public consumers?

---

## 4. Database-Driven Content Architecture Principle

> [!IMPORTANT]
> **Single Source of Truth Rule**: All organisational content displayed on the public-facing website MUST be retrieved dynamically from the database.
> - Never hardcode organisational information, lists, achievements, objectives, or thematic areas in frontend PHP templates or JavaScript files.
> - Static UI chrome (navigation labels, button labels, modal close text) remains in templates; all organizational statements, narratives, statistics, and media URLs must originate from the database.
> - All administrative edits made in the Admin Dashboard must immediately reflect on the public website upon saving without code deployments.

### 4.1 Relational Architecture & The 10 CMS Modules

The database schema provides 11 dedicated relational tables supporting 10 modular content management domains:

| Module | Table Name | Key Columns | Public Availability | Admin Management Tab |
| :--- | :--- | :--- | :--- | :--- |
| **1. Thematic Areas** | `thematic_areas` | `id, title, slug, intro, points, icon, image_url, display_order, published` | `published = 1` | `admin_dashboard.php?tab=thematic` |
| **2. Organisational Objectives** | `objectives` | `id, category, title, description, display_order, published` | `published = 1` | `admin_dashboard.php?tab=objectives` |
| **3. Publications & Reports** | `publications` | `id, title, slug, category, summary, file_path, file_size, cover_image, author, published_date, download_count, display_order, published` | `published = 1` | `admin_dashboard.php?tab=publications` |
| **4. Testimonials** | `testimonials` | `id, author_name, author_role, author_organization, author_image, quote, display_order, published` | `published = 1` | `admin_dashboard.php?tab=testimonials` |
| **5. Frequently Asked Questions** | `faqs` | `id, category, question, answer, display_order, published` | `published = 1` | `admin_dashboard.php?tab=faqs` |
| **6. Partnerships** | `partners` | `id, name, logo_url, website_url, description, partner_type, display_order, published` | `published = 1` | `admin_dashboard.php?tab=partners` |
| **7. Volunteer Management** | `volunteer_opportunities`<br>`volunteer_applications` | `id, title, department, location, commitment_type, slots, deadline, description, requirements, published`<br>`id, opportunity_id, applicant_name, applicant_email, applicant_phone, skills, motivation, resume_path, status, notes` | `published = 1`<br>Private (Admin Only) | `admin_dashboard.php?tab=volunteers` |
| **8. Our Story & Milestones** | `our_story` | `id, year, milestone_date, title, description, image_url, display_order, published` | `published = 1` | `admin_dashboard.php?tab=story` |
| **9. Core Values** | `core_values` | `id, title, description, icon, display_order, published` | `published = 1` | `admin_dashboard.php?tab=values` |
| **10. Achievements & Impact** | `achievements` | `id, metric_value, label, unit, reporting_period, description, display_order, published` | `published = 1` | `admin_dashboard.php?tab=achievements` |

### 4.2 REST API Routing & Public Consumption

The backend exposes a unified REST API via `buniafrika.co.ke/public/api.php` supporting both public consumption and administrative updates:

- **Public Endpoints (GET)**:
  - `GET /api/thematic-areas` (or `api.php?endpoint=thematic-areas`)
  - `GET /api/objectives`
  - `GET /api/publications[?category=...]`
  - `GET /api/testimonials`
  - `GET /api/faqs[?category=...]`
  - `GET /api/partners`
  - `GET /api/volunteer-opportunities`
  - `GET /api/our-story`
  - `GET /api/core-values`
  - `GET /api/achievements`
- **Public Submissions (POST)**:
  - `POST /api/volunteer-applications` (validates required fields, checks rate limits, and persists applicant records).
- **Client Service**:
  Frontend components and dynamic widgets consume these endpoints via `BuniafrikaAPI` defined in `assets/js/api_service.js`.

### 4.3 Publication Download Security & Statistics

Visitors downloading publication PDFs must be routed through the dedicated download handler (`download-publication.php?id=X`):
1. **Verification**: Queries database for valid, published record.
2. **Path Traversal Prevention**: Computes `realpath()` and verifies that the file resides strictly within the permitted `assets/uploads/publications` directory.
3. **MIME Verification**: Verifies `finfo_file` MIME type is strictly `application/pdf`.
4. **Metrics Tracking**: Atomically increments `download_count` in `publications` table.
5. **Streaming**: Streams binary content with correct `Content-Disposition`, `Content-Length`, and cache headers.

### 4.4 Data Integrity Standard for Achievements

> [!CAUTION]
> **No Fabricated Statistics**: All figures registered in `achievements` and displayed on the website must be grounded in verified project evaluations, actual workshop attendees, or confirmed community datasets. Never fabricate placeholder metric numbers.

---

## 5. Website-Wide Form Reliability, Security, Content Availability and Responsive Standards

### 5.1 Form Submission Standard
All form submission feedback across public pages and administrative interfaces must use the centralized SweetAlert2 notification utility (`BuniAlert` in `js/alerts.js`):
1. **Confirmed Persistence Only**: Never display a success alert before the server confirms successful processing and database persistence.
2. **Prevent Double Submissions**: The active submit button must be disabled immediately with a loading state (`Processing...` spinner) and `form.dataset.submitting = 'true'`. Restore the button state upon completion.
3. **Preserve Input on Failure**: Failed submissions, validation errors, or network outages must never clear or reset form fields. Entered data must remain intact for user correction.
4. **Validation Feedback**: Invalid fields must be highlighted with `.is-invalid`, accompanied by accessible `.field-error` elements, and summarized via `BuniAlert.validation()`.
5. **Confirmation Dialogs**: All destructive administrative operations (deleting records, removing documents, failing transactions, clearing logs) must require explicit confirmation using `BuniAlert.confirm()`.

### 5.2 Security & CSRF Protection Standard
1. **CSRF Enforcement**: Every state-changing request (POST, PUT, DELETE) must provide a valid CSRF token. Generate tokens with `generateCsrfToken()` and include them as `<input type="hidden" name="csrf_token" value="<?php echo h(generateCsrfToken()); ?>">` and in `X-CSRF-Token` HTTP headers.
2. **Strict Verification**: Backends must verify tokens with `verifyCsrfToken()` or `requireValidCsrfToken($conn)`. Requests with missing, mismatched, or expired tokens must be rejected with HTTP 403 Forbidden.
3. **Parameterized Queries**: All database queries with dynamic input must use prepared statements (`$stmt = $conn->prepare(...)`). Raw string concatenation in SQL queries is strictly prohibited.
4. **Safe File Uploads**: Upload handlers must enforce strict file extensions (e.g., `['pdf']` for publications, `['jpg','jpeg','png','webp']` for images, `['pdf','doc','docx']` for resumes), MIME validation, size caps (50MB for publications, 12MB for images, 10MB for resumes), and store files with cryptographically random filenames.

### 5.3 Public Form Protection & Rate Limiting Standard
Every publicly accessible submission endpoint must enforce server-side rate limiting backed by the `rate_limits` table (`checkRateLimit()` / `enforceRateLimit()`):
- **Contact Form**: Max 5 submissions per 10 minutes (300s block).
- **Registration Form**: Max 5 submissions per 15 minutes (600s block).
- **Donation Form**: Max 10 submissions per 10 minutes (300s block).
- **Newsletter Subscription**: Max 5 subscriptions per 15 minutes (600s block).
- **Volunteer Application**: Max 3 applications per 30 minutes (900s block).
- **Article Submission**: Max 3 articles per 30 minutes (900s block).
- **Password Reset**: Max 4 attempts per 15 minutes (900s block).
- **Admin Login**: Max 5 attempts per 15 minutes (900s block).

When rate limits are exceeded, endpoints must return HTTP 429 Too Many Requests with a `Retry-After: <seconds>` header. Clients display `BuniAlert.rateLimit(seconds)` with an active countdown timer.

### 5.4 Content Availability Standard
Public-facing pages must never present a blank screen or unhandled empty section when database records are missing:
1. **Four Distinct States**:
   - **Loading**: Data retrieval in progress.
   - **Empty**: The request succeeded, but no published records exist. Render a structured `.empty-state` container with an icon, "Content Not Available", and instructions to check back later.
   - **Error**: Backend/database communication failed. Render appropriate error feedback (do not disguise as empty).
   - **Not Found**: The requested individual item does not exist (e.g. `leader.php?id=99999`). Emit HTTP 404 with a structured "Profile Not Found" view.
2. **Section-Level Handling**: If one section has no published records (e.g. Testimonials or Core Values), display that section's empty state or omit it cleanly while keeping the rest of the page fully functional.
3. **Zero Hardcoded Fallbacks**: Never disguise empty queries with hardcoded organizational content, fake metric numbers, or legacy static HTML arrays.

### 5.5 Responsive Testing & Browser DevTools QA Standard
All UI components, forms, tables, modals, and content grids must be tested using browser Developer Tools device emulation across the full viewport matrix:
- **Small mobile**: 320 × 568 (iPhone SE)
- **Standard mobile**: 375 × 812 (iPhone X/12)
- **Large mobile**: 430 × 932 (iPhone Pro Max)
- **Small tablet**: 768 × 1024 (iPad Mini/Air)
- **Large tablet**: 1024 × 1366 (iPad Pro)
- **Laptop**: 1366 × 768
- **Desktop**: 1440 × 900
- **Large desktop**: 1920 × 1080 (FHD)

Requirements:
- **Zero Horizontal Overflow**: `document.documentElement.scrollWidth <= window.innerWidth`.
- **Responsive Tables**: Data tables must be wrapped in `.table-responsive` with horizontal touch scrolling.
- **Mobile Navigation**: Menus must fold into the drawer navigation (`.drawer`) with accessible toggle controls (`aria-expanded`, `aria-label`).
- **Responsive Forms**: Input fields, select dropdowns, and buttons must adapt gracefully without overflowing container boundaries.

### 5.6 Quality Assurance & Verification Standard
A form or component is not complete until:
1. Valid submission and database persistence have been verified.
2. Server-side validation rejects invalid inputs with specific feedback.
3. Missing or invalid CSRF tokens are rejected with HTTP 403.
4. Excessive submissions trigger HTTP 429 rate limiting.
5. All responsive viewports pass automated overflow audits.
6. The regression test suite (`php scripts/verify.php`) passes 100%.

---

## 6. Navigation Architecture & Standards

> [!IMPORTANT]
> **Navigation Completeness Protection Rule**:
> Navigation restructuring must preserve all existing public routes and destinations unless an explicit removal or deprecation has been approved. Every navigation change must include a route-completeness check.

### 6.1 Centralized Menu Configuration (`includes/navigation.php`)
All public navigation routes and hierarchical structures must be defined centrally in `buniafrika/buniafrika/includes/navigation.php` via `getNavigationStructure($base_path)`.
- **Single Source of Truth**: Neither the desktop navbar nor the mobile drawer hardcodes list items or URLs in page templates. Both render through centralized helper functions:
  - `renderDesktopNavigation($nav_items, $current_page)`
  - `renderMobileDrawerNavigation($nav_items, $current_page)`
- **Configuration Schema**:
  ```php
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
          // Additional children...
      ],
  ]
  ```
- **Approved Hierarchy**:
  - **Home** (`index.php`)
  - **About Us** (`about.php`): Our Story (`#story`), Vision and Mission (`mission-vision.php`), Objectives (`mission-vision.php#objectives`), Core Values (`#values`), Leadership (`#leadership`)
  - **Our Work** (`programmes.php`): Thematic Areas (`programmes.php`), Projects (`projects.php`), Achievements and Impact (`index.php#achievements`), Partnerships (`about.php#partners`)
  - **News and Events** (`news.php`): News (`news.php`), Events (`events.php`)
  - **Resources** (`publications.php`): Publications and Reports (`publications.php`), Media Gallery (`gallery.php`), Testimonials (`index.php#testimonials`), FAQs (`programmes.php#faqs`)
  - **Get Involved** (`volunteer.php`): Volunteer Opportunities (`volunteer.php`), Donate and Support (`donate.php`)
  - **Contact Us** (`contact.php`): Styled as primary navigation action button (`.nav-contact-btn`, `is_cta: true`)

### 6.2 Desktop Navigation & Accessible Dropdown Menus
Desktop navigation is active from 1024px upwards:
1. **Dropdown Interaction Model**:
   - **Hover Intent Delay**: 160ms buffer timer prevents accidental closure when pointer traverses between toggle button and menu.
   - **Hover Gap Bridge**: An invisible `::before` pseudo-element on `.nav-dropdown-menu` spans the distance between the button and menu panel, preventing hover loss.
   - **Click Toggling**: Mouse or touch clicks toggle the open/closed state and synchronize `aria-expanded="true|false"`. Clicking outside or focus loss automatically closes active menus.
2. **Keyboard Navigation (WAI-ARIA Compliant)**:
   - `ArrowDown` / `ArrowUp` on toggle button opens menu and focuses the first/last menuitem.
   - `ArrowDown` / `ArrowUp` within menu navigates items with circular wrapping.
   - `Home` / `End` immediately focuses the first / last menu item.
   - `Escape` closes the open menu and restores focus to the toggle button.
   - `Tab` navigates out and automatically closes the dropdown.
3. **Viewport Overflow Prevention**:
   - Rightmost dropdowns (e.g. `Get Involved`) use right-aligned menus (`.nav-desktop-links > .nav-item-dropdown:nth-last-child(-n+2) .nav-dropdown-menu { left: auto; right: 0; }`) to ensure menus expand inwards and never exceed the right viewport boundary.
   - Under 1200px (tablet landscape), secondary auxiliary items (such as the phone number link `.call-us`) are gracefully hidden, and desktop nav padding scales dynamically to prevent horizontal scroll overflow.

### 6.3 Mobile Navigation Drawer & Accordion Menus
Mobile navigation is active below 1024px:
1. **Drawer Modal Standards**:
   - Off-canvas container `.drawer` implements `role="dialog"`, `aria-modal="true"`, `aria-hidden="true|false"`, and `aria-label="Mobile navigation"`.
   - Backdrop overlay `.drawer-overlay` dims page content and closes drawer on touch or click.
   - `Escape` key immediately closes the drawer and restores focus to the opening hamburger button (`.menu-btn`).
2. **Accordion Group Navigation**:
   - Group toggles (`.drawer-group-toggle`) expand and collapse nested links (`.drawer-submenu`).
   - Accessible state is exposed via `aria-expanded="true|false"` and native `hidden` attributes on submenus.
   - Chevron indicator animates 180° rotation on expansion.
3. **Scroll Locking & Auto-Closure**:
   - Background page scrolling is locked (`document.body.style.overflow = 'hidden'`) while drawer is open, preventing disorienting background scroll.
   - Selecting any navigation link automatically closes the drawer and restores normal page scrolling.
4. **Touch Ergonomics & Small-Screen Compatibility**:
   - All interactive touch targets strictly exceed 44 × 44px.
   - Zero horizontal overflow on devices as narrow as 320px width (`scrollWidth <= innerWidth`).

### 6.4 Route Synchronization, Active Highlighting & Deep-Linking
1. **Recursive Active Highlighting**:
   - The helper function `isNavActive($item, $current_page)` checks both the top-level route and all nested child routes.
   - If a user is on `volunteer.php`, the parent group button (`Get Involved`) receives the `.active` styling.
   - If a user is on a direct top-level link (`contact.php`), that link receives `.active` styling.
2. **Anchor Deep-Linking**:
   - Nested links point to designated ID anchors on their respective target pages (e.g. `index.php#achievements`, `about.php#leadership`, `programmes.php#faqs`).
   - All public pages implement `html { scroll-behavior: smooth; scroll-padding-top: 80px; }` so that fixed headers do not obscure section headings upon anchor navigation.

### 6.5 Offline Icon Robustness & Design System Integrity
- Dropdown and accordion indicators use vector SVGs or constrained element boxes (`max-width: 1.5em; overflow: hidden`) to guarantee layout stability.
- External webfont loading latency or sandbox network isolation will never trigger Flash of Unstyled Text (FOUT) that alters element measurements or induces horizontal overflow.

---

## 7. Search Engine Optimization (SEO) & Answer Engine Optimization (AEO) Standards

### 7.1 Single Source of Truth for Metadata
All metadata is database-driven and managed via the Admin Dashboard. Hardcoding `<title>` or `<meta>` tags directly in template files is strictly prohibited.
1. **Database Tables**:
   - `seo_page_metadata`: Stores route-specific `meta_title`, `meta_description`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image_url`, `og_type`, and `twitter_card`.
   - `site_settings`: Stores global fallback settings (`seo_default_title`, `seo_default_description`, `seo_canonical_domain`, `seo_default_og_image`, `seo_twitter_handle`, `seo_organization_type`, `seo_org_founding_date`, `seo_org_area_served`).
2. **Dynamic Context Inheritance**:
   - Public pages define dynamic context via `$seo_context = [...]` before including `header.php`.
   - The centralized service `getSeoMetadata($conn, $route, $seo_context)` merges page metadata with dynamic overrides and global defaults.

### 7.2 Dynamic Head Tag Rendering
- Every public template must include `includes/header.php`, which executes `renderSeoHeadTags($seo)`.
- **Title Standards**: Length between 50–60 characters. Must contain the primary page topic and the brand name (`BuniAfrika CBO`).
- **Description Standards**: Length between 130–155 characters. Accurately reflect user search intent without generic filler.
- **Canonical URLs**: Must always be absolute HTTPS URLs (e.g. `https://buniafrika.co.ke/about.php`). For dynamic profile pages (e.g. `leader.php?id=...`), query parameters must be preserved in the canonical link.
- **Robots Directives**:
   - Public indexable pages: `index, follow`.
   - Private administrative/auth pages (`admin/*`, `login.php`, `register.php`, `reset_password.php`, `submit_article.php`): `noindex, nofollow`.
   - 404 or missing dynamic entity pages: `noindex, nofollow`.

### 7.3 Structured Data (Schema.org JSON-LD)
All public pages automatically receive interconnected JSON-LD structured data via `buildSchemaJsonLd()` in `header.php`:
1. **Core Graph Entities**:
   - `Organization` (subtype `NGO`): Legal name, URL, logo, contact points, founding year, area served, and official social media profiles (`sameAs`).
   - `WebSite`: Name, URL, and publisher reference (`@id` linked to Organization).
   - `WebPage`: Canonical URL, title, description, and breadcrumbs.
   - `BreadcrumbList`: Positional navigation trail.
2. **Route-Specific Dynamic Subtypes**:
   - `leader.php?id=...`: Emits a `Person` entity referencing job title, description, portrait image, and `worksFor` link to the BuniAfrika NGO.
   - `programmes.php`: Emits a `FAQPage` entity wrapping all published Q&A pairs for featured snippets and answer engines.
   - `publications.php`: Emits `DigitalDocument` items referencing report title, publication date, PDF download link, and author.

### 7.4 Semantic HTML5 & Heading Hierarchy Standards
1. **Strict Linear Heading Hierarchy**:
   - Exactly **one `<h1>`** per page stating the page's primary subject.
   - Main content sections use **`<h2>`** (use `.visually-hidden` if visual design omits an explicit heading).
   - Cards, accordion headers, and individual items use **`<h3>`**.
   - Skipping heading levels (e.g. `h1` to `h3`) is strictly prohibited.
2. **Media Optimization**:
   - Every `<img>` tag must include a descriptive, meaningful `alt` attribute. Decorative images must use `alt=""`.
   - Images positioned below the fold must include `loading="lazy"`.
   - Open Graph share images must be landscape 1200 × 630 pixels (`aspect-ratio: 1.91:1`).

### 7.5 Technical SEO & Crawler Directives
1. **Dynamic XML Sitemap**:
   - Endpoint `sitemap.php` dynamically queries the database for indexable pages and active leaders, outputting valid XML compliant with Sitemaps.org schema 0.9.
   - Apache rewrite rule `RewriteRule ^sitemap\.xml$ sitemap.php [L]` routes requests to the dynamic script.
   - The static file `sitemap.xml` is synchronized on every admin change as a resilient cache.
2. **Robots.txt Integrity**:
   - Never disallow `/assets/uploads/`; image search engines must be permitted to index publication covers and leadership portraits.
   - Disallow `/admin/`, `/includes/`, `/.env`, `/database/`, and AJAX form handlers.
   - Explicitly permit and configure access rules for major AI search bots (`GPTBot`, `PerplexityBot`, `ClaudeBot`, `Applebot-Extended`).
3. **Machine-Readable Discovery (`llms.txt`)**:
   - `/llms.txt` and `/llms-full.txt` must be maintained at the web root to provide concise, factual, markdown summaries for LLM crawlers.

### 7.6 Answer Engine Optimization (AEO) Guidelines
Content managers and developers must format informational content following the Two-Tier Answer Model:
1. **Tier 1 (Direct Answer)**: The opening 1–2 sentences (30–50 words) must state the direct, complete answer without rhetorical filler, preamble, or conversational fluff.
2. **Tier 2 (Supporting Explanation)**: 1–3 paragraphs providing operational context, methodology, data points, or step-by-step procedures.
3. **Tier 3 (Contextual Citations)**: Explicit source citation links with descriptive anchor text linking to authoritative internal subpages.

### 7.7 Admin Dashboard SEO Module (Independent-Form Principle)
The SEO & AEO management tab (`admin_dashboard.php?tab=seo`) must adhere strictly to the Independent-Form Architecture:
- Form 1 (`save_global_seo`): Global SEO Defaults & Schema Settings.
- Form 2 (`save_page_seo`): Page-Level Metadata Editor with interactive character counters.
- Form 3 (`sync_sitemap`): Dynamic XML Sitemap Synchronization & Cache Generator.
- Each form must feature its own submit button, dedicated POST handler, isolated validation, independent activity logging, and dedicated user feedback.





