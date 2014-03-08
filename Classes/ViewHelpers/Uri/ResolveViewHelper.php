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
 * A view helper for dynamic rendering of links.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, sota studio
 * @package helperkit
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Uri_ResolveViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper
{
	/**
	 * @return void
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('absolute', 'bool', 'If true, prepends resolved uri by base url.', false, false);
		$this->registerArgument('path', 'string', 'Path to resource.', false, null);
		$this->registerArgument('bypass', 'bool', 'If true, deactivation path resolution.', false, false);
	}

	protected function getBaseUrl()
	{
		return $GLOBALS['TSFE']->baseUrl;
	}

	/**
	 * ViewHelper Bootstrap.
	 *
	 * @return mixed|void
	 */
	public function render()
	{
		$absolute = $this->arguments['absolute'];
		$path = $this->arguments['path'];
		$bypass = $this->arguments['bypass'];

		if (isset($path) && !empty($path)) {
			$uri = ($bypass == false ) ? Tx_Helperkit_Utility_Div::getFileResource($path) : $path;

			if ($absolute == true) {
				$uri = $this->controllerContext->getRequest()->getBaseURI() . $uri;
			}

			return $uri;
		} else {
			return '';
		}
	}
}