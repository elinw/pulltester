<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Page
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Request Page Controller Class
 *
 * @package     Joomla.PullTester
 * @subpackage  Page
 * @since       1.0
 */
class PTPageRequest extends PTPage
{
	/**
	 * Build the page and set buffers in the theme object.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function build()
	{
		$this->theme->buffer->set(
			'page.body',
			sprintf('The Request %d Page.', $this->input->getInt('request_id'))
		);
	}
}
