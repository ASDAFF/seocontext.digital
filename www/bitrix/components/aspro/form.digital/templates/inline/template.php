<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?$this->setFrameMode(false);?>
<div class="form inline<?=($arResult['isFormNote'] == 'Y' ? ' success' : '')?><?=($arResult['isFormErrors'] == 'Y' ? ' error' : '')?>">
	<?if($arResult["isFormNote"] == "Y"){?>
		<div class="form-header">
			<div class="text">
				<div class="title"><?=GetMessage("SUCCESS_TITLE")?></div>
				<?=$arResult["FORM_NOTE"]?>
			</div>
		</div>
		<script>
			if(arDigitalOptions['THEME']['USE_FORMS_GOALS'] !== 'NONE')
			{
				var eventdata = {goal: 'goal_webform_success' + (arDigitalOptions['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arParams["IBLOCK_ID"]?>'), params: <?=CUtil::PhpToJSObject($arParams, false)?>};
				BX.onCustomEvent('onCounterGoals', [eventdata]);
			}
		</script>
		<?if( $arParams["DISPLAY_CLOSE_BUTTON"] == "Y" ){?>
			<div class="form-footer" style="text-align: center;">
				<?=str_replace('class="', 'class="btn-lg ', $arResult["CLOSE_BUTTON"])?>
			</div>
		<?}
	}else{?>
		<?=$arResult["FORM_HEADER"]?>
			<div class="form-header">
				<div class="text">
					<?if( $arResult["isIblockTitle"] ){?>
						<div class="title"><?=$arResult["IBLOCK_TITLE"]?></div>
					<?}?>
					<?if( $arResult["isIblockDescription"] ){
						if( $arResult["IBLOCK_DESCRIPTION_TYPE"] == "text" ){?>
							<p><?=$arResult["IBLOCK_DESCRIPTION"]?></p>
						<?}else{?>
							<?=$arResult["IBLOCK_DESCRIPTION"]?>
						<?}
					}?>
				</div>
			</div>
			<?if($arResult['isFormErrors'] == 'Y'):?>
				<div class="form-error alert alert-danger">
					<?=$arResult['FORM_ERRORS_TEXT']?>
				</div>
			<?endif;?>
			<div class="form-body">
				<?if(is_array($arResult["QUESTIONS"])):?>
					<?foreach( $arResult["QUESTIONS"] as $FIELD_SID => $arQuestion ){
						if( $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden' ){
							echo $arQuestion["HTML_CODE"];
						}else{?>
							<div class="row" data-SID="<?=$FIELD_SID?>">
								<div class="col-md-12">
									<div class="form-group <?=( $arQuestion['FIELD_TYPE'] != "file" ? "animated-labels" : "");?> <?=( $arQuestion['VALUE'] ? "input-filed" : "");?>">
										<?=$arQuestion["CAPTION"]?>
										<div class="input">
											<?=$arQuestion["HTML_CODE"]?>
										</div>
										<?if( !empty( $arQuestion["HINT"] ) ){?>
											<div class="hint"><?=$arQuestion["HINT"]?></div>
										<?}?>
									</div>
								</div>
							</div>
						<?}
					}?>
				<?endif;?>
				<?if( $arResult["isUseCaptcha"] == "Y" ){?>
					<div class="row captcha-row">
						<div class="col-md-8 col-sm-8 col-xs-8">
							<div class="form-group animated-labels">
								<?=$arResult["CAPTCHA_CAPTION"]?>
								<div class="input <?=$arResult["CAPTCHA_ERROR"] == "Y" ? "error" : ""?>">
									<?=$arResult["CAPTCHA_FIELD"]?>
								</div>
							</div>
						</div>
						<div class="col-md-4 col-sm-4 col-xs-4">
							<div class="form-group">
								<div class="captcha-img">
									<?=$arResult["CAPTCHA_IMAGE"]?>
									<span class="refresh"><a href="javascript:;" rel="nofollow"><?=GetMessage("REFRESH")?></a></span>
								</div>
							</div>
						</div>
					</div>
				<?}?>
			</div>
			<div class="form-footer clearfix">
				<?if($arParams["SHOW_LICENCE"] == "Y"):?>
					<div class="licence_block bx_filter">
						<input type="checkbox" id="licenses" name="licenses" required value="Y">
						<label for="licenses">
							<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
						</label>
					</div>
				<?endif;?>
				<div class="">
					<?=str_replace('class="', 'class="btn-lg ', $arResult["SUBMIT_BUTTON"])?>
				</div>
			</div>
		<?=$arResult["FORM_FOOTER"]?>
	<?}?>
</div>

<script>
	$(document).ready(function(){
		$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"]').validate({
			highlight: function( element ){
				$(element).parent().addClass('error');
			},
			unhighlight: function( element ){
				$(element).parent().removeClass('error');
			},
			submitHandler: function( form ){
				if( $('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"]').valid() ){
					$(form).find('button[type="submit"]').attr('disabled', 'disabled');
					form.submit();
				}
			},
			errorPlacement: function( error, element ){
				error.insertBefore(element);
			},
			messages:{
		      licenses: {
		        required : BX.message('JS_REQUIRED_LICENSES')
		      }
			}
		});

		if(arDigitalOptions['THEME']['PHONE_MASK'].length){
			var base_mask = arDigitalOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
			$('.inline form[name="<?=$arResult['IBLOCK_CODE']?>"] input.phone').inputmask('mask', {'mask': arDigitalOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
			$('.inline form[name="<?=$arResult['IBLOCK_CODE']?>"] input.phone').blur(function(){
				if( $(this).val() == base_mask || $(this).val() == '' ){
					if( $(this).hasClass('required') ){
						$(this).parent().find('div.error').html(BX.message('JS_REQUIRED'));
					}
				}
			});
		}

		if(arDigitalOptions['THEME']['DATE_MASK'].length)
			$('.inline form[name="<?=$arResult["IBLOCK_CODE"]?>"] input.date').inputmask(arDigitalOptions['THEME']['DATE_MASK'], { 'placeholder': arDigitalOptions['THEME']['DATE_PLACEHOLDER'], 'showMaskOnHover': false });

		$('.jqmClose').closest('.jqmWindow').jqmAddClose('.jqmClose');

		$('input[type=file]').uniform({fileButtonHtml: BX.message('JS_FILE_BUTTON_NAME'), fileDefaultHtml: BX.message('JS_FILE_DEFAULT')});
	});
</script>