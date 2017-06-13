<?php
$EM_CONF[$_EXTKEY] = [
	'title'            => 'Streit Viewhelper',
	'description'      => 'Sammlung von ViewHelpern für Streit GmbH',
	'category'         => 'misc',
	'author'           => 'Sebastian Wolfertz',
	'author_email'     => 'S.Wolfertz@Streit-Online.de',
	'author_company'   => 'Streit GmbH',
	'state'            => 'alpha',
	'private'          => true,
	'uploadfolder'     => '0',
	'createDirs'       => '',
	'clearCacheOnLoad' => 0,
	'version'          => '0.1.0',
	'constraints'      => [
		'depends'   => [
			'typo3' => '8.7.0-8.7.99',
		],
		'conflicts' => [],
		'suggests'  => [],
	],
];
