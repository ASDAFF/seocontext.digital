<?$isAjax = (((isset($_POST["itemData"]) || (isset($_POST["ajaxPost"]) && $_POST["ajaxPost"] == "Y")) && $_SERVER["REQUEST_METHOD"] == "POST") || ((isset($_GET["itemData"]) || isset($_GET["remove"])) && $_SERVER["REQUEST_METHOD"] == "GET"));?>
<?if($isAjax):?>
	<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
	<?\Bitrix\Main\Loader::includeModule('aspro.digital');

	$arTheme = CDigital::GetFrontParametrsValues(SITE_ID);
	$arBasketItems = CDigital::processBasket();

	$template = strtolower($arTheme["ORDER_BASKET_VIEW"]);?>
<?else:?>
	<?$template = strtolower($arTheme["ORDER_VIEW"]["DEPENDENT_PARAMS"]["ORDER_BASKET_VIEW"]["VALUE"]);?>
	<!-- noindex -->
	<div class="ajax_basket">
<?endif;?>
	<?$APPLICATION->IncludeComponent(
		"aspro:basket.digital", 
		$template, 
		array(
			"COMPONENT_TEMPLATE" => $template,
			"NO_REDIRECT" => "Y",
			"CHECK_BASKET_URL" => "Y",
			"PATH_TO_CATALOG" => SITE_DIR."product/"
		),
		false, array("HIDE_ICONS" => "Y")
	);?>
<?if(!$isAjax):?>
	</div>
	<!-- /noindex -->
<?endif;?>