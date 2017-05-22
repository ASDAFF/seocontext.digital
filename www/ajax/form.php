<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
if(\Bitrix\Main\Loader::includeModule('aspro.digital'))
	$arTheme = CDigital::GetFrontParametrsValues(SITE_ID);
$id = (isset($_REQUEST["id"]) ? $_REQUEST["id"] : false);

$isCallBack = $id == CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0];
$successMessage = ($isCallBack ? "<p>Наш менеджер перезвонит вам в ближайшее время.</p><p>Спасибо за ваше обращение!</p>" : "Спасибо! Ваше сообщение отправлено!");
?>
<span class="jqmClose top-close fa fa-close"></span>
<?if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'review'):?>
	<?include_once('review.php');?>
<?elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'auth'):?>
	<?include_once('auth.php');?>
<?elseif($id):?>
	<?$APPLICATION->IncludeComponent(
		"aspro:form.digital", "popup",
		Array(
			"IBLOCK_TYPE" => "aspro_digital_form",
			"IBLOCK_ID" => $id,
			"AJAX_MODE" => "Y",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "N",
			"AJAX_OPTION_HISTORY" => "N",
			"SHOW_LICENCE" => $arTheme["SHOW_LICENCE"],
			"LICENCE_TEXT" => $arTheme["LICENCE_TEXT"],
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "100000",
			"AJAX_OPTION_ADDITIONAL" => "",
			//"IS_PLACEHOLDER" => "Y",
			"SUCCESS_MESSAGE" => $successMessage,
			"SEND_BUTTON_NAME" => "Отправить",
			"SEND_BUTTON_CLASS" => "btn btn-default",
			"DISPLAY_CLOSE_BUTTON" => "Y",
			"POPUP" => "Y",
			"CLOSE_BUTTON_NAME" => "Закрыть",
			"CLOSE_BUTTON_CLASS" => "jqmClose btn btn-default bottom-close"
		)
	);?>
<?endif;?>