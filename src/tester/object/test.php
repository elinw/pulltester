<?php

/**
 * @package     Joomla.PullTester
 * @subpackage  Object
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Pull Request test object for the Joomla Pull Request Tester Application.
 *
 * @package     Joomla.PullTester
 * @subpackage  Object
 * @since       1.0
 */
class PTObjectTest extends JObject
{

	public $testId;

	public $pullId;

	public $revision;

	public $testedTime;

	public $headRevision;

	public $baseRevision;

	public $data;
}
