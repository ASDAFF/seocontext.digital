<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);?>
<?if($arResult["ITEMS"]){?>
	<?include_once("function.php");?>
	<div class="bx_filter bx_filter_<?=strtolower($arParams["FILTER_VIEW_MODE"]);?> catalog swipeignore">
	<div class="border_block">
		<div class="bx_filter_section">
			<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arParams["FORM_URL"]?>" method="get" class="smartfilter">
				<input type="hidden" name="del_url" id="del_url" value="<?echo $arResult["SEF_DEL_FILTER_URL"]?>" />
				<?foreach($arResult["HIDDEN"] as $arItem):?>
				<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
				<?endforeach;?>

				<div class="row">
				<div class="col-md-12">
					<div class="bx_filter_parameters_box active">
						<div class="titles">
							<?=GetMessage("CT_BCSF_FILTER_TITLE");?>
						</div>
					</div>
				</div>

				<?$isFilter=false;
				//prices
				foreach($arResult["ITEMS"] as $key=>$arItem){
					$key = $arItem["ENCODED_ID"];
					if(isset($arItem["PRICE"])):
						if ($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"] <= 0)
							continue;
						?>
						<div class="col-md-3">
							<div class="bx_filter_parameters_box active">
								<span class="bx_filter_container_modef"></span>
								<div class="bx_filter_parameters_box_title" ><?=GetMessage("PRICE")//$arItem["NAME"]?></div>
								<div class="bx_filter_block">
									<div class="bx_filter_parameters_box_container">
										<div class="wrapp_all_inputs wrap_md">
											<div class="wrapp_change_inputs iblock">
												<div class="bx_filter_parameters_box_container_block">
													<div class="bx_filter_input_container bg">
														<input
															class="min-price"
															type="text"
															name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
															id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
															value="<?echo $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>"
															size="5"
															onkeyup="smartFilter.keyup(this)"
														/>
													</div>
												</div>
												<div class="bx_filter_parameters_box_container_block">
													<div class="bx_filter_input_container bg">
														<input
															class="max-price"
															type="text"
															name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
															id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
															value="<?echo $arItem["VALUES"]["MAX"]["HTML_VALUE"]?>"
															size="5"
															onkeyup="smartFilter.keyup(this)"
														/>
													</div>
												</div>
												<span class="divider"></span>
												<div style="clear: both;"></div>
											</div>
											<div class="wrapp_slider iblock">
												<div class="bx_ui_slider_track" id="drag_track_<?=$key?>">
													<?
													$isConvert=false;
													if($arParams["CONVERT_CURRENCY"]=="Y"){
														$isConvert=true;
													}
													$price1 = $arItem["VALUES"]["MIN"]["VALUE"];
													$price2 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"])/4);
													$price3 = $arItem["VALUES"]["MIN"]["VALUE"] + round(($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"])/2);
													$price4 = $arItem["VALUES"]["MIN"]["VALUE"] + round((($arItem["VALUES"]["MAX"]["VALUE"] - $arItem["VALUES"]["MIN"]["VALUE"])*3)/4);
													$price5 = $arItem["VALUES"]["MAX"]["VALUE"];

													if($isConvert){
														$price1 =SaleFormatCurrency($price1, $arParams["CURRENCY_ID"], true);
														$price2 =SaleFormatCurrency($price2, $arParams["CURRENCY_ID"], true);
														$price3 =SaleFormatCurrency($price3, $arParams["CURRENCY_ID"], true);
														$price4 =SaleFormatCurrency($price4, $arParams["CURRENCY_ID"], true);
														$price5 =SaleFormatCurrency($price5, $arParams["CURRENCY_ID"], true);
													}
													?>
													<div class="bx_ui_slider_part first p1"><span><?=$price1?></span></div>
													<div class="bx_ui_slider_part p2"><span><?=$price2?></span></div>
													<div class="bx_ui_slider_part p3"><span><?=$price3?></span></div>
													<div class="bx_ui_slider_part p4"><span><?=$price4?></span></div>
													<div class="bx_ui_slider_part last p5"><span><?=$price5?></span></div>

													<div class="bx_ui_slider_pricebar_VD" style="left: 0;right: 0;" id="colorUnavailableActive_<?=$key?>"></div>
													<div class="bx_ui_slider_pricebar_VN" style="left: 0;right: 0;" id="colorAvailableInactive_<?=$key?>"></div>
													<div class="bx_ui_slider_pricebar_V"  style="left: 0;right: 0;" id="colorAvailableActive_<?=$key?>"></div>
													<div class="bx_ui_slider_range" id="drag_tracker_<?=$key?>"  style="left: 0%; right: 0%;">
														<a class="bx_ui_slider_handle left"  style="left:0;" href="javascript:void(0)" id="left_slider_<?=$key?>"></a>
														<a class="bx_ui_slider_handle right" style="right:0;" href="javascript:void(0)" id="right_slider_<?=$key?>"></a>
													</div>
												</div>
												<div style="opacity: 0;height: 1px;"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?
							$isFilter=true;
							$precision = 2;
							if (Bitrix\Main\Loader::includeModule("currency"))
							{
								$res = CCurrencyLang::GetFormatDescription($arItem["VALUES"]["MIN"]["CURRENCY"]);
								$precision = $res['DECIMALS'];
							}
							$arJsParams = array(
								"leftSlider" => 'left_slider_'.$key,
								"rightSlider" => 'right_slider_'.$key,
								"tracker" => "drag_tracker_".$key,
								"trackerWrap" => "drag_track_".$key,
								"minInputId" => $arItem["VALUES"]["MIN"]["CONTROL_ID"],
								"maxInputId" => $arItem["VALUES"]["MAX"]["CONTROL_ID"],
								"minPrice" => $arItem["VALUES"]["MIN"]["VALUE"],
								"maxPrice" => $arItem["VALUES"]["MAX"]["VALUE"],
								"curMinPrice" => $arItem["VALUES"]["MIN"]["HTML_VALUE"],
								"curMaxPrice" => $arItem["VALUES"]["MAX"]["HTML_VALUE"],
								"fltMinPrice" => intval($arItem["VALUES"]["MIN"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MIN"]["FILTERED_VALUE"] : $arItem["VALUES"]["MIN"]["VALUE"] ,
								"fltMaxPrice" => intval($arItem["VALUES"]["MAX"]["FILTERED_VALUE"]) ? $arItem["VALUES"]["MAX"]["FILTERED_VALUE"] : $arItem["VALUES"]["MAX"]["VALUE"],
								"precision" => $precision,
								"colorUnavailableActive" => 'colorUnavailableActive_'.$key,
								"colorAvailableActive" => 'colorAvailableActive_'.$key,
								"colorAvailableInactive" => 'colorAvailableInactive_'.$key,
							);
							?>
							<script type="text/javascript">
								BX.ready(function(){
									window['trackBar<?=$key?>'] = new BX.Iblock.SmartFilter(<?=CUtil::PhpToJSObject($arJsParams)?>);
								});
							</script>
						</div> <?//col-md-3?>					
					<?endif;?>
				<?}
				//not prices
				?>

				<?
				$isFilter=true;
				if(isset($arResult["ITEMS"]["TOP_BLOCK"])){?>
					</div>
					<div class="row line_row">
						<?
							$countSubElement=count($arItem);
							$colSub=12;
							if($countSubElement>2){
								$colSub=4;
							}elseif($countSubElement>1 && $countSubElement<3){
								$colSub=6;
							}
							foreach($arItem as $keySub=>$arSubItem){?>
								<?ShowFilterItemExt($keySub, $arSubItem, $colSub);?>
							<?}
						?>
					</div>
					<hr class="filter_hor" />
					<div class="row">
				<?}
				$countElement=count($arResult["ITEMS"]);
				$col=4;
				if($countElement>2){
					$col=3;
				}elseif($countElement>1 && $countElement<3){
					$col=2;
				}
				$col=12;
				foreach($arResult["ITEMS"] as $key=>$arItem){
					if($key!="TOP_BLOCK"){?>
						<?ShowFilterItemExt($key, $arItem, $col);?>
					<?}?>
				<?}
				if($isFilter){?>
					<div class="col-md-12">
						<div class="bx_filter_button_box active">
							<div class="bx_filter_block">
								<div class="bx_filter_parameters_box_container">
									<?if($arParams["FILTER_VIEW_MODE"] == "VERTICAL"):?>
										<div class="bx_filter_popup_result right" id="modef_mobile" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
											<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num_mobile">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
											<a href="<?echo $arResult["FILTER_URL"]?>" class="button white_bg"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
										</div>
									<?endif?>
									<div class="bx_filter_popup_result right" id="modef" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?> style="display: inline-block;">
										<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
										<!-- noindex --><a href="<?echo $arResult["FORM_ACTION"]?>" class="button white_bg" rel="nofollow"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a><!-- /noindex -->
									</div>
									<input class="bx_filter_search_button btn btn-default" type="submit" id="set_filter" name="set_filter"  value="<?=GetMessage("CT_BCSF_SET_FILTER")?>" />
									<?/*<input class="bx_filter_search_reset button small transparent" type="reset" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>" />*/?>
									<button class="bx_filter_search_reset btn btn-transparent" type="submit" id="del_filter" name="del_filter">
										<?=GetMessage("CT_BCSF_DEL_FILTER")?>
									</button>
									<div class="clearfix"></div>
								</div>
							</div>
						</div>
					</div>
				<?}?>
				</div>
				<?/*</div>*/?>
			</form>
			<div style="clear: both;"></div>
		</div>
	</div>
	</div>
	<?$arSite = CSite::GetByID( SITE_ID )->Fetch();?>
	<script>
		var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arParams["FORM_URL"])?>', '<?=$arParams["FILTER_VIEW_MODE"];?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
		<?if(!$isFilter){?>
			$('.bx_filter_vertical').remove();
		<?}?>
		$(document).ready(function(){
			$('.bx_filter_search_reset').on('click', function(){
				<?if($arParams["SEF_MODE"]=="Y"){?>
					location.href=$('form.smartfilter').find('#del_url').val();
				<?}else{?>
					location.href=$('form.smartfilter').attr('action');
				<?}?>
			})
			/*$('.bx_filter_search_button').on('click', function(e){
				e.preventDefault();
				location.href=$('#modef a').attr('href');
			})	*/		
			$(".bx_filter_parameters_box_title").click( function(){
				var active=2;
				if ($(this).closest(".bx_filter_parameters_box").hasClass("active")) { $(this).next(".bx_filter_block").slideUp(100); }
				else { $(this).next(".bx_filter_block").slideDown(200); }
				$(this).closest(".bx_filter_parameters_box").toggleClass("active");

				if($(this).closest(".bx_filter_parameters_box").hasClass("active")){
					active=3;
				}
				//checkOpened($(this));
				
				$.cookie.json = true;			
				$.cookie("DIGITAL_filter_prop_"+$(this).closest(".bx_filter_parameters_box").data('prop_code'), active,{
					path: '/',
					domain: '',
					expires: 360
				});
			});
			$('.bx_filter_parameters_box').each(function(){
				if($.cookie("DIGITAL_filter_prop_"+$(this).data('prop_code'))==2){
					$(this).removeClass('active');
					$(this).find('.bx_filter_block').hide();
				}else if($.cookie("DIGITAL_filter_prop_"+$(this).data('prop_code'))==3){
					$(this).addClass('active');
					$(this).find('.bx_filter_block').show();
				}
			})
		})
	</script>
<?}?>