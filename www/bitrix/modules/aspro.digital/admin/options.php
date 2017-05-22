<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

$moduleClass = "CDigital";
$moduleID = "aspro.digital";
global  $APPLICATION;
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/options.php");

CModule::IncludeModule($moduleID);
IncludeModuleLangFile(__FILE__);

$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/style.css");

$RIGHT = $APPLICATION->GetGroupRight($moduleID);

if($RIGHT >= "R"){
	$by = "id";
	$sort = "asc";

	$arSites = array();
	$db_res = CSite::GetList($by, $sort, array("ACTIVE"=>"Y"));
	while($res = $db_res->Fetch()){
		$arSites[] = $res;
	}

	$arTabs = array();
	foreach($arSites as $key => $arSite){
		$arBackParametrs = $moduleClass::GetBackParametrsValues($arSite["ID"], false);
		$arTabs[] = array(
			"DIV" => "edit".($key+1),
			"TAB" => GetMessage("MAIN_OPTIONS_SITE_TITLE", array("#SITE_NAME#" => $arSite["NAME"], "#SITE_ID#" => $arSite["ID"])),
			"ICON" => "settings",
			"TITLE" => GetMessage("MAIN_OPTIONS_TITLE"),
			"PAGE_TYPE" => "site_settings",
			"SITE_ID" => $arSite["ID"],
			"SITE_DIR" => $arSite["DIR"],
			"OPTIONS" => $arBackParametrs,
		);
	}

	$tabControl = new CAdminTabControl("tabControl", $arTabs);

	if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) && $RIGHT >= "W" && check_bitrix_sessid()){
		global $APPLICATION;
		if(strlen($RestoreDefaults)){
			COption::RemoveOption($moduleID, "OPTION");
			COption::RemoveOption($moduleID, "NeedGenerateCustomTheme");
			$APPLICATION->DelGroupRight($moduleID);
		}
		else{
			COption::RemoveOption($moduleID, "sid");

			foreach($arTabs as $key => $arTab){
				$optionsSiteID = $arTab["SITE_ID"];
				//$arThemeOptions = $moduleClass::GetBackParametrsValues($arTab["SITE_ID"]);
				foreach($moduleClass::$arParametrsList as $blockCode => $arBlock){
					foreach($arBlock["OPTIONS"] as $optionCode => $arOption){
						if($arOption['TYPE'] === 'array'){
							$arOptionsRequiredKeys = array();
							$arOptionsKeys = array_keys($arOption['OPTIONS']);
							$itemsKeysCount = COption::GetOptionString($moduleID, $optionCode, '0', $optionsSiteID);
							$fullKeysCount = 0;

							if($arOption['OPTIONS'] && is_array($arOption['OPTIONS'])){
								foreach($arOption['OPTIONS'] as $_optionCode => $_arOption){
									if(strlen($_arOption['REQUIRED']) && $_arOption['REQUIRED'] === 'Y'){
										$arOptionsRequiredKeys[] = $_optionCode;
									}
								}
								for($itemKey = 0, $cnt = $itemsKeysCount + 50; $itemKey <= $cnt; ++$itemKey){
									$bFull = true;
									if($arOptionsRequiredKeys){
										foreach($arOptionsRequiredKeys as $_optionCode){
											if(!strlen($_REQUEST[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID])){
												$bFull = false;
												break;
											}
										}
									}
									if($bFull){
										foreach($arOptionsKeys as $_optionCode){
											$newOptionValue = $_REQUEST[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID];
											COption::SetOptionString($moduleID, $optionCode.'_array_'.$_optionCode.'_'.$fullKeysCount, $newOptionValue, '', $optionsSiteID);
											unset($_REQUEST[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID]);
											unset($_FILES[$optionCode.'_array_'.$_optionCode.'_'.$itemKey.'_'.$optionsSiteID]);
										}

										++$fullKeysCount;
									}
								}
							}

							COption::SetOptionString($moduleID, $optionCode, $fullKeysCount, 0, $optionsSiteID);
						}
						else{
							if($optionCode == "BASE_COLOR_CUSTOM"){
								$moduleClass::CheckColor($_REQUEST[$optionCode."_".$optionsSiteID]);
							}
							if($optionCode == "BASE_COLOR" && $_REQUEST[$optionCode."_".$optionsSiteID] === 'CUSTOM'){
								COption::SetOptionString($moduleID, "NeedGenerateCustomTheme", 'Y', '', $arTab["SITE_ID"]);
							}
							$newVal = $_REQUEST[$optionCode."_".$optionsSiteID];

							if($arOption["TYPE"] == "checkbox"){
								if(!strlen($newVal) || $newVal != "Y"){
									$newVal = "N";
								}

								if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS'])
								{
									foreach($arOption['DEPENDENT_PARAMS'] as $keyOption => $arOtionValue)
									{
										if($arOtionValue['TYPE'] == 'dynamic_iblock')
										{
											$arIblocks = array();
											$arSort = array(
												"SORT" => "ASC", 
												"ID" => "ASC"
											);
											$arFilter = array(
												"ACTIVE" => "Y", 
												"SITE_ID" => $optionsSiteID, 
												"TYPE" => "aspro_digital_form"
											);
											$rsItems = CIBlock::GetList($arSort, $arFilter);
											while($arItem = $rsItems->Fetch()){
												if($arItem["CODE"] != "aspro_digital_example" && $arItem["CODE"] != "aspro_digital_order_page")
												{
													$iblock_val = $_REQUEST[$keyOption."_".$arItem["CODE"]."_".$optionsSiteID];
													if($iblock_val)
													{
														COption::SetOptionString($moduleID, $keyOption."_".strtoupper($arItem['CODE']), $iblock_val, "", $arTab["SITE_ID"]);
													}
												}
											}
										}
										else
										{
											if(isset($arTab["OPTIONS"][$keyOption]))
											{
												$newDependentVal = $_REQUEST[$keyOption."_".$optionsSiteID];
												if((!strlen($newDependentVal) || $newDependentVal != "Y") && $arOtionValue["TYPE"] == "checkbox"){
													$newDependentVal = "N";
												}

												if($keyOption == "YA_COUNTER_ID" && strlen($newDependentVal))
													$newDependentVal = str_replace('yaCounter', '', $newDependentVal);

												COption::SetOptionString($moduleID, $keyOption, $newDependentVal, "", $arTab["SITE_ID"]);

											}
										}
									}
								}
							}elseif($arOption["TYPE"] == "file"){
								$arValueDefault = serialize(array());
								$newVal = unserialize(COption::GetOptionString($moduleID, $optionCode, $arValueDefault, $optionsSiteID));
								if(isset($_REQUEST[$optionCode."_".$optionsSiteID.'_del']) || (isset($_FILES[$optionCode."_".$optionsSiteID]) && strlen($_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['0']))){
									$arValues = $newVal;
									$arValues = (array)$arValues;
									foreach($arValues as $fileID){
										CFile::Delete($fileID);
									}
									$newVal = serialize(array());
								}

								if(isset($_FILES[$optionCode."_".$optionsSiteID]) && (strlen($_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['n0']) || strlen($_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['0']))){
									$arValues = array();
									$absFilePath = (strlen($_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['n0']) ? $_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['n0'] : $_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['0']);
									$arOriginalName = (strlen($_FILES[$optionCode."_".$optionsSiteID]['name']['n0']) ? $_FILES[$optionCode."_".$optionsSiteID]['name']['n0'] : $_FILES[$optionCode."_".$optionsSiteID]['name']['0']);
									if(file_exists($absFilePath)){
										$arFile = CFile::MakeFileArray($absFilePath);
										$arFile['name'] = $arOriginalName; // for original file extension

										if($bIsIco = strpos($arOriginalName, '.ico') !== false){
											$script_files = COption::GetOptionString("fileman", "~script_files", "php,php3,php4,php5,php6,phtml,pl,asp,aspx,cgi,dll,exe,ico,shtm,shtml,fcg,fcgi,fpl,asmx,pht,py,psp,var");
											$arScriptFiles = explode(',', $script_files);
											if(($p = array_search('ico', $arScriptFiles)) !== false){
												unset($arScriptFiles[$p]);
											}
											$tmp = implode(',', $arScriptFiles);
											COption::SetOptionString("fileman", "~script_files", $tmp);
										}

										if($fileID = CFile::SaveFile($arFile, $moduleClass)){
											$arValues[] = $fileID;
										}

										if($bIsIco){
											COption::SetOptionString("fileman", "~script_files", $script_files);
										}
									}
									$newVal = serialize($arValues);
								}

								if(!isset($_FILES[$optionCode."_".$optionsSiteID]) || (!strlen($_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['n0']) && !strlen($_FILES[$optionCode."_".$optionsSiteID]['tmp_name']['0']) && !isset($_REQUEST[$optionCode."_".$optionsSiteID.'_del']))){
									//return;
								}

								if($optionCode === 'FAVICON_IMAGE'){
									$moduleClass::CopyFaviconToSiteDir($newVal, $optionsSiteID); //copy favicon for search bots
								}
								if(is_array($newVal))
									$newVal = serialize($newVal);
								COption::SetOptionString($moduleID, $optionCode, $newVal, "", $arTab["SITE_ID"]);
								unset($arTab["OPTIONS"][$optionCode]);
							}elseif($arOption["TYPE"] == "selectbox" && (isset($arOption["SUB_PARAMS"]) && $arOption["SUB_PARAMS"])){
								if(isset($arOption["LIST"]) && $arOption["LIST"]){
									$arSubValues = array();
									foreach($arOption["LIST"] as $key2 => $value) {
										if($arOption["SUB_PARAMS"][$key2] && $key2 == $newVal){
											foreach($arOption["SUB_PARAMS"][$key2] as $key3 => $arSubValue){
												if($_REQUEST[$key2."_".$key3."_".$optionsSiteID])
												{
													$arSubValues[$key3] = $_REQUEST[$key2."_".$key3."_".$optionsSiteID];
													unset($arTab["OPTIONS"][$key2."_".$key3]);
												}
												elseif($arTab["OPTIONS"][$key2."_".$key3])
												{
													if($arSubValue["TYPE"] == "checkbox" && $key2 == $newVal)
														$arSubValues[$key3] = "N";

													unset($arTab["OPTIONS"][$key2."_".$key3]);
												}

											}
										}
									}
									if($arSubValues)
									{
										COption::SetOptionString($moduleID, "NESTED_OPTIONS_".$optionCode."_".$newVal, serialize($arSubValues), "", $arTab["SITE_ID"]);
									}
								}
							}

							if($arOption["TYPE"] != "file")
								$arTab["OPTIONS"][$optionCode] = $newVal;

							COption::SetOptionString($moduleID, $optionCode, $newVal, "", $arTab["SITE_ID"]);
						}
					}
				}

				$moduleClass::ClearSomeComponentsCache($optionsSiteID);
				CBitrixComponent::clearComponentCache('aspro:form.digital', $SITE_ID);
				$arTabs[$key] = $arTab;
			}
		}
		// die();
		if($compositeMode = $moduleClass::IsCompositeEnabled()){
			$obCache = new CPHPCache();
			$obCache->CleanDir('', 'html_pages');
			$moduleClass::EnableComposite($compositeMode === 'AUTO_COMPOSITE');
		}

		$APPLICATION->RestartBuffer();
	}

	CJSCore::Init(array("jquery"));
	CAjax::Init();
	$tabControl->Begin();
	?>
	<form method="post" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($mid)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?=bitrix_sessid_post();?>
		<?
		foreach($arTabs as $key => $arTab){
			$tabControl->BeginNextTab();
			if($arTab["SITE_ID"]){
				$optionsSiteID = $arTab["SITE_ID"];
				foreach($moduleClass::$arParametrsList as $blockCode => $arBlock)
				{?>
					<tr class="heading"><td colspan="2"><?=$arBlock["TITLE"]?></td></tr>
					<?
					foreach($arBlock["OPTIONS"] as $optionCode => $arOption)
					{
						if(isset($arTab["OPTIONS"][$optionCode]) || $arOption["TYPE"] == 'note' || $arOption["TYPE"] == 'includefile')
						{
							$arControllerOption = CControllerClient::GetInstalledOptions($module_id);
							if($arOption['TYPE'] === 'array'){
								$itemsKeysCount = COption::GetOptionString($moduleID, $optionCode, 0, $optionsSiteID);
								if($arOption['OPTIONS'] && is_array($arOption['OPTIONS'])){
									$arOptionsKeys = array_keys($arOption['OPTIONS']);
									?>
									<tr>
										<td style="text-align:center;" colspan="2"><?=$arOption["TITLE"]?></td>
									</tr>
									<tr>
										<td colspan="2">
											<table class="aspro-admin-item-table">
												<tr style="text-align:center;">
													<?
													for($itemKey = 0, $cnt = $itemsKeysCount; $itemKey <= $cnt; ++$itemKey){
														$_arParameters = array();
														foreach($arOptionsKeys as $_optionKey){
															$_arParameters[$optionCode.'_array_'.$_optionKey.'_'.($itemKey != $cnt ? $itemKey : 'new')] = $arOption['OPTIONS'][$_optionKey];
															if(!$itemKey){
																?><th colspan="2"><?=$arOption['OPTIONS'][$_optionKey]['TITLE']?></th><?
															}
														}
														?>
												</tr>
												<tr class="aspro-admin-item<?=(!$itemKey ? ' first' : '')?><?=($itemKey == $itemsKeysCount - 1 ? ' last' : '')?><?=($itemKey == $cnt ? ' new' : '')?>" data-itemkey="<?=$itemKey?>" style="text-align:center;"><?
														foreach($_arParameters as $_optionCode => $_arOption){
															$moduleClass::ShowAdminRow($_optionCode, $_arOption, $arTab, $arControllerOption);
														}
														?><td class="rowcontrol"><span class="up"></span><span class="down"></span><span class="remove"></span></td></tr><?
													}
													?>
												<tr style="text-align:center;">
													<td><a href="javascript:;" class="adm-btn adm-btn-save adm-btn-add" title="<?=GetMessage('PRIME_OPTIONS_ADD_BUTTON_TITLE')?>"><?=GetMessage('OPTIONS_ADD_BUTTON_TITLE')?></a></td>
												</tr>
											</table>
										</td>
									</tr><?
								}
							}
							else{
								if($arOption["TYPE"] == 'note')
								{
									if($optionCode === 'CONTACTS_EDIT_LINK_NOTE'){
										$contactsHref = str_replace('//', '/', $arTab['SITE_DIR'].'/contacts/?bitrix_include_areas=Y');
										$arOption["TITLE"] = GetMessage('CONTACTS_OPTIONS_EDIT_LINK_NOTE', array('#CONTACTS_HREF#' => $contactsHref));
									}
									?>
									<tr>
										<td colspan="2" align="center">
											<?=BeginNote('align="center" name="'.htmlspecialcharsbx($optionCode)."_".$optionsSiteID.'"');?>
											<?=$arOption["TITLE"]?>
											<?=EndNote();?>
										</td>
									</tr>
									<?
								}
								else{
									$optionName = $arOption["TITLE"];
									$optionType = $arOption["TYPE"];
									$optionList = $arOption["LIST"];
									$optionDefault = $arOption["DEFAULT"];
									$optionVal = $arTab["OPTIONS"][$optionCode];
									$optionSize = $arOption["SIZE"];
									$optionCols = $arOption["COLS"];
									$optionRows = $arOption["ROWS"];
									$optionChecked = $optionVal == "Y" ? "checked" : "";
									$optionDisabled = isset($arControllerOption[$optionCode]) || array_key_exists("DISABLED", $arOption) && $arOption["DISABLED"] == "Y" ? "disabled" : "";
									$optionSup_text = array_key_exists("SUP", $arOption) ? $arOption["SUP"] : "";
									$optionController = isset($arControllerOption[$optionCode]) ? "title='".GetMessage("MAIN_ADMIN_SET_CONTROLLER_ALT")."'" : "";
									?>
									<tr>
										<?=$moduleClass::ShowAdminRow($optionCode, $arOption, $arTab, $arControllerOption);?>
									</tr>
									<?if(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS'] && (isset($arOption['LIST']) && $arOption['LIST'])): //nested params?>
										<?foreach($arOption['LIST'] as $key => $value):?>
											<tr data-parent='<?=$optionCode."_".$arTab["SITE_ID"]?>' class="block <?=$key?>" <?=($key == $arTab["OPTIONS"][$optionCode] ? "style='display:table-row'" : "style='display:none'");?>>
												<?if($arOption['SUB_PARAMS'][$key]):?><td style="text-align:center;" colspan="2"><?=GetMessage('SUB_PARAMS');?></td><?endif;?>
											</tr>
											<?foreach((array)$arOption['SUB_PARAMS'][$key] as $key2 => $arValue):?>
												<tr data-parent='<?=$optionCode."_".$arTab["SITE_ID"]?>' class="block <?=$key?>" <?=($key == $arTab["OPTIONS"][$optionCode] ? "style='display:table-row'" : "style='display:none'");?>><?=$moduleClass::ShowAdminRow($key.'_'.$key2, $arValue, $arTab, $arControllerOption);?></tr>
											<?endforeach;?>
										<?endforeach;?>
									<?endif;?>
									<?if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']): //dependent params?>
										<?foreach($arOption['DEPENDENT_PARAMS'] as $key => $arValue):?>
											<?if(!isset($arValue['CONDITIONAL_VALUE']) || ($arValue['CONDITIONAL_VALUE'] && $arTab["OPTIONS"][$optionCode] == $arValue['CONDITIONAL_VALUE']))
												$style = "style='display:table-row'";
											else
												$style = "style='display:none'";
											?>
											<tr class="depend-block <?=$key?>" <?=((isset($arValue['CONDITIONAL_VALUE']) && $arValue['CONDITIONAL_VALUE']) ? "data-show='".$arValue['CONDITIONAL_VALUE']."'" : "");?> data-parent='<?=$optionCode."_".$arTab["SITE_ID"]?>' <?=$style;?>><?=$moduleClass::ShowAdminRow($key, $arValue, $arTab, $arControllerOption);?></tr>
										<?endforeach;?>
									<?endif;?>
									<?
								}
							}
						}
					}
				}
			}
		}
		?>
		<?
		if($REQUEST_METHOD == "POST" && strlen($Update.$Apply.$RestoreDefaults) && check_bitrix_sessid())
		{
			if(strlen($Update) && strlen($_REQUEST["back_url_settings"]))
				LocalRedirect($_REQUEST["back_url_settings"]);
			else
				LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($mid)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($_REQUEST["back_url_settings"])."&".$tabControl->ActiveTabParam());
		}
		$tabControl->Buttons();
		?>
		<input <?if($RIGHT < "W") echo "disabled"?> type="submit" name="Apply" class="submit-btn" value="<?=GetMessage("MAIN_OPT_APPLY")?>" title="<?=GetMessage("MAIN_OPT_APPLY_TITLE")?>">
		<?if(strlen($_REQUEST["back_url_settings"])):?>
			<input type="button" name="Cancel" value="<?=GetMessage("MAIN_OPT_CANCEL")?>" title="<?=GetMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?=htmlspecialchars(CUtil::addslashes($_REQUEST["back_url_settings"]))?>'">
			<input type="hidden" name="back_url_settings" value="<?=htmlspecialchars($_REQUEST["back_url_settings"])?>">
		<?endif;?>
		<?if(CDigital::IsCompositeEnabled()):?>
			<div class="adm-info-message"><?=GetMessage("WILL_CLEAR_HTML_CACHE_NOTE")?></div><div style="clear:both;"></div>
		<?endif;?>
		<script type="text/javascript">
		$(document).ready(function() {
			<?if(CDigital::IsCompositeEnabled()):?>
				$('input[name^="THEME_SWITCHER"]').change(function() {
					var ischecked = $(this).attr('checked');
					if(typeof(ischecked) != 'undefined'){
						if(!confirm("<?=GetMessage("NO_COMPOSITE_NOTE")?>")){
							$(this).removeAttr('checked');
						}
					}
				});
			<?endif;?>

			$('select[name^="SCROLLTOTOP_TYPE"]').change(function() {
				var posSelect = $(this).parents('table').first().find('select[name^="SCROLLTOTOP_POSITION"]');
				if(posSelect){
					var posSelectTr = posSelect.parents('tr').first();
					var isNone = $(this).val().indexOf('NONE') != -1;
					if(isNone){
						if(posSelectTr.is(':visible')){
							posSelectTr.hide();
						}
					}
					else{
						if(!posSelectTr.is(':visible')){
							posSelectTr.show();
						}
						var isRound = $(this).val().indexOf('ROUND') != -1;
						var isTouch = posSelect.val().indexOf('TOUCH') != -1;
						if(isRound && !!posSelect){
							posSelect.find('option[value^="TOUCH"]').attr('disabled', 'disabled');
							if(isTouch){
								posSelect.val(posSelect.find('option[value^="PADDING"]').first().attr('value'));
							}
						}
						else{
							posSelect.find('option[value^="TOUCH"]').removeAttr('disabled');
						}
					}
				}
			});

			$('select[name^="PAGE_CONTACTS"]').change(function() {
				var value = $(this).val();
				if(value == 'custom'){
					$(this).parents('table').find('[name^="CONTACTS_EDIT_LINK_NOTE"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_ADDRESS"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_PHONE"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_REGIONAL_PHONE"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_EMAIL"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_SCHEDULE"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_DESCRIPTION"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_REGIONAL_DESCRIPTION"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_USE_FEEDBACK"]').closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_USE_MAP"]').first().closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_MAP"]').first().closest('tr').hide();
					$(this).parents('table').find('[name^="CONTACTS_MAP_NOTE"]').closest('tr').hide();
				}
				else{
					$(this).parents('table').find('[name^="CONTACTS_EDIT_LINK_NOTE"]').closest('tr').show();
					$(this).parents('table').find('[name^="CONTACTS_EMAIL"]').closest('tr').show();
					$(this).parents('table').find('[name^="CONTACTS_USE_MAP"]').first().closest('tr').show();

					if($(this).val() < 3){
						$(this).parents('table').find('[name^="CONTACTS_PHONE"]').closest('tr').show();
						$(this).parents('table').find('[name^="CONTACTS_REGIONAL_PHONE"]').closest('tr').hide();
						$(this).parents('table').find('[name^="CONTACTS_SCHEDULE"]').closest('tr').show();
						$(this).parents('table').find('[name^="CONTACTS_DESCRIPTION12"]').closest('tr').show();
						$(this).parents('table').find('[name^="CONTACTS_REGIONAL_DESCRIPTION34"]').closest('tr').hide();
						$(this).parents('table').find('[name^="CONTACTS_REGIONAL_DESCRIPTION5"]').closest('tr').hide();
						$(this).parents('table').find('[name^="CONTACTS_USE_FEEDBACK"]').closest('tr').show();
						$(this).parents('table').find('[name^="CONTACTS_MAP"]').first().closest('tr').show();
						$(this).parents('table').find('[name^="CONTACTS_MAP_NOTE"]').closest('tr').show();
					}
					else{
						$(this).parents('table').find('[name^="CONTACTS_PHONE"]').closest('tr').show();
						$(this).parents('table').find('[name^="CONTACTS_REGIONAL_PHONE"]').closest('tr').hide();
						$(this).parents('table').find('[name^="CONTACTS_SCHEDULE"]').closest('tr').hide();
						if(value < 5){
							$(this).parents('table').find('[name^="CONTACTS_ADDRESS"]').closest('tr').show();
							$(this).parents('table').find('[name^="CONTACTS_DESCRIPTION12"]').closest('tr').hide();
							$(this).parents('table').find('[name^="CONTACTS_REGIONAL_DESCRIPTION34"]').closest('tr').show();
							$(this).parents('table').find('[name^="CONTACTS_REGIONAL_DESCRIPTION5"]').closest('tr').hide();
							$(this).parents('table').find('[name^="CONTACTS_USE_FEEDBACK"]').closest('tr').show();
						}
						else{
							$(this).parents('table').find('[name^="CONTACTS_ADDRESS"]').closest('tr').hide();
							$(this).parents('table').find('[name^="CONTACTS_DESCRIPTION12"]').closest('tr').hide();
							$(this).parents('table').find('[name^="CONTACTS_REGIONAL_DESCRIPTION34"]').closest('tr').hide();
							$(this).parents('table').find('[name^="CONTACTS_REGIONAL_DESCRIPTION5"]').closest('tr').show();
							$(this).parents('table').find('[name^="CONTACTS_USE_FEEDBACK"]').closest('tr').hide();
						}
						$(this).parents('table').find('[name^="CONTACTS_MAP"]').first().closest('tr').hide();
						$(this).parents('table').find('[name^="CONTACTS_MAP_NOTE"]').closest('tr').hide();
					}
				}
			});

			$('.aspro-admin-item-table .adm-btn-add').click(function() {
				var $table = $(this).closest('.aspro-admin-item-table');
				var $newItem = $table.find('.aspro-admin-item.new');
				if($newItem.length){
					var lastItemKey = $table.find('.aspro-admin-item.last').length ? $table.find('.aspro-admin-item.last').attr('data-itemkey') * 1 : -1;
					var $clone = $newItem.clone().insertBefore($newItem).removeClass('new');
					$clone.attr('data-itemkey', lastItemKey + 1);
					$clone.find('td:not(.rowcontrol)').each(function(i) {
						var name = $(this).find('*[name]:first-of-type').attr('name');
						var newName = name.replace('_new_', '_' + (lastItemKey + 1) + '_');
						$(this).find('*[name]:first-of-type').attr('name', newName);
					});
				}
				$table.find('.aspro-admin-item').removeClass('first, last');
				$table.find('.aspro-admin-item:not(.new)').first().addClass('first');
				$table.find('.aspro-admin-item:not(.new)').last().addClass('last');
			});

			$(document).on('click', '.rowcontrol>span', function() {
				var action = ($(this).hasClass('up') ? 'up' : ($(this).hasClass('down') ? 'down' : 'remove'));
				var $table = $(this).closest('.aspro-admin-item-table');
				var $item = $(this).closest('.aspro-admin-item');
				var itemKey = $item.attr('data-itemkey');

				if(action === 'up'){
					var prevItemKey = $item.prev().attr('data-itemkey');
					$item.find('td:not(.rowcontrol)').each(function(i) {
						var name = $(this).find('*[name]:first-of-type').attr('name');
						if(typeof(name) !== 'undefined'){
							var newName = name.replace('_' + itemKey + '_', '_' + prevItemKey + '_');
							$(this).find('*[name]:first-of-type').attr('name', newName);
							var name = $item.prev().find('td:not(.rowcontrol)').eq(i).find('*[name]:first-of-type').attr('name');
							var newName = name.replace('_' + prevItemKey + '_', '_' + itemKey + '_');
							$item.prev().find('td:not(.rowcontrol)').eq(i).find('*[name]:first-of-type').attr('name', newName);
						}
					});
					$item.attr('data-itemkey', prevItemKey);
					$item.prev().attr('data-itemkey', itemKey);
					$item.clone().insertBefore($item.prev());
				}
				else if(action === 'down'){
					var nextItemKey = $item.next().attr('data-itemkey');
					$item.find('td:not(.rowcontrol)').each(function(i) {
						var name = $(this).find('*[name]:first-of-type').attr('name');
						if(typeof(name) !== 'undefined'){
							var newName = name.replace('_' + itemKey + '_', '_' + nextItemKey + '_');
							$(this).find('*[name]:first-of-type').attr('name', newName);
							var name = $item.next().find('td:not(.rowcontrol)').eq(i).find('*[name]:first-of-type').attr('name');
							var newName = name.replace('_' + nextItemKey + '_', '_' + itemKey + '_');
							$item.next().find('td:not(.rowcontrol)').eq(i).find('*[name]:first-of-type').attr('name', newName);
						}
					});
					$item.attr('data-itemkey', nextItemKey);
					$item.next().attr('data-itemkey', itemKey);
					$item.clone().insertAfter($item.next());
				}
				$item.detach();
				$table.find('.aspro-admin-item').removeClass('first').removeClass('last');
				$table.find('.aspro-admin-item:not(.new)').first().addClass('first');
				$table.find('.aspro-admin-item:not(.new)').last().addClass('last');
			});

			$('select[name^="SCROLLTOTOP_TYPE"]').change();
			$('select[name^="PAGE_CONTACTS"]').change();
		});
		</script>
	</form>
		<script type="text/javascript">
			$(document).ready(function() {
				$('select[name^="INDEX_TYPE"]').change(function() {
					var value = $(this).val()
						sub_block = $('tr.block[data-parent='+$(this).attr('name')+']');
					if(sub_block.length)
					{
						sub_block.css({'display':'none'});
						$('tr.block.'+value+'[data-parent='+$(this).attr('name')+']').css({'display':'table-row'});
					}
				});
				$('input.depend-check').change(function() {
					var ischecked = $(this).prop('checked'),
						depend_block = $('.depend-block[data-parent='+$(this).attr('id')+']');
					if(depend_block.length && $(this).attr('id').indexOf('YA_GOLAS') < 0)
					{
						if(typeof(depend_block.data('show')) != 'undefined')
						{
							if(depend_block.data('show') == 'Y')
							{
								if(ischecked)
								{
									depend_block.fadeIn();
									// depend_block.css('display', 'table-row');
								}
								else
								{
									depend_block.fadeOut();
									// depend_block.css('display', 'none');
								}
							}
							else
							{
								if(ischecked)
								{
									depend_block.fadeOut();
									// depend_block.css('display', 'none');
								}
								else
								{
									depend_block.fadeIn();
									// depend_block.css('display', 'table-row');
								}
							}
						}
					}
				});
			})

			$('select[name^="USE_FORMS_GOALS"]').change(function() {
				var parent = $(this).closest('tr').data('parent');
				var inUAC = $(this).parents('table').first().find('input#'+parent);
				
				if(inUAC.length && inUAC.attr('checked')){
					var isNone = $(this).val().indexOf('NONE') != -1;
					var isCommon = $(this).val().indexOf('COMMON') != -1;
					var itrUFGNote = $(this).parents('table').first().find('tr.USE_FORMS_GOALS_NOTE');
					if(!isNone){
						if(isCommon){
							itrUFGNote.find('[data-value=common]').show();
							itrUFGNote.find('[data-value=single]').hide();
						}
						else{
							itrUFGNote.find('[data-value=common]').hide();
							itrUFGNote.find('[data-value=single]').show();
						}
						itrUFGNote.fadeIn();
					}
					else{
						itrUFGNote.fadeOut();
					}
				}
			});

			$('input[name^="USE_SALE_GOALS"]').change(function() {
				var parent = $(this).closest('tr').data('parent');
				var inUAC = $(this).parents('table').first().find('input#'+parent);
				if(inUAC.length && inUAC.attr('checked')){
					var itrUSGNote = $(this).parents('table').first().find('tr.USE_SALE_GOALS_NOTE');
					var ischecked = $(this).attr('checked');
					if(typeof(ischecked) != 'undefined'){
						itrUSGNote.fadeIn();
					}
					else{
						itrUSGNote.fadeOut();
					}
				}
			});

			$('input[name^="USE_DEBUG_GOALS"]').change(function() {
				var parent = $(this).closest('tr').data('parent');
				var inUAC = $(this).parents('table').first().find('input#'+parent);
				if(inUAC.length && inUAC.attr('checked')){
					var itrUDGNote = $(this).parents('table').first().find('tr.USE_DEBUG_GOALS_NOTE');
					var ischecked = $(this).attr('checked');
					if(typeof(ischecked) != 'undefined'){
						itrUDGNote.fadeIn();
					}
					else{
						itrUDGNote.fadeOut();
					}
				}
			});
			$('input[name^="YA_GOLAS"]').change(function() {
					var itrYACID = $(this).parents('table').first().find('tr.YA_COUNTER_ID');
					var itrUFG = $(this).parents('table').first().find('tr.USE_FORMS_GOALS');
					var itrUFGNote = $(this).parents('table').first().find('tr.USE_FORMS_GOALS_NOTE');
					var itrUSG = $(this).parents('table').first().find('tr.USE_SALE_GOALS');
					var itrUSGNote = $(this).parents('table').first().find('tr.USE_SALE_GOALS_NOTE');
					var itrUDG = $(this).parents('table').first().find('tr.USE_DEBUG_GOALS');
					var itrUDGNote = $(this).parents('table').first().find('tr.USE_DEBUG_GOALS_NOTE');
					var ischecked = $(this).attr('checked');
					
					if(typeof(ischecked) != 'undefined'){
						itrYACID.fadeIn(10);
						itrUFG.fadeIn(10);
						if(itrUFG.find('select').val().indexOf('NONE') == -1){
							$('select[name^="USE_FORMS_GOALS"]').change();
						}
						itrUSG.fadeIn();
						if(itrUSG.find('input').attr('checked')){
							itrUSGNote.fadeIn(10);
						}
						itrUDG.fadeIn();
						if(itrUDG.find('input').attr('checked')){
							itrUDGNote.fadeIn(10);
						}
					}
					else{

						itrYACID.fadeOut(10);
						itrUFG.fadeOut(10);
						itrUFGNote.fadeOut(10);
						itrUSG.fadeOut(10);
						itrUSGNote.fadeOut(10);
						itrUDG.fadeOut(10);
						itrUDGNote.fadeOut(10);
					}
				});

				$('input[name^="YA_GOLAS"]').change();
				$('select[name^="USE_FORMS_GOALS"]').change();
				$('input[name^="USE_SALE_GOALS"]').change();
				$('input[name^="USE_DEBUG_GOALS"]').change();
		</script>
	<?$tabControl->End();?>
	<?
}
else
	CAdminMessage::ShowMessage(GetMessage('NO_RIGHTS_FOR_VIEWING'));
?>

<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>