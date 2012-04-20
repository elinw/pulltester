<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Joomla Pull Tester Checkstyle table class.
 *
 * @package     Joomla.PullTester
 * @subpackage  Table
 * @since       1.0
 */
class PTTableCheckstyle extends JTable
{
	/**
	 * Constructor
	 *
	 * @param  database  $db  A database connector object
	 *
	 * @return  JTableCategory
	 *
	 * @since   11.1
	 */
	public function __construct(&$db)
	{
		parent::__construct('phpCsResults', 'id', $db);
	}

	/**
	 * Store only new records. Find previous.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @see JTable::store()
	 */
	public function store($updateNulls = false)
	{
		$query = $this->_db->getQuery(true);

		$query->from($this->_tbl);
		$query->select('id');
		$query->where('pulls_id='.$this->pulls_id);

		$this->_db->setQuery($query);

		$id = $this->_db->loadResult();

		if($id)
		{
			$this->id = $id;
		}

		return parent::store($updateNulls);
	}

	/**
	 * Truncate the table.
	 *
	 * @return void
	 */
	public function truncate()
	{
		$this->_db->setQuery('TRUNCATE TABLE '.$this->_tbl);

		$this->_db->query();

		return;
	}
}
