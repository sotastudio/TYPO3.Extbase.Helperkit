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
 * Returns an array of distinct values.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\Page
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Object_DistinctValuesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

    /**
     * @param mixed $object
     * @param string $property
     * @param string $as
     * @param string $sort
     * @return mixed
     */
    public function render($object, $property, $as, $sort = 'asc')
    {
        if (!in_array($sort, Array('asc', 'desc'))) $sort = 'asc';

        $propertyPath = explode('.', $property);
        $values = array();

        foreach($object as $item) {
            $values[] = Tx_Helperkit_Utility_Array::getValueFromPath($item, $propertyPath);
        }

        $values = array_unique($values);
        ($sort == 'asc') ? sort($values) : rsort($values);

        $this->templateVariableContainer->add($as, $values);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $output;

    }
}