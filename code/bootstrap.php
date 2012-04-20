<?php
/**
 * Distribution bootstrap file for the Joomla Pull Request Tester application.
 *
 * @package    Joomla.PullTester
 *
 * @copyright  Copyright (C) 2011 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Set the Joomla execution flag.
define('_JEXEC', 1);

// Define the path for the Joomla Platform.
if (!defined('JPATH_PLATFORM'))
{
	$platform = getenv('JPLATFORM_HOME');
	if ($platform)
	{
		define('JPATH_PLATFORM', realpath($platform));
	}
	else
	{
		define('JPATH_PLATFORM', realpath(dirname(dirname(__DIR__)) . '/joomla-platform/libraries'));
	}
}

// Ensure that required path constants are defined.
if (!defined('JPATH_BASE'))
{
	define('JPATH_BASE', realpath(__DIR__));
}
if (!defined('JPATH_SITE'))
{
	define('JPATH_SITE', JPATH_BASE);
}
if (!defined('JPATH_CACHE'))
{
	define('JPATH_CACHE', '/tmp/joomla-pull-tester/cache');
}

// Import the platform.
require_once JPATH_PLATFORM . '/import.php';

// Make sure that the Joomla Platform has been successfully loaded.
if (!class_exists('JLoader'))
{
	exit('Joomla Platform not loaded.');
}

// Setup the autoloader for the Joomla Pull Request Tester application classes.
JLoader::registerPrefix('PT', __DIR__);
