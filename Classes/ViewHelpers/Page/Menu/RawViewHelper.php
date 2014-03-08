<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2014 Andy Hausmann <ah@sota-studio.de>, sota studio
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
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
 * ************************************************************* */

/**
 * ### Page: Directory Menu ViewHelper
 *
 * ViewHelper for rendering TYPO3 list menus in Fluid
 *
 * Just returns an array of the pages.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, sota studio
 * @package helperkit
 * @subpackage ViewHelpers\Page\Menu
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Page_Menu_RawViewHelper extends Tx_Vhs_ViewHelpers_Page_Menu_AbstractMenuViewHelper
{

	/**
	 * @return void
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('pages', 'mixed', 'Parent page UIDs of subpages to include in the menu. Can be CSV, array or an object implementing Traversable.', TRUE);
	}

	/**
	 * Render method
	 *
	 * @return string
	 */
	public function render()
	{
		$pages = $this->processPagesArgument();
		if (NULL === $pages) {
			return;
		}
		$menuData = array();
		$rootLineData = $this->pageSelect->getRootLine($GLOBALS['TSFE']->id);
		foreach ($pages as $pageUid) {
			$menuData = array_merge($menuData, $this->pageSelect->getMenu($pageUid));
		}
		$menu = $this->parseMenu($menuData, $rootLineData);
		return $menu;
	}

}
