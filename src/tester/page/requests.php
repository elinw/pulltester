<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Page
 *
 * @copyright   Copyright (C) 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Requests Page Controller Class
 *
 * @package     Joomla.PullTester
 * @subpackage  Page
 * @since       1.0
 */
class PTPageRequests extends PTPage
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
		$state = $this->getModelState();
		$state->set('list.filter.state', $this->input->get('list_state', '0'));
		$state->set('list.filter.mergeable', $this->input->get('list_mergeable'));
		$state->set('list.filter.user', $this->input->get('list_user'));

		$state->set('list.sort.mode', $this->input->get('list_sort'));
		$state->set('list.sort.direction', $this->input->get('list_sort_direction'));
		$state->set('list.tests', 1);

		// Instantiate a requests model.
		$model = new PTRepository($state);

		// Instantiate a view.
		$view = new PTView($model, $this->getViewPaths());

		// Render the header and set it to the theme buffer.
		$view->setLayout('requests.header');
		$this->theme->buffer->set('page.header', $view->render());

		// Render the body and set it to the theme buffer.
		$view->setLayout('requests.body');
		$this->theme->buffer->set('page.body', $view->render());
	}
}
