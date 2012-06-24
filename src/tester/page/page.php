<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Page
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Page Controller Class
 *
 * @package     Joomla.PullTester
 * @subpackage  Page
 * @since       1.0
 */
abstract class PTPage extends JControllerBase
{
	/**
	 * @var     PTTheme  The application theme.
	 * @since   1.0
	 */
	protected $theme;

	/**
	 * Instantiate the controller.
	 *
	 * @param   JInput            $input  The input object.
	 * @param   JApplicationBase  $app    The application object.
	 * @param   PTTheme           $theme  The theme object.
	 *
	 * @since   1.0
	 */
	public function __construct(JInput $input = null, JApplicationBase $app = null, PTTheme $theme = null)
	{
		parent::__construct($input, $app);

		// Setup dependencies.
		$this->theme = isset($theme) ? $theme : $this->loadTheme();
	}

	/**
	 * Build the page and set buffers in the theme object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	abstract protected function build();

	/**
	 * Execute the controller.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 * @throws  LogicException
	 * @throws  RuntimeException
	 */
	public function execute()
	{
		// Build the page.
		$this->build();

		// Render the theme.
		$themePath = $this->app->get('theme.path', JPATH_SITE . '/theme');
		$buffer = $this->app->getTheme()->render($themePath . '/theme.phtml');

		// Set the rendered theme to the response body.
		$this->app->setBody($buffer);

		return true;
	}

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

	/**
	 * Load the application theme.
	 *
	 * @return  PTTheme  The application theme.
	 *
	 * @since   1.0
	 */
	protected function loadTheme()
	{
		return $this->app->getTheme();
	}
}
