<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Theme
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Joomla Pull Request Tester Theme
 *
 * @package     Joomla.PullTester
 * @subpackage  Theme
 * @since       1.0
 */
class PTTheme extends JViewHtml
{
	/**
	 * The view layout.
	 *
	 * @var    string
	 * @since  12.1
	 */
	protected $layout = 'theme';

	/**
	 * Method to get the layout path.
	 *
	 * @param   string  $layout  The layout name.
	 *
	 * @return  mixed  The layout file name if found, false otherwise.
	 *
	 * @since   12.1
	 */
	public function getPath($layout)
	{
		// Get the layout file name.
		$file = JPath::clean($layout . '.php');

			// Start looping through the path set
		foreach ($this->paths as $path)
		{
			// Get the path to the file
			$fullname = $path . '/' . $file;

			// Get the file path.
			if (file_exists($fullname) && substr($fullname, 0, strlen($path)) == $path)
			{
				return $fullname;
			}
		}
	}

	/**
	 * Method to render the view.
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   12.1
	 * @throws  RuntimeException
	 */
	public function render()
	{
		// Get the layout path.
		$path = $this->getPath($this->getLayout());

		// Check if the layout path was found.
		if (!$path)
		{
			throw new RuntimeException('Layout Path Not Found');
		}

		// Start an output buffer.
		ob_start();

		// Load the layout.
		include $path;

		// Get the layout contents.
		$output = ob_get_clean();

		return $output;
	}

	/**
	 * Method to load the paths queue.
	 *
	 * @return  SplPriorityQueue  The paths queue.
	 *
	 * @since   12.1
	 */
	protected function loadPaths()
	{
		$paths = new SplPriorityQueue;
		$paths->insert(JPATH_SITE . '/html', 100);

		return $paths;
	}
}
