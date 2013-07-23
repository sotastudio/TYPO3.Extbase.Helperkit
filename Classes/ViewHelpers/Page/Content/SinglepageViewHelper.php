<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Andy Hausmann <ah@sota-studio.de>, sota studio
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

/**
 * ### Page: Directory Menu ViewHelper
 *
 * ViewHelper for rendering TYPO3 list menus in Fluid
 *
 * Supports both automatic, tag-based rendering (which
 * defaults to `ul > li` with options to set both the
 * parent and child tag names. When using manual rendering
 * a range of support CSS classes are available along
 * with each page record.
 *
 * @author Andy Hausmann <ah@sota-studio.de>, sota studio
 * @package helperkit
 * @subpackage ViewHelpers\Page\Content
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class Tx_Helperkit_ViewHelpers_Page_Content_SinglepageViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;

	/**
	 * The current view, as resolved by resolveView()
	 *
	 * @var \TYPO3\CMS\Extbase\Mvc\View\ViewInterface
	 * @api
	 */
	protected $view = NULL;

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'Tx_Flux_MVC_View_ExposedTemplateView';

	/**
	 * @var Tx_Fluidpages_Service_PageService
	 */
	protected $pageService;

	/**
	 * @var Tx_Fluidpages_Service_ConfigurationService
	 */
	protected $configurationService;

	/**
	 * @var Tx_Flux_Provider_ConfigurationService
	 */
	protected $providerConfigurationService;

	/**
	 * @var Tx_Flux_Service_FlexForm
	 */
	protected $flexFormService;


	/**
	 * Injects the object manager
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 * @return void
	 */
	public function injectObjectManager(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		$this->objectManager = $objectManager;
		$this->arguments = $this->objectManager->create('TYPO3\\CMS\\Extbase\\Mvc\\Controller\\Arguments');
	}

	/**
	 * @param Tx_Fluidpages_Service_PageService $pageService
	 */
	public function injectPageService(Tx_Fluidpages_Service_PageService $pageService) {
		$this->pageService = $pageService;
	}

	/**
	 * @param Tx_Fluidpages_Service_ConfigurationService $configurationService
	 * @return void
	 */
	public function injectConfigurationService(Tx_Fluidpages_Service_ConfigurationService $configurationService) {
		$this->configurationService = $configurationService;
	}

	/**
	 * @param Tx_Flux_Service_FlexForm $flexFormService
	 * @return void
	 */
	public function injectFlexFormService(Tx_Flux_Service_FlexForm $flexformService) {
		$this->flexFormService = $flexformService;
	}

	/**
	 * @param Tx_Flux_Provider_ConfigurationService $providerConfigurationService
	 * @return void
	 */
	public function injectProviderConfigurationService(Tx_Flux_Provider_ConfigurationService $providerConfigurationService) {
		$this->providerConfigurationService = $providerConfigurationService;
	}

	/**
	 * @return void
	 */
	public function initializeArguments() {
		parent::initializeArguments();
		$this->registerArgument('limit', 'integer', 'Optional limit to the number of content elements to render');
		$this->registerArgument('showHidden', 'boolean', 'Include "hidden in menu" pages', FALSE, FALSE);
		$this->registerArgument('resolveExclude', 'boolean', 'Exclude link if realurl/cooluri flag tx_realurl_exclude is set', FALSE, FALSE);
		$this->registerArgument('useShortcutData', 'boolean', 'If TRUE, fetches ALL data from the shortcut target before any additional processing takes place. Note that this overrides everything, including the UID, effectively substituting the shortcut for the target', FALSE, FALSE);
		$this->registerArgument('titleFields', 'string', 'CSV list of fields to use as link label - default is "nav_title,title", change to for example "tx_myext_somefield,subtitle,nav_title,title". The first field that contains text will be used. Field value resolved AFTER page field overlays.', FALSE, 'nav_title,title');
		$this->registerArgument('doktypes', 'mixed', 'CSV list or array of allowed doktypes from constant names or integer values, i.e. 1,254 or DEFAULT,SYSFOLDER,SHORTCUT or just default,sysfolder,shortcut');
		$this->registerArgument('pages', 'mixed', 'Parent page UIDs of subpages to include in the menu. Can be CSV, array or an object implementing Traversable.', TRUE);
		$this->registerArgument('hashPrefix', 'string', 'Field to use for prefixing the hash link', FALSE, 'onepage-pageid-');
		$this->registerArgument('hashField', 'string', 'Field to use for building the hash link', FALSE, 'uid');
		$this->registerArgument('sectionTag', 'string', 'Tag to wrap the page contents with.', FALSE, 'section');
	}

	/**
	 * Initialize object
	 * @return void
	 */
	public function initializeObject() {
		if (is_array($GLOBALS['TSFE']->fe_user->user) === TRUE) {
			$groups = array(-2, 0);
			$groups = array_merge($groups, (array) array_values($GLOBALS['TSFE']->fe_user->groupData['uid']));
		} else {
			$groups = array(-1, 0);
		}
		$this->pageSelect = new t3lib_pageSelect();
		//$this->pageSelect->init((boolean) $this->arguments['showHidden']);
		$clauses = array();
		foreach ($groups as $group) {
			$clause = "fe_group = '" . $group . "' OR fe_group LIKE '" .
				$group . ",%' OR fe_group LIKE '%," . $group . "' OR fe_group LIKE '%," . $group . ",%'";
			array_push($clauses, $clause);
		}
		array_push($clauses, "fe_group = '' OR fe_group = '0'");
		$this->pageSelect->where_groupAccess = ' AND (' . implode(' OR ', $clauses) .  ')';
	}

	/**
	 * @param array $page
	 * @param array $rootLine
	 * @return array
	 */
	protected function getMenuItemEntry($page, $rootLine) {
		$getLL = $GLOBALS['TSFE']->sys_language_uid;
		$pageUid = $page['uid'];
		if ($this->arguments['useShortcutData'] && $page['doktype'] == constant('t3lib_pageSelect::DOKTYPE_SHORTCUT')) {
			// first, ensure the complete data array is present based on the shortcut page's data
			$page = $this->pageSelect->getPage($pageUid);
			switch ($page['shortcut_mode']) {
				case 3:
					// mode: parent page of current or selected page
					if ($page['shortcut'] > 0) {
						// start off by overwriting $page with specifically chosen page
						$page = $this->pageSelect->getPage($page['shortcut']);
					}
					// overwrite page with parent page data
					$page = $this->pageSelect->getPage($page['pid']);
					$pageUid = $page['uid'];
					break;
				case 2:
					// mode: random subpage of selected or current page
					$menu = $this->pageSelect->getMenu($page['shortcut'] > 0 ? $page['shortcut'] : $pageUid);
					$randomKey =
					$page = count($menu) > 0 ? $menu[rand(0, count($menu) - 1)] : $page;
					$pageUid = $page['uid'];
					break;
				case 1:
					// mode: first subpage of selected or current page
					$menu = $this->pageSelect->getMenu($page['shortcut'] > 0 ? $page['shortcut'] : $pageUid);
					// note: if menu does not contain items, let TYPO3 linking take care of shortcut handling
					$page = count($menu) > 0 ? $menu[0] : $page;
					$pageUid = $page['uid'];
					break;
				case 0:
				default:
					$page = $this->pageSelect->getPage($page['shortcut']);
					$pageUid = $page['uid'];
			}
		}
		$doktype = $page['doktype'];
		if ($getLL){
			$pageOverlay = $this->pageSelect->getPageOverlay($pageUid, $getLL);
			foreach ($pageOverlay as $name => $value) {
				if (empty($value) === FALSE) {
					$page[$name] = $value;
				}
			}
		} else {
			$page = $this->pageSelect->getPage($pageUid);
		}
		$title = $page['title'];
		$titleFieldList = t3lib_div::trimExplode(',', $this->arguments['titleFields']);
		foreach ($titleFieldList as $titleFieldName) {
			if (empty($page[$titleFieldName]) === FALSE) {
				$title = $page[$titleFieldName];
				break;
			}
		}
		$shortcut = ($doktype == constant('t3lib_pageSelect::DOKTYPE_SHORTCUT')) ? $page['shortcut'] : $page['url'];
		$page['doktype'] = (integer) $doktype;

		if ($doktype == 3) {
			$urlTypes = array(
				'1' => 'http://',
				'4' => 'https://',
				'2' => 'ftp://',
				'3' => 'mailto:'
			);
			$page['link'] = $urlTypes[$page['urltype']] . $page['url'];
		}

		return $page;
	}

	/**
	 * Get a list from allowed doktypes for pages
	 *
	 * @return array
	 */
	protected function allowedDoktypeList() {
		if (TRUE === isset($this->arguments['doktypes']) && FALSE === empty($this->arguments['doktypes'])) {
			if (TRUE === is_array($this->arguments['doktypes'])) {
				$types = $this->arguments['doktypes'];
			} else {
				$types = t3lib_div::trimExplode(',', $this->arguments['doktypes']);
			}
			foreach ($types as $index => $type) {
				if (FALSE === ctype_digit($type)) {
					$types[$index] = constant('t3lib_pageSelect::DOKTYPE_' . strtoupper($type));
				}
			}
		} else {
			$types = array(
				constant('t3lib_pageSelect::DOKTYPE_DEFAULT'),
				constant('t3lib_pageSelect::DOKTYPE_LINK'),
				constant('t3lib_pageSelect::DOKTYPE_SHORTCUT'),
				constant('t3lib_pageSelect::DOKTYPE_MOUNTPOINT')
			);
		}
		if ($this->arguments['includeSpacers'] && FALSE === in_array(constant('t3lib_pageSelect::DOKTYPE_SPACER'), $types)) {
			array_push($types, constant('t3lib_pageSelect::DOKTYPE_SPACER'));
		}
		return $types;
	}

	/**
	 * Filter the fetched menu according to visibility etc.
	 *
	 * @param array $menu
	 * @param array $rootLine
	 * @return array
	 */
	protected function parseMenu($menu, $rootLine) {
		$filtered = array();
		$allowedDocumentTypes = $this->allowedDoktypeList();
		foreach ($menu as $page) {
			if ($page['hidden'] == 1) {
				continue;
			} elseif ($page['nav_hide'] == 1 && $this->arguments['showHidden'] < 1) {
				continue;
			} elseif (TRUE === isset($page['tx_realurl_exclude']) && $page['tx_realurl_exclude'] == 1 && $this->arguments['resolveExclude'] == 1) {
				continue;
			} elseif (TRUE === isset($page['tx_cooluri_exclude']) && $page['tx_cooluri_exclude'] == 1 && $this->arguments['resolveExclude'] == 1) {
				continue;
			} elseif ($page['l18n_cfg'] == 1 && $GLOBALS['TSFE']->sys_language_uid == 0) {
				continue;
			} elseif ($page['l18n_cfg'] == 2 && $GLOBALS['TSFE']->sys_language_uid != 0) {
				continue;
			} elseif (in_array($page['doktype'], $allowedDocumentTypes)) {
				$page = $this->getMenuItemEntry($page, $rootLine);
				$filtered[] = $page;
			}
		}
		return $filtered;
	}

	/**
	 * Returns array of page UIDs from provided pages
	 * argument or NULL if not processable
	 *
	 * @return array
	 */
	public function processPagesArgument() {
		$pages = $this->arguments['pages'];
		if ($pages instanceof Traversable) {
			$pages = iterator_to_array($pages);
		} elseif (is_string($pages)) {
			$pages = t3lib_div::trimExplode(',', $pages, TRUE);
		}
		if (FALSE === is_array($pages)) {
			return array();
		}

		return $pages;
	}

	/**
	 * @param string
	 * @param Tx_Extbase_MVC_View_ViewInterface $view
	 *
	 * @return mixed
	 */
	public function renderPage($uid) {
		$row = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('*', 'pages', 'uid=' . $uid);
		$row = $row[0];

		$providers = $this->providerConfigurationService->resolveConfigurationProviders('pages', 'tx_fed_page_flexform', $row);
		//$priority = 0;
		/** @var $pageConfigurationProvider Tx_Fluidpages_Provider_PageConfigurationProvider */
		$pageConfigurationProvider = NULL;
		foreach ($providers as $provider) {
			if ($provider->getPriority($row) >= $priority) {
				$pageConfigurationProvider = $provider;
			}
		}
		if (NULL === $pageConfigurationProvider) {
			throw new Exception('Unable to resolve the PageConfigurationProvider - this is grave error and indicates that EXT:fluidpages is broken', 1358693007);
		}

		$paths = $pageConfigurationProvider->getTemplatePaths($row);
		$flexformData = $pageConfigurationProvider->getFlexFormValues($row);
		$templatePathAndFilename = $provider->getTemplatePathAndFilename($row);

		$view = $this->objectManager->create('Tx_Fluid_View_StandaloneView');
		$view->setFormat('html');
		$view->setTemplatePathAndFilename($templatePathAndFilename);
		$view->setLayoutRootPath($paths['layoutRootPath']);
		$view->setPartialRootPath($paths['partialRootPath']);
		$view->assignMultiple($flexformData);
		$view->assign('page', $row);

		$output = $view->render();

		return $output;

	}

	/**
	 * Render method
	 *
	 * @return mixed
	 */
	public function render() {
		if (TYPO3_MODE == 'BE') {
			return '';
		}
		$sectionTag = $this->arguments['sectionTag'];
		$hashPrefix = $this->arguments['hashPrefix'];
		$hashField = $this->arguments['hashField'];
		$pages = $this->processPagesArgument();
		if (NULL === $pages) return;

		$menuData = array();
		$rootLineData = $this->pageSelect->getRootLine($GLOBALS['TSFE']->id);
		foreach ($pages as $pageUid) {
			$menuData = array_merge($menuData, $this->pageSelect->getMenu($pageUid));
		}
		$menu = $this->parseMenu($menuData, $rootLineData);

		$output = '';
		if (count($menu) > 0) {
			foreach ($menu as $menuItem) {
				$id = $hashPrefix . $menuItem[$hashField];
				//$output .= '<' . $sectionTag . ' id="' . $id . '">' . $this->getContentRecords($menuItem['uid']) .'</' . $sectionTag . '>';

				$content = $this->renderPage($menuItem['uid']);
				$output .= '<' . $sectionTag . ' id="' . $id . '">' . $content .'</' . $sectionTag . '>';
				//\TYPO3\CMS\Core\Utility\DebugUtility::debug($curPage);
			}
		}

		return $output;
	}

}