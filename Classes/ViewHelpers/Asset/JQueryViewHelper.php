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
 *
 * A ViewHelper for adding jQuery to the frontend.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\Asset
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Asset_JQueryViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractTagBasedViewHelper
{
	/**
	 * Adds T3Jquery as Lib
	 *
	 * If T3Jquery is available, it adds it's script file(s)
	 * Otherwise, it includes the script of this Ext.
	 *
	 * @param string $altJQueryFile
	 * @param bool $moveToFooter
	 * @return void
	 */
	public function render($altJQueryFile = NULL, $moveToFooter = FALSE)
	{
		// checks if t3jquery is loaded
		if (t3lib_extMgm::isLoaded('t3jquery')) {
			require_once(t3lib_extMgm::extPath('t3jquery') . 'class.tx_t3jquery.php');
		}
		// if t3jquery is loaded and the custom Library had been created
		if (T3JQUERY === true) {
			tx_t3jquery::addJqJS();

		} else {
			if ($altJQueryFile) {
				Tx_Helperkit_Utility_Page::addCssJsFile(
					$altJQueryFile,
					$moveToFooter
				);
			} else {
				Tx_Helperkit_Utility_Div::renderFlashMessage(
					'jQuery not loaded',
					'jQuery could not be loaded. Please check the path to the alternative jQuery library or simply use the Extension t3jquery.',
					\TYPO3\CMS\Core\Messaging\FlashMessage::ERROR
				);
			}
		}
	}
}