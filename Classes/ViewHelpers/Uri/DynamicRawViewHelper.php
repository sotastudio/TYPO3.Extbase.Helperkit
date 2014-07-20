<?php
namespace SotaStudio\Helperkit\ViewHelpers\Uri;
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

use TYPO3\CMS\Core\Utility\GeneralUtility,
	TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 *
 * A view helper for dynamic rendering of links.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\Uri
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DynamicRawViewHelper extends AbstractViewHelper {

	/**
	 * @return void
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('target', 'string', 'Via Link Wizard generated target.');
	}

	/**
	 * ViewHelper Bootstrap.
	 *
	 * @return mixed|void
	 */
	public function render()
	{
		$target = $this->arguments['target'];

		if (isset($target) && !empty($target)) {
			$cObj = GeneralUtility::makeInstance('tslib_cObj');
			$configuration = array(
				'parameter' => $target,
				'returnLast' => TRUE
			);
			$href = $cObj->typolink('', $configuration);

			return $href;
		} else {
			return '';
		}
	}
}