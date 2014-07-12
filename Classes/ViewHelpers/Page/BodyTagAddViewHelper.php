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
 * A view helper for adding CSS and JS files to teh frontend.
 *
 * This helper needs to be called once per file.
 * It is not possible to add multiple files via array or such, yet.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\Page
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Page_BodyTagAddViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper
{
	/**
	 * @var array List of arguments being ignored by the later on rendering process.
	 */
	protected $excludedArguments = array();

	/**
	 * @var string Container var for the reference to the TSFE page setup for the body tag add string.
	 */
	protected $bodyTagAdd = '';


	/**
	 * Sets an exclude argument
	 *
	 * @param $argument
	 * @return void
	 */
	private function excludeArgument($argument)
	{
		array_push($this->excludedArguments, $argument);
	}

	/**
	 * Sets excluded arguments
	 *
	 * @param array $arguments
	 * @return void
	 */
	private function excludeArguments(array $arguments)
	{
		foreach ($arguments as $argument) {
			$this->excludeArgument($argument);
		}
	}

	/**
	 * Adds attributes to the argument-collection
	 *
	 * @param array $arguments collection of arguments to add. key = argument name, value = argument value
	 * @param boolean $escapeSpecialCharacters apply htmlspecialchars to argument values
	 * @return void
	 */
	private function addArguments(array $arguments, $escapeSpecialCharacters = true)
	{
		foreach ($arguments as $argumentName => $argumentValue) {
			$this->addArgument($argumentName, $argumentValue, $escapeSpecialCharacters);
		}
	}

	/**
	 * Adds an argument to the argument-collection
	 *
	 * @param string $argumentName name of the argument to be added
	 * @param string $argumentValue argument value
	 * @param boolean $escapeSpecialCharacters apply htmlspecialchars to argument value
	 * @return void
	 */
	private function addArgument($argumentName, $argumentValue, $escapeSpecialCharacters = true)
	{
		if ($escapeSpecialCharacters) {
			$argumentValue = htmlspecialchars($argumentValue);
		}
		$this->arguments[$argumentName] = $argumentValue;
	}

	/**
	 * Returns the current value of the given attribute, if available.
	 *
	 * @param $attributeName
	 * @return string
	 */
	public function getCurrentValue($attributeName)
	{
		$str = $this->bodyTagAdd;
		if (strstr($str, $attributeName)) {
			$l = explode($attributeName . "=\"", $str);
			$r = explode("\"", $l[1]);
			return $r[0];
		} else {
			return '';
		}
	}

	/**
	 * Builds and returns content for Body Tag Addition.
	 *
	 * @return string
	 */
	public function getContent()
	{
		$merge = ($this->arguments['override'] !== true && !empty($this->bodyTagAdd)) ? true : false;

		$o = '';
		foreach ($this->arguments as $argumentName => $argumentValue) {
			if (!in_array($argumentName, $this->excludedArguments)) {
				if ($merge) {
					$argumentValue = $this->getCurrentValue($argumentName) . ' ' . $argumentValue;
				}
				if (trim($argumentValue) !== '' && $argumentValue !== null) {
					$o .= $argumentName . '="' . trim($argumentValue) . '" ';
				}
			}
		}
		return $o;
	}

	/**
	 * @return void
	 */
	public function initialize()
	{
		parent::initialize();
		$this->bodyTagAdd =& $GLOBALS['TSFE']->pSetup['bodyTagAdd'];
	}

	/**
	 * @return void
	 */
	public function initializeArguments()
	{
		parent::initializeArguments();
		$this->registerArgument('override', 'boolean', 'If set, everything already defined will be overridden.', false, false);
		$this->registerArgument('class', 'string', 'CSS class(es) for this element', false);
		$this->registerArgument('dir', 'string', 'Text direction for this HTML element. Allowed strings: "ltr" (left to right), "rtl" (right to left)', false);
		$this->registerArgument('id', 'string', 'Unique (in this file) identifier for this HTML element.', false);
		$this->registerArgument('lang', 'string', 'Language for this element. Use short names specified in RFC 1766', false);
		$this->registerArgument('style', 'string', 'Individual CSS styles for this element', false);
		$this->registerArgument('title', 'string', 'Tooltip text of element', false);
		$this->registerArgument('accesskey', 'string', 'Keyboard shortcut to access this element', false);
		$this->registerArgument('tabindex', 'integer', 'Specifies the tab order of this element', false);
		$this->registerArgument('onclick', 'string', 'JavaScript evaluated for the onclick event', false);
		$this->registerArgument('additionalAttributes', 'array', 'Additional body attributes. They will be added directly to the resulting HTML string.', false);
		$this->excludeArguments(array('override', 'additionalAttributes'));
	}

	/**
	 * Adds JS and CSS to the frontend
	 *
	 * @param string $file
	 * @param bool $moveToFooter
	 * @return void Flag to include file into footer - doesn't work for CSS files
	 */

	public function render()
	{
		if ($this->hasArgument('additionalAttributes') && is_array($this->arguments['additionalAttributes'])) {
			$this->addArguments($this->arguments['additionalAttributes']);
		}

		$this->bodyTagAdd = $this->getContent();
	}
}