<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<div class="module-form-block-wr registraion-page">
	<?if($USER->IsAuthorized()){?>
		<p><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>
	<?}else{?>
		<?if (count($arResult["ERRORS"]) > 0){
			foreach ($arResult["ERRORS"] as $key => $error)
				if (intval($key) == 0 && $key !== 0) 
					$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

			ShowError(implode("<br />", $arResult["ERRORS"]));
		}elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"){?>
			
		<?}?>
	<?}?>

	<?if( empty($arResult["ERRORS"]) && !empty($_POST["register_submit_button"]) && $arResult["USE_EMAIL_CONFIRMATION"]=="N"){
		LocalRedirect(SITE_DIR.'personal/');
	}elseif( empty($arResult["ERRORS"]) && !empty($_POST["register_submit_button"]) && $arResult["USE_EMAIL_CONFIRMATION"]=="Y"){?>
		<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
	<?}else{?>
		<div class="form border_block">
			<div class="wrap_md">
				<div class="main_info iblock max-form-block">
					<div class="top">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/register_description.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("REGISTER_INCLUDE_AREA"), ));?>
					</div>
					<script>
						$(document).ready(function(){
							$.validator.addClassRules({
								'phone_input':{
									regexp: arDigitalOptions['THEME']['VALIDATE_PHONE_MASK']
								}
							})
							$("form#registraion-page-form").validate
							({
								rules:{ emails: "email"},
								messages: {
									"captcha_word": {
										remote: '<?=GetMessage("VALIDATOR_CAPTCHA")?>'
									},
								},
								submitHandler: function( form ){
									$('input#input_LOGIN').val($('input#input_EMAIL').val());
										form.submit();
									if( $("form#registraion-page-form").valid() ){
										setTimeout(function() {
											//$(form).find('button[type="submit"]').attr("disabled", "disabled");
										}, 300);
									}
								},
								errorPlacement: function( error, element ){
									error.insertBefore(element);
								}
							});
							$("form[name=bx_auth_servicesform_inline]").validate();

							if(arDigitalOptions['THEME']['PHONE_MASK'].length){
								var base_mask = arDigitalOptions['THEME']['PHONE_MASK'].replace( /(\d)/g, '_' );
								$('form#registraion-page-form input.phone_input').inputmask('mask', {'mask': arDigitalOptions['THEME']['PHONE_MASK'], 'showMaskOnHover': false });
								$('form#registraion-page-form input.phone_input').blur(function(){
									if( $(this).val() == base_mask || $(this).val() == '' ){
										if( $(this).hasClass('required') ){
											$(this).parent().find('label.error').html(BX.message('JS_REQUIRED'));
										}
									}
								});
							}
						})
					</script>
					
					<form id="registraion-page-form" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data" >					
						<?if($arResult["BACKURL"] <> ''):?>
							<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
						<?endif;?>
						<input type="hidden" name="register_submit_button" value="reg" />
						<?
						$arTmpField=$arFields=$arUFields=array();
						$arTmpField=array_combine($arResult['SHOW_FIELDS'], $arResult['SHOW_FIELDS']);
						unset($arTmpField["PASSWORD"]);
						unset($arTmpField["CONFIRM_PASSWORD"]);

						if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"){
							foreach($arParams["USER_PROPERTY"] as $name){
								$arUFields[$name]=$arResult["USER_PROPERTIES"]["DATA"][$name];
							}
						}

						if($arParams["SHOW_FIELDS"]){
							foreach($arParams["SHOW_FIELDS"] as $name){
								$arFields[$arTmpField[$name]]=$name;
							}
						}else{
							$arFields=$arTmpField;
						}
						$arFields["PASSWORD"]="PASSWORD";
						$arFields["CONFIRM_PASSWORD"]="CONFIRM_PASSWORD";
						$arFields["LOGIN"]="LOGIN";
						$class = "form-control bg-color";

						global $arTheme;
						if($arTheme['CABINET']['DEPENDENT_PARAMS']["PERSONAL_ONEFIO"]["VALUE"] != "N")
						{
							unset($arFields["LAST_NAME"]);
							unset($arFields["SECOND_NAME"]);
						}
						?>
						<?foreach ($arFields as $FIELD):?>
							<?if( $FIELD != "LOGIN" ){?>
								<div class="form-group animated-labels bg-color <?=($arResult["VALUES"][$FIELD] ? 'input-filed' : '');?>">
									<div class="wrap_md">
										<div class="iblock label_block">
							<?}?>
										<?if( $FIELD != "LOGIN" ):?>
											<label for="input_<?=$FIELD;?>"><?=(($arTheme['CABINET']['DEPENDENT_PARAMS']["PERSONAL_ONEFIO"]["VALUE"] != "N" && $FIELD == "NAME") ? GetMessage("REGISTER_FIELD_ONENAME") : GetMessage("REGISTER_FIELD_".$FIELD));?> <?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="required-star">*</span><?endif;?></label>
										<?endif;?>
										<?if( array_key_exists( $FIELD, $arResult["ERRORS"] ) ):?>
											<?$class.=' error'?>
										<?endif;?>
										<div class="input">
										<?switch ($FIELD){
											case "PASSWORD":?>
												<input size="30" type="password" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="form-control bg-color password <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>"  />
												
											<?break;
											case "CONFIRM_PASSWORD":?>
												<input size="30" type="password" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" required value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="form-control bg-color confirm_password <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>" />
											
											<?break;
											case "PERSONAL_GENDER":?>
												<select name="REGISTER[<?=$FIELD?>]" id="input_<?=$FIELD;?>">
													<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
													<option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
													<option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
												</select>
												<?break;
											case "PERSONAL_COUNTRY":
											case "WORK_COUNTRY":?>
												<select name="REGISTER[<?=$FIELD?>]" id="input_<?=$FIELD;?>">
													<?foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value){?>
														<option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
													<?}?>
												</select>
												<?break;
											case "PERSONAL_PHOTO":
											case "WORK_LOGO":?>
												<input size="30" type="file" class="form-control bg-color" id="input_<?=$FIELD;?>" name="REGISTER_FILES_<?=$FIELD?>" />
												<?break;
											case "PERSONAL_NOTES":
											case "WORK_NOTES":?>
												<textarea cols="30" rows="5" class="form-control bg-color" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>
												
											<?case "PERSONAL_STREET":?>
												<textarea cols="30" rows="5" class="form-control bg-color" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea>
												<?break;?>
											<?case "EMAIL":?>
												<?//print_r($arResult);?>
												<input size="30" type="email" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" <?=($arResult["EMAIL_REQUIRED"] || in_array($FIELD, $arResult["REQUIRED_FIELDS"]) ? "required" : "");?> value="<?=$arResult["VALUES"][$FIELD]?>" class="<?=$class?>" id="emails"/>
											<?break;?>
											<?case "NAME":?>
												<input size="30" type="text" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? "required": "");?> value="<?=$arResult["VALUES"][$FIELD]?>" class="<?=$class?>"/>                              
											<?break;?>
											<?case "PERSONAL_PHONE":?>
												<input size="30" type="text" id="input_<?=$FIELD;?>" name="REGISTER[<?=$FIELD?>]" class="form-control bg-color phone_input <?=(array_key_exists( $FIELD, $arResult["ERRORS"] ))? 'error': ''?>" <?=($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y" ? "required": "");?> value="<?=$arResult["VALUES"][$FIELD]?>" />
											<?break;?>	
											<?break;
											default:?>
												<?// hide login?>
												<input size="30" class="form-control bg-color" id="input_<?=$FIELD;?>" <?=$FIELD == "LOGIN" ? 'type="hidden" value="1"' : 'type="text"'?> name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" />
												<?if ($FIELD == "PERSONAL_BIRTHDAY"){?>
													<?$APPLICATION->IncludeComponent(
														'bitrix:main.calendar',
														'',
														array(
															'SHOW_INPUT' => 'N',
															'FORM_NAME' => 'regform',
															'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
															'SHOW_TIME' => 'N'
														),
														null,
														array("HIDE_ICONS"=>"Y")
													);?>
												<?}?>
												<?break;?>
										<?}?>
										</div>
										<?if( $FIELD != "LOGIN" && array_key_exists( $FIELD, $arResult["ERRORS"] ) ):?>
											<label class="error"><?=GetMessage("REGISTER_FILL_IT")?></label>
										<?endif;?>
							<?if( $FIELD != "LOGIN" ){?>
										</div>
										<div class="iblock text_block">
											<?=GetMessage("REGISTER_FIELD_TEXT_".$FIELD);?>
										</div>
									</div>
								</div>
							<?}?>
						<?endforeach?>
						<?if($arUFields){?>
							<?foreach($arUFields as $arUField){?>
								<div class="r">				
									<label><?=$arUField["EDIT_FORM_LABEL"];?>:<?if ($arUField["MANDATORY"] == "Y"):?><span class="star">*</span><?endif;?></label>
									<?$APPLICATION->IncludeComponent(
									"bitrix:system.field.edit",
									$arUField["USER_TYPE"]["USER_TYPE_ID"],
									array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUField, "form_name" => "regform"), null, array("HIDE_ICONS"=>"Y"));?>
								</div>
							<?}?>
						<?}?>
						<?if ($arResult["USE_CAPTCHA"] == "Y"){?>
							<div class="row form-group animated-labels register-captcha captcha-row">
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="form-group animated-labels bg-color">
										<label for="captcha_word"><?=GetMessage("REGISTER_CAPTCHA_PROMT")?> <span class="required-star">*</span></label>
										<div class="input">
											<input type="text" name="captcha_word" id="captcha_word" class="form-control bg-color captcha required" maxlength="50" value="" tabindex="3"/>
										</div>
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">
									<div class="form-group">
										<div class="captcha-img">
											<input type="hidden" name="captcha_sid" class="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
											<img class="captcha_img" src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
											<span class="refresh"><a href="javascript:;" rel="nofollow"><?=GetMessage("RELOAD")?></a></span>
										</div>
									</div>
								</div>

							</div>
						<?}?>
						<div class="but-r">
							<button class="btn btn-default btn-lg bold register" type="submit" name="register_submit_button1" value="<?=GetMessage("AUTH_REGISTER")?>">
								<?=GetMessage("REGISTER_REGISTER")?>
							</button>
							<div class="clearboth"></div>
						</div>				
					</form>
				</div>
				<div class="social_block iblock">
					<?$APPLICATION->IncludeComponent(
						"bitrix:system.auth.form",
						"digital",
						array(
							"PROFILE_URL" => $arParams["PATH_TO_PERSONAL"],
							"SHOW_ERRORS" => "Y",
							"POPUP_AUTH" => "Y"
						)
					);?>
				</div>
			</div>
		</div>
	<?}?>
</div>