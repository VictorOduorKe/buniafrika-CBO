/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ngo_web_app
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-6 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `admins`
--
USE ypl_db;

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES
(1,'admin','$2y$12$68wzvFRz6.ZZgQMcaT/zh.C6Bm8abxEryAuHsJp1RK7BK1fld5Pum','2026-06-08 14:31:10');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `contact_submissions`
--

DROP TABLE IF EXISTS `contact_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_submissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_submissions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `contact_submissions` WRITE;
/*!40000 ALTER TABLE `contact_submissions` DISABLE KEYS */;
INSERT INTO `contact_submissions` VALUES
(1,'getting-started','victoroduor723@gmail.com','Testing email scripts','checking if email engine is working','2026-06-09 15:20:58'),
(2,'getting-started','tumcomrade7@gmail.com','Testing email scripts','helo','2026-06-09 15:30:15'),
(3,'getting-started','tumcomrade7@gmail.com','Testing email scripts','helo','2026-06-09 15:32:33'),
(4,'getting-started','tumcomrade7@gmail.com','Testing email scripts','helo','2026-06-09 15:32:37'),
(5,'getting-started','tumcomrade7@gmail.com','Testing email scripts','helo','2026-06-09 15:32:54'),
(6,'getting-started','tumcomrade7@gmail.com','Testing email scripts','hello email test','2026-06-09 15:36:19'),
(7,'John','j@e.com','test','msg','2026-06-09 15:41:03'),
(8,'John','j@e.com','test','msg','2026-06-09 15:43:23');
/*!40000 ALTER TABLE `contact_submissions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `gallery_items`
--

DROP TABLE IF EXISTS `gallery_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `image_url` varchar(500) NOT NULL,
  `caption` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `gallery_items` WRITE;
/*!40000 ALTER TABLE `gallery_items` DISABLE KEYS */;
INSERT INTO `gallery_items` VALUES
(1,'Mass Tree planting Campaign','environment','assets/uploads/gallery/resized_0fc8d3d5c1406657d64239a5a664d260.webp','Mobilization of community members to engage in mass tree planting to conserve the environment','2026-06-08 15:42:23',1),
(2,'Community Farming','Community','assets/uploads/gallery/resized_fc4a38bfe5a2ade57082b58bda77f87e.webp','Visited one of the cumminity famers to inspect the current state of their farms.','2026-06-08 15:43:27',1),
(3,'School Project — Chesingei Primary School','education','assets/uploads/gallery/resized_388178b00e1d7cde91c0af0d9cf4dc97.webp','Handing books to Chesngei primary school to reduce the shortage of study materials','2026-06-08 15:45:45',1),
(4,'Sanitary Towels Distribution','education','assets/uploads/gallery/resized_dd881b9310d5ef9824ba533b5a299a86.webp','Giving out sanitary towels to primary school girls to prevent absentism and other factors that  might lead to low perfrmance.','2026-06-08 15:48:01',1),
(6,'Tree planting','environment','assets/uploads/gallery/resized_e4504f28d95a9a30ac69af75ed3ab084.webp','Engaging in tree planting activity for better tommorows environment','2026-06-08 15:51:22',1);
/*!40000 ALTER TABLE `gallery_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `login_attempts`
--

DROP TABLE IF EXISTS `login_attempts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `login_attempts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `attempted_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ip_ua_idx` (`ip_address`,`user_agent`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `login_attempts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `login_attempts` WRITE;
/*!40000 ALTER TABLE `login_attempts` DISABLE KEYS */;
/*!40000 ALTER TABLE `login_attempts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `news_articles`
--

DROP TABLE IF EXISTS `news_articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `content` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `author` varchar(100) DEFAULT 'Lumina Trust',
  `published_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_articles`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `news_articles` WRITE;
/*!40000 ALTER TABLE `news_articles` DISABLE KEYS */;
INSERT INTO `news_articles` VALUES
(1,'Panel on Combating Hate and Its Impact on Communities','Press Release','In Toronto delivering speech about hate and its impact','<h3>About&nbsp;This&nbsp;Initiative</h3><p></p><p>Addressing&nbsp;the&nbsp;root&nbsp;causes&nbsp;and&nbsp;societal&nbsp;impacts&nbsp;of&nbsp;hate&nbsp;speech&nbsp;and&nbsp;discrimination&nbsp;is&nbsp;essential&nbsp;for&nbsp;fostering&nbsp;inclusive&nbsp;and&nbsp;resilient&nbsp;urban&nbsp;spaces.&nbsp;Held&nbsp;in&nbsp;Toronto,&nbsp;this&nbsp;panel&nbsp;discussion&nbsp;brought&nbsp;together&nbsp;international&nbsp;delegates,&nbsp;policy&nbsp;experts,&nbsp;and&nbsp;community&nbsp;advocates&nbsp;to&nbsp;examine&nbsp;how&nbsp;hate&nbsp;impacts&nbsp;local&nbsp;communities.&nbsp;The&nbsp;session&nbsp;emphasized&nbsp;the&nbsp;shared&nbsp;global&nbsp;responsibility&nbsp;of&nbsp;countering&nbsp;division&nbsp;through&nbsp;constructive&nbsp;intercultural&nbsp;and&nbsp;political&nbsp;dialogue.</p><p></p><h3>Objectives</h3><p></p><ul><li><strong>Examine&nbsp;the&nbsp;community-level&nbsp;impacts</strong>&nbsp;of&nbsp;hate,&nbsp;discrimination,&nbsp;and&nbsp;exclusionary&nbsp;rhetoric.</li><li><strong>Share&nbsp;international&nbsp;strategies&nbsp;and&nbsp;best&nbsp;practices</strong>&nbsp;for&nbsp;building&nbsp;social&nbsp;cohesion&nbsp;and&nbsp;protecting&nbsp;vulnerable&nbsp;populations.</li><li><strong>Amplify&nbsp;diverse&nbsp;voices</strong>&nbsp;by&nbsp;providing&nbsp;a&nbsp;platform&nbsp;for&nbsp;global&nbsp;and&nbsp;local&nbsp;advocates&nbsp;to&nbsp;address&nbsp;systemic&nbsp;challenges.</li><li><strong>Strengthen&nbsp;institutional&nbsp;partnerships</strong>&nbsp;among&nbsp;international&nbsp;ministries,&nbsp;civic&nbsp;organizations,&nbsp;and&nbsp;grassroots&nbsp;leaders&nbsp;to&nbsp;actively&nbsp;counter&nbsp;hate.</li></ul><p></p><h3>Key&nbsp;Topics&nbsp;Covered</h3><p></p><p>The&nbsp;panel&nbsp;addressed&nbsp;the&nbsp;evolving&nbsp;landscape&nbsp;of&nbsp;hate&nbsp;speech,&nbsp;exploring&nbsp;how&nbsp;systemic&nbsp;biases&nbsp;undermine&nbsp;the&nbsp;safety&nbsp;and&nbsp;stability&nbsp;of&nbsp;diverse&nbsp;neighborhoods.&nbsp;Speakers&nbsp;highlighted&nbsp;the&nbsp;critical&nbsp;role&nbsp;of&nbsp;governmental&nbsp;cooperation&nbsp;drawing&nbsp;attention&nbsp;to&nbsp;supporting&nbsp;frameworks&nbsp;like&nbsp;those&nbsp;from&nbsp;the&nbsp;Ministry&nbsp;of&nbsp;Foreign&nbsp;Affairs&nbsp;of&nbsp;Denmark,&nbsp;cultivating&nbsp;grassroots&nbsp;resilience.&nbsp;The&nbsp;discussion&nbsp;emphasized&nbsp;actionable&nbsp;public&nbsp;policy,&nbsp;legislative&nbsp;protection,&nbsp;and&nbsp;the&nbsp;intersectional&nbsp;realities&nbsp;of&nbsp;those&nbsp;targeted&nbsp;by&nbsp;hate.</p><h3>Community&nbsp;Dialogue</h3><p>A&nbsp;central&nbsp;pillar&nbsp;of&nbsp;the&nbsp;initiative&nbsp;was&nbsp;fostering&nbsp;open,&nbsp;high-level&nbsp;dialogue&nbsp;that&nbsp;directly&nbsp;connects&nbsp;global&nbsp;policy&nbsp;with&nbsp;lived&nbsp;community&nbsp;experiences.&nbsp;The&nbsp;panel&nbsp;facilitated&nbsp;a&nbsp;dynamic&nbsp;exchange&nbsp;where&nbsp;delegates&nbsp;actively&nbsp;listened,&nbsp;shared&nbsp;insights,&nbsp;and&nbsp;addressed&nbsp;challenging&nbsp;societal&nbsp;dynamics&nbsp;in&nbsp;a&nbsp;collaborative&nbsp;setting.&nbsp;By&nbsp;encouraging&nbsp;transparent&nbsp;communication&nbsp;between&nbsp;international&nbsp;bodies&nbsp;and&nbsp;local&nbsp;advocates,&nbsp;the&nbsp;forum&nbsp;created&nbsp;a&nbsp;trusted&nbsp;space&nbsp;to&nbsp;brainstorm&nbsp;scalable&nbsp;solutions&nbsp;for&nbsp;safer,&nbsp;more&nbsp;unified&nbsp;communities.</p><p></p><h3>Impact&nbsp;</h3><p></p><p>By&nbsp;analyzing&nbsp;the&nbsp;profound&nbsp;effects&nbsp;of&nbsp;hate&nbsp;on&nbsp;public&nbsp;safety&nbsp;and&nbsp;community&nbsp;well-being,&nbsp;the&nbsp;panel&nbsp;equipped&nbsp;attendees&nbsp;and&nbsp;organizing&nbsp;partners&nbsp;with&nbsp;a&nbsp;deeper&nbsp;framework&nbsp;for&nbsp;advocacy&nbsp;and&nbsp;policy&nbsp;reform.&nbsp;The&nbsp;insights&nbsp;shared&nbsp;during&nbsp;the&nbsp;Toronto&nbsp;session&nbsp;underscore&nbsp;the&nbsp;necessity&nbsp;of&nbsp;continuous&nbsp;cross-border&nbsp;collaboration,&nbsp;reinforcing&nbsp;a&nbsp;collective&nbsp;determination&nbsp;to&nbsp;replace&nbsp;divisive&nbsp;narratives&nbsp;with&nbsp;solidarity,&nbsp;equity,&nbsp;and&nbsp;mutual&nbsp;respect.</p><p></p><h3>Project&nbsp;Details</h3><p></p><ul><li><strong>Category:</strong>&nbsp;Human&nbsp;Rights&nbsp;/&nbsp;Community&nbsp;Advocacy</li><li><strong>Reach:</strong>&nbsp;International&nbsp;delegates,&nbsp;policymakers,&nbsp;community&nbsp;advocates,&nbsp;and&nbsp;civic&nbsp;leaders</li><li><strong>Mode&nbsp;of&nbsp;Delivery:</strong>&nbsp;Panel&nbsp;presentation,&nbsp;interactive&nbsp;Q&amp;A,&nbsp;and&nbsp;policy&nbsp;dialogue&nbsp;session</li><li><strong>Media:</strong>&nbsp;Documented&nbsp;photographic&nbsp;coverage</li></ul><p></p>','assets/uploads/news/resized_c53a6c97d2497bada721b127a41f1210.webp','YPL','2026-06-08','2026-06-08 15:08:00',1,''),
(2,'Youth Sports Empowerment Initiative','empowerment','Youth empowerment sports activity','<h3>About This Initiative</h3><p></p><p>Investing in youth sports and mentorship is a powerful catalyst for nurturing talent, promoting physical well-being, and fostering community pride. As captured in empowerment.jpg, this grassroots initiative brought together community leaders, civic representatives, and young athletes to support local youth development through sports. The event was conducted in an encouraging and inclusive manner, emphasizing the role of mentorship in steering the younger generation toward positive life paths.</p><p></p><h3>Objectives</h3><p></p><p></p><p></p><h3>Key Topics Covered</h3><p></p><p>The initiative highlighted sports as a valuable tool for social change, focusing on character building, discipline, and community cohesion. Organizers and leadership representatives discussed the importance of keeping youth positively engaged through extracurricular activities to mitigate social risks. The session emphasized providing grassroots teams with the visibility and material support they need to thrive.</p><p></p><h3>Community Dialogue</h3><p></p><p>A vibrant component of the event was the direct, positive interaction between civic leaders and the youth. As shown in empowerment.jpg, community dignitaries actively engaged with a local youth soccer team dressed in matching yellow jerseys. The ceremonial presentation of a soccer ball served as a powerful symbol of encouragement, facilitating a supportive, open dialogue where young players felt visible, valued, and motivated by their community&#39;s leadership.</p><p></p><h3>Impact</h3><p></p><p>By equipping young athletes with sporting gear and personal encouragement, this initiative significantly boosts youth morale and promotes long-term community wellness. The successful outreach strengthens the bond between local leadership and the younger generation, laying down a foundation for sustained mentorship, talent development, and positive civic engagement across the region.</p><h3>Project Details</h3><p></p><p></p><p></p>','assets/uploads/news/resized_aae7c6efa6ba0c8b20195e0356995e70.webp','YPL','2026-06-08','2026-06-08 15:56:27',1,'');
/*!40000 ALTER TABLE `news_articles` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `published` tinyint(1) DEFAULT 1,
  `video_url` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `projects`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `projects` WRITE;
/*!40000 ALTER TABLE `projects` DISABLE KEYS */;
INSERT INTO `projects` VALUES
(1,'Youth Empowerment — Koykoy Soccer Team','empowerment','<p><strong style=\"color: rgb(0, 41, 102);\">About This Initiative</strong></p><p></p><p>Young people thrive when they have structured outlets for their energy, creativity, and ambition. This initiative centred on the Koykoy soccer team — a group of passionate youth leaders who use sport as a vehicle for discipline, community belonging, and personal development. By donating a football to the team, we made a simple but meaningful investment in their journey and their dreams.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Why This Matters</strong></p><p></p><p>Sport plays a critical role in youth development. It fosters teamwork, resilience, time management, and leadership — skills that extend far beyond the pitch. The Koykoy soccer team is not just a sports club; its members are recognised youth leaders in their community who mentor peers and champion positive values through the beautiful game.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Objectives</strong></p><p></p><p>Provide sporting equipment to support youth athletic activities.</p><p>Recognise and celebrate the role of youth leaders in the community.</p><p>Encourage youth engagement through sport and positive peer mentorship.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">The Handover</strong></p><p></p><p>A football was officially handed over to the Koykoy team&#39;s representatives in a community event. The handover was accompanied by words of encouragement from organisers and a call to keep inspiring the next generation. Team members expressed gratitude and reaffirmed their commitment to using sport as a platform for positive change.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Impact</strong></p><p></p><p>The support given to the Koykoy soccer team sends a wider message to the community: youth efforts are seen, valued, and worthy of backing. Such recognition motivates young people to stay engaged in structured, constructive activities and reduces the risk of idleness and disengagement among the youth population.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Project Details</strong></p><p></p><p><strong style=\"color: rgb(31, 56, 100);\">Category: </strong>Youth Empowerment</p><p><strong style=\"color: rgb(31, 56, 100);\">Beneficiaries: </strong>Koykoy Soccer Team members and youth leaders</p><p><strong style=\"color: rgb(31, 56, 100);\">Mode of Support: </strong>In-kind donation of football equipment</p>','assets/uploads/projects/resized_0314fa690bc436fbd1555f75a2133b58.webp','2026-06-08 14:37:32',1,''),
(2,'School Project — Chesingei Primary School','education','<p><strong style=\"color: rgb(0, 41, 102);\">About This Initiative</strong></p><p></p><p>This education drive focused on providing much-needed study books to learners at Chesingei Primary School. Access to quality learning materials remains one of the biggest barriers to academic achievement in many rural communities. Through this project, we stepped in to bridge that gap by sourcing and distributing a wide range of textbooks, exercise books, and reference materials directly to the students who need them most.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Objectives</strong></p><p></p><p>Supply essential study books to pupils who lack access to learning materials.</p><p>Support academic performance and school attendance at Chesingei Primary School.</p><p>Strengthen the partnership between the organisation and the local school community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">What We Did</strong></p><p></p><p>Community leaders, teachers, and parents were consulted to identify the most critical learning resource gaps at the school. Study books were then sourced, packaged, and handed directly to pupils during a school assembly, ensuring every child received materials in good condition. The handover was conducted with participation from school administration and local community members.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Impact</strong></p><p></p><p>With proper study materials now in hand, students can prepare more effectively for exams, engage deeper with their subjects, and build the academic foundation they need to succeed. Teachers are better equipped to assign reading and homework, and parents are encouraged by the visible investment in their children&#39;s futures.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Project Details</strong></p><p></p><p><strong style=\"color: rgb(31, 56, 100);\">Category: </strong>Education</p><p><strong style=\"color: rgb(31, 56, 100);\">Beneficiaries: </strong>Pupils of Chesingei Primary School</p><p><strong style=\"color: rgb(31, 56, 100);\">Mode of Support: </strong>In-kind donation of study books</p>','assets/uploads/projects/resized_927bb78311d44b94fa9b69ee9b65dd8e.webp','2026-06-08 14:45:01',1,''),
(3,'Family Planning Mass Campaign','health','<p><strong style=\"color: rgb(0, 41, 102);\">About This Initiative</strong></p><p></p><p>Access to accurate information about family planning is a cornerstone of healthy, thriving communities. This mass campaign brought together health professionals, community leaders, and local residents to raise awareness about the importance of family planning. The campaign was conducted in a culturally sensitive and inclusive manner, reaching community members across different ages and backgrounds.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Objectives</strong></p><p></p><p>Raise awareness about family planning options available at local health facilities.</p><p>Dispel myths and misconceptions surrounding reproductive health and contraception.</p><p>Empower families to make informed decisions about birth spacing and maternal health.</p><p>Highlight the social and economic benefits of planned families for the broader community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Key Topics Covered</strong></p><p></p><p>The campaign addressed reproductive health education including birth spacing, maternal wellbeing, and available contraception options. Open community dialogues were held to address cultural concerns and create a safe space for honest questions. Health professionals led informational sessions tailored to the needs of both men and women in the community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Community Dialogue</strong></p><p></p><p>A key component of the campaign was open dialogue. Facilitators created spaces for community members to ask questions, voice concerns, and engage with health information in a non-judgmental environment. Cultural sensitivities were respected throughout, and local leaders played an active role in encouraging community participation and normalising conversations around family health.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Media Coverage</strong></p><p></p><p>The campaign attracted attention from local media outlets, including television broadcast coverage. This amplified the message about family planning beyond the immediate event, extending its reach to a wider audience across the region. Media visibility helped reinforce the legitimacy and importance of the campaign&#39;s public health goals.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Impact</strong></p><p></p><p>By delivering clear, accurate, and respectful information, this campaign equipped families with the knowledge to make better health decisions. Greater awareness of family planning contributes to improved maternal and child health outcomes, reduced pressure on household resources, and stronger, more stable family units across the community.</p><p></p><p><strong style=\"color: rgb(0, 41, 102);\">Project Details</strong></p><p></p><p><strong style=\"color: rgb(31, 56, 100);\">Category: </strong>Health</p><p><strong style=\"color: rgb(31, 56, 100);\">Reach: </strong>Local community members — men, women, and youth</p><p><strong style=\"color: rgb(31, 56, 100);\">Mode of Delivery: </strong>Mass campaign with community dialogue sessions</p><p><strong style=\"color: rgb(31, 56, 100);\">Media: </strong>Local television broadcast coverage</p>','assets/uploads/projects/resized_14f0ae7b890363e5137124c4b1814d8d.webp','2026-06-08 14:47:10',1,''),
(4,'School Food Distribution Initiative','education','<h3>About This Initiative</h3><p></p><p>Ensuring food security and nutritional support is essential for building resilient and thriving communities. This is community-focused initiative brought together local leaders, administration, and residents to distribute vital food supplies to those in need. The initiative was conducted in a transparent and structured manner, fostering a spirit of mutual support and community resilience.</p><p></p><h3>Objectives</h3><p></p><ul><li><strong>Provide&nbsp;immediate&nbsp;relief</strong>&nbsp;by&nbsp;distributing&nbsp;essential&nbsp;food&nbsp;staples&nbsp;directly&nbsp;to&nbsp;community&nbsp;members.</li><li><strong>Strengthen&nbsp;community&nbsp;networks</strong>&nbsp;through&nbsp;collaborative&nbsp;relief&nbsp;efforts&nbsp;involving&nbsp;local&nbsp;administration&nbsp;and&nbsp;residents.</li><li><strong>Support&nbsp;vulnerable&nbsp;households</strong>&nbsp;by&nbsp;alleviating&nbsp;the&nbsp;financial&nbsp;and&nbsp;logistical&nbsp;burden&nbsp;of&nbsp;sourcing&nbsp;core&nbsp;food&nbsp;commodities.</li></ul><p></p><h3>Key&nbsp;Topics&nbsp;Covered</h3><p></p><p>The&nbsp;initiative&nbsp;centered&nbsp;around&nbsp;the&nbsp;efficient&nbsp;logistics&nbsp;of&nbsp;food&nbsp;aid&nbsp;delivery&nbsp;and&nbsp;public-private&nbsp;community&nbsp;cooperation.&nbsp;Organizers&nbsp;and&nbsp;local&nbsp;leaders&nbsp;engaged&nbsp;with&nbsp;community&nbsp;members&nbsp;to&nbsp;assess&nbsp;ongoing&nbsp;needs,&nbsp;discuss&nbsp;distribution&nbsp;equity,&nbsp;and&nbsp;ensure&nbsp;that&nbsp;relief&nbsp;packages&nbsp;reached&nbsp;the&nbsp;intended&nbsp;families.</p><p></p><h3>Community&nbsp;Dialogue</h3><p></p><p>A&nbsp;foundational&nbsp;aspect&nbsp;of&nbsp;this&nbsp;event&nbsp;was&nbsp;direct&nbsp;engagement&nbsp;and&nbsp;mutual&nbsp;respect.&nbsp;As&nbsp;seen&nbsp;in&nbsp;food.jpg,&nbsp;local&nbsp;representatives&nbsp;and&nbsp;community&nbsp;members&nbsp;shared&nbsp;meaningful&nbsp;interactions,&nbsp;symbolized&nbsp;by&nbsp;a&nbsp;handshake,&nbsp;confirming&nbsp;a&nbsp;shared&nbsp;commitment&nbsp;to&nbsp;community&nbsp;welfare.&nbsp;These&nbsp;open&nbsp;handovers&nbsp;provide&nbsp;a&nbsp;trusted&nbsp;platform&nbsp;for&nbsp;residents&nbsp;to&nbsp;express&nbsp;their&nbsp;local&nbsp;challenges&nbsp;and&nbsp;build&nbsp;transparent&nbsp;relationships&nbsp;with&nbsp;community&nbsp;organizers.</p><p></p><h3>Impact</h3><p></p><p>By&nbsp;delivering&nbsp;substantial&nbsp;food&nbsp;resources&nbsp;directly&nbsp;to&nbsp;the&nbsp;area,&nbsp;this&nbsp;distribution&nbsp;drive&nbsp;provided&nbsp;immediate&nbsp;relief&nbsp;to&nbsp;household&nbsp;budgets&nbsp;and&nbsp;improved&nbsp;daily&nbsp;food&nbsp;security.&nbsp;The&nbsp;successful&nbsp;collaboration&nbsp;reinforces&nbsp;community&nbsp;solidarity,&nbsp;boosts&nbsp;public&nbsp;morale,&nbsp;and&nbsp;establishes&nbsp;a&nbsp;reliable&nbsp;framework&nbsp;for&nbsp;future&nbsp;community&nbsp;support&nbsp;and&nbsp;development&nbsp;projects.</p><p></p><h3>Project&nbsp;Details</h3><p></p><ul><li><strong>Category:</strong>&nbsp;Social&nbsp;Welfare&nbsp;/&nbsp;Community&nbsp;Support</li><li><strong>Reach:</strong>&nbsp;Local&nbsp;community&nbsp;members,&nbsp;families,&nbsp;and&nbsp;institutional&nbsp;representatives</li><li><strong>Mode&nbsp;of&nbsp;Delivery:</strong>&nbsp;Direct&nbsp;food&nbsp;supply&nbsp;handover&nbsp;and&nbsp;community&nbsp;engagement</li><li><strong>Media:</strong>&nbsp;Documented&nbsp;photographic&nbsp;coverage</li></ul>','assets/uploads/projects/resized_da7ae576ba75e7a002a384cab45cb60a.webp','2026-06-08 14:54:19',1,'');
/*!40000 ALTER TABLE `projects` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `registrations`
--

DROP TABLE IF EXISTS `registrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `registrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `role` varchar(50) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `registrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `registrations` WRITE;
/*!40000 ALTER TABLE `registrations` DISABLE KEYS */;
INSERT INTO `registrations` VALUES
(1,'victor','oduor','admin@school.com','+254 102 638 973','volunteer','testing email','2026-06-09 15:16:30','::1');
/*!40000 ALTER TABLE `registrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `upcoming_events`
--

DROP TABLE IF EXISTS `upcoming_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `upcoming_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `location` varchar(255) NOT NULL,
  `published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upcoming_events`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `upcoming_events` WRITE;
/*!40000 ALTER TABLE `upcoming_events` DISABLE KEYS */;
INSERT INTO `upcoming_events` VALUES
(1,'Annual Community Fundraiser','Join us for our annual fundraising gala to support local agriculture initiatives.','2027-04-15 18:00:00','Nairobi Convention Center',1,'2026-06-09 13:39:03');
/*!40000 ALTER TABLE `upcoming_events` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-06-09 18:52:40
