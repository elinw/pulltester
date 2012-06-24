<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Widget
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Widget Controller Class
 *
 * @package     Joomla.PullTester
 * @subpackage  Widget
 * @since       1.0
 */
abstract class PTWidget extends JControllerBase
{
	/**
	 * Execute the controller.
	 *
	 * @return  string  The widget output.
	 *
	 * @since   1.0
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	abstract public function execute();

	/**
	 * Get the model state.
	 *
	 * @return  JRegistry  The model state.
	 *
	 * @since   1.0
	 */
	protected function getModelState()
	{
		// Instantiate the state with any general input.
		$state = new JRegistry;

		return $state;
	}

	/**
	 * Get the view paths queue.
	 *
	 * @return  SplPriorityQueue  The paths queue.
	 *
	 * @since   1.0
	 */
	protected function getViewPaths()
	{
		// Get the theme path.
		$themePath = $this->app->get('theme.path', JPATH_SITE . '/theme');

		// Setup the page paths.
		$paths = new SplPriorityQueue;
		$paths->insert($themePath . '/tmpl', 1);

		return $paths;
	}
}
