<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 Andy Hausmann <ah@sota-studio.de>, sota studio
 *  (c) 2012-2013 Xaver Maierhofer <xaver.maierhofer@xwissen.info>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Helper Class which makes various tools and helper available
 *
 * @author Andy Hausmann <ah@sota-studio.de>, sota studio
 * @author Xaver Maierhofer <xaver.maierhofer@xwissen.info>
 * @package helperkit
 * @suboackage Classes\Utility
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_Utility_Page
{
	/**
	 * Returns the reference to a 'resource' in TypoScript.
	 *
	 * @param string $file File get a reference from - can contain EXT:ext_name
	 * @return mixed
	 */
	protected static function getFileResource($file)
	{
		return Tx_Helperkit_Utility_Div::getFileResource($file);
	}

	/**
	 * Adds a JavaScript file to the head.
	 *
	 * @param string $file The resource.
	 * @return void
	 */
	protected static function addJsFile($file)
	{
		$GLOBALS['TSFE']->getPageRenderer()->addJsFile($file);
	}

	/**
	 * Adds a JavaScript file right before the closing body tag.
	 *
	 * @param string $file The resource.
	 * @return void
	 */
	protected static function addJsFooterFile($file)
	{
		$GLOBALS['TSFE']->getPageRenderer()->addJsFooterFile($file);
	}

	/**
	 * Adds a Stylesheet file to the head.
	 *
	 * @param string $file The resource.
	 * @return void
	 */
	protected static function addCssFile($file)
	{
		$GLOBALS['TSFE']->getPageRenderer()->addCssFile($file);
	}

	/**
	 * Adds JavaScript code inline.
	 *
	 * @param string $name Unique name for the output.
	 * @param string $code The code.
	 * @return void
	 */
	protected static function addJsInlineCode($name, $code)
	{
		$GLOBALS['TSFE']->getPageRenderer()->addJsInlineCode($name, $code);
	}

	/**
	 * Adds JavaScript code inline right before the closing body tag.
	 *
	 * @param string $name Unique name for the output.
	 * @param string $code The code.
	 * @return void
	 */
	protected static function addJsFooterInlineCode($name, $code)
	{
		$GLOBALS['TSFE']->getPageRenderer()->addJsFooterInlineCode($name, $code);
	}

	/**
	 * Adds a Stylesheet inline.
	 *
	 * @param string $name Unique name for the output.
	 * @param string $code The code.
	 * @return void
	 */
	protected static function addCssInlineBlock($name, $code)
	{
		$GLOBALS['TSFE']->getPageRenderer()->addCssInlineBlock($name, $code);
	}

	/**
	 * Checks a passed CSS or JS file and adds it to the Frontend.
	 *
	 * @param string $file File reference
	 * @param bool $moveToFooter Flag to include file into footer - doesn't work for CSS files
	 * @param string $type Override for media type detection
	 * @return void
	 */
	public static function addCssJsFile($file, $moveToFooter = false, $type = null)
	{
		// Get file reference
		$resolved = self::getFileResource($file);

		if ($resolved) {
			// Get defined type, otherwise automatically detected file extension
			$mediaTypeSplit = ($type !== '' && $type !== null) ? '.' . $type : strrchr($file, '.');

			// JavaScript processing
			if ($mediaTypeSplit == '.js') {
				($moveToFooter)
					? self::addJsFooterFile($resolved)
					: self::addJsFile($resolved);

				// Stylesheet processing
			} elseif ($mediaTypeSplit == '.css') {
				self::addCssFile($resolved);
			}
		}
	}

	/**
	 * Checks a passed CSS or JS file and adds it to the Frontend.
	 *
	 * @param string $script JS Block
	 * @param string $addUnique Unique key to avoid multiple inclusions
	 * @param bool $moveToFooter Flag to include file into footer - doesn't work for CSS files
	 */
	public static function addJsInline($code, $name = '', $moveToFooter = false)
	{
		if ($code !== '') {
			($moveToFooter)
				? self::addJsFooterInlineCode($name, $code)
				: self::addJsInline($name, $code);
		}
	}
}