-- ============================================================================
-- BUNIAFRIKA SEO & AEO EXPANSION MIGRATION
-- Migration: 20261104_create_seo_and_aeo_tables.sql
-- ============================================================================

-- 1. Create SEO Page Metadata Table for Route-Level SEO & Social Overrides
CREATE TABLE IF NOT EXISTS `seo_page_metadata` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `page_route` VARCHAR(100) NOT NULL,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` VARCHAR(500) DEFAULT NULL,
  `canonical_url` VARCHAR(500) DEFAULT NULL,
  `robots_index` TINYINT(1) NOT NULL DEFAULT 1,
  `robots_follow` TINYINT(1) NOT NULL DEFAULT 1,
  `og_title` VARCHAR(255) DEFAULT NULL,
  `og_description` VARCHAR(500) DEFAULT NULL,
  `og_image_url` VARCHAR(500) DEFAULT NULL,
  `og_type` VARCHAR(50) NOT NULL DEFAULT 'website',
  `twitter_card` VARCHAR(50) NOT NULL DEFAULT 'summary_large_image',
  `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_page_route` (`page_route`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Seed Baseline Route-Level SEO Metadata
INSERT IGNORE INTO `seo_page_metadata` (`page_route`, `meta_title`, `meta_description`, `canonical_url`, `robots_index`, `robots_follow`, `og_title`, `og_description`, `og_image_url`, `og_type`, `twitter_card`) VALUES
('index.php', 'BuniAfrika CBO | Civic Technology, Heritage and Civic Action', 'BuniAfrika CBO bridges research, civic technology and cultural heritage preservation to advance public accountability and community empowerment in Kenya.', 'https://buniafrika.co.ke/', 1, 1, 'BuniAfrika CBO | Civic Technology, Heritage and Civic Action', 'BuniAfrika CBO bridges research, civic technology and cultural heritage preservation to advance public accountability and community empowerment in Kenya.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('about.php', 'About Us | BuniAfrika CBO — Mission, History & Leadership', 'Learn about BuniAfrika CBO\u2019s vision, mission, strategic objectives, community milestones, core values, and confirmed public leadership.', 'https://buniafrika.co.ke/about.php', 1, 1, 'About Us | BuniAfrika CBO — Mission, History & Leadership', 'Learn about BuniAfrika CBO\u2019s vision, mission, strategic objectives, community milestones, core values, and confirmed public leadership.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('mission-vision.php', 'Vision, Mission and Strategic Objectives | BuniAfrika CBO', 'Read BuniAfrika CBO\u2019s official vision, mission statement, and strategic organizational objectives driving informed citizenship and inclusive progress.', 'https://buniafrika.co.ke/mission-vision.php', 1, 1, 'Vision, Mission and Strategic Objectives | BuniAfrika CBO', 'Read BuniAfrika CBO\u2019s official vision, mission statement, and strategic organizational objectives driving informed citizenship and inclusive progress.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('programmes.php', 'Thematic Areas & Programmes | BuniAfrika CBO', 'Explore BuniAfrika CBO\u2019s database-driven thematic areas, research streams, community action programmes, and public FAQs.', 'https://buniafrika.co.ke/programmes.php', 1, 1, 'Thematic Areas & Programmes | BuniAfrika CBO', 'Explore BuniAfrika CBO\u2019s database-driven thematic areas, research streams, community action programmes, and public FAQs.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('projects.php', 'Active Projects & Field Initiatives | BuniAfrika CBO', 'Discover community-led civic technology, open data, and cultural heritage documentation projects implemented across Kenya by BuniAfrika CBO.', 'https://buniafrika.co.ke/projects.php', 1, 1, 'Active Projects & Field Initiatives | BuniAfrika CBO', 'Discover community-led civic technology, open data, and cultural heritage documentation projects implemented across Kenya by BuniAfrika CBO.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('publications.php', 'Publications, Research & Baseline Reports | BuniAfrika CBO', 'Access and download verified open public research reports, policy briefs, and community baseline studies published by BuniAfrika CBO.', 'https://buniafrika.co.ke/publications.php', 1, 1, 'Publications, Research & Baseline Reports | BuniAfrika CBO', 'Access and download verified open public research reports, policy briefs, and community baseline studies published by BuniAfrika CBO.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('news.php', 'Research & News Updates | BuniAfrika CBO', 'Read latest field research findings, perspectives, field reports, and community updates published by BuniAfrika CBO.', 'https://buniafrika.co.ke/news.php', 1, 1, 'Research & News Updates | BuniAfrika CBO', 'Read latest field research findings, perspectives, field reports, and community updates published by BuniAfrika CBO.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('events.php', 'Community Events & Gatherings | BuniAfrika CBO', 'Discover and participate in upcoming civic tech forums, community town halls, research webinars, and workshops organized by BuniAfrika CBO.', 'https://buniafrika.co.ke/events.php', 1, 1, 'Community Events & Gatherings | BuniAfrika CBO', 'Discover and participate in upcoming civic tech forums, community town halls, research webinars, and workshops organized by BuniAfrika CBO.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('volunteer.php', 'Volunteer Opportunities & Community Fellowships | BuniAfrika CBO', 'Join BuniAfrika CBO as a volunteer or community fellow to advance civic technology, digital rights, and African cultural heritage.', 'https://buniafrika.co.ke/volunteer.php', 1, 1, 'Volunteer Opportunities & Community Fellowships | BuniAfrika CBO', 'Join BuniAfrika CBO as a volunteer or community fellow to advance civic technology, digital rights, and African cultural heritage.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('gallery.php', 'Media & Field Documentation Gallery | BuniAfrika CBO', 'Browse verified photographic documentation and visual stories from BuniAfrika CBO field workshops, forums, and community forums.', 'https://buniafrika.co.ke/gallery.php', 1, 1, 'Media & Field Documentation Gallery | BuniAfrika CBO', 'Browse verified photographic documentation and visual stories from BuniAfrika CBO field workshops, forums, and community forums.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('contact.php', 'Contact BuniAfrika CBO | Enquiries, Offices & Partnerships', 'Get in touch with BuniAfrika CBO for general enquiries, institutional partnerships, or finance questions via email, phone, or our online enquiry desk.', 'https://buniafrika.co.ke/contact.php', 1, 1, 'Contact BuniAfrika CBO | Enquiries, Offices & Partnerships', 'Get in touch with BuniAfrika CBO for general enquiries, institutional partnerships, or finance questions via email, phone, or our online enquiry desk.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('donate.php', 'Support Our Work & Donations | BuniAfrika CBO', 'Support BuniAfrika CBO\u2019s civic technology, youth empowerment, and cultural heritage initiatives through transparent donations and grants.', 'https://buniafrika.co.ke/donate.php', 1, 1, 'Support Our Work & Donations | BuniAfrika CBO', 'Support BuniAfrika CBO\u2019s civic technology, youth empowerment, and cultural heritage initiatives through transparent donations and grants.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('privacy.php', 'Privacy Notice & Data Protection Policy | BuniAfrika CBO', 'Read BuniAfrika CBO\u2019s privacy notice explaining how personal information submitted through public contact and application forms is safeguarded.', 'https://buniafrika.co.ke/privacy.php', 1, 1, 'Privacy Notice & Data Protection Policy | BuniAfrika CBO', 'Read BuniAfrika CBO\u2019s privacy notice explaining how personal information submitted through public contact and application forms is safeguarded.', 'assets/images/buniafrika-og-banner.png', 'website', 'summary_large_image'),
('login.php', 'Administrator Login | BuniAfrika CBO', 'Secure administrative authentication portal for BuniAfrika CBO staff.', 'https://buniafrika.co.ke/login.php', 0, 0, NULL, NULL, NULL, 'website', 'summary'),
('register.php', 'Partner Registration Desk | BuniAfrika CBO', 'Register interest in institutional partnership or volunteering.', 'https://buniafrika.co.ke/register.php', 0, 0, NULL, NULL, NULL, 'website', 'summary'),
('reset_password.php', 'Reset Password | BuniAfrika CBO', 'Administrator password recovery portal.', 'https://buniafrika.co.ke/reset_password.php', 0, 0, NULL, NULL, NULL, 'website', 'summary'),
('submit_article.php', 'Submit Article Draft | BuniAfrika CBO', 'Community article draft submission portal for editorial review.', 'https://buniafrika.co.ke/submit_article.php', 0, 0, NULL, NULL, NULL, 'website', 'summary');

-- 3. Seed Global SEO & AEO Settings into site_settings
INSERT IGNORE INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('seo_default_title', 'BuniAfrika CBO | Civic Technology, Heritage and Civic Action'),
('seo_default_description', 'BuniAfrika CBO bridges research, civic technology and cultural heritage preservation to advance public accountability and community empowerment in Kenya.'),
('seo_default_og_image', 'assets/images/buniafrika-og-banner.png'),
('seo_canonical_domain', 'https://buniafrika.co.ke'),
('seo_default_robots', 'index, follow'),
('seo_twitter_handle', '@BuniAfrika'),
('seo_organization_type', 'NGO'),
('seo_org_founding_date', '2024'),
('seo_org_area_served', 'Kenya');

-- 4. Enhance faqs Table for Answer Engine Optimization (AEO)
ALTER TABLE `faqs` ADD COLUMN IF NOT EXISTS `detailed_answer` TEXT DEFAULT NULL AFTER `answer`;
ALTER TABLE `faqs` ADD COLUMN IF NOT EXISTS `page_slug` VARCHAR(100) NOT NULL DEFAULT 'programmes.php' AFTER `category`;
ALTER TABLE `faqs` ADD COLUMN IF NOT EXISTS `related_link_url` VARCHAR(500) DEFAULT NULL AFTER `page_slug`;
ALTER TABLE `faqs` ADD COLUMN IF NOT EXISTS `related_link_text` VARCHAR(150) DEFAULT NULL AFTER `related_link_url`;

-- 5. Seed Initial Detailed Explanations for Existing FAQs
UPDATE `faqs` SET
  `detailed_answer` = 'Civic technology encompasses digital platforms, open data repositories, and community-driven mobile interfaces specifically designed to enhance government transparency, foster citizen participation, and resolve localized civic challenges collaboratively.',
  `related_link_url` = 'programmes.php',
  `related_link_text` = 'Explore CivicTech Thematic Area'
WHERE `id` = 1 AND (`detailed_answer` IS NULL OR `detailed_answer` = '');

UPDATE `faqs` SET
  `detailed_answer` = 'Through community consultation, ethical oral history recording, and participatory archival practices, BuniAfrika ensures cultural narratives and ancestral knowledge remain preserved by and accessible to the communities who created them.',
  `related_link_url` = 'about.php#story',
  `related_link_text` = 'Read Our Story and Milestones'
WHERE `id` = 2 AND (`detailed_answer` IS NULL OR `detailed_answer` = '');

UPDATE `faqs` SET
  `detailed_answer` = 'We welcome collaborations with civic society organizations, academic institutions, technological innovators, and philanthropic partners who align with our mission of informed citizenship and democratic accountability.',
  `related_link_url` = 'contact.php',
  `related_link_text` = 'Contact Partnerships Desk'
WHERE `id` = 3 AND (`detailed_answer` IS NULL OR `detailed_answer` = '');

UPDATE `faqs` SET
  `detailed_answer` = 'Volunteer cohorts work alongside our research and technology teams across community documentation, software development, event facilitation, and civic advocacy across Kenya.',
  `related_link_url` = 'volunteer.php',
  `related_link_text` = 'View Active Volunteer Opportunities'
WHERE `id` = 4 AND (`detailed_answer` IS NULL OR `detailed_answer` = '');
