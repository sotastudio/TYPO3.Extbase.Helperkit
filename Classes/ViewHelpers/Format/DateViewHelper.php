<?php
namespace SotaStudio\Helperkit\ViewHelpers\Format;
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

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper to format a date, using strftime
 *
 * # Example: Basic example using default strftime
 * <code>
 * <n:format.date>{dateObject}</b:format.date>
 * </code>
 * <output>
 * 2013-06-08
 * </output>
 *
 * # Example: Basic example using default strftime and a format
 * <code>
 * <n:format.date format="%B">{dateObject}</b:format.date>
 * </code>
 * <output>
 * June
 * </output>
 *
 * # Example: Basic example using datetime
 * <code>
 * <n:format.date format="c" strftime="0">{dateObject}</n:format.date>
 * </code>
 * <output>
 * 2004-02-12T15:19:21+00:00
 * </output>
 *
 * # Example: Render current time
 * <code>
 * <n:format.date format="c" strftime="0" currentDate="1">{dateObject}</n:format.date>
 * </code>
 * <output>
 * 2013-06-12T15:19:21+00:00
 * </output>
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\Format
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class DateViewHelper extends AbstractViewHelper {

	/**
	 * Render the supplied DateTime object as a formatted date.
	 *
	 * @param mixed $date DateTime object or a string that is accepted by DateTime constructor
	 * @param string $format Format String which is taken to format the Date/Time
	 * @param bool $currentDate if TRUE, the current date is used
	 * @param bool $strftime if TRUE, the strftime is used instead of date()
	 * @throws Exception
	 * @return string Formatted date
	 */
	public function render($date = NULL, $format = '%Y-%m-%d', $currentDate = FALSE, $strftime = TRUE) {
		if ($currentDate) {
			if ($strftime) {
				return utf8_encode(strftime($format, $GLOBALS['EXEC_TIME']));
			} else {
				return utf8_encode(date($format, $GLOBALS['EXEC_TIME']));
			}
		}

		if ($date === NULL) {
			$date = $this->renderChildren();
			if ($date === NULL) {
				return '';
			}
		}
		if (!$date instanceof DateTime) {
			try {
				$date = new DateTime($date);
			} catch (Exception $exception) {
				throw new Exception('"' . $date . '" could not be parsed by DateTime constructor.', 1241722579);
			}
		}

		if ($strftime) {
			$formattedDate = strftime($format, $date->format('U'));
		} else {
			$formattedDate = date($format, $date->format('U'));
		}

		return utf8_encode($formattedDate);
	}
}