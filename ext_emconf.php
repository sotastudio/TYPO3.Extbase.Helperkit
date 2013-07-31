<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "helperkit".
 *
 * Auto generated 31-07-2013 19:22
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
	'version' => '2.1.0',
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
	'author' => 'Andreas Walter',
	'author_email' => 'aw@sota-studio.de',
	'author_company' => 'sota studio',
	'CGLcompliance' => NULL,
	'CGLcompliance_note' => NULL,
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '6.0-0.0.0',
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