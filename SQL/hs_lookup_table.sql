-- Replace with the DB you want to have the table created in
USE YOUR_DB;

-- Craete table `hs_url_lookup`
CREATE TABLE IF NOT EXISTS `hs_url_lookup` (
  `url_id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique identity field',
  `big_url` varchar(255) NOT NULL COMMENT 'Original URL',
  `small_url` varchar(255) NOT NULL COMMENT 'Minified URL',
  `ip_address` varchar(255) NOT NULL COMMENT 'IP address of visitor',
	`url_added_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date and time the URL was minified',
  PRIMARY KEY (`url_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Create trigger to update `url_added_at` field when new insert
CREATE TRIGGER `hs_url_lookup_added_at` BEFORE INSERT ON `hs_url_lookup` FOR EACH ROW SET NEW.url_added_at = IFNULL(NEW.url_added_at, NOW()),
 NEW.url_added_at = NOW();