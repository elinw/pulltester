<?php
/**
 * @package    Joomla.PullTester
 *
 * @copyright  Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/*
 * Ensure that required path constants are defined.  These can be overriden within the phpunit.xml file
 * if you chose to create a custom version of that file.
 */
if (!defined('JPATH_TESTS'))
{
	define('JPATH_TESTS', realpath(__DIR__));
}

// Import the Joomla Platform and testing classes.
require_once __DIR__ . '/joomla-test.phar';

// Import the application loader.
require_once __DIR__ . '/../src/import.php';
