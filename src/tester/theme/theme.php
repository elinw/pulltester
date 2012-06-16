<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Theme
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * Joomla Platform Theme Class
 *
 * @package     Joomla.Platform
 * @subpackage  Theme
 * @since       1.0
 */
class PTTheme
{
	/**
	 * The theme buffer.
	 *
	 * @var    JRegistry
	 * @since  1.0
	 */
	public $buffer;

	/**
	 * The theme options.
	 *
	 * @var    JRegistry
	 * @since  1.0
	 */
	public $options;

	/**
	 * The theme renderer.
	 *
	 * @var    PTThemeRenderer
	 * @since  1.0
	 */
	public $renderer;

	/**
	 * Instantiate the theme.
	 *
	 * @param   JRegistry        $buffer    The theme buffer.
	 * @param   JRegistry        $options   The theme options.
	 * @param   PTThemeRenderer  $renderer  The theme renderer.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public function __construct(JRegistry $buffer = null, JRegistry $options = null, PTThemeRenderer $renderer = null)
	{
		// Setup dependencies.
		$this->buffer = isset($buffer) ? $buffer : $this->loadBuffer();
		$this->options = isset($options) ? $options : $this->loadOptions();
		$this->renderer = isset($renderer) ? $renderer : $this->loadRenderer();
	}

	/**
	 * Render the theme.
	 *
	 * @param   string  $path  The path to the theme.
	 *
	 * @return  string  The rendered theme.
	 *
	 * @since   1.0
	 */
	public function render($path)
	{
		// Render the theme.
		return $this->renderer->render($path);
	}

	/**
	 * Load the theme buffer.
	 *
	 * @return  JRegistry  The theme buffer.
	 *
	 * @since   1.0
	 */
	protected function loadBuffer()
	{
		return new JRegistry;
	}

	/**
	 * Load the theme options.
	 *
	 * @return  JRegistry  The theme options.
	 *
	 * @since   1.0
	 */
	protected function loadOptions()
	{
		return new JRegistry;
	}

	/**
	 * Load a theme renderer.
	 *
	 * @return  PTThemeRenderer  The theme renderer.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	protected function loadRenderer()
	{
		return PTThemeRenderer::getInstance('Php', $this);
	}
}
