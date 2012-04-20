<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Joomla Pull Request Tester Staging Refresh Controller
 *
 * @package     Joomla.PullTester
 * @subpackage  Controller
 * @since       1.0
 */
class PTControllerRefreshStaging extends JControllerBase
{
	public function execute()
	{
		// Get some values from the application configuration.
		$base = $this->app->get('repo_path');
		$user = $this->app->get('github.user');
		$repo = $this->app->get('github.repo');
		$host = $this->app->get('github.host');

		// Instantiate the staging repository.
		$r = new PTGitRepository($base . '/' . $user);
		
		// If the respository doesn't exist create it.
		if (!$r->exists())
		{
			$r->create($host . '/' . $user . '/' . $repo . '.git');
		}
		// Otherwise update from the origin staging branch.
		else
		{
			$r->fetch()->merge('origin/staging');
		}
		
		// Clean things up.
		$r->clean();
	}
}
