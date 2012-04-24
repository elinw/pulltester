<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Object
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Checkstyle report object for the Joomla Pull Request Tester Application.
 *
 * @package     Joomla.PullTester
 * @subpackage  Object
 * @since       1.0
 */
class PTObjectCheckstyle extends JObject
{
	public $pullId;
	public $testId;
	public $errorCount;
	public $warningCount;
	public $data = array('errors' => array(), 'warnings' => array());
}
