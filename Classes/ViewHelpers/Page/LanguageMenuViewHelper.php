<?php
namespace SotaStudio\Helperkit\ViewHelpers\Page;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2014 Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 *  (c) 2012 Dominic Garms <djgarms@gmail.com>, DMFmedia GmbH
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

use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper for rendering TYPO3 menus in Fluid
 * Require the extension static_info_table
 *
 * @author Dominic Garms, DMFmedia GmbH
 * @author Andy Hausmann <ah@sota-studio.de>, SOTA Studio
 * @package helperkit
 * @subpackage ViewHelpers\Page\C
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class LanguageMenuViewHelper extends Tx_Vhs_ViewHelpers_Page_LanguageMenuViewHelper {

    /**
     * Initialize
     * @return void
     */
    public function initializeArguments() {
        $this->registerUniversalTagAttributes();
        $this->registerArgument('tagName', 'string', 'Tag name to use for enclosing container, list and flags (not finished) only', FALSE, 'ul');
        $this->registerArgument('tagNameChildren', 'string', 'Tag name to use for child nodes surrounding links, list and flags only', FALSE, 'li');
        $this->registerArgument('defaultIsoFlag', 'string', 'ISO code of the default flag', FALSE, 'gb');
        $this->registerArgument('defaultLanguageLabel', 'string', 'Label for the default language', FALSE, 'English');
        $this->registerArgument('order', 'mixed', 'Orders the languageIds after this list', FALSE, '');
        $this->registerArgument('labelOverwrite', 'mixed', 'Overrides language labels', FALSE, '');
        $this->registerArgument('hideNotTranslated', 'boolean', 'Hides languageIDs which are not translated', FALSE, FALSE);
        $this->registerArgument('layout', 'string', 'How to render links when using autorendering. Possible selections: name,flag - use fx "name" or "flag,name" or "name,flag"', FALSE, 'flag,name');
        $this->registerArgument('useCHash', 'boolean', 'Use cHash for typolink', FALSE, TRUE);
        $this->registerArgument('flagPath', 'string', 'Overwrites the path to the flag folder', FALSE, 'typo3/sysext/t3skin/images/flags/');
        $this->registerArgument('flagImageType', 'string', 'Sets type of flag image: png, gif, jpeg', FALSE, 'png');
        $this->registerArgument('linkCurrent', 'boolean', 'Sets flag to link current language or not', FALSE, TRUE);
        $this->registerArgument('as', 'string', 'If used, stores the menu pages as an array in a variable named according to this value and renders the tag content - which means automatic rendering is disabled if this attribute is used', FALSE, 'languageMenu');
        $this->registerArgument('pageUid', 'string', 'Uid of the page to link to', FALSE, '1');
    }

    /**
     * Sets all parameter for langMenu
     *
	 * @var	$GLOBALS['TSFE'] \TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController
	 * @var	$GLOBALS['TYPO3_DB'] \TYPO3\CMS\Core\Database\DatabaseConnection
     * @return array
     */
    protected function parseLanguageMenu() {
        $order = ($this->arguments['order']) ? GeneralUtility::trimExplode(',', $this->arguments['order']) : '';
        $labelOverwrite = ($this->arguments['labelOverwrite']) ? GeneralUtility::trimExplode(',', $this->arguments['labelOverwrite']) : '';

        $tempArray = $languageMenu = array();

        $tempArray[0] = array(
            'label' => $this->arguments['defaultLanguageLabel'],
            'flag' => $this->arguments['defaultIsoFlag']
        );

        $select = 'uid, title, flag';
        $from = 'sys_language';
        $where = '1=1' . $this->cObj->enableFields('sys_language');
        $sysLanguage = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows($select, $from, $where);

        foreach ($sysLanguage as $value) {
            $tempArray[$value['uid']] = array(
                'label' => $value['title'],
                'flag' => $value['flag'],
            );
        }

        // reorders languageMenu
        if (!empty($order)) {
            foreach ($order as $value) {
                $languageMenu[$value] = $tempArray[$value];
            }
        } else {
            $languageMenu = $tempArray;
        }

        // overwrite of label
        if (!empty($labelOverwrite)) {
            $i = 0;
            foreach ($languageMenu as $key => $value) {
                $languageMenu[$key]['label'] = $labelOverwrite[$i];
                $i++;
            }
        }

        // Select all pages_language_overlay records on the current page. Each represents a possibility for a language.
        $pageArray = array();
        $table = 'pages_language_overlay';
        $whereClause = 'pid=' . $this->arguments['pageUid'] . ' ';
        $whereClause .= $GLOBALS['TSFE']->sys_page->enableFields($table);
        $sysLang = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('DISTINCT sys_language_uid', $table, $whereClause);

        if (!empty($sysLang)) {
            foreach ($sysLang as $val) {
                $pageArray[$val['sys_language_uid']] = $val['sys_language_uid'];
            }
        }

        foreach ($languageMenu as $key => $value) {
            $current = ($GLOBALS['TSFE']->sys_language_uid == $key) ? 1 : 0;
            $inactive = ($pageArray[$key] || $key == $this->defaultLangUid) ? 0 : 1;
            $languageMenu[$key]['current'] = $current;
            $languageMenu[$key]['inactive'] = $inactive;
            $languageMenu[$key]['url'] = $this->getLanguageUrl($key, $inactive);
            $languageMenu[$key]['flagSrc'] = $this->getLanguageFlagSrc($value['flag']);
            if ($this->arguments['hideNotTranslated'] && $inactive) {
                unset($languageMenu[$key]);
            }
        }

        return $languageMenu;
    }

    /**
     * Get link of language menu entry
     *
     * @param $uid
     * @return string
     */
    protected function getLanguageUrl($uid) {
        $getValues = GeneralUtility::_GET();
        $getValues['L'] = $uid;
        $currentPage = $this->arguments['pageUid'];
        unset($getValues['id']);
        unset($getValues['cHash']);
        $addParams = http_build_query($getValues);
        $config = array(
            'parameter' => $currentPage,
            'returnLast' => 'url',
            'additionalParams' => '&' . $addParams,
            'useCacheHash' => $this->arguments['useCHash']
        );
        return $this->cObj->typoLink('', $config);
    }
}
