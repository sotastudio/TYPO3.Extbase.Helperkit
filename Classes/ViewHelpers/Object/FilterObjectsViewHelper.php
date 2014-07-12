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
 * Returns an array of filtered objects.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\Page
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Object_FilterObjectsViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper
{

    /**
     * @param mixed $object
     * @param array $filter
     * @param string $as
     * @return mixed
     */
    public function render($object, $filter, $as)
    {
        $propertyPath = explode('.', $filter[0]);
        $filterValue = $filter[1];

        $i = 0;
        foreach($object as &$item) {
            $i++;
            $itemValue = Tx_Helperkit_Utility_Array::getValueFromPath($item, $propertyPath);
            if($itemValue != $filterValue) unset($object[$i]);
        }

        $this->templateVariableContainer->add($as, $object);
        $output = $this->renderChildren();
        $this->templateVariableContainer->remove($as);

        return $output;

    }
}