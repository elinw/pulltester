<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Joomla Pull Request Tester CLI Application Class
 *
 * @package     Joomla.PullTester
 * @subpackage  Application
 * @since       1.0
 */
class PTApplicationCli extends JApplicationCli
{
	/**
	 * A database object for the application to use.
	 *
	 * @var    JDatabaseDriver
	 * @since  1.0
	 */
	protected $db;

	/**
	 * Execute the application.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function doExecute()
	{
		// Check if help is needed.
		if ($this->input->get('h') || $this->input->get('help') || empty($this->input->args[0]))
		{
			$this->help();

			return;
		}

		// Load the database object if necessary.
		if (empty($this->db))
		{
			$this->loadDatabase();
		}

		// Get the controller instance based on the request.
		$controller = $this->fetchController();

		// Execute the controller.
		$controller->execute();
	}

	/**
	 * Method to get a controller object based on the command line input.
	 *
	 * @return  JController
	 *
	 * @since   1.0
	 * @throws  InvalidArgumentException
	 */
	protected function fetchController()
	{
		// Build the base namespace for the controller class based on API version and type.
		$base = 'PTController';

		jimport('joomla.environment.uri');

		// Get a URI object for the current route.
		$uri = JURI::getInstance($this->input->args[0]);

		// Convert the base path into an array of segments to build the controller.
		$parts = explode('/', trim($uri->getPath(), ' /'));

		// Iterate backwards over the route segments so we get the most specific class to handle the request.
		for ($i = count($parts); $i > 0; $i--)
		{
			// Build the controller class name from the path information.
			$class = $base . JStringNormalise::toCamelCase(implode(' ', array_slice($parts, 0, $i)));

			// If the requested controller exists let's use it.
			if (class_exists($class))
			{
				$input = new JInput;

				foreach ($uri->getQuery(true) as $name => $value)
				{
					$input->set($name, $value);
				}

				return new $class($input, $this);
			}
		}

		// Nothing found. Panic.
		throw new InvalidArgumentException('Unable to handle the request for route: ' . $this->input->args[0], 400);
	}

	/**
	 * Method to get the application configuration data to be loaded.
	 *
	 * @return  object  An object to be loaded into the application configuration.
	 *
	 * @since   1.0
	 */
	protected function fetchConfigurationData()
	{
		// Instantiate variables.
		$config = array();

		// Ensure that required path constants are defined.
		if (!defined('JPATH_CONFIGURATION'))
		{
			$path = getenv('PULLTESTER_CONFIG');
			if ($path)
			{
				define('JPATH_CONFIGURATION', realpath($path));
			}
			else
			{
				define('JPATH_CONFIGURATION', realpath(dirname(JPATH_BASE) . '/config'));
			}
		}

		// Set the configuration file path for the application.
		if (file_exists(JPATH_CONFIGURATION . '/config.json'))
		{
			$file = JPATH_CONFIGURATION . '/config.json';
		}
		else
		{
			$file = JPATH_CONFIGURATION . '/config.dist.json';
		}

		if (!is_readable($file))
		{
			throw new RuntimeException('Configuration file does not exist or is unreadable.');
		}

		// Load the configuration file into an object.
		$config = json_decode(file_get_contents($file));

		return $config;
	}

	/**
	 * Display the help text.
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	protected function help()
	{
		$this->out('Joomla Pull Request Tester 1.0 by Open Source Matters, Inc.');
		$this->out();
		$this->out('Usage:    pull-tester [switches] <path>');
		$this->out();
		$this->out('  -h | --help    Prints this usage information.');
		$this->out();
		$this->out('Examples: pull-tester test/pull?request_id=108');
		$this->out('          pull-tester refresh/staging');
		$this->out();
	}

	/**
	 * Method to create a database driver for the Web application.
	 *
	 * @return void
	 *
	 * @since 1.0
	 */
	protected function loadDatabase()
	{
		$this->db = JDatabaseDriver::getInstance(
			array(
				'driver' => $this->get('db_driver'),
				'host' => $this->get('db_host'),
				'user' => $this->get('db_user'),
				'password' => $this->get('db_pass'),
				'database' => $this->get('db_name'),
				'prefix' => $this->get('db_prefix')
			)
		);

		// Select the database.
		$this->db->select($this->get('db_name'));

		// Set the debug flag.
		$this->db->setDebug($this->get('debug'));

		// Set the database to our static cache.
		JFactory::$database = $this->db;
	}
}
