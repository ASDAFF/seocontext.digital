<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'T_REVIEWS' => array(
		'SORT' => 702,
		'NAME' => GetMessage('T_REVIEWS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_DOCS' => array(
		'SORT' => 703,
		'NAME' => GetMessage('T_DOCS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'T_PROJECTS' => array(
		'SORT' => 704,
		'NAME' => GetMessage('T_PROJECTS'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
);

$arTemplateParameters['IMAGE_POSITION'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'SORT' => 250,
	'NAME' => GetMessage('IMAGE_POSITION'),
	'TYPE' => 'LIST',
	'VALUES' => array(
		'left' => GetMessage('IMAGE_POSITION_LEFT'),
		'right' => GetMessage('IMAGE_POSITION_RIGHT'),
	),
	'DEFAULT' => 'left',
);

$arTemplateParameters['COUNT_IN_LINE'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => GetMessage('COUNT_IN_LINE'),
	'TYPE' => 'STRING',
	'DEFAULT' => '3',
);
?>