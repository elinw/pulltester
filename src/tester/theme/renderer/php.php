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
class PTThemeRendererPhp extends PTThemeRenderer
{
	/**
	 * Get the property using the theme value if set, then the renderer.
	 *
	 * @param   string  $property  The property name.
	 *
	 * @return  mixed  The property value.
	 *
	 * @since   1.0
	 */
	public function __get($property)
	{
		// Get the property from the theme if it is set.
		if (isset($this->theme->$property))
		{
			return $this->theme->$property;
		}
		// Get the property from the renderer if it is set.
		else
		{
			return isset($this->$property) ? $this->$property : null;
		}
	}

	/**
	 * Check if the property is set by checking if it exists in the theme first, then the renderer.
	 *
	 * @param   string  $property  The property name.
	 *
	 * @return  boolean  True if the property is set, false otherwise.
	 *
	 * @since   1.0
	 */
	public function __isset($property)
	{
		// Check if the property is set in the theme.
		return isset($this->theme->$property) ?: isset($this->$property);
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
		ob_start();
		include $path;
		$content = ob_get_clean();

		return $content;
	}
}
