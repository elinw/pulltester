<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Git
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Joomla Git Repository Class
 *
 * @package     Joomla.PullTester
 * @subpackage  Git
 * @since       1.0
 */
class PTGitRepository
{
	/**
	 * @var    string  The filesystem path for the repository root.
	 * @since  1.0
	 */
	private $_root;

	/**
	 * Object Constructor.
	 *
	 * @param   string  $root  The filesystem path for the repository root.
	 *
	 * @since   1.0
	 */
	public function __construct($root)
	{
		$this->_root = $root;
	}

	public function exists()
	{
		// If we don't have a configuration file for the repository it doesn't exist.
		return file_exists($this->_root . '/.git/config');
	}

	public function create($remote = 'git://github.com/joomla/joomla-platform.git')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// We add the users repo to our remote list if it isn't already there
		if (!file_exists($this->_root . '/.git'))
		{
			// Execute the command.
			exec('git clone -q ' . escapeshellarg($remote) . ' ' . escapeshellarg($this->_root), $out, $return);
		}
		else
		{
			throw new InvalidArgumentException('Repository already exists at ' . $this->_root . '.');
		}

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('The clone failed from remote %s with code %d and message %s.', $remote, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function fetch($remote = 'origin')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// Ensure that either the remote exists or is a valid URL.
		if (!filter_var($remote, FILTER_VALIDATE_URL) && !in_array($remote, $this->_getRemotes()))
		{
			throw new InvalidArgumentException('No valid remote ' . $remote . ' exists.');
		}

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git fetch -q ' . escapeshellarg($remote), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('The fetch failed from remote %s with code %d and message %s.', $remote, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function merge($branch = 'origin/master')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git merge ' . escapeshellarg($branch), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('Unable to merge branch %s with code %d and message %s.', $branch, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function remoteAdd($name = 'joomla', $url = 'git@github.com:joomla/joomla-platform.git')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// Ensure that the remote doesn't already exist.
		if (in_array($name, $this->_getRemotes()))
		{
			throw new InvalidArgumentException('The remote ' . $name . ' already exists.');
		}

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git remote add ' . escapeshellarg($name) . ' ' . escapeshellarg($url), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('The remote %s could not be added from %s with code %d and message %s.', $name, $url, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function remoteExists($name = 'joomla')
	{
		return in_array($name, $this->_getRemotes());
	}

	public function remoteSetUrl($name = 'joomla', $url = 'git@github.com:joomla/joomla-platform.git')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// Ensure that the remote already exists.
		if (!in_array($name, $this->_getRemotes()))
		{
			throw new InvalidArgumentException('The remote ' . $name . ' doesn\'t exist.  Try adding it.');
		}

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git remote set-url ' . escapeshellarg($name) . ' ' . escapeshellarg($url), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('Could not set the url %s for remote %s. Error code %d and message %s.', $url, $name, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function remoteRemove($name = 'joomla')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// If the remote doesn't already exist we have nothing to do.
		if (!in_array($name, $this->_getRemotes()))
		{
			return true;
		}

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git remote rm ' . escapeshellarg($name), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('The remote %s could not be removed with code %d and message %s.', $name, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function branchCheckout($name = 'staging')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// Ensure that the branch already exists.
		if (!in_array($name, $this->_getBranches()))
		{
			throw new InvalidArgumentException('The branch ' . $name . ' does not exist.');
		}

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git checkout ' . escapeshellarg($name), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('Branch %s could not be checked out with code %d and message %s.', $name, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function branchCreate($name = 'staging', $parent = 'master', $parentRemote = null)
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// Ensure that the branch doesn't already exist.
		if (in_array($name, $this->_getBranches()))
		{
			throw new InvalidArgumentException('The branch ' . $name . ' already exists.');
		}

		// If we have a parent remote then fetch latest updates and set up the parent.
		if (!empty($parentRemote))
		{
			$this->fetch($parentRemote);

			$parent = $parentRemote . '/' . $parent;
		}

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git checkout -b ' . escapeshellarg($name) . ' ' . escapeshellarg($parent), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('Branch %s could not be created with code %d and message %s.', $name, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function branchExists($name = 'joomla')
	{
		return in_array($name, $this->_getBranches());
	}

	public function branchRemove($name = 'staging')
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// If the branch doesn't already exist we have nothing to do.
		if (!in_array($name, $this->_getBranches()))
		{
			return true;
		}

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git branch -D ' . escapeshellarg($name), $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('Branch %s could not be removed with code %d and message %s.', $name, $return, implode("\n", $out)));
		}

		return $this;
	}

	public function clean()
	{
		// Initialize variables.
		$out = array();
		$return = null;

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git clean -fd', $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('Failure cleaning the repository with code %d and message %s.', $return, implode("\n", $out)));
		}

		return $this;
	}

	public function reset($hard = true)
	{
		// Initialize variables.
		$out = array();
		$return = null;

		$flag = $hard ? ' --hard' : '';

		// Execute the command.
		$wd = getcwd();
		chdir($this->_root);
		exec('git reset' . $flag, $out, $return);
		chdir($wd);

		// Validate the response.
		if ($return !== 0)
		{
			throw new RuntimeException(sprintf('Failure resetting the repository with code %d and message %s.', $return, implode("\n", $out)));
		}

		return $this;
	}

	private function _getBranches()
	{
		// If we don't have a configuration file for the repository PANIC!
		if (!file_exists($this->_root . '/.git/config'))
		{
			throw new RuntimeException('Not a valid Git repository at ' . $this->_root);
		}

		// Initialize variables.
		$branches = array();

		// Parse the repository configuration file.
		$config = parse_ini_file($this->_root . '/.git/config', true);

		// Go find the remotes from the configuration file.
		foreach ($config as $section => $data)
		{
			if (strpos($section, 'branch ') === 0)
			{
				$branches[] = trim(substr($section, 7));
			}
		}

		return $branches;
	}

	private function _getRemotes()
	{
		// If we don't have a configuration file for the repository PANIC!
		if (!file_exists($this->_root . '/.git/config'))
		{
			throw new RuntimeException('Not a valid Git repository at ' . $this->_root);
		}

		// Initialize variables.
		$remotes = array();

		// Parse the repository configuration file.
		$config = parse_ini_file($this->_root . '/.git/config', true);

		// Go find the remotes from the configuration file.
		foreach ($config as $section => $data)
		{
			if (strpos($section, 'remote ') === 0)
			{
				$remotes[] = trim(substr($section, 7));
			}
		}

		return $remotes;
	}
}
