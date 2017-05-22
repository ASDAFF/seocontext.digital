<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Заполнение формы");
?>
<style type="text/css">
section.page-top {display:none;}
.form.inline{margin-top:15px;}
.form .form-header{border-top: 1px solid #f5f5f5 !important;;padding: 15px 0px 9px 0px;}
</style>
<?
if(\Bitrix\Main\Loader::includeModule('aspro.digital'))
	$arTheme = CDigital::GetFrontParametrsValues(SITE_ID);

$id = (isset($_REQUEST["id"]) ? $_REQUEST["id"] : false);
$isCallBack = $id == CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0];
$successMessage = ($isCallBack ? "<p>Наш менеджер перезвонит вам в ближайшее время.</p><p>Спасибо за ваше обращение!</p>" : "Спасибо! Ваше сообщение отправлено!");
$arDataTrigger = json_decode((isset($_REQUEST["data-trigger"]) ? $_REQUEST["data-trigger"] : '{}'), true); // allways UTF-8
?>
<?if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'review'):?>
	<?include_once('../ajax/review.php');?>
<?elseif(isset($_REQUEST['type']) && $_REQUEST['type'] == 'auth'):?>
	<?include_once('../ajax/auth.php');?>
<?elseif($id):?>
	<?$APPLICATION->IncludeComponent(
		"aspro:form.digital", "inline",
		Array(
			"IBLOCK_TYPE" => "#IBLOCK_DIGITAL_FORM_TYPE#",
			"IBLOCK_ID" => $id,
			"USE_CAPTCHA" => $captcha,
			"AJAX_MODE" => "Y",
			"AJAX_OPTION_JUMP" => "Y",
			"AJAX_OPTION_STYLE" => "Y",
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
			"DISPLAY_CLOSE_BUTTON" => "N",
			"CLOSE_BUTTON_NAME" => "Закрыть",
			"CLOSE_BUTTON_CLASS" => "jqmClose btn btn-default bottom-close",
		)
	);?>
	<?if($arDataTrigger && strlen($name)):?>
		<script type="text/javascript">
		var name = '<?=$name?>';
		var arTriggerAttrs = <?=json_encode($arDataTrigger)?>;
		$(document).ready(function() {
			$.each(arTriggerAttrs, function(index, val){
				if( /^data\-autoload\-(.+)$/.test(index)){
					var key = index.match(/^data\-autoload\-(.+)$/)[1];
					var el = $('input[name="'+key.toUpperCase()+'"]');
					if(el.closest('.form-group').length)
						el.closest('.form-group').addClass('input-filed');
					el.val(val).attr('readonly', 'readonly').attr('title', val);
				}
			});
			
			if(name == 'order_product'){
				if(arTriggerAttrs['data-product'].length){
					$('input[name="PRODUCT"]').val(arTriggerAttrs['data-product']).attr('readonly', 'readonly').attr('title', arTriggerAttrs['data-product']);
				}
			}
		});
		</script>
	<?endif;?>
<?else:?>
	<div class="alert alert-warning">Не указан ID формы</div>
	<?CDigital::goto404Page();?>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>