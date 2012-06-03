<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Controller to display help for the application.
 *
 * @package     Joomla.PullTester
 * @subpackage  Controller
 * @since       1.0
 */
class PTControllerCommandHelp extends JControllerBase
{
	/**
	 * Method to execute the controller.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function execute()
	{
		$this->app->out('Joomla Pull Request Tester 1.0 by Open Source Matters, Inc.');
		$this->app->out();
		$this->app->out('Usage:    pull-tester <command> [options]');
		$this->app->out();
		$this->app->out('  -h | --help   Prints this usage information.');
		$this->app->out();
		$this->app->out('Examples: pull-tester install');
		$this->app->out('          pull-tester update');
		$this->app->out();
	}
}
