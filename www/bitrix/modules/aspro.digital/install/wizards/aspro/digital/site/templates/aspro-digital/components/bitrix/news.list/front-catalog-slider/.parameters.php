<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'ORDER_VIEW' => array(
		'SORT' => 100,
		'NAME' => GetMessage('ORDER_VIEW'),
		'TYPE' => 'STRING',
		'DEFAULT' => '$bOrderViewBasket',
	),
	'TITLE' => array(
		'NAME' => GetMessage('TITLE'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('TITLE_DEFAULT'),
	),
	'HIT_PROP' => array(
		'NAME' => GetMessage('HIT_PROP'),
		'TYPE' => 'STRING',
		'DEFAULT' => 'HIT',
	),
	'S_ORDER_PRODUCT' => array(
		'NAME' => GetMessage('S_ORDER_PRODUCT'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('S_ORDER_PRODUCT_TEXT'),
	),
	'S_MORE_PRODUCT' => array(
		'NAME' => GetMessage('S_MORE_PRODUCT'),
		'TYPE' => 'STRING',
		'DEFAULT' => GetMessage('S_MORE_PRODUCT_TEXT'),
	),
);
?>