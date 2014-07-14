<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2014 Andy Hausmann <ah@sota-studio.de>, SOTA Studio
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
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage Classes\Utility
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_Utility_Div
{
	/**
	 * Returns the reference to a 'resource' in TypoScript.
	 *
	 * @param string $file File get a reference from - can contain EXT:ext_name
	 * @return mixed
	 */
	public static function getFileResource($file)
	{
		return $GLOBALS['TSFE']->tmpl->getFileName($file);
	}

	/**
	 * Adds/renders a Flash message.
	 *
	 * @param string $title The title
	 * @param string $message The message
	 * @param int $type Message level
	 * @return mixed
	 */
	public static function renderFlashMessage($title, $message, $type = \TYPO3\CMS\Core\Messaging\FlashMessage::WARNING)
	{
		$code = ".typo3-message .message-header{padding: 10px 10px 0 30px;font-size:0.9em;}";
		$code .= ".typo3-message .message-body{padding: 10px;font-size:0.9em;}";

		$GLOBALS['TSFE']->getPageRenderer()->addCssFile(\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::siteRelPath('t3skin') . 'stylesheets/visual/element_message.css');
		$GLOBALS['TSFE']->getPageRenderer()->addCssInlineBlock('flashmessage', $code);

		$flashMessage = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('t3lib_FlashMessage', $message, $title, $type);
		return $flashMessage->render();
	}

	/**
	 * Checks whether the given url IS a url.
	 * Though it doen't check the TLD.
	 *
	 * @param $url
	 * @return bool
	 */
	public static function isUrl($url)
	{
		return (filter_var($url, FILTER_VALIDATE_URL)) ? true : false;
	}

}