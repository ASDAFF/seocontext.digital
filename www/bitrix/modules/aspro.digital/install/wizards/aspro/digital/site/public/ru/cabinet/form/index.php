<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Написать директору");
?>
<?$APPLICATION->IncludeComponent(
	"aspro:form.digital", 
	"director", 
	array(
		"IBLOCK_TYPE" => "#IBLOCK_DIGITAL_FORM_TYPE#",
		"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_director"][0],
		"USE_CAPTCHA" => "Y",
		"IS_PLACEHOLDER" => "N",
		"SUCCESS_MESSAGE" => "<p>Спасибо! Ваше сообщение отправлено!</p>",
		"SEND_BUTTON_NAME" => "Отправить",
		"SEND_BUTTON_CLASS" => "btn btn-default",
		"DISPLAY_CLOSE_BUTTON" => "N",
		"CLOSE_BUTTON_NAME" => "Обновить страницу",
		"CLOSE_BUTTON_CLASS" => "btn btn-default refresh-page",
		"AJAX_MODE" => "Y",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "100000",
		"AJAX_OPTION_ADDITIONAL" => "",
		"COMPONENT_TEMPLATE" => "director",
		"CACHE_GROUPS" => "Y"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>