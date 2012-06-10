<?php
/**
 * @package    Joomla.PullTester
 *
 * @copyright  Copyright (C) 2011 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Setup the Pharsanity!
Phar::interceptFileFuncs();

// Make sure that the Joomla Platform has been successfully loaded.
if (!class_exists('JLoader'))
{
	throw new RuntimeException('Joomla Platform not loaded.');
}

// Register the application classes with the loader.
JLoader::registerPrefix('PT', 'phar://' . __FILE__ . '/tester');

__HALT_COMPILER();?>
