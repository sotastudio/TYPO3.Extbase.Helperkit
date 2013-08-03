<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 Andy Hausmann <ah@sota-studio.de>, sota studio
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
 * Supports both automatic, tag-based rendering (which
 * defaults to `ul > li` with options to set both the
 * parent and child tag names. When using manual rendering
 * a range of support CSS classes are available along
 * with each page record.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, sota studio
 * @package helperkit
 * @subpackage ViewHelpers\Page\Menu
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Page_Menu_SinglepageViewHelper extends Tx_Vhs_ViewHelpers_Page_Menu_DirectoryViewHelper
{

	/**
	 * @return void
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('hashPrefix', 'string', 'Field to use for prefixing the hash link', FALSE, 'onepage-pageid-');
		$this->registerArgument('hashField', 'string', 'Field to use for building the hash link', FALSE, 'uid');
	}

	/**
	 * Automatically render a menu
	 *
	 * @param array $menu
	 * @param integer $level
	 * @return string
	 */
	protected function autoRender($menu, $level = 1)
	{
		# Overrides
		$level = 1;

		$hashPrefix = $this->arguments['hashPrefix'];
		$hashField = $this->arguments['hashField'];

		$tagName = $this->arguments['tagNameChildren'];
		$substElementUid = $this->arguments['substElementUid'];
		$html = array();
		$itemsRendered = 0;
		$numberOfItems = count($menu);
		foreach ($menu as $page) {
			$link = '#' . $hashPrefix . $page[$hashField];
			$class = trim($page['class']) != '' ? ' class="' . $page['class'] . '"' : '';
			$elementId = $substElementUid ? ' id="elem_' . $page['uid'] . '"' : '';
			$target = $page['target'] != '' ? ' target="' . $page['target'] . '"' : '';
			$html[] = '<' . $tagName . $elementId . $class . '>';
			$html[] = '<a href="' . $link . '"' . $class . $target . '>' . $page['linktext'] . '</a>';
			$html[] = '</' . $tagName . '>';
			$itemsRendered++;
			if (TRUE === isset($this->arguments['divider']) && $itemsRendered < $numberOfItems) {
				$html[] = $this->arguments['divider'];
			}
		}
		return implode(LF, $html);
	}

}
