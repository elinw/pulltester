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
class PTModelRequests extends JModelDatabase
{
	/**
	 * Get a list of Pull Requests based on the model state.
	 *
	 * @return  array
	 *
	 * @since   1.0
	 */
	public function getRequests()
	{
		// Initialize variables.
		$pullRequests = array();

		// Build the query to get the pull requests.
		$query = $this->db->getQuery(true);
		$query->select('r.pull_id, r.github_id, r.data');
		$query->from('#__pull_requests AS r');
		$query->leftJoin('#__pull_request_tests AS t ON r.pull_id = t.pull_id');

		// Set the filtering for the query.
		$query = $this->_setFiltering($query);

		// Set the sorting clause.
		$query = $this->_setSorting($query);

		try
		{
			$this->db->setQuery($query, $this->state->get('list.start', 0), $this->state->get('list.limit', 50));
			$pullRequests = $this->db->loadObjectList();

			// Callback function to decode the expanded pull request data.
			$decodeCallback = function($request)
			{
				$request->data = json_decode($request->data);
				return $request;
			};

			// Decode the serialized pull request data for the entire array.
			$pullRequests = array_map($decodeCallback, $pullRequests);
		}
		catch (RuntimeException $e)
		{
			JLog::add('Error: ' . $e->getMessage(), JLog::DEBUG);
		}

		return $pullRequests;
	}

	/**
	 * Set the filtering for the query.
	 *
	 * @param   JDatabaseQuery  $query  The query on which to set the filtering.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0
	 */
	private function _setFiltering(JDatabaseQuery $query)
	{
		// Set the state filter.
		$state = $this->state->get('list.filter.state');
		if ($state !== null)
		{
			$query->where('r.state = ' . $state ? 1 : 0);
		}

		// Set the mergeable filter.
		$mergeable = $this->state->get('list.filter.mergeable');
		if ($mergeable !== null)
		{
			$query->where('r.is_mergeable = ' . $mergeable ? 1 : 0);
		}

		// Set the user filter.
		$user = $this->state->get('list.filter.user');
		if ($user !== null)
		{
			$query->where('r.user = ' . $query->q($user));
		}

		// Set the pending tests filter if required.
		if ($this->state->get('list.filter.pending_tests', 0))
		{
			$query->where('((t.tested_time IS NULL) OR (r.updated_time < t.tested_time))');
		}

		return $query;
	}

	/**
	 * Set the sorting clause for the query.
	 *
	 * @param   JDatabaseQuery  $query  The query on which to set the sorting clause.
	 *
	 * @return  JDatabaseQuery
	 *
	 * @since   1.0
	 */
	private function _setSorting(JDatabaseQuery $query)
	{
		// Get the sorting direction.
		$direction = $this->state->get('list.sort.direction', 'down');
		$direction = ($direction == 'down') ? 'DESC' : 'ASC';

		switch ($this->state->get('list.sort.mode'))
		{
			case 'author':
				$query->order('r.user ' . $direction);
				break;
			case 'closed':
				$query->order('r.closed_time ' . $direction);
				break;
			case 'created':
				$query->order('r.created_time ' . $direction);
				break;
			case 'mergeability':
				$query->order('r.is_mergeable ' . $direction);
				break;
			default:
			case 'updated':
				$query->order('r.updated_time ' . $direction);
				break;
		}

		return $query;
	}
}
