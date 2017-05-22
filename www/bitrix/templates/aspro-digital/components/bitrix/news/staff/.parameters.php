<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => array(
		'PARENT' => 'LIST_SETTINGS',
		'SORT' => 500,
		'NAME' => GetMessage('SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
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
