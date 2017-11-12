<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Модуль 'местоположения");
?><?$APPLICATION->IncludeComponent(
	"seocontext:locations",
	"",
	Array(
		"CACHE_TIME" => "36000",
		"CACHE_TYPE" => "A",
		"RELOAD_PAGE" => "N"
	)
);?><?$APPLICATION->IncludeComponent(
	"seocontext:cond.include",
	".default",
	Array(
		"ALL_ON_PAGE" => "Y",
		"CACHE_TIME" => "36000",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"CONTENT" => "",
		"INCLUDE_ID" => "includeArea"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>