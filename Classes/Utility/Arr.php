<?php
namespace SotaStudio\Helperkit\Utility;
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
 * Helper Class which makes array tools and helper available
 *
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage Classes\Utility
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Arr {

    /**
     * Walks through an array by the defined path.
     *
     * @param $arr Array to get the value from.
     * @param $path Array of path segments to walk trough within $arr.
     * @return mixed The Value
     */
    public static function getValueFromPath($arr, $path)
    {
        // todo: add checks on $path
        $dest = $arr;
        $finalKey = array_pop($path);
        foreach ($path as $key) {
            $dest = $dest[$key];
        }
        return $dest[$finalKey];
    }

    /**
     * Better implementation of php's array_combine().
     * This wont throw FALSE in case both array haven't an identical size.
     *
     * @static
     * @param array $a Array containing the keys.
     * @param array $b Array containing the values.
     * @param bool $pad Switch for allowing padding. Fills the combined array with empty values if any array is larger than the other one.
     * @return array Combined array.
     */
    public static function combineArray($a, $b, $pad = TRUE)
    {
        $acount = count($a);
        $bcount = count($b);
        // more elements in $a than $b but we don't want to pad either
        if (!$pad) {
            $size = ($acount > $bcount) ? $bcount : $acount;
            $a = array_slice($a, 0, $size);
            $b = array_slice($b, 0, $size);
        } else {
            // more headers than row fields
            if ($acount > $bcount) {
                // how many fields are we missing at the end of the second array?
                // Add empty strings to ensure arrays $a and $b have same number of elements
                $more = $acount - $bcount;
                for ($i = 0; $i < $more; $i++) {
                    $b[] = "";
                }
                // more fields than headers
            } else if ($acount < $bcount) {
                $more = $bcount - $acount;
                // fewer elements in the first array, add extra keys
                for ($i = 0; $i < $more; $i++) {
                    $key = 'extra_field_0' . $i;
                    $a[] = $key;
                }

            }
        }

        return array_combine($a, $b);
    }

}