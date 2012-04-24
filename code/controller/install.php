<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Joomla Pull Request Tester Install Controller
 *
 * @package     Joomla.PullTester
 * @subpackage  Controller
 * @since       1.0
 */
class PTControllerInstall extends JControllerBase
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
		// Get the application database object.
		$db = JFactory::getDBO();

		// Get the installation database schema split into individual queries.
		switch ($db->name)
		{
			case 'sqlite':
				$queries = JDatabaseDriver::splitSql(file_get_contents(dirname(JPATH_BASE) . '/database/schema/sqlite/pulltester.sql'));
				break;

			case 'mysql':
			case 'mysqli':
				$queries = JDatabaseDriver::splitSql(file_get_contents(dirname(JPATH_BASE) . '/database/schema/mysql/pulltester.sql'));
				break;

			default:
				throw new RuntimeException(sprintf('Database engine %s is not supported.', $db->name));
				break;
		}

		// Execute the installation schema queries.
		foreach ($queries as $query)
		{
			$db->setQuery($query)->execute();
		}
	}
}
