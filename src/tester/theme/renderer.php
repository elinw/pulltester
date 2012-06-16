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
 * Joomla Platform Theme Renderer Class
 *
 * @package     Joomla.Platform
 * @subpackage  Theme
 * @since       1.0
 */
abstract class PTThemeRenderer
{
	/**
	 * The theme object.
	 *
	 * @var    PTTheme
	 * @since  1.0
	 */
	protected $theme;

	/**
	 * Get a renderer instance.
	 *
	 * @param   string   $type   The renderer type.
	 * @param   PTTheme  $theme  The theme to render.
	 *
	 * @return  PTThemeRenderer  A theme renderer.
	 *
	 * @since   1.0
	 * @throws  RuntimeException
	 */
	public static function getInstance($type, PTTheme $theme)
	{
		// Normalise the renderer class.
		$type = JStringNormalise::toCamelCase($type);
		$class = get_called_class() . $type;

		// Check if the renderer exists.
		if (!class_exists($class))
		{
			throw new RuntimeException(sprintf('%s::getInstance(*%s*) renderer not found.', get_called_class(), $type));
		}

		// Instantiate the renderer.
		$renderer = new $class($theme);

		return $renderer;
	}

	/**
	 * Instantiate the renderer.
	 *
	 * @param   PTTheme  $theme  The theme to render.
	 *
	 * @since   1.0
	 */
	public function __construct(PTTheme $theme)
	{
		// Setup dependencies.
		$this->theme = $theme;
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
	abstract public function render($path);
}
