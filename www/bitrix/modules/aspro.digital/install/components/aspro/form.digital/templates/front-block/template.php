<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<div class="front-form">
	<div class="maxwidth-theme">
		<div class="col-md-12">
			<div class="form contacts<?=($arResult['isFormNote'] == 'Y' ? ' success' : '')?><?=($arResult['isFormErrors'] == 'Y' ? ' error' : '')?> item-views blocks">
				<?if( $arResult["isFormNote"] == "Y" ){?>
					<div class="form-header">
						<div class="text">
							<h3><?=GetMessage("SUCCESS_TITLE")?></h3>
							<div class="desc"><?=$arResult["FORM_NOTE"]?></div>
						</div>
					</div>
					<script>
						if(arDigitalOptions['THEME']['USE_FORMS_GOALS'] !== 'NONE')
						{
							var eventdata = {goal: 'goal_webform_success' + (arDigitalOptions['THEME']['USE_FORMS_GOALS'] === 'COMMON' ? '' : '_<?=$arParams["IBLOCK_ID"]?>'), params: <?=CUtil::PhpToJSObject($arParams, false)?>};
							BX.onCustomEvent('onCounterGoals', [eventdata]);
						}
					</script>
					<?if( $arParams["DISPLAY_CLOSE_BUTTON"] ){?>
						<div class="form-footer" style="text-align: center;">
							<?=str_replace('class="', 'class="btn-lg ', $arResult["CLOSE_BUTTON"])?>
						</div>
					<?}
				}else{?>
					<?=$arResult["FORM_HEADER"]?>
						<div class="inner-wrapper">
							<div class="top">
								<?if( $arResult["isIblockTitle"] ){?>
									<h3><?=$arResult["IBLOCK_TITLE"]?></h3>
								<?}?>
								<?if( $arResult["isIblockDescription"] ){?>
									<div class="desc">
										<?if( $arResult["IBLOCK_DESCRIPTION_TYPE"] == "text" ){?>
											<p><?=$arResult["IBLOCK_DESCRIPTION"]?></p>
										<?}else{?>
											<?=$arResult["IBLOCK_DESCRIPTION"]?>
										<?}?>
									</div>
								<?}?>
							</div>
							<div class="bottom">
								<div class="row">
									<?if($arResult['isFormErrors'] == 'Y'):?>
										<div class="col-md-12">
											<div class="form-error alert alert-danger">
												<?=$arResult['FORM_ERRORS_TEXT']?>
											</div>
										</div>
									<?endif;?>
									<div class="col-md-12">
										<div class="row">
											<?if(is_array($arResult["QUESTIONS"])):?>
												<?foreach( $arResult["QUESTIONS"] as $FIELD_SID => $arQuestion ){
													if( $FIELD_SID == "MESSAGE" ) continue;
													if( $arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden' ){
														echo $arQuestion["HTML_CODE"];
													}else{?>
														<div class="col-md-4">
															<div class="row-block" data-SID="<?=$FIELD_SID?>">
																<div class="form-group  <?=( $arQuestion['FIELD_TYPE'] != "file" ? "animated-labels" : "");?> <?=( $arQuestion['VALUE'] ? "input-filed" : "");?>">
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
										</div>
									</div>
									<?if($arResult["QUESTIONS"]["MESSAGE"]):?>
										<div class="col-md-12">
											<div class="row" data-SID="MESSAGE">
												<div class="col-md-12">
													<div class="form-group  animated-labels">
														<?=$arResult["QUESTIONS"]["MESSAGE"]["CAPTION"]?>
														<div class="input">
															<?=$arResult["QUESTIONS"]["MESSAGE"]["HTML_CODE"]?>
														</div>
														<?if( !empty( $arResult["QUESTIONS"]["MESSAGE"]["HINT"] ) ){?>
															<div class="hint"><?=$arResult["QUESTIONS"]["MESSAGE"]["HINT"]?></div>
														<?}?>
													</div>
												</div>
											</div>
										</div>
									<?endif;?>
								</div>
								<?
								$frame = $this->createFrame()->begin('');
								$frame->setBrowserStorage(true);
								?>
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
								<?}else{?>
									<div style="display:none;"></div>
								<?}?>
								<?$frame->end();?>
								<div class="row">
									<div class="col-md-12 col-sm-12" style="margin-top: 5px;">
										<?if($arParams["SHOW_LICENCE"] == "Y"):?>
											<div class="licence_block bx_filter">
												<input type="checkbox" id="licenses" name="licenses" required value="Y">
												<label for="licenses">
													<?$APPLICATION->IncludeFile(SITE_DIR."include/licenses_text.php", Array(), Array("MODE" => "html", "NAME" => "LICENSES")); ?>
												</label>
											</div>
										<?endif;?>
										<div class="text-center">
											<?=str_replace('class="', 'class="btn-lg ', $arResult["SUBMIT_BUTTON"])?>
										</div>
									</div>
								</div>
							</div>
						</div>
					<?=$arResult["FORM_FOOTER"]?>
				<?}?>
			</div>
		</div>
	</div>
</div>
