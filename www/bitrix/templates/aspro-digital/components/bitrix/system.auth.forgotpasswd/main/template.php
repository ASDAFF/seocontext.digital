<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(isset($APPLICATION->arAuthResult))
	$arResult['ERROR_MESSAGE'] = $APPLICATION->arAuthResult;?>

<div class="border_block">
	<div class="module-form-block-wr lk-page form">
		<?ShowMessage($arResult['ERROR_MESSAGE']);?>
		<div class="form-block">
			<form name="bform" method="post" target="_top" class="bf" action="<?=$arParams["URL"];?>">
				<?if (strlen($arResult["BACKURL"]) > 0){?><input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" /><?}?>
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="SEND_PWD">
				<div class="top-text-block"><?=GetMessage("AUTH_FORGOT_PASSWORD_1")?></div>
				<div class="max-form-block">
					<div class="form-group animated-labels input-filed bg-color">
						<label for="USER_EMAIL"><?=GetMessage("AUTH_EMAIL")?> <span class="required-star">*</span></label>
						<div class="input">
							<input type="email" class="form-control bg-color required" name="USER_EMAIL" id="USER_EMAIL" maxlength="255" />
						</div>
					</div>	

					<div class="but-r">
						<button class="btn btn-default btn-lg bold" type="submit" name="send_account_info" value=""><span><?=GetMessage("RETRIEVE")?></span></button>
					</div>
				</div>
			</form>			
		</div>
		<script type="text/javascript">
			document.bform.USER_EMAIL.focus();
			$("form.bf").validate({rules:{ EMAIL: { email: true }}	});
		</script>
	</div>
</div>