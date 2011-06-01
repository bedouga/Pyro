<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Polls extends Module {

	public $version = '0.5';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Polls'
			),
			'description' => array(
				'en' => 'Create totally awesome polls.'
			),
			'frontend' => TRUE,
			'backend' => TRUE,
			'menu' => FALSE
		);
	}

	public function install()
	{
		// Create polls table
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `polls` (
			`id` tinyint(11) unsigned NOT NULL AUTO_INCREMENT,
			`slug` varchar(64) NOT NULL,
			`title` varchar(64) NOT NULL,
			`type` enum('single','multiple') NOT NULL DEFAULT 'single',
			`description` text,
			`open_date` int(16) unsigned DEFAULT NULL,
			`close_date` int(16) unsigned DEFAULT NULL,
			`created` int(16) unsigned NOT NULL,
			`last_updated` int(16) unsigned DEFAULT NULL,
			`comments_enabled` tinyint(1) NOT NULL DEFAULT '0',
			`members_only` tinyint(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		// Create poll_options table
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `poll_options` (
			`id` smallint(11) unsigned NOT NULL AUTO_INCREMENT,
			`poll_id` tinyint(11) unsigned NOT NULL,
			`type` enum('defined','other') NOT NULL DEFAULT 'defined',
			`title` varchar(64) NOT NULL,
			`order` tinyint(2) unsigned DEFAULT NULL,
			`votes` mediumint(11) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`id`),
			KEY `poll_id` (`poll_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		// Create poll_other_votes table
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `poll_other_votes` (
			`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
			`parent_id` smallint(11) unsigned NOT NULL,
			`text` tinytext NOT NULL,
			`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`),
			KEY `parent_id` (`parent_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		// Create poll_voters table
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `poll_voters` (
			`id` mediumint(32) unsigned NOT NULL AUTO_INCREMENT,
			`poll_id` tinyint(11) unsigned NOT NULL,
			`user_id` smallint(5) unsigned DEFAULT NULL,
			`session_id` varchar(40) NOT NULL,
			`ip_address` varchar(16) NOT NULL,
			`timestamp` int(11) unsigned NOT NULL,
			PRIMARY KEY (`id`),
			KEY `poll_id` (`poll_id`),
			KEY `user_id` (`user_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
		");
		
		// Referental integrity fo' sho
		$this->db->query("
			ALTER TABLE `poll_options`
			ADD CONSTRAINT `poll_options_ibfk_1`
			FOREIGN KEY (`poll_id`)
			REFERENCES `polls` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE;
		");
		
		$this->db->query("
			ALTER TABLE `poll_other_votes`
			ADD CONSTRAINT `poll_other_votes_ibfk_1`
			FOREIGN KEY (`parent_id`)
			REFERENCES `poll_options` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE;
		");
		
		$this->db->query("
			ALTER TABLE `poll_voters`
			ADD CONSTRAINT `poll_votes_ibfk_1`
			FOREIGN KEY (`poll_id`)
			REFERENCES `polls` (`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE;
		");
		
		// It worked!
		return TRUE;
	}

	public function uninstall()
	{
		// Drop some tables
		$this->db->query("DROP TABLE `poll_voters`, `poll_other_votes`, `poll_options`, `polls`");
		return TRUE;
	}

	public function upgrade($old_version)
	{
		// Version 0.4 (the first official release)
		if ($old_version == '0.4')
		{
			// Update polls table
			$this->db->query("
				ALTER TABLE  `polls` ADD  `type` enum('single','multiple') NOT NULL DEFAULT 'single'
			");
			
			// Update poll_options table
			$this->db->query("
				ALTER TABLE  `poll_options` ADD  `type` enum('defined','other') NOT NULL DEFAULT 'defined'
			");
			
			// Add poll_other_votes table
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `poll_other_votes` (
				`id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
				`parent_id` smallint(11) unsigned NOT NULL,
				`text` tinytext NOT NULL,
				`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				KEY `parent_id` (`parent_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
			");
		
			// Add poll_voters table
			$this->db->query("
				CREATE TABLE IF NOT EXISTS `poll_voters` (
				`id` mediumint(32) unsigned NOT NULL AUTO_INCREMENT,
				`poll_id` tinyint(11) unsigned NOT NULL,
				`user_id` smallint(5) unsigned DEFAULT NULL,
				`session_id` varchar(40) NOT NULL,
				`ip_address` varchar(16) NOT NULL,
				`timestamp` int(11) unsigned NOT NULL,
				PRIMARY KEY (`id`),
				KEY `poll_id` (`poll_id`),
				KEY `user_id` (`user_id`)
				) ENGINE=InnoDB  DEFAULT CHARSET=latin1;
			");
			
			// Referental integrity fo' sho
			$this->db->query("
				ALTER TABLE `poll_other_votes`
				ADD CONSTRAINT `poll_other_votes_ibfk_1`
				FOREIGN KEY (`parent_id`)
				REFERENCES `poll_options` (`id`)
				ON DELETE CASCADE
				ON UPDATE CASCADE;
			");
			
			$this->db->query("
				ALTER TABLE `poll_voters`
				ADD CONSTRAINT `poll_votes_ibfk_1`
				FOREIGN KEY (`poll_id`)
				REFERENCES `polls` (`id`)
				ON DELETE CASCADE
				ON UPDATE CASCADE;
			");
		}
		return TRUE;
	}

	public function help()
	{
		return '<a href="https://github.com/vmichnowicz/polls">View Source on Github</a>';
	}
}
/* End of file details.php */