<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 Andy Hausmann <ah@sota-studio.de>
 *  (c) 2012-2013 Simon Rauterberg <rauterberg@goldland-media.com>
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
 * @author Simon Rauterberg <rauterberg@goldland-media.com>
 * @package helperkit
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Uri_DynamicViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper
{

	/**
	 * @var string
	 */
	protected $tagName = 'a';


	/**
	 * Arguments initialization
	 *
	 * @return void
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerUniversalTagAttributes();
		$this->registerArgument('respectArguments', 'boolean', 'If TRUE the via ViewHelper given link attributes are NOT being overriden by the values set via link wizard. By default they are, so the given arguments are normally treated as some kind of placeholder values. This applies for target, class and title.', false, false);
		$this->registerTagAttribute('href', 'string', 'The Hyperlink.', true, null);
	}


	/**
	 * Workaround for parent::setArguments().
	 *
	 * Mentioned method is inconsistent:
	 * - in TYPO3 4.5 it expects an object instance of Tx_Fluid_Core_ViewHelper_Arguments
	 * - in TYPO3 > 4.5 it expects just an array.
	 *
	 * In order to avoid fatal errors, this new method has been temporarily implemented.
	 *
	 * @param array $arguments
	 * @return void
	 */
	public function setArgumentsFromArray(array $arguments) {
		foreach ($arguments as $k => $v) {
			if ($k == 'class') {
				$this->arguments[$k] .= ' ' . $v;
			} else {
				$this->arguments[$k] = $v;
			}
		}

	}


	/**
	 * Get the name of this form element.
	 * Either returns arguments['name'], or the correct name for Object Access.
	 *
	 * In case property is something like bla.blubb (hierarchical), then [bla][blubb] is generated.
	 *
	 * @return string Name
	 */
	protected function getHref() {
		$href = $this->arguments['href'];
		return $href;
	}


	/**
	 * Checks and processes the given link parameters.
	 *
	 * @param string $link Output from TYPO3 link wizard.
	 * @return bool Returns true if it is possible to build a link.
	 */
	protected function resolveWizardLink($link)
	{
		$linkAttributeModel = array('href', 'target', 'class', 'title');

		$linkAttributeData = explode(' ', $link, count($linkAttributeModel));
		// Combine labels and values into one array
		$linkData = Tx_WidgetReferences_Utility_Div::combineArray($linkAttributeModel, $linkAttributeData, false);

		if (isset($linkData['href']) && !empty($linkData['href'])) {
			// Save link data into ViewHelper arguments

			$cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
			$configuration = array(
				'parameter' => $this->arguments['href'],
				'returnLast' => true
			);
			$linkData['href'] = $cObj->typolink('', $configuration);
			$this->setArgumentsFromArray($linkData);

			return true;
		} else {
			return false;
		}
	}


	/**
	 * Adds attributes to the tag builder if they're not empty.
	 *
	 * @return void
	 */
	protected function addTagAttributes()
	{
		foreach (array('href', 'target', 'class', 'title') as $attributeName) {
			if (isset($this->arguments[$attributeName]) && !empty($this->arguments[$attributeName])) {
				$this->tag->addAttribute($attributeName, $this->arguments[$attributeName]);
			}
		}
	}


	/**
	 * ViewHelper Bootstrap.
	 *
	 * @return mixed|void
	 */
	public function render()
	{
		if ($this->resolveWizardLink($this->getHref())) {
			$this->addTagAttributes();
			$this->tag->setContent($this->renderChildren());
			return $this->tag->render();
		} else {
			return $this->renderChildren();
		}
	}
}
?>