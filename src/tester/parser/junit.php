<?php
/**
 * @package     Joomla.PullTester
 * @subpackage  Parser
 *
 * @copyright   Copyright (C) 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * JUnit XML report parser for the Joomla Pull Request Tester Application.
 *
 * @package     Joomla.PullTester
 * @subpackage  Parser
 * @since       1.0
 */
class PTParserJunit extends PTParser
{
	/**
	 * Parse a JUnit XML report into a value object.
	 *
	 * @param   PTTestReportUnittest  $report  The report on which to bind parsed data.
	 * @param   string                $file    The filesystem path of the JUnit report to parse.
	 *
	 * @return  PTTestReportUnittest
	 *
	 * @see     PTParser::parse()
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function parse($report, $file)
	{
		// Verify that the report file exists.
		if (!file_exists($file) || filesize($file) < 1)
		{
			throw new RuntimeException('Unit Tests log not found.');
		}

		// Clean all the paths in the file.
		file_put_contents($file, $this->cleanPaths(file_get_contents($file)));

		// I'm not sure if this is really necessary, but it appears to validate the full XML document using SimpleXML.
		libxml_use_internal_errors(true);
		$xml = simplexml_load_file($file);
		if (empty($xml))
		{
			throw new RuntimeException('Unit Tests log was corrupt.');
		}
		unset($xml);

		$reader = new XMLReader;
		$reader->open($file);

		while ($reader->read() && $reader->name !== 'testsuite');

		// Set some report aggregate data.
		$report->test_count 		+= $reader->getAttribute('tests');
		$report->assertion_count	+= $reader->getAttribute('assertions');
		$report->failure_count 		+= $reader->getAttribute('failures');
		$report->error_count 		+= $reader->getAttribute('errors');
		$report->elapsed_time 		+= $reader->getAttribute('time');

		while ($reader->read())
		{
			if ($reader->name == 'error')
			{
				$s = $reader->readString();

				if ($s)
				{
					$report->data->errors[] = $this->cleanPaths($s);
				}
			}

			if ($reader->name == 'failure')
			{
				$s = $reader->readString();

				if ($s)
				{
					$report->data->failures[] = $this->cleanPaths($s);
				}
			}
		}

		// Clean up.
		$reader->close();

		return $report;
	}
}
