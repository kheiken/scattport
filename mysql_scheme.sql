SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `experiments` (
  `id` varchar(40) NOT NULL,
  `project_id` varchar(40) NOT NULL,
  `creator_id` varchar(16) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` text NOT NULL,
  `program_id` varchar(8) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `experiments_parameters` (
  `experiment_id` varchar(40) NOT NULL,
  `parameter_id` varchar(16) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`experiment_id`,`parameter_id`),
  KEY `trial_id` (`experiment_id`),
  KEY `parameter_id` (`parameter_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `groups` (
  `id` varchar(16) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
('356a192b7913b04c', 'admins', 'Administrators'),
('da4b9237bacccdf1', 'users', 'Users');

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` varchar(40) NOT NULL,
  `experiment_id` varchar(40) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `started_by` varchar(40) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `finished_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  `notified` tinyint(1) NOT NULL DEFAULT '0',
  `status` set('idle','deploying','pending','running','finished','aborted') NOT NULL,
  `progress` tinyint(3) NOT NULL DEFAULT '0',
  `server` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `experiment_id` (`experiment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `parameters` (
  `id` varchar(16) NOT NULL,
  `program_id` varchar(8) NOT NULL,
  `name` varchar(255) NOT NULL,
  `readable` varchar(100) NOT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `default_value` varchar(255) NOT NULL,
  `description` text,
  `type` varchar(20) NOT NULL,
  `sort_number` mediumint(8) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `program_id` (`program_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `parameters` (`id`, `program_id`, `name`, `readable`, `unit`, `default_value`, `description`, `type`, `sort_number`) VALUES
('7effed917effed91', '28dc3aee', 'refractive_idx_im', 'Refractive index (Im)', NULL, '', 'Imaginary part of refractive index', 'float', 4),
('76676cbe76676cbe', '28dc3aee', 'refractive_idx_re', 'Refractive index (Re)', NULL, '', 'Real part of refractive index', 'float', 3),
('bccf34eabccf34ea', '28dc3aee', 'lung_de_unda', 'Wavelength', 'µm', '', 'Wavelength of the light', 'float', 6),
('f0f2c64af0f2c64a', '28dc3aee', 'Nrank', 'Nrank', NULL, '10', NULL, 'integer', 2),
('6ce5f99f6ce5f99f', '28dc3aee', 'Mrank', 'Mrank', '', '5', '', 'integer', 1),
('930a320e930a320e', '28dc3aee', 'radius_norm', 'Radius for normalization', NULL, '', NULL, 'float', 5),
('56434914c460f829', '3cc9f0cb', 'time', 'Time', 's', '', 'The time', 'integer', 1),
('b55579734a6f24a7', '28dc3aee', 'boolean', 'Boolean that is true or false', '', '', 'Boolean that is true or false depending on whatever', 'boolean', 7);

CREATE TABLE IF NOT EXISTS `programs` (
  `id` varchar(8) NOT NULL,
  `name` varchar(100) NOT NULL,
  `driver` varchar(20) NOT NULL,
  `config_template` text,
  `output_line` text,
  PRIMARY KEY (`id`),
  KEY `driver` (`driver`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `programs` (`id`, `name`, `driver`, `config_template`, `output_line`) VALUES
('28dc3aee', 'SScaTT', 'scatt', '&calculation_parameters\ngeo_file_name=default.obj;\ntmat_file_name=default.tma;\nscat_diag_file_name=default.out;\n{parameters}\n{name}={value};\n{/parameters}\n', '0'),
('3cc9f0cb', 'Sample Program', 'lmsm', '{type} {param} = {value}\r\n', '0');

CREATE TABLE IF NOT EXISTS `projects` (
  `id` varchar(40) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `owner` varchar(16) NOT NULL,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_access` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `browsable` tinyint(1) NOT NULL,
  `public` tinyint(1) NOT NULL,
  `default_model` varchar(255) DEFAULT NULL,
  `default_config` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `servers` (
  `id` varchar(20) NOT NULL,
  `secret` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `hardware` text NOT NULL,
  `os` varchar(25) NOT NULL,
  `uptime` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `owner` varchar(16) NOT NULL,
  `available` tinyint(1) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `workload` double NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`),
  KEY `secret` (`secret`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `servers` (`id`, `secret`, `description`, `hardware`, `os`, `uptime`, `location`, `owner`, `available`, `last_update`, `workload`, `ip_address`) VALUES
('SP-KHEIKEN-01', 'd769926f814ba466736875ed4ff4da2b4c53b3e7', 'Linux', 'Intel Core(TM)2 Duo CPU     P8400  @ 2.26GHz, 2 Cores', 'Linux', '419855.42', 'Hochschule Emden, Technikum, Raum E10', '215cd70f310ae6ae', 1, '2011-09-29 14:45:29', 0.0680576254346746, '127.0.0.1'),
('SP-KHEIKEN-02', 'aeeb9921fb2c6abc3c0ebe240d7ae727b6959327', 'Virtuelle Windows-XP Maschine auf dem Laptop.', 'Intel Core(TM)2 Duo CPU     P8400  @ 2.26GHz, 1 Core', 'Windows XP', '33304.0', 'Virtuelle Maschine, Sony Vaio Laptop', '215cd70f310ae6ae', 0, '2011-09-28 19:20:29', 0.0401376146788991, '127.0.0.2');

CREATE TABLE IF NOT EXISTS `sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) DEFAULT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` varchar(16) DEFAULT NULL,
  `user_data` text,
  PRIMARY KEY (`session_id`),
  KEY `last_activity` (`last_activity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` varchar(8) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`id`, `name`, `value`) VALUES
('ef7858ab', 'offline', '0'),
('6fc4f295', 'offline_message', 'ScattPort is currently offline for maintenance.');

CREATE TABLE IF NOT EXISTS `shares` (
  `project_id` varchar(40) NOT NULL,
  `user_id` varchar(16) NOT NULL,
  `can_edit` tinyint(1) NOT NULL DEFAULT '0',
  `seen` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`project_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `users` (
  `id` varchar(16) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(40) NOT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `institution` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `last_login` int(10) unsigned NOT NULL DEFAULT '0',
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `group_id` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `username`, `password`, `salt`, `remember_code`, `forgotten_password_code`, `firstname`, `lastname`, `institution`, `phone`, `email`, `last_login`, `last_activity`, `group_id`) VALUES
('c092f1529716b4b5', 'demouser', 'e66297e0d522e00f261184c7b3d5bdb7472d155d', '97a7d092f69397607fb1cd974b2607d72b7e141f', '092cafd4413e0a15afa9ba02c973046fcab9bc1b', NULL, 'Demo', 'User', '', '', 'demo@localhost.de', 1317298881, 1317303227, '356a192b7913b04c');

CREATE TABLE IF NOT EXISTS `users_settings` (
  `user_id` varchar(40) NOT NULL,
  `projects_sort_recently` tinyint(1) NOT NULL DEFAULT '1',
  `jobs_check_interval` mediumint(8) unsigned NOT NULL DEFAULT '5',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `users_settings` (`user_id`, `projects_sort_recently`, `jobs_check_interval`) VALUES
('c092f1529716b4b5', 0, 5);
