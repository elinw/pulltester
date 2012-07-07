<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * View Class
 *
 * @package     Joomla.PullTester
 * @subpackage  View
 * @since       1.0
 */
class PTView extends JViewHtml
{
	/**
	 * Get a formatted date difference.
	 *
	 * @param   JDate  $from  The from date.
	 * @param   JDate  $to    The to date.
	 *
	 * @return  string
	 *
	 * @since   1.0
	 */
	public function ago(JDate $from, JDate $to = null)
	{
		// Make sure we have a to date.
		if (empty($to))
		{
			$to = new JDate;
		}

		// Calculate the difference in seconds betweeen the two date objects.
		$difference = $to->diff($from);

		/*
		 * Based on the interval, determine the number of units between the two dates From this point on, you would be hard
		 * pushed telling the difference between this function and DateDiff. If the $datediff returned is 1, be sure to return
		 * the singular of the unit, e.g. 'day' rather 'days'
		 */
		switch (true)
		{
			// If difference is greater than or equal to a year, return year.
			case ($difference->y >= 1):
				$res = ($difference->y == 1) ? $difference->y . ' year ago' : $difference->y . ' years ago';
				break;

			case ($difference->m >= 1):
				$res = ($difference->m == 1) ? $difference->m . ' month ago' : $difference->m . ' months ago';
				break;

			// If difference is between 1 week and 30 days weeks is a good interval
			case ($difference->d >= 7):
				$weeks = floor($difference->d / 7);
				$res = ($weeks == 1) ? 'last week' : $weeks . ' weeks ago';
				break;

			// If difference is between 1 day and 7 days days is a good interval
			case (0 < $difference->d && $difference->d < 7):
				$res = ($difference->d == 1) ? 'yesterday' : $difference->d . ' days ago';
				break;

			// If difference is between 1 hour and 24 hours hours is a good interval
			case (0 < $difference->h && $difference->h < 24):
				$res = ($difference->h == 1) ? $difference->h . ' hour ago' : $difference->h . ' hours ago';
				break;

			// If difference is between 60 seconds and 60 minutes, minutes is a good interval
			case (0 < $difference->i && $difference->i < 60):
				$res = ($difference->i == 1) ? $difference->i . ' minute ago' : $difference->i . ' minutes ago';
				break;

			// If difference is less than 60 seconds, seconds is a good interval of choice
			case ($difference->s < 60):
				$res = ($difference->s == 1) ? $difference->s . ' second ago' : $difference->s . ' seconds ago';
				break;
		}

		return $res;
	}

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
		$file = JPath::clean($layout . '.phtml');

		// Find the layout file path.
		$path = JPath::find(clone ($this->paths), $file);

		return $path;
	}

	/**
	 * Method to render the view.
	 *
	 * @param   string  $layout  The name of the layout to render.
	 *
	 * @return  string  The rendered view.
	 *
	 * @since   12.1
	 * @throws  RuntimeException
	 */
	public function render($layout = null)
	{
		// Get the layout path.
		$path = $this->getPath($layout ? $layout : $this->getLayout());

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
}
