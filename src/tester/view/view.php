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
		// Defaults and assume if 0 is passed in that its an error rather than the epoch
		$from = $from->getTimestamp();
		if ($from == 0)
		{
			return 'Long ago...';
		}

		// Make sure we have a to timestamp.
		if (empty($to))
		{
			$to = new JDate;
		}
		$to = $to->getTimestamp();

		// Calculate the difference in seconds betweeen the two timestamps
		$difference = $to - $from;

		/*
		 * Based on the interval, determine the number of units between the two dates From this point on, you would be hard
		 * pushed telling the difference between this function and DateDiff. If the $datediff returned is 1, be sure to return
		 * the singular of the unit, e.g. 'day' rather 'days'
		 */
		switch (true)
		{
			// If difference is less than 60 seconds, seconds is a good interval of choice
			case (strtotime('-1 min', $to) < $from):
				$datediff = $difference;
				$res = ($datediff == 1) ? $datediff . ' second ago' : $datediff . ' seconds ago';
				break;

			// If difference is between 60 seconds and 60 minutes, minutes is a good interval
			case (strtotime('-1 hour', $to) < $from):
				$datediff = floor($difference / 60);
				$res = ($datediff == 1) ? $datediff . ' minute ago' : $datediff . ' minutes ago';
				break;

			// If difference is between 1 hour and 24 hours hours is a good interval
			case (strtotime('-1 day', $to) < $from):
				$datediff = floor($difference / 60 / 60);
				$res = ($datediff == 1) ? $datediff . ' hour ago' : $datediff . ' hours ago';
				break;

			// If difference is between 1 day and 7 days days is a good interval
			case (strtotime('-1 week', $to) < $from):
				$dayDifference = 1;
				while (strtotime('-' . $dayDifference . ' day', $to) >= $from)
				{
					$dayDifference++;
				}

				$datediff = $dayDifference;
				$res = ($datediff == 1) ? 'yesterday' : $datediff . ' days ago';
				break;

			// If difference is between 1 week and 30 days weeks is a good interval
			case (strtotime('-1 month', $to) < $from):
				$weekDifference = 1;
				while (strtotime('-' . $weekDifference . ' week', $to) >= $from)
				{
					$weekDifference++;
				}

				$datediff = $weekDifference;
				$res = ($datediff == 1) ? 'last week' : $datediff . ' weeks ago';
				break;

			/*
			 * If difference is between 30 days and 365 days months is a good interval, again, the same thing
			 * applies, if the 29th February happens to exist between your 2 dates, the function will return
			 * the 'incorrect' value for a day
			 */
			case (strtotime('-1 year', $to) < $from):
				$monthDifference = 1;
				while (strtotime('-' . $monthDifference . ' month', $to) >= $from)
				{
					$monthDifference++;
				}

				$datediff = $monthDifference;
				$res = ($datediff == 1) ? $datediff . ' month ago' : $datediff . ' months ago';

				break;

			/*
			 * If difference is greater than or equal to 365 days, return year. This will be incorrect if
			 * for example, you call the function on the 28th April 2008 passing in 29th April 2007. It will return
			 * 1 year ago when in actual fact (yawn!) not quite a year has gone by
			 */
			case (strtotime('-1 year', $to) >= $from):
				$yearDifference = 1;
				while (strtotime('-' . $yearDifference . ' year', $to) >= $from)
				{
					$yearDifference++;
				}

				$datediff = $yearDifference;
				$res = ($datediff == 1) ? $datediff . ' year ago' : $datediff . ' years ago';
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
