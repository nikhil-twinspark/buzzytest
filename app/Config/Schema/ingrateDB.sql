

DROP TABLE IF EXISTS `integrateortho`.`balance_histories`;
DROP TABLE IF EXISTS `integrateortho`.`campaigns`;
DROP TABLE IF EXISTS `integrateortho`.`cities`;
DROP TABLE IF EXISTS `integrateortho`.`client_credentials`;
DROP TABLE IF EXISTS `integrateortho`.`clients`;
DROP TABLE IF EXISTS `integrateortho`.`documents`;
DROP TABLE IF EXISTS `integrateortho`.`notifications`;
DROP TABLE IF EXISTS `integrateortho`.`patients`;
DROP TABLE IF EXISTS `integrateortho`.`promotions`;
DROP TABLE IF EXISTS `integrateortho`.`redeem_rewards`;
DROP TABLE IF EXISTS `integrateortho`.`refers`;
DROP TABLE IF EXISTS `integrateortho`.`reward_lists`;
DROP TABLE IF EXISTS `integrateortho`.`staffs`;
DROP TABLE IF EXISTS `integrateortho`.`states`;
DROP TABLE IF EXISTS `integrateortho`.`themes`;
DROP TABLE IF EXISTS `integrateortho`.`wish_lists`;


CREATE TABLE `integrateortho`.`balance_histories` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`balance_id` int(11) NOT NULL,
	`authorization` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`service_product` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`redeemed` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`amount` int(11) DEFAULT NULL,
	`campaign_id` bigint(20) NOT NULL,
	`client_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`real` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`orig_amount` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`date` date NOT NULL,
	`campaign_type` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`card_number` int(11) NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`campaigns` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`client_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`campaign_id` bigint(20) NOT NULL,
	`name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`type` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`points_ratio` int(11) DEFAULT NULL,
	`reward_ratio` int(11) DEFAULT NULL,
	`currency` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`glyph` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,	PRIMARY KEY  (`id`),
	KEY `user_id` (`client_id`),
	KEY `campaign_id` (`campaign_id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`cities` (
	`city` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`state_code` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL	) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`client_credentials` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`api_user` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`api_key` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`accountId` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`api_url` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`site_window_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`staff_url` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`patient_url` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`client_id` int(11) NOT NULL,
	`site_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,	PRIMARY KEY  (`id`),
	KEY `api_user` (`api_user`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`clients` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`client_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`client_password` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`client_first_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`client_last_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`client_email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`is_facebook` int(11) DEFAULT 0 NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`documents` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`document` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`title` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`campaign_id` bigint(20) NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`notifications` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`customer_card` int(11) NOT NULL,
	`client_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`order_status` int(1) DEFAULT 0 NOT NULL,
	`earn_points` int(1) DEFAULT 0 NOT NULL,
	`reward_challenges` int(1) DEFAULT 0 NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`patients` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`code` bigint(20) NOT NULL,
	`client_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`card_number` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`first_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`last_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`customer_password` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`phone` bigint(20) DEFAULT NULL,
	`email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`street1` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`street2` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`city` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`state` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`postal_code` int(11) DEFAULT NULL,
	`gender` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`main_interest` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`country` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_date` date DEFAULT NULL,
	`parents_email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`customer_username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_field` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`registered` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`enrollment_stamp` datetime DEFAULT NULL,
	`custom_field_2` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_field_3` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_field_4` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_field_5` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_field_6` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_field_7` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`custom_field_8` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`facebook_id` bigint(20) DEFAULT NULL,
	`is_facebook` int(4) DEFAULT NULL,
	`facebook_like_status` tinyint(1) DEFAULT NULL,
	`status` int(4) DEFAULT NULL,
	`is_varified` int(4) DEFAULT NULL,
	`blocked` tinyint(1) DEFAULT '0',	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`promotions` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`promotion_id` int(11) NOT NULL,
	`operand` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`value` int(11) NOT NULL,
	`description` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`promo_custom_id` int(11) DEFAULT NULL,
	`campaign_id` bigint(20) NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`redeem_rewards` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`TransId` int(11) NOT NULL,
	`TransDate` datetime NOT NULL,
	`UserId` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`Reward` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`CardNumber` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`campaign_id` bigint(20) NOT NULL,
	`CustomerFirstName` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`reward_id` bigint(20) NOT NULL,
	`CustomerEmail` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`RedeemAmount` int(11) NOT NULL,
	`EmailTo` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`Status` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=MyISAM;

CREATE TABLE `integrateortho`.`refers` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`client_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`card_number` int(11) NOT NULL,
	`first_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`last_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`email` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`massage` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`status` int(11) DEFAULT 0 NOT NULL,
	`refdate` date NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`reward_lists` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`reward_id` int(11) NOT NULL,
	`campaign_id` bigint(20) NOT NULL,
	`points` int(10) DEFAULT NULL,
	`description` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`category` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`imagepath` varchar(250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,	PRIMARY KEY  (`id`),
	KEY `campaign_id` (`campaign_id`),
	KEY `campaign_id_2` (`campaign_id`),
	KEY `campaign_id_3` (`campaign_id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`staffs` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`staff_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`client_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`staff_first_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`staff_last_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`staff_password` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`staff_pin` int(11) DEFAULT NULL,
	`staff_addtl_info` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`staff_language` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`staff_language_custom` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`staff_timezone` int(11) NOT NULL,
	`staff_role` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`staff_allowed_campaigns` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`states` (
	`state` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
	`state_code` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL	) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`themes` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`client_credential_id` int(11) DEFAULT NULL,
	`staff_logo_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`patient_logo_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`backgroud_image_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`patient_footer_logo_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`patient_question_mark` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`facebook_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`pintrest_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`twitter_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`instagram_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`google_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`yelp_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`youtube_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`healthgrade_url` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`analytic_code` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`challenge_header_image` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`challenge_name` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`challenge_description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`challenge_area` text CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`fb_app_id` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
	`fb_app_key` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

CREATE TABLE `integrateortho`.`wish_lists` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`reward_id` int(11) NOT NULL,
	`customer_card` int(11) NOT NULL,
	`client_id` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,	PRIMARY KEY  (`id`)) 	DEFAULT CHARSET=latin1,
	COLLATE=latin1_swedish_ci,
	ENGINE=InnoDB;

