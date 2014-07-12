<?php

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Helperkit for Fluid and Extbase Development.',
	'description' => 'An extension providing a set of Fluid ViewHelper and Extbase Utilities.',
	'category' => 'misc',
	'version' => '2.4.0',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'clearCacheOnLoad' => 1,
	'author' => 'Andy Hausmann',
	'author_email' => 'ah@sota-studio.de',
	'author_company' => 'SOTA Studio',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.1.0-6.2.99',
			'cms' => '',
			'vhs' => '',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
);