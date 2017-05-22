<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");?>
<?$APPLICATION->IncludeComponent(
	"aspro:basket.digital", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"PATH_TO_CATALOG" => SITE_DIR."product/"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>