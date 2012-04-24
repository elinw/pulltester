-- Joomla Pull Request Tester Schema
-- ---------------------------------

-- Base pull request table for tracking the open pull requests.

CREATE TABLE `pt_pull_requests` (
  `pull_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `github_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `is_mergeable` tinyint(4) NOT NULL DEFAULT '0',
  `user` varchar(255) NOT NULL DEFAULT '',
  `avatar_url` varchar(255) NOT NULL DEFAULT '',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `closed_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `merged_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`pull_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pt_pull_request_tests` (
  `test_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pull_id` int(10) unsigned NOT NULL DEFAULT '0',
  `revision` int(10) unsigned NOT NULL DEFAULT '0',
  `tested_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `head_revision` varchar(255) NOT NULL DEFAULT '',
  `base_revision` varchar(255) NOT NULL DEFAULT '',
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`test_id`),
  CONSTRAINT `pt_pull_request_tests_pull_request` FOREIGN KEY (`pull_id`) REFERENCES `pt_pull_requests` (`pull_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pt_pull_request_test_checkstyle_reports` (
  `pull_id` int(10) unsigned NOT NULL DEFAULT '0',
  `test_id` int(10) unsigned NOT NULL DEFAULT '0',
  `error_count` int(10) unsigned NOT NULL DEFAULT '0',
  `warning_count` int(10) unsigned NOT NULL DEFAULT '0',
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`pull_id`, `test_id`),
  CONSTRAINT `pt_pull_request_test_checkstyle_reports_pull_request` FOREIGN KEY (`pull_id`) REFERENCES `pt_pull_requests` (`pull_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pt_pull_request_test_checkstyle_reports_test` FOREIGN KEY (`test_id`) REFERENCES `pt_pull_request_tests` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `pt_pull_request_test_unit_test_reports` (
  `pull_id` int(10) unsigned NOT NULL DEFAULT '0',
  `test_id` int(10) unsigned NOT NULL DEFAULT '0',
  `test_count` int(10) unsigned NOT NULL DEFAULT '0',
  `assertion_count` int(10) unsigned NOT NULL DEFAULT '0',
  `failure_count` int(10) unsigned NOT NULL DEFAULT '0',
  `error_count` int(10) unsigned NOT NULL DEFAULT '0',
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`pull_id`, `test_id`),
  CONSTRAINT `pt_pull_request_test_unit_test_reports_pull_request` FOREIGN KEY (`pull_id`) REFERENCES `pt_pull_requests` (`pull_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pt_pull_request_test_unit_test_reports_test` FOREIGN KEY (`test_id`) REFERENCES `pt_pull_request_tests` (`test_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
