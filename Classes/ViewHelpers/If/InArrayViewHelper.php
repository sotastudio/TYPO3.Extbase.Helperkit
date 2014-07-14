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
 * ### Condition: Type of value is array
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\If
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_If_InArrayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
	/**
	 * Render method
	 *
	 * @param mixed $needle The searched value.
	 * @param mixed $haystack The array.
	 * @param bool $strict If set to true then the function will also check the types of the needle in the haystack.
	 * @throws Exception In case the $needle is not present.
	 * @return string
	 */
	public function render($needle, $haystack = null, $strict = false)
	{
		if ($haystack == '' || $haystack == null) {
			return '';
		} else if (!is_array($haystack) === true) {
			throw new Exception('Passed haystack needs to be an array.');
		}

		if (in_array($needle, $haystack, $strict) === true) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}