-- BuniAfrika Dynamic CMS Expansion Migration
-- Creates dedicated relational tables for all database-driven content modules.
-- Safe and additive: uses CREATE TABLE IF NOT EXISTS and INSERT IGNORE.

-- 1. Thematic Areas
CREATE TABLE IF NOT EXISTS `thematic_areas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(180) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT 'account_balance',
  `image_url` varchar(500) DEFAULT NULL,
  `points` text DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_thematic_published_order` (`published`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Organisational Objectives
CREATE TABLE IF NOT EXISTS `objectives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT 'Strategic',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_objectives_published_order` (`published`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Publications and Reports
CREATE TABLE IF NOT EXISTS `publications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `author` varchar(255) DEFAULT 'BuniAfrika Research Team',
  `category` varchar(100) NOT NULL DEFAULT 'Annual Report',
  `file_url` varchar(500) NOT NULL,
  `file_size` varchar(50) DEFAULT NULL,
  `file_type` varchar(50) DEFAULT 'PDF',
  `cover_image_url` varchar(500) DEFAULT NULL,
  `publication_date` date NOT NULL,
  `publication_year` int(4) DEFAULT NULL,
  `download_count` int(11) NOT NULL DEFAULT 0,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_publications_published_date` (`published`, `publication_date`),
  KEY `idx_publications_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Testimonials
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(150) NOT NULL,
  `designation` varchar(150) DEFAULT NULL,
  `organization` varchar(150) DEFAULT NULL,
  `testimonial_text` text NOT NULL,
  `author_image_url` varchar(500) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_testimonials_published_order` (`published`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Frequently Asked Questions (FAQs)
CREATE TABLE IF NOT EXISTS `faqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'General',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_faqs_published_order` (`published`, `display_order`),
  KEY `idx_faqs_category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Partnerships
CREATE TABLE IF NOT EXISTS `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `logo_url` varchar(500) DEFAULT NULL,
  `website_url` varchar(2048) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_partners_published_order` (`published`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Volunteer Opportunities
CREATE TABLE IF NOT EXISTS `volunteer_opportunities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `responsibilities` text DEFAULT NULL,
  `location` varchar(150) DEFAULT 'Mombasa / Remote',
  `deadline` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vol_opp_published_status` (`published`, `status`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Volunteer Applications
CREATE TABLE IF NOT EXISTS `volunteer_applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `opportunity_id` int(11) DEFAULT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `skills_or_experience` text DEFAULT NULL,
  `motivation_message` text NOT NULL,
  `resume_url` varchar(500) DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vol_app_status_created` (`status`, `created_at`),
  KEY `idx_vol_app_opportunity_id` (`opportunity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Our Story / Milestones
CREATE TABLE IF NOT EXISTS `our_story` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year_or_date` varchar(50) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_story_published_order` (`published`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Core Values
CREATE TABLE IF NOT EXISTS `core_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `icon` varchar(100) DEFAULT 'verified',
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_values_published_order` (`published`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Achievements and Impact
CREATE TABLE IF NOT EXISTS `achievements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `metric_value` varchar(50) DEFAULT NULL,
  `metric_unit` varchar(100) DEFAULT NULL,
  `reporting_period` varchar(100) DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_achievements_published_order` (`published`, `display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================================
-- INITIAL SEED DATA (Preserving existing organisational information)
-- ==============================================================================

-- Seed Thematic Areas with BuniAfrika's existing primary focus areas
INSERT IGNORE INTO `thematic_areas` (`id`, `title`, `description`, `icon`, `points`, `display_order`, `published`) VALUES
(1, 'CivicTech Awareness', 'Civic technology education and digital participation.', 'account_balance', 'Democratic governance, transparency and public accountability.\nDigital rights and responsible technology.\nYouth leadership and inclusive civic participation.\nEvidence-based research and civic problem-solving.\nCollaborative policy engagement.', 1, 1),
(2, 'Culture and Heritage', 'Community-led approaches to documenting and responsibly preserving African cultural identity and heritage.', 'museum', 'African cultural identity and heritage preservation.\nCommunity histories and indigenous knowledge.\nDigital archiving and cultural storytelling.\nIntergenerational knowledge transfer.\nCommunity-led heritage documentation.\nResponsible digital preservation.', 2, 1);

-- Seed Organisational Objectives with existing established objectives
INSERT IGNORE INTO `objectives` (`id`, `title`, `description`, `category`, `display_order`, `published`) VALUES
(1, 'Advance democratic governance and public accountability.', 'Support public institutions and citizen action through transparency and ethical governance.', 'Governance', 1, 1),
(2, 'Generate evidence-based research on critical public-interest challenges.', 'Produce reliable studies and grassroots data to inform community problem-solving.', 'Research', 2, 1),
(3, 'Develop innovative technological solutions to civic problems.', 'Design open civic technology tools that empower citizens to access public information and services.', 'Innovation', 3, 1),
(4, 'Strengthen inclusive civic participation, digital rights and youth leadership.', 'Equip youth, women, and marginalized groups with digital safety and advocacy capabilities.', 'Participation', 4, 1),
(5, 'Promote collaborative policy reform and measurable social impact.', 'Facilitate community dialogues and policy engagements that translate ideas into lasting social change.', 'Policy', 5, 1);

-- Seed FAQs from existing common questions
INSERT IGNORE INTO `faqs` (`id`, `question`, `answer`, `category`, `display_order`, `published`) VALUES
(1, 'What is civic technology?', 'Civic technology uses digital tools and public-interest knowledge to support civic participation, accountability and community problem-solving.', 'General', 1, 1),
(2, 'How does BuniAfrika approach cultural heritage?', 'BuniAfrika partners with community elders, custodians, and youth to document indigenous knowledge, oral histories, and cultural identity through responsible digital archiving and storytelling.', 'Programmes', 2, 1),
(3, 'How can an organisation discuss a partnership?', 'Send a partnership enquiry through our online partner registration portal or contact our partnerships desk directly at partnerships@buniafrika.co.ke.', 'Partnerships', 3, 1),
(4, 'How can I volunteer or get involved?', 'Browse our current volunteer opportunities on the website, select a role matching your skills, and submit an application through the online form.', 'Volunteering', 4, 1);

-- Seed Core Values
INSERT IGNORE INTO `core_values` (`id`, `title`, `description`, `icon`, `display_order`, `published`) VALUES
(1, 'Integrity & Accountability', 'We hold ourselves and public institutions to the highest standards of honesty, transparency, and ethical responsibility.', 'gavel', 1, 1),
(2, 'Inclusive Participation', 'Every community member—especially youth and women—deserves an active voice in decisions that shape their future.', 'groups', 2, 1),
(3, 'Innovation for Public Good', 'We harness technology responsibly to solve real community challenges and defend digital rights.', 'lightbulb', 3, 1),
(4, 'Cultural Preservation', 'We honor and preserve African cultural heritage, indigenous knowledge systems, and historical memory.', 'museum', 4, 1),
(5, 'Evidence-Based Action', 'Our advocacy, programmes, and civic solutions are grounded in rigorous research and verifiable data.', 'science', 5, 1);

-- Seed Our Story Milestones
INSERT IGNORE INTO `our_story` (`id`, `year_or_date`, `title`, `description`, `image_url`, `display_order`, `published`) VALUES
(1, '2020', 'Founding of BuniAfrika', 'Established in Mombasa, Kenya, to bridge research, technology, and grassroots civic action.', 'assets/about.jpg', 1, 1),
(2, '2022', 'CivicTech & Youth Empowerment', 'Launched community civic technology training and digital literacy workshops for coastal youth.', 'assets/empowerment.jpg', 2, 1),
(3, '2024', 'Heritage Archiving & Research Expansion', 'Initiated community-led African cultural identity documentation and evidence-based policy research.', 'assets/rooted-heritage.jpg', 3, 1);

-- 11. Rate Limits Table for Security & Abuse Prevention
CREATE TABLE IF NOT EXISTS `rate_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(64) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 1,
  `first_attempt_at` int(11) NOT NULL,
  `last_attempt_at` int(11) NOT NULL,
  `blocked_until` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_action_ip` (`action`, `ip_address`),
  KEY `idx_blocked` (`blocked_until`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


