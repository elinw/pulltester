<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Object
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * JUnit report object for the Joomla Pull Request Tester Application.
 *
 * @package     Joomla.PullTester
 * @subpackage  Object
 * @since       1.0
 */
class PTObjectJunit extends JObject
{
	public $pullId;
	public $testId;
	public $testCount;
	public $assertionCount;
	public $failureCount;
	public $errorCount;
	public $data = array('errors' => array(), 'failures' => array());
}
