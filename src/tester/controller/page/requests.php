<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Controller to render the pull requests list page.
 *
 * @package     Joomla.PullTester
 * @subpackage  Controller
 * @since       1.0
 */
class PTControllerPageRequests extends JControllerBase
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
		$model = new PTModelRequests;
		$view = new PTTheme($model);
		$this->app->setBody($view->render());
	}
}
