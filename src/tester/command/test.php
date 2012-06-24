<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Controller to test a pull request or all untested mergeable pull requests.
 *
 * @package     Joomla.PullTester
 * @subpackage  Controller
 * @since       1.0
 */
class PTCommandTest extends JControllerBase
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
		$state = new JRegistry;
		$state->set('list.filter.pending_tests', 1);
		$state->set('list.filter.mergeable', 1);
		$state->set('list.filter.state', 0);

		// Build the repository path.
		$repoPath = $this->app->get('repo_path', sys_get_temp_dir());
		$state->set('repo', $repoPath . '/' . $this->app->get('github.user') . '/' . $this->app->get('github.repo'));

		// Add the GitHub configuration values.
		$state->set('github.api', $this->app->get('github.api'));
		$state->set('github.host', $this->app->get('github.host'));
		$state->set('github.user', $this->app->get('github.user'));
		$state->set('github.repo', $this->app->get('github.repo'));

		$repository = new PTRepository($state);

		// If the `full` flag is set, then also test the master branch first.
		if ($this->app->input->getBool('f', false))
		{
			$repository->testMaster();
		}

		// Get the pull requests to test.
		$pullRequests = array_slice($this->app->input->args, 1);
		if ($this->app->input->getBool('a', false) || empty($pullRequests))
		{
			$pullRequests = $repository->getRequests();
		}
		else
		{
			foreach ($pullRequests as $k => $v)
			{
				$pullRequests[$k] = $repository->getRequest((int) $v);
			}
		}

		foreach ($pullRequests as $request)
		{
			$repository->testRequest($request);
		}
	}
}
