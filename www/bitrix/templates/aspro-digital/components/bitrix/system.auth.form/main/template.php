<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?/*<link rel="stylesheet" type="text/css" href="/bitrix/js/socialservices/css/ss.css">*/?>
<?if( $arResult["FORM_TYPE"] == "login" ){?>
	<div id="ajax_auth">
		<div class="auth_wrapp form-block">
			<div class="wrap_md">
				<div class="main_info form">
					<div class="form-wr form-body">
						<?if( $arResult["ERROR"] ){?>
							<div class="alert alert-danger">
								<p><?=GetMessage('AUTH_ERROR')?></p>
							</div>
						<?}?>
						<form id="avtorization-form" name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arParams["AUTH_URL"]?>?login=yes">
							<?if($arResult["BACKURL"] <> ''):?>
								<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
							<?endif?>
							<?foreach ($arResult["POST"] as $key => $value):?><input type="hidden" name="<?=$key?>" value="<?=$value?>" /><?endforeach?>
							<input type="hidden" name="AUTH_FORM" value="Y" />
							<input type="hidden" name="TYPE" value="AUTH" />

							<div class="row" data-sid="USER_LOGIN_POPUP">
								<div class="form-group animated-labels input-filed">
									<div class="col-md-12">
										<label for="USER_LOGIN_POPUP"><?=GetMessage("AUTH_LOGIN")?> <span class="required-star">*</span></label>
										<div class="input">
											<input type="text" name="USER_LOGIN" id="USER_LOGIN_POPUP" class="form-control required" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" autocomplete="on" tabindex="1"/>
										</div>
									</div>
								</div>
							</div>
							<div class="row" data-sid="USER_PASSWORD_POPUP">
								<div class="form-group animated-labels input-filed">
									<div class="col-md-12">
										<label for="USER_PASSWORD_POPUP"><?=GetMessage("AUTH_PASSWORD")?> <span class="required-star">*</span></label>
										<div class="input">
											<input type="password" name="USER_PASSWORD" id="USER_PASSWORD_POPUP" class="form-control required" maxlength="50" value="" autocomplete="on" tabindex="2"/>
										</div>
									</div>
								</div>
							</div>

							<?if ($arResult["CAPTCHA_CODE"]):?>
								<div class="row captcha-row">
									<div class="col-md-6 col-sm-6 col-xs-6">
										<div class="form-group animated-labels">
											<label for="captcha_word"><?=GetMessage("AUTH_CAPTCHA_PROMT")?> <span class="required-star">*</span></label>
											<div class="input">
												<input type="text" name="captcha_word" id="captcha_word" class="form-control captcha required" maxlength="50" value="" tabindex="3"/>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-sm-6 col-xs-6">
										<div class="form-group">
											<div class="captcha-img">
												<input type="hidden" name="captcha_sid" class="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
												<img class="captcha_img" src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
												<span class="refresh"><a href="javascript:;" rel="nofollow"><?=GetMessage("REFRESH")?></a></span>
											</div>
										</div>
									</div>
								</div>
							<?endif?>
							<div class="but-r">
								<div class="filter block">
									<a class="forgot pull-right" href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"];?>" tabindex="3"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a>
									<div class="prompt remember pull-left">
										<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" tabindex="5"/>
										<label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>" tabindex="5"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
									</div>
									<div class="clearfix"></div>
								</div>
								<div class="buttons clearfix">
									<button type="submit" class="btn btn-default bold" name="Login" value="" tabindex="4">
										<span><?=GetMessage("AUTH_LOGIN_BUTTON")?></span>
									</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			
			<script>
				$(document).ready(function()
				{
					$("form[name=bx_auth_servicesform]").validate(); 
					$('form#avtorization-form').validate({
						rules:{
							USER_LOGIN:{ 
								required:true 
							}
						},
						submitHandler: function( form ){
							if( $( form ).valid() ){
								jsAjaxUtil.CloseLocalWaitWindow( 'id', 'wrap_ajax_auth' );
								jsAjaxUtil.ShowLocalWaitWindow( 'id', 'wrap_ajax_auth', true );
								$.ajax({
									type: "POST",
									url: $(form).attr('action'),
									data: $(form).serialize()
								}).done(function( html ){
									console.log(location);
									if($(html).find('.alert').length)
									{
										$('#ajax_auth').html( html );
										jsAjaxUtil.CloseLocalWaitWindow( 'id', 'wrap_ajax_auth' );
									}
									else
										BX.reload(false);
								});
							}
						},
						errorPlacement: function( error, element ){
							$( error ).attr( 'alt', $( error ).text() );
							$( error ).attr( 'title', $( error ).text() );
							error.insertBefore( element );
						}
					} );
				})
			</script>
			
			<?if($arResult["AUTH_SERVICES"]):?>
				<div class="reg-new">
					<div class="soc-avt">
						<div class="title"><?=GetMessage("SOCSERV_AS_USER_FORM");?></div>
						<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons", 
							array(
								"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
								"AUTH_URL" => SITE_DIR."cabinet/?login=yes",
								"POST" => $arResult["POST"],
								"SUFFIX" => "form",
							), 
							$component, array("HIDE_ICONS"=>"Y")
						);
						?>
					</div>
				</div>
			<?endif;?>

			<div class="form-footer socserv">
				<div class="inner-table-block">
				<!--noindex--><a href="<?=$arResult["AUTH_REGISTER_URL"];?>" rel="nofollow" class="btn transparent bold register" tabindex="6"><?=GetMessage("AUTH_REGISTER_NEW")?></a><!--/noindex-->
				</div>
				<div class="inner-table-block">
					<div class="more_text_small">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/top_auth.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TOP_AUTH_REGISTER")));?>
					</div>
				</div>
			</div>
				
		</div>
	</div>
<?}else{?>
	<script>
			BX.reload(true);
	</script>
<?}?>