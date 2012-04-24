<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Joomla Pull Request Tester Pull Request Model
 *
 * @package     Joomla.PullTester
 * @subpackage  Model
 * @since       1.0
 */
class PTModelRepository extends JModelDatabase
{
	/**
	 * @var    array  The list of current pull requests in the database.
	 * @since  1.0
	 */
	protected $currentPullRequests;

	/**
	 * @var    JGithub  The GitHub API connector.
	 * @since  1.0
	 */
	protected $github;

	/**
	 * @var    JDatabaseQuery  The query to insert a pull request into the database.
	 * @since  1.0
	 */
	protected $queryInsert;

	/**
	 * @var    JDatabaseQuery  The query to update a pull request in the database.
	 * @since  1.0
	 */
	protected $queryUpdate;

	/**
	 * Instantiate the model.
	 *
	 * @param   JRegistry        $state  The model state.
	 * @param   JDatabaseDriver  $db     The database adpater.
	 *
	 * @since   12.1
	 */
	public function __construct(JRegistry $state = null, JDatabaseDriver $db = null)
	{
		// Execute the parent constructor.
		parent::__construct($state, $db);

		// Setup the GitHub API connector.
		$this->github = new JGithub;
		$this->github->setOption('api.url', $this->state->get('github.api'));
	}

	/**
	 * Method to synchronize the local testing repository with the github repository.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function syncCodeWithGithub()
	{
		// Instantiate the repository object.
		$r = new PTGitRepository($this->state->get('repo'));

		// If the respository doesn't exist create it.
		if (!$r->exists())
		{
			$r->create($this->state->get('github.host') . '/' . $this->state->get('github.user') . '/' . $this->state->get('github.repo') . '.git');
		}
		// Otherwise update from the origin staging branch.
		else
		{
			$r->fetch('origin')->branchCheckout('master')->merge('origin/staging');
		}

		// Clean things up.
		$r->clean();
	}

	/**
	 * Method to synchronize the local pull request metadata with the github repository.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function syncMetadataWithGithub()
	{
		// Synchronize closed pull requests first.
		$page  = 1;
		$pulls = $this->github->pulls->getList($this->state->get('github.user'), $this->state->get('github.repo'), 'closed', $page, 100);

		// Paginate over closed pull requests until there aren't any more.
		while (!empty($pulls))
		{
			// Process the pull requests.
			$this->processPullRequests($pulls);

			// Get the next page of pull requests.
			$page++;
			$pulls = $this->github->pulls->getList($this->state->get('github.user'), $this->state->get('github.repo'), 'closed', $page, 100);
		}

		// Synchronize open pull requests first.
		$page  = 1;
		$pulls = $this->github->pulls->getList($this->state->get('github.user'), $this->state->get('github.repo'), 'open', $page, 100);

		// Paginate over open pull requests until there aren't any more.
		while (!empty($pulls))
		{
			// Process the pull requests.
			$this->processPullRequests($pulls);

			// Get the next page of pull requests.
			$page++;
			$pulls = $this->github->pulls->getList($this->state->get('github.user'), $this->state->get('github.repo'), 'open', $page, 100);
		}
	}

	protected function executePHPCS()
	{
		// Initialize variables.
		$out = array();
		$return = null;

		$command = array(
			'phpcs',
			'--report=checkstyle',
			'--report-file=' . $this->state->get('repo') . '/checkstyle.xml',
			'--standard=' . $this->state->get('phpcs.standard', 'build/phpcs/Joomla')
		);

		// Add the PHPCS paths to the command.
		$command = array_merge($command, (array) $this->state->get('phpcs.paths', array()));

		// Execute the command.
		$wd = getcwd();
		chdir($this->state->get('repo'));
		exec(implode(' ', $command), $out, $return);
		chdir($wd);

		return $this;
	}

	/**
	 * Method to get the list of current pull request github_id and updated_time values from the database.
	 *
	 * @param   boolean  $refresh  True to refresh the list from the database even it if has been loaded.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	protected function fetchCurrentPullRequests($refresh = false)
	{
		// Only fetch the data if it hasn't been fetched or we have a refresh flag.
		if (isset($this->currentPullRequests) || $refresh)
		{
			return $this->currentPullRequests;
		}

		// Build the query to retrieve the current pull requests and updated timestamps.
		$query = $this->db->getQuery(true);
		$query->select('github_id, updated_time');
		$query->from('#__pull_requests');

		// Set the query to the driver.
		$this->db->setQuery($query);

		$this->currentPullRequests = $this->db->loadAssocList('github_id');

		return $this->currentPullRequests;
	}

	/**
	 * Method to get a prepared statement query for inserting a pull request into the database.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0
	 */
	protected function fetchInsertQuery()
	{
		// Only create the query if it doesn't exist.
		if (isset($this->queryInsert))
		{
			return $this->queryInsert;
		}

		// Build the insert query.
		$this->queryInsert = $this->db->getQuery(true);
		$this->queryInsert->insert('#__pull_requests');
		$this->queryInsert->columns('github_id, title, state, is_mergeable, user, avatar_url, created_time, updated_time, closed_time, merged_time, data');
		$this->queryInsert->values(':githubId, :title, :state, :isMergeable, :user, :avatarUrl, :createdTime, :updatedTime, :closedTime, :mergedTime, :data');

		return $this->queryInsert;
	}

	/**
	 * Method to get a prepared statement query for updating a pull request in the database.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0
	 */
	protected function fetchUpdateQuery()
	{
		// Only create the query if it doesn't exist.
		if (isset($this->queryUpdate))
		{
			return $this->queryUpdate;
		}

		// Build the update query.
		$this->queryUpdate = $this->db->getQuery(true);
		$this->queryUpdate->update('#__pull_requests');
		$this->queryUpdate->columns('title, state, is_mergeable, updated_time, closed_time, merged_time, data');
		$this->queryUpdate->values(':title, :state, :isMergeable, :updatedTime, :closedTime, :mergedTime, :data');
		$this->queryUpdate->where('github_id = :githubId');

		return $this->queryUpdate;
	}

	protected function processPullRequests($pulls)
	{
		// Get the list of existing pull requests in our database.
		$current = $this->fetchCurrentPullRequests();

		// Iterate over the incoming pull requests and make sure they are all synchronized with our database.
		foreach ($pulls as $pull)
		{
			// First we need to get a date object for the last updated timestamp.
			$updated = JFactory::getDate($pull->updated_at);

			// If either the pull request doesn't exist in our list or the last updated timestamp is different we need to update our database.
			if (empty($current[$pull->number]) || JFactory::getDate($current[$pull->number]['updated_time']) != $updated)
			{
				// Get the full pull request object from GitHub.
				$pull = $this->github->pulls->get($this->state->get('github.user'), $this->state->get('github.repo'), $pull->number);

				// If the pull request doesn't exist in our current list then we need to insert it into the database.
				if (empty($current[$pull->number]))
				{
					// Get a query object for inserting the pull request into our database.
					$insert = $this->fetchInsertQuery();

					// Bind the values to our query.
					$insert->bind('githubId', $pull->number, PDO::PARAM_INT);
					$insert->bind('title', $pull->title);
					$state = ($pull->state == 'open' ? 0 : 1);
					$insert->bind('state', $state, PDO::PARAM_INT);
					$mergeable = ($pull->mergeable ? 1 : 0);
					$insert->bind('isMergeable', $mergeable, PDO::PARAM_INT);
					$insert->bind('user', $pull->user->login);
					$insert->bind('avatarUrl', $pull->user->avatar_url);
					$createdTime = JFactory::getDate($pull->created_at)->toISO8601();
					$insert->bind('createdTime', $createdTime);
					$updatedTime = JFactory::getDate($pull->updated_at)->toISO8601();
					$insert->bind('updatedTime', $updatedTime);
					$closedTime = ($pull->closed_at ? JFactory::getDate($pull->closed_at)->toISO8601() : '');
					$insert->bind('closedTime', $closedTime);
					$mergedTime = ($pull->merged_at ? JFactory::getDate($pull->merged_at)->toISO8601() : '');
					$insert->bind('mergedTime', $mergedTime);
					$data = json_encode($pull);
					$insert->bind('data', $data);

					// Set the query for execution by our driver.
					$this->db->setQuery($insert);
				}
				// Otherwise we simply need to update the data in our database for the pull request.
				else
				{
					// Get a query object for updating the pull request in our database.
					$update = $this->fetchUpdateQuery();

					// Bind the values to our query.
					$update->bind('githubId', $pull->number, PDO::PARAM_INT);
					$update->bind('title', $pull->title);
					$state = ($pull->state == 'open' ? 0 : 1);
					$update->bind('state', $state, PDO::PARAM_INT);
					$mergeable = ($pull->mergeable ? 1 : 0);
					$update->bind('isMergeable', $mergeable, PDO::PARAM_INT);
					$updatedTime = JFactory::getDate($pull->updated_at)->toISO8601();
					$update->bind('updatedTime', $updatedTime);
					$closedTime = ($pull->closed_at ? JFactory::getDate($pull->closed_at)->toISO8601() : '');
					$update->bind('closedTime', $closedTime);
					$mergedTime = ($pull->merged_at ? JFactory::getDate($pull->merged_at)->toISO8601() : '');
					$update->bind('mergedTime', $mergedTime);
					$data = json_encode($pull);
					$update->bind('data', $data);

					// Set the query for execution by our driver.
					$this->db->setQuery($update);
				}

				try
				{
					$this->db->execute();

				}
				catch (RuntimeException $e)
				{
					JLog::add('Unable to ' . (empty($current[$pull->number]) ? 'add': 'update') . ' pull request: ' . $pull->number, JLog::DEBUG);
					JLog::add('Error: ' . $e->getMessage(), JLog::DEBUG);
				}
			}
		}
	}
}