<?php
/**
 * Distribution bootstrap file for the Joomla Pull Request Tester application.
 *
 * @package    Joomla.PullTester
 *
 * @copyright  Copyright (C) 2011 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// Bootstrap the application.
$path = getenv('PT_HOME');
if ($path)
{
	require_once $path . '/bootstrap.php';
}
else
{
	require_once dirname(__DIR__) . '/code/bootstrap.php';
}

try
{
	// Instantiate the application.
	$application = JApplicationWeb::getInstance('PTApplicationWeb');

	// Store the application.
	JFactory::$application = $application;

	// Execute the application.
	$application->loadSession()
		->loadDatabase()
		->loadIdentity()
		->execute();
}
catch (Exception $e)
{
	// Set the server response code.
	header('Status: 500', true, 500);

	// An exception has been caught, echo the message and exit.
	echo json_encode(array('message' => $e->getMessage(), 'code' => $e->getCode()));
	exit;
}
