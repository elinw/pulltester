<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Parser
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Checkstyle XML report parser for the Joomla Pull Request Tester Application.
 *
 * @package     Joomla.PullTester
 * @subpackage  Parser
 * @since       1.0
 */
class PTParserCheckstyle extends PTParser
{
	/**
	 * Parse a Checkstyle XML report into a value object.
	 *
	 * @param   string  $file  The filesystem path of the checkstyle report to parse.
	 *
	 * @return  PTObjectCheckstyle
	 *
	 * @see     PTParser::parse()
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function parse($file)
	{
		// Verify that the report file exists.
		if (!file_exists($file) || filesize($file) < 1)
		{
			throw new RuntimeException('Checkstyle analysis not found.');
		}

		// Clean all the paths in the file.
		file_put_contents($file, $this->cleanPaths(file_get_contents($file)));

		// Create the report data object.
		$report = new PTObjectCheckstyle;

		$reader = new XMLReader;
		$reader->open($file);
		while ($reader->read())
		{
			if ($reader->name == 'file')
			{
				$fName = $reader->getAttribute('name');
			}

			if ($reader->name == 'error')
			{
				if ($reader->getAttribute('severity') == 'warning')
				{
					$e = new stdClass;
					$e->file = $fName;
					$e->line = (int) $reader->getAttribute('line');
					$e->message = $reader->getAttribute('message');

					$report->data['warnings'][] = $e;
				}

				if ($reader->getAttribute('severity') == 'error')
				{
					$e = new stdClass;
					$e->file = $fName;
					$e->line = (int) $reader->getAttribute('line');
					$e->message = $reader->getAttribute('message');

					$report->data['errors'][] = $e;
				}
			}
		}

		$reader->close();

		// Set the aggregate counts.
		$report->errorCount = count($report->data['errors']);
		$report->warningCount = count($report->data['warnings']);

		return $report;
	}
}
