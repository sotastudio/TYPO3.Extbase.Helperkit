<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "helperkit".
 *
 * Auto generated 02-08-2013 13:28
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Helperkit for Fluid and Extbase Development.',
	'description' => 'An extension providing a set of Fluid ViewHelper and Extbase Utilities.',
	'category' => 'misc',
	'shy' => 0,
	'version' => '2.1.1',
	'dependencies' => 'extbase,fluid',
	'conflicts' => NULL,
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 1,
	'lockType' => '',
	'author' => 'Andy Hausmann',
	'author_email' => 'ah@sota-studio.de',
	'author_company' => 'sota studio',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '6.0-6.0.99',
			'extbase' => '1.3',
			'fluid' => '1.3',
		),
		'conflicts' => '',
		'suggests' => 
		array (
			'flux' => '',
			'fluidpages' => '',
			'vhs' => '',
		),
	),
	'suggests' => 
	array (
	),
);

?>