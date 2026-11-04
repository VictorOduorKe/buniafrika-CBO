-- Add default leader description keys to site_settings if not present
INSERT IGNORE INTO `site_settings` (`setting_key`, `setting_value`) VALUES
('leader_rahma_description', ''),
('leader_brodox_description', ''),
('leader_kennedy_description', '');
