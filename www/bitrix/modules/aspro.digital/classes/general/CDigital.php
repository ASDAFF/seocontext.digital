<?
/**
 * Digital module
 * @copyright 2017 Aspro
 */

if(!defined('DIGITAL_MODULE_ID'))
	define('DIGITAL_MODULE_ID', 'aspro.digital');

IncludeModuleLangFile(__FILE__);
use \Bitrix\Main\Type\Collection;

// initialize module parametrs list and default values
include_once __DIR__.'/../../parametrs.php';

class CDigital{
	const MODULE_ID = DIGITAL_MODULE_ID;
	const PARTNER_NAME = 'aspro';
	const SOLUTION_NAME = 'digital';
	const devMode = false; // set to false before release

	static $arParametrsList = array();
	private static $arMetaParams = array();

	public function checkModuleRight($reqRight = 'R', $bShowError = false){
		global  $APPLICATION;

		if($APPLICATION->GetGroupRight(self::MODULE_ID) < $reqRight){
			if($bShowError){
				$APPLICATION->AuthForm(GetMessage('DIGITAL_ACCESS_DENIED'));
			}
			return false;
		}

		return true;
	}

	function ClearSomeComponentsCache($SITE_ID){
		CBitrixComponent::clearComponentCache('bitrix:news.list', $SITE_ID);
		CBitrixComponent::clearComponentCache('bitrix:news.detail', $SITE_ID);
	}

	static function AjaxAuth(){
		if(!defined('ADMIN_SECTION') && isset($_REQUEST['auth_service_id']) && $_REQUEST['auth_service_id'])
		{
			if($_REQUEST['auth_service_id']):
				global $APPLICATION, $CACHE_MANAGER;?>
				<?$APPLICATION->IncludeComponent(
					"bitrix:system.auth.form",
					"popup",
					array(
						"PROFILE_URL" => "",
						"SHOW_ERRORS" => "Y",
						"POPUP_AUTH" => "Y"
					)
				);?>
			<?endif;?>
		<?}
	}

	static function GetSections($arItems, $arParams){
		$arSections = array(
			'PARENT_SECTIONS' => array(),
			'CHILD_SECTIONS' => array(),
			'ALL_SECTIONS' => array(),
		);
		if(is_array($arItems) && $arItems)
		{
			$arSectionsIDs = array();
			foreach($arItems as $arItem)
			{
				if($SID = $arItem['IBLOCK_SECTION_ID'])
					$arSectionsIDs[] = $SID;
			}
			if($arSectionsIDs)
			{
				$arSections['ALL_SECTIONS'] = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
				$bCheckRoot = false;
				foreach($arSections['ALL_SECTIONS'] as $key => $arSection)
				{
					if($arSection['DEPTH_LEVEL'] > 1)
					{
						$bCheckRoot = true;
						$arSections['CHILD_SECTIONS'][$key] = $arSection;
						unset($arSections['ALL_SECTIONS'][$key]);

						$arFilter = array('IBLOCK_ID'=>$arSection['IBLOCK_ID'], '<=LEFT_BORDER' => $arSection['LEFT_MARGIN'], '>=RIGHT_BORDER' => $arSection['RIGHT_MARGIN'], 'DEPTH_LEVEL' => 1);
						$arSelect = array('ID', 'SORT', 'IBLOCK_ID', 'NAME');
						$arParentSection = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arFilter, false, $arSelect);

						$arSections['ALL_SECTIONS'][$arParentSection['ID']]['SECTION'] = $arParentSection;
						$arSections['ALL_SECTIONS'][$arParentSection['ID']]['CHILD_IDS'][$arSection['ID']] = $arSection['ID'];

						$arSections['PARENT_SECTIONS'][$arParentSection['ID']] = $arParentSection;
					}
					else
					{
						$arSections['ALL_SECTIONS'][$key]['SECTION'] = $arSection;
						$arSections['PARENT_SECTIONS'][$key] = $arSection;
					}
				}

				if($bCheckRoot)
				{
					// get root sections
					$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y', 'DEPTH_LEVEL' => 1, 'ID' => array_keys($arSections['ALL_SECTIONS']));
					$arSelect = array('ID', 'SORT', 'IBLOCK_ID', 'NAME');
					$arRootSections = CCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), $arFilter, false, $arSelect);
					foreach($arRootSections as $arSection)
					{
						$arSections['ALL_SECTIONS']['SORTED'][$arSection['ID']] = $arSections['ALL_SECTIONS'][$arSection['ID']];
						unset($arSections['ALL_SECTIONS'][$arSection['ID']]);
					}
					foreach($arSections['ALL_SECTIONS']['SORTED'] as $key => $arSection)
					{
						$arSections['ALL_SECTIONS'][$key] = $arSection;
					}
					unset($arSections['ALL_SECTIONS']['SORTED']);
				}
			}
		}
		return $arSections;
	}

	static function ShowPageType($type = 'indexblocks'){
		global $APPLICATION, $arTheme;
		$path = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/page_blocks/'.$type.'_';
		$file = null;
		if(is_array($arTheme) && $arTheme)
		{
			switch($type):
				case 'page_contacts':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'contacts/'.$type);
					$file = $path.'_'.$arTheme['PAGE_CONTACTS']['VALUE'].'.php';
					break;
				case 'search_title_component':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
					$file = $path.'site-search.php';
					break;
				case 'basket_component':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
					$file = $path.'site-basket.php';
					break;
				case 'auth_component':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/footer/');
					$file = $path.'site-auth.php';
					break;
				case 'bottom_counter':
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.'include/');
					$file = $path.'invis-counter.php';
					break;
				case 'page_width':
					$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/width-'.$arTheme['PAGE_WIDTH']['VALUE'].'.css');
					break;
				case 'h1_style':					
					if ($arTheme['H1_STYLE']['VALUE']=='Normal') {
						$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-normal.css');
					}elseif(1) {
						$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-bold.css');
					}
					break;					
				case 'footer':
					$file = $path.$arTheme['FOOTER_TYPE']['VALUE'].'.php';
					break;
				case 'header':
					$file = $path.$arTheme['HEADER_TYPE']['VALUE'].'.php';
					break;
				case 'header_fixed':
					$file = $path.$arTheme['TOP_MENU_FIXED']['DEPENDENT_PARAMS']['HEADER_FIXED']['VALUE'].'.php';
					break;
				case 'header_mobile':
					$file = $path.$arTheme['HEADER_MOBILE']['VALUE'].'.php';
					break;
				case 'header_mobile_menu':
					$file = $path.$arTheme['HEADER_MOBILE_MENU']['VALUE'].'.php';
					break;
				case 'page_title':
					$file = $path.$arTheme['PAGE_TITLE']['VALUE'].'.php';
					break;
				default:
					$path = str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].'/'.SITE_DIR.$type);
					$file = $path.'_'.$arTheme['INDEX_TYPE']['VALUE'].'.php';
					break;
			endswitch;
			if ($file) {
				@include_once $file;
			}
		}
	}

	static function ShowLogo(){
		global $arSite;
		$arTheme = self::GetFrontParametrsValues();
		return '<a href="'.SITE_DIR.'"><img src="'.$arTheme["LOGO_IMAGE"].'" alt="'.$arSite["SITE_NAME"].'" title="'.$arSite["SITE_NAME"].'" /></a>';
	}

	static function GetBackParametrsValues($SITE_ID, $bStatic = true){
		if($bStatic)
			static $arValues;

		if($bStatic && $arValues === NULL || !$bStatic){
			$arDefaultValues = $arValues = $arNestedValues = array();
			$bNestedParams = false;
			if(self::$arParametrsList && is_array(self::$arParametrsList))
			{
				foreach(self::$arParametrsList as $blockCode => $arBlock)
				{
					if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS']))
					{
						foreach($arBlock['OPTIONS'] as $optionCode => $arOption)
						{
							if($arOption['TYPE'] !== 'note' && $arOption['TYPE'] !== 'includefile'){
								if($arOption['TYPE'] === 'array'){
									$itemsKeysCount = COption::GetOptionString(self::MODULE_ID, $optionCode, '0', $SITE_ID);
									if($arOption['OPTIONS'] && is_array($arOption['OPTIONS'])){
										for($itemKey = 0, $cnt = $itemsKeysCount + 1; $itemKey < $cnt; ++$itemKey){
											$_arParameters = array();
											$arOptionsKeys = array_keys($arOption['OPTIONS']);
											foreach($arOptionsKeys as $_optionKey){
												$arrayOptionItemCode = $optionCode.'_array_'.$_optionKey.'_'.$itemKey;
												$arValues[$arrayOptionItemCode] = COption::GetOptionString(self::MODULE_ID, $arrayOptionItemCode, '', $SITE_ID);
												$arDefaultValues[$arrayOptionItemCode] = $arOption['OPTIONS'][$_optionKey]['DEFAULT'];
											}
										}
									}
									$arValues[$optionCode] = $itemsKeysCount;
									$arDefaultValues[$optionCode] = 0;
								}
								else{
									$arDefaultValues[$optionCode] = $arOption['DEFAULT'];
									$arValues[$optionCode] = COption::GetOptionString(self::MODULE_ID, $optionCode, $arOption['DEFAULT'], $SITE_ID);

									if(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS']) //get nested params default value
									{
										if($arOption['TYPE'] == 'selectbox' && (isset($arOption['LIST'])) && $arOption['LIST'])
										{
											$bNestedParams = true;
											$arNestedValues[$optionCode] = $arOption['LIST'];
											foreach($arOption['LIST'] as $key => $value)
											{
												if($arOption['SUB_PARAMS'][$key])
												{
													foreach($arOption['SUB_PARAMS'][$key] as $key2 => $arSubOptions)
														$arDefaultValues[$key.'_'.$key2] = $arSubOptions['DEFAULT'];
												}
											}
										}
									}

									if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) //get dependent params default value
									{
										foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOption)
										{
											$arDefaultValues[$key] = $arSubOption['DEFAULT'];
											$arValues[$key] = COption::GetOptionString(self::MODULE_ID, $key, $arSubOption['DEFAULT'], $SITE_ID);
										}
									}
								}
							}
						}
					}
				}
			}
			if($arNestedValues && $bNestedParams) //get nested params bd value
			{
				foreach($arNestedValues as $key => $arAllValues)
				{
					$arTmpValues = array();
					foreach($arAllValues as $key2 => $arOptionValue)
					{
						$arTmpValues = unserialize(COption::GetOptionString(self::MODULE_ID, 'NESTED_OPTIONS_'.$key.'_'.$key2, serialize(array()), $SITE_ID));
						if($arTmpValues)
						{
							foreach($arTmpValues as $key3 => $value)
							{
								$arValues[$key2.'_'.$key3] = $value;
							}
						}
					}

				}
			}

			if($arValues && is_array($arValues))
			{
				foreach($arValues as $optionCode => $arOption)
				{
					if(!isset($arDefaultValues[$optionCode]))
						unset($arValues[$optionCode]);
				}
			}

			if($arDefaultValues && is_array($arDefaultValues))
			{
				foreach($arDefaultValues as $optionCode => $arOption)
				{
					if(!isset($arValues[$optionCode]))
						$arValues[$optionCode] = $arOption;
				}
			}

			foreach($arValues as $key => $value)
			{
				if($key == 'LOGO_IMAGE' || $key == 'FAVICON_IMAGE' || $key == 'APPLE_TOUCH_ICON_IMAGE'){
					$arValue = unserialize(\Bitrix\Main\Config\Option::get(self::MODULE_ID, $key, serialize(array()), $SITE_ID));
					$arValue = (array)$arValue;
					$fileID = $arValue ? current($arValue) : false;

					if($key === 'FAVICON_IMAGE')
						$arValues[$key] = str_replace('//', '/', SITE_DIR.'/favicon.ico');

					if($fileID)
					{
						if($key !== 'FAVICON_IMAGE')
							$arValues[$key] = CFIle::GetPath($fileID);
					}
					else
					{
						if($key === 'APPLE_TOUCH_ICON_IMAGE')
							$arValues[$key] = str_replace('//', '/', SITE_DIR.'/include/apple-touch-icon.png');
						elseif($key === 'LOGO_IMAGE')
							$arValues[$key] = str_replace('//', '/', SITE_DIR.'/logo.png');
					}

					if(!file_exists(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$arValues[$key]))){
						$arValues[$key] = '';
					}
					else
					{
						if($key === 'FAVICON_IMAGE')
							$arValues[$key] .= '?'.filemtime(str_replace('//', '/', $_SERVER['DOCUMENT_ROOT'].$arValues[$key]));
					}

				}
			}

			// replace #SITE_DIR#
			if(!defined('ADMIN_SECTION'))
			{
				if($arValues && is_array($arValues))
				{
					foreach($arValues as $optionCode => $arOption)
					{
						if(!is_array($arOption))
							$arValues[$optionCode] = str_replace('#SITE_DIR#', SITE_DIR, $arOption);
					}
				}
			}
		}

		return $arValues;
	}

	static function GetFrontParametrsValues($SITE_ID){
		if(!strlen($SITE_ID))
			$SITE_ID = SITE_ID;
		$arBackParametrs = self::GetBackParametrsValues($SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y')
			$arValues = array_merge((array)$arBackParametrs, (array)$_SESSION['THEME'][$SITE_ID]);
		else
			$arValues = (array)$arBackParametrs;

		return $arValues;
	}

	static function GetFrontParametrValue($optionCode, $SITE_ID){
		static $arFrontParametrs;

		if(!isset($arFrontParametrs)){
			$arFrontParametrs = self::GetFrontParametrsValues($SITE_ID);
		}

		return $arFrontParametrs[$optionCode];
	}

	static function ShowAdminRow($optionCode, $arOption, $arTab, $arControllerOption){
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
		$optionsSiteID = $arTab["SITE_ID"];
		$isArrayItem = strpos($optionCode, '_array_') !== false;
		?>
		<?if($optionType == "dynamic_iblock"):?>
			<?if(\Bitrix\Main\Loader::IncludeModule('iblock')):?>
				<td colspan="2">
					<div class="title"  align="center"><b><?=$optionName;?></b></div>
					<?
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
							$arItem['THEME_VALUE'] = COption::GetOptionString(self::MODULE_ID, htmlspecialcharsbx($optionCode)."_".htmlspecialcharsbx(strtoupper($arItem['CODE'])), '', $optionsSiteID);
							$arIblocks[] = $arItem;
						}
					}
					if($arIblocks):?>
						<table width="100%">
							<?foreach($arIblocks as $arIblock):?>
								<tr>
									<td class="adm-detail-content-cell-l" width="50%">
										<?=GetMessage("SUCCESS_SEND_FORM", array("#IBLOCK_CODE#" => $arIblock["NAME"]));?>
									</td>
									<td class="adm-detail-content-cell-r" width="50%">
										<input type="text" <?=((isset($arOption['PARAMS']) && isset($arOption['PARAMS']['WIDTH'])) ? 'style="width:'.$arOption['PARAMS']['WIDTH'].'"' : '');?> <?=$optionController?> size="<?=$optionSize?>" maxlength="255" value="<?=htmlspecialcharsbx($arIblock['THEME_VALUE'])?>" name="<?=htmlspecialcharsbx($optionCode)."_".htmlspecialcharsbx($arIblock['CODE'])."_".$optionsSiteID?>" <?=$optionDisabled?>>
									</td>
								</tr>
							<?endforeach;?>
						</table>
					<?endif;?>
				</td>
			<?endif;?>
		<?elseif($optionType == "note"):?>
			<?if($optionCode == 'USE_FORMS_GOALS_NOTE'){
				$FORMS_GOALS_LIST = '';
				$arIblocksIDs = array();
				if(CCache::$arIBlocks[$optionsSiteID]['aspro_digital_form'] && is_array(CCache::$arIBlocks[$optionsSiteID]['aspro_digital_form'])){
					foreach(CCache::$arIBlocks[$optionsSiteID]['aspro_digital_form'] as $arIDs){
						if($arIDs && is_array($arIDs)){
							foreach($arIDs as $IBLOCK_ID){
								if(CCache::$arIBlocksInfo && CCache::$arIBlocksInfo[$IBLOCK_ID] && is_array(CCache::$arIBlocksInfo[$IBLOCK_ID])){
									$FORMS_GOALS_LIST .= CCache::$arIBlocksInfo[$IBLOCK_ID]['NAME'].' - <i>goal_webform_success_'.$IBLOCK_ID.'</i><br />';
								}
							}
						}
					}
				}
				$arOption["NOTE"] = str_replace('#FORMS_GOALS_LIST#', $FORMS_GOALS_LIST, $arOption["NOTE"]);
			}
			?>
			<td colspan="2" align="center">
				<?=BeginNote('align="center"');?>
				<?=$arOption["NOTE"]?>
				<?=EndNote();?>
			</td>
		<?else:?>
			<?if(!$isArrayItem):?>
				<td class="<?=(in_array($optionType, array("multiselectbox", "textarea", "statictext", "statichtml")) ? "adm-detail-valign-top" : "")?>" width="50%">
					<?if($optionType == "checkbox"):?>
						<label for="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=$optionName?></label>
					<?else:?>
						<?=$optionName.($optionCode == "BASE_COLOR_CUSTOM" ? ' #' : '')?>
					<?endif;?>
					<?if(strlen($optionSup_text)):?>
						<span class="required"><sup><?=$optionSup_text?></sup></span>
					<?endif;?>
				</td>
			<?endif;?>
			<td<?=(!$isArrayItem ? ' width="50%"' : '')?>>
				<?if($optionType == "checkbox"):?>
					<input type="checkbox" <?=((isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) ? "class='depend-check'" : "");?> <?=$optionController?> id="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" value="Y" <?=$optionChecked?> <?=$optionDisabled?> <?=(strlen($optionDefault) ? $optionDefault : "")?>>
				<?elseif($optionType == "text" || $optionType == "password"):?>
					<input type="<?=$optionType?>" <?=((isset($arOption['PARAMS']) && isset($arOption['PARAMS']['WIDTH'])) ? 'style="width:'.$arOption['PARAMS']['WIDTH'].'"' : '');?> <?=$optionController?> size="<?=$optionSize?>" maxlength="255" value="<?=htmlspecialcharsbx($optionVal)?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionDisabled?> <?=($optionCode == "password" ? "autocomplete='off'" : "")?>>
				<?elseif($optionType == "selectbox"):?>
					<?
					if(!is_array($optionList)) $optionList = (array)$optionList;
					$arr_keys = array_keys($optionList);
					?>
					<select name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" <?=$optionController?> <?=$optionDisabled?>>
						<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
							<option value="<?=$arr_keys[$j]?>" <?if($optionVal == $arr_keys[$j]) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
						<?endfor;?>
					</select>
				<?elseif($optionType == "multiselectbox"):?>
					<?
					if(!is_array($optionList)) $optionList = (array)$optionList;
					$arr_keys = array_keys($optionList);
					if(!is_array($optionVal)) $optionVal = (array)$optionVal;
					?>
					<select size="<?=$optionSize?>" <?=$optionController?> <?=$optionDisabled?> multiple name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>[]" >
						<?for($j = 0, $c = count($arr_keys); $j < $c; ++$j):?>
							<option value="<?=$arr_keys[$j]?>" <?if(in_array($arr_keys[$j], $optionVal)) echo "selected"?>><?=htmlspecialcharsbx((is_array($optionList[$arr_keys[$j]]) ? $optionList[$arr_keys[$j]]["TITLE"] : $optionList[$arr_keys[$j]]))?></option>
						<?endfor;?>
					</select>
				<?elseif($optionType == "textarea"):?>
					<textarea <?=$optionController?> <?=$optionDisabled?> rows="<?=$optionRows?>" cols="<?=$optionCols?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>"><?=htmlspecialcharsbx($optionVal)?></textarea>
				<?elseif($optionType == "statictext"):?>
					<?=htmlspecialcharsbx($optionVal)?>
				<?elseif($optionType == "statichtml"):?>
					<?=$optionVal?>
				<?elseif($optionType == "file"):?>
					<?$val = unserialize(\Bitrix\Main\Config\Option::get(self::MODULE_ID, $optionCode, serialize(array()), $optionsSiteID));

					$arOption['MULTIPLE'] = 'N';
					if($optionCode == 'LOGO_IMAGE'){
						$arOption['WIDTH'] = 394;
						$arOption['HEIGHT'] = 140;
					}
					elseif($optionCode == 'FAVICON_IMAGE'){
						$arOption['WIDTH'] = 16;
						$arOption['HEIGHT'] = 16;
					}
					elseif($optionCode == 'APPLE_TOUCH_ICON_IMAGE'){
						$arOption['WIDTH'] = 180;
						$arOption['HEIGHT'] = 180;
					}
					self::__ShowFilePropertyField($optionCode."_".$optionsSiteID, $arOption, $val);?>
				<?elseif($optionType === 'includefile'):?>
					<?
					if(!is_array($arOption['INCLUDEFILE'])){
						$arOption['INCLUDEFILE'] = array($arOption['INCLUDEFILE']);
					}
					foreach($arOption['INCLUDEFILE'] as $includefile){
						$includefile = str_replace('//', '/', str_replace('#SITE_DIR#', $arTab['SITE_DIR'].'/', $includefile));
						if(strpos($includefile, '#') === false){
							$template = (isset($arOption['TEMPLATE']) && strlen($arOption['TEMPLATE']) ? 'include_area.php' : $arOption['TEMPLATE']);
							$href = (!strlen($includefile) ? "javascript:;" : "javascript: new BX.CAdminDialog({'content_url':'/bitrix/admin/public_file_edit.php?site=".$arTab['SITE_ID']."&bxpublic=Y&from=includefile&templateID=".TEMPLATE_NAME."&path=".$includefile."&lang=".LANGUAGE_ID."&template=".$template."&subdialog=Y&siteTemplateId=".TEMPLATE_NAME."','width':'1009','height':'503'}).Show();");
							?><a class="adm-btn" href="<?=$href?>" name="<?=htmlspecialcharsbx($optionCode)."_".$optionsSiteID?>" title="<?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?>"><?=GetMessage('OPTIONS_EDIT_BUTTON_TITLE')?></a>&nbsp;<?
						}
					}
					?>
				<?endif;?>
			</td>
		<?endif;?>
		<?
	}

	function CheckColor($strColor){
		$strColor = substr(str_replace('#', '', $strColor), 0, 6);
		$strColor = base_convert(base_convert($strColor, 16, 2), 2, 16);
		for($i = 0, $l = 6 - (function_exists('mb_strlen') ? mb_strlen($strColor) : strlen($strColor)); $i < $l; ++$i)
			$strColor = '0'.$strColor;
		return $strColor;
	}

	function UpdateFrontParametrsValues(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		if($arBackParametrs['THEME_SWITCHER'] === 'Y'){
			if($_REQUEST && isset($_REQUEST['BASE_COLOR'])){
				if($_REQUEST['THEME'] === 'default'){
					if(self::$arParametrsList && is_array(self::$arParametrsList)){
						foreach(self::$arParametrsList as $blockCode => $arBlock){
							unset($_SESSION['THEME'][SITE_ID]);
							$_SESSION['THEME'][SITE_ID] = null;

							if(isset($_SESSION['THEME_ACTION']))
							{
								unset($_SESSION['THEME_ACTION'][SITE_ID]);
								$_SESSION['THEME_ACTION'][SITE_ID] = null;
							}
						}
					}
					COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', '', SITE_ID);
				}
				else{
					if(self::$arParametrsList && is_array(self::$arParametrsList)){
						foreach(self::$arParametrsList as $blockCode => $arBlock){
							if($arBlock['OPTIONS'] && is_array($arBlock['OPTIONS'])){
								foreach($arBlock['OPTIONS'] as $optionCode => $arOption){
									if($arOption['THEME'] === 'Y'){
										if(isset($_REQUEST[$optionCode])){
											if($optionCode == 'BASE_COLOR_CUSTOM'){
												$_REQUEST[$optionCode] = self::CheckColor($_REQUEST[$optionCode]);
											}
											if($optionCode == 'BASE_COLOR' && $_REQUEST[$optionCode] === 'CUSTOM'){
												COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'Y', '', SITE_ID);
											}
											if(isset($arOption['LIST'])){
												if(isset($arOption['LIST'][$_REQUEST[$optionCode]])){
													$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
												}
												else{
													$_SESSION['THEME'][SITE_ID][$optionCode] = $arOption['DEFAULT'];
												}
											}
											else{
												$_SESSION['THEME'][SITE_ID][$optionCode] = $_REQUEST[$optionCode];
											}
											if($optionCode == 'ORDER_VIEW'){
												self::ClearSomeComponentsCache(SITE_ID);
											}

											if(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS']) //nested params
											{

												if($arOption['TYPE'] == 'selectbox' && isset($arOption['LIST']))
												{
													$propValue = $_SESSION['THEME'][SITE_ID][$optionCode];
													if($arOption['SUB_PARAMS'][$propValue])
													{
														foreach($arOption['SUB_PARAMS'][$propValue] as $subkey => $arSubvalue)
														{
															if($_REQUEST[$propValue.'_'.$subkey])
																$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = $_REQUEST[$propValue.'_'.$subkey];
															else
															{
																if($arSubvalue['TYPE'] == 'checkbox')
																	$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = 'N';
																else
																	$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = $arSubvalue['DEFAULT'];
															}
														}
													}
												}
											}

											if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) //dependent params
											{
												foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOptions)
												{
													if($arSubOptions['THEME'] == 'Y')
													{
														if($_REQUEST[$key])
															$_SESSION['THEME'][SITE_ID][$key] = $_REQUEST[$key];
														else
														{
															if($arSubOptions['TYPE'] == 'checkbox')
															{
																if(isset($_SESSION['THEME_ACTION']) && (isset($_SESSION['THEME_ACTION'][SITE_ID][$key]) && $_SESSION['THEME_ACTION'][SITE_ID][$key]))
																{
																	$_SESSION['THEME'][SITE_ID][$key] = $_SESSION['THEME_ACTION'][SITE_ID][$key];
																	unset($_SESSION['THEME_ACTION'][SITE_ID][$key]);
																}
																else
																	$_SESSION['THEME'][SITE_ID][$key] = 'N';
															}
															else
															{
																if(isset($_SESSION['THEME_ACTION']) && (isset($_SESSION['THEME_ACTION'][SITE_ID][$key]) && $_SESSION['THEME_ACTION'][SITE_ID][$key]))
																{
																	$_SESSION['THEME'][SITE_ID][$key] = $_SESSION['THEME_ACTION'][SITE_ID][$key];
																	unset($_SESSION['THEME_ACTION'][SITE_ID][$key]);
																}
																else
																	$_SESSION['THEME'][SITE_ID][$key] = $arSubOptions['DEFAULT'];
															}
														}
													}
												}
											}

											$bChanged = true;
										}
										else
										{
											if($arOption['TYPE'] == 'checkbox' && !$_REQUEST[$optionCode])
											{
												$_SESSION['THEME'][SITE_ID][$optionCode] = 'N';
												if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) //dependent params save
												{
													foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOptions)
													{
														if($arSubOptions['THEME'] == 'Y')
														{
															if(isset($_SESSION['THEME'][SITE_ID][$key]))
																$_SESSION['THEME_ACTION'][SITE_ID][$key] = $_SESSION['THEME'][SITE_ID][$key];
															else
																$_SESSION['THEME_ACTION'][SITE_ID][$key] = $arBackParametrs[$key];
														}
													}
												}
											}

											if(isset($arOption['SUB_PARAMS']) && $arOption['SUB_PARAMS']) //nested params
											{

												if($arOption['TYPE'] == 'selectbox' && isset($arOption['LIST']))
												{
													$propValue = $_SESSION['THEME'][SITE_ID][$optionCode];
													if($arOption['SUB_PARAMS'][$propValue])
													{
														foreach($arOption['SUB_PARAMS'][$propValue] as $subkey => $arSubvalue)
														{
															if($_REQUEST[$propValue.'_'.$subkey])
																$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = $_REQUEST[$propValue.'_'.$subkey];
															else
																$_SESSION['THEME'][SITE_ID][$propValue.'_'.$subkey] = 'N';
														}
													}
												}
											}
										}
									}
								}
							}
						}
					}
					if(isset($_REQUEST["backurl"]) && $_REQUEST["backurl"])
						LocalRedirect($_REQUEST["backurl"]);
				}
				if(isset($_REQUEST["BASE_COLOR"]) && $_REQUEST["BASE_COLOR"])
					LocalRedirect($_SERVER["HTTP_REFERER"]);
			}
		}
		else{
			unset($_SESSION['THEME'][SITE_ID]);
			if(isset($_SESSION['THEME_ACTION'][SITE_ID]))
				unset($_SESSION['THEME_ACTION'][SITE_ID]);
		}
	}

	function GenerateThemes(){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		$arBaseColors = self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['LIST'];
		$isCustomTheme = $_SESSION['THEME'][SITE_ID]['BASE_COLOR'] === 'CUSTOM';

		$bNeedGenerateAllThemes = COption::GetOptionString(self::MODULE_ID, 'NeedGenerateThemes', 'N', SITE_ID) === 'Y';
		$bNeedGenerateCustomTheme = COption::GetOptionString(self::MODULE_ID, 'NeedGenerateCustomTheme', 'N', SITE_ID) === 'Y';

		$baseColorCustom = '';
		$lastGeneratedBaseColorCustom = COption::GetOptionString(self::MODULE_ID, 'LastGeneratedBaseColorCustom', '', SITE_ID);
		if(isset(self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR_CUSTOM'])){
			$baseColorCustom = $arBackParametrs['BASE_COLOR_CUSTOM'] = str_replace('#', '', $arBackParametrs['BASE_COLOR_CUSTOM']);
			if($arBackParametrs['THEME_SWITCHER'] === 'Y' && strlen($_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM'])){
				$baseColorCustom = $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM'] = str_replace('#', '', $_SESSION['THEME'][SITE_ID]['BASE_COLOR_CUSTOM']);
			}
		}

		$bGenerateAll = self::devMode || $bNeedGenerateAllThemes;
		$bGenerateCustom = $bGenerateAll || $bNeedGenerateCustomTheme || ($arBackParametrs['THEME_SWITCHER'] === 'Y' && $isCustomTheme && strlen($baseColorCustom) && $baseColorCustom != $lastGeneratedBaseColorCustom);
		if($arBaseColors && is_array($arBaseColors) && ($bGenerateAll || $bGenerateCustom)){
			if(!class_exists('lessc')){
				include_once 'lessc.inc.php';
			}
			$less = new lessc;
			try{
				foreach($arBaseColors as $colorCode => $arColor){
					if(($bCustom = ($colorCode == 'CUSTOM')) && $bGenerateCustom){
						if(strlen($baseColorCustom)){
							$less->setVariables(array('bcolor' => (strlen($baseColorCustom) ? '#'.$baseColorCustom : $arBaseColors[self::$arParametrsList['MAIN']['OPTIONS']['BASE_COLOR']['DEFAULT']]['COLOR'])));
						}
					}
					elseif($bGenerateAll){
						$less->setVariables(array('bcolor' => $arColor['COLOR']));
					}

					if($bGenerateAll || ($bCustom && $bGenerateCustom)){
						if(defined('SITE_TEMPLATE_PATH')){
							$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/themes/'.$colorCode.($colorCode !== 'CUSTOM' ? '' : '_'.SITE_ID).'/';
							if(!is_dir($themeDirPath)) mkdir($themeDirPath, 0755, true);
							$output = $less->compileFile(__DIR__.'/../../css/colors.less', $themeDirPath.'colors.css');
							if($output && $bCustom){
								COption::SetOptionString(self::MODULE_ID, 'LastGeneratedBaseColorCustom', $baseColorCustom, '', SITE_ID);
							}
						}
					}
				}
			}
			catch(exception $e){
				echo 'Fatal error: '.$e->getMessage();
				die();
			}

			if($bNeedGenerateAllThemes){
				COption::SetOptionString(self::MODULE_ID, "NeedGenerateThemes", 'N', '', SITE_ID);
			}
			if($bNeedGenerateCustomTheme){
				COption::SetOptionString(self::MODULE_ID, "NeedGenerateCustomTheme", 'N', '', SITE_ID);
			}
		}
	}

	public function sendAsproBIAction($action = 'unknown') {
		if(CModule::IncludeModule('main')){

		}
	}

	public function correctInstall(){
		if(CModule::IncludeModule('main')){
			if(COption::GetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED') == 'Y'){
				require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/wizard.php');
				@set_time_limit(0);
				if(!CWizardUtil::DeleteWizard(self::PARTNER_NAME.':'.self::SOLUTION_NAME)){
					if(!DeleteDirFilesEx($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/')){
						self::removeDirectory($_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards/'.self::PARTNER_NAME.'/'.self::SOLUTION_NAME.'/');
					}
				}

				UnRegisterModuleDependences('main', 'OnBeforeProlog', self::MODULE_ID, __CLASS__, 'correctInstall');
				COption::SetOptionString(self::MODULE_ID, 'WIZARD_DEMO_INSTALLED', 'N');
			}
		}
	}

	protected function getBitrixEdition(){
		$edition = 'UNKNOWN';

		if(CModule::IncludeModule('main')){
			include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/update_client.php');
			$arUpdateList = CUpdateClient::GetUpdatesList(($errorMessage = ''), 'ru', 'Y');
			if(array_key_exists('CLIENT', $arUpdateList) && $arUpdateList['CLIENT'][0]['@']['LICENSE']){
				$edition = $arUpdateList['CLIENT'][0]['@']['LICENSE'];
			}
		}

		return $edition;
	}

	protected function removeDirectory($dir){
		if($objs = glob($dir.'/*')){
			foreach($objs as $obj){
				if(is_dir($obj)){
					self::removeDirectory($obj);
				}
				else{
					if(!@unlink($obj)){
						if(chmod($obj, 0777)){
							@unlink($obj);
						}
					}
				}
			}
		}
		if(!@rmdir($dir)){
			if(chmod($dir, 0777)){
				@rmdir($dir);
			}
		}
	}

	function get_file_info($fileID){
		$file = CFile::GetFileArray($fileID);
		$pos = strrpos($file['FILE_NAME'], '.');
		$file['FILE_NAME'] = substr($file['FILE_NAME'], $pos);
		if(!$file['FILE_SIZE']){
			// bx bug in some version
			$file['FILE_SIZE'] = filesize($_SERVER['DOCUMENT_ROOT'].$file['SRC']);
		}
		$frm = explode('.', $file['FILE_NAME']);
		$frm = $frm[1];
		if($frm == 'doc' || $frm == 'docx'){
			$type = 'doc';
		}
		elseif($frm == 'xls' || $frm == 'xlsx'){
			$type = 'xls';
		}
		elseif($frm == 'jpg' || $frm == 'jpeg'){
			$type = 'jpg';
		}
		elseif($frm == 'png'){
			$type = 'png';
		}
		elseif($frm == 'ppt'){
			$type = 'ppt';
		}
		elseif($frm == 'tif'){
			$type = 'tif';
		}
		elseif($frm == 'txt'){
			$type = 'txt';
		}
		else{
			$type = 'pdf';
		}
		return $arr = array('TYPE' => $type, 'FILE_SIZE' => $file['FILE_SIZE'], 'SRC' => $file['SRC'], 'DESCRIPTION' => $file['DESCRIPTION'], 'ORIGINAL_NAME' => $file['ORIGINAL_NAME']);
	}

	function filesize_format($filesize){
		$formats = array(GetMessage('CT_NAME_b'), GetMessage('CT_NAME_KB'), GetMessage('CT_NAME_MB'), GetMessage('CT_NAME_GB'), GetMessage('CT_NAME_TB'));
		$format = 0;
		while($filesize > 1024 && count($formats) != ++$format){
			$filesize = round($filesize / 1024, 1);
		}
		$formats[] = GetMessage('CT_NAME_TB');
		return $filesize.' '.$formats[$format];
	}

	function getChilds($input, &$start = 0, $level = 0){
		$arIblockItemsMD5 = array();

		if(!$level){
			$lastDepthLevel = 1;
			if($input && is_array($input)){
				foreach($input as $i => $arItem){
					if($arItem['DEPTH_LEVEL'] > $lastDepthLevel){
						if($i > 0){
							$input[$i - 1]['IS_PARENT'] = 1;
						}
					}
					$lastDepthLevel = $arItem['DEPTH_LEVEL'];
				}
			}
		}

		$childs = array();
		$count = count($input);
		for($i = $start; $i < $count; ++$i){
			$item = $input[$i];
			if(!isset($item)){
				continue;
			}
			if($level > $item['DEPTH_LEVEL'] - 1){
				break;
			}
			else{
				if(!empty($item['IS_PARENT'])){
					$i++;
					$item['CHILD'] = self::getChilds($input, $i, $level + 1);
					$i--;
				}

				$childs[] = $item;
			}
		}
		$start = $i;

		if(is_array($childs)){
			foreach($childs as $j => $item){
				if($item['PARAMS']){
					$md5 = md5($item['TEXT'].$item['LINK'].$item['SELECTED'].$item['PERMISSION'].$item['ITEM_TYPE'].$item['IS_PARENT'].serialize($item['ADDITIONAL_LINKS']).serialize($item['PARAMS']));

					// check if repeat in one section chids list
					if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']])){
						if(isset($arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level]) || ($item['DEPTH_LEVEL'] === 1 && !$level)){
							unset($childs[$j]);
							continue;
						}
					}
					if(!isset($arIblockItemsMD5[$md5])){
						$arIblockItemsMD5[$md5] = array($item['PARAMS']['DEPTH_LEVEL'] => array($level => true));
					}
					else{
						$arIblockItemsMD5[$md5][$item['PARAMS']['DEPTH_LEVEL']][$level] = true;
					}
				}
			}
		}

		if(!$level){
			$arIblockItemsMD5 = array();
		}

		return $childs;
	}

	function sort_sections_by_field($arr, $name){
		$count = count($arr);
		for($i = 0; $i < $count; $i++){
			for($j = 0; $j < $count; $j++){
				if(strtoupper($arr[$i]['NAME']) < strtoupper($arr[$j]['NAME'])){
					$tmp = $arr[$i];
					$arr[$i] = $arr[$j];
					$arr[$j] = $tmp;
				}
			}
		}
		return $arr;
	}

	function getIBItems($prop, $checkNoImage){
		$arID = array();
		$arItems = array();
		$arAllItems = array();

		if($prop && is_array($prop)){
			foreach($prop as $reviewID){
				$arID[]=$reviewID;
			}
		}
		if($checkNoImage) $empty=false;
		$arItems = self::cacheElement(false, array('ID' => $arID, 'ACTIVE' => 'Y'));
		if($arItems && is_array($arItems)){
			foreach($arItems as $key => $arItem){
				if($checkNoImage){
					if(empty($arProject['PREVIEW_PICTURE'])){
						$empty=true;
					}
				}
				$arAllItems['ITEMS'][$key] = $arItem;
				if($arItem['DETAIL_PICTURE']) $arAllItems['ITEMS'][$key]['DETAIL'] = CFile::GetFileArray( $arItem['DETAIL_PICTURE'] );
				if($arItem['PREVIEW_PICTURE']) $arAllItems['ITEMS'][$key]['PREVIEW'] = CFile::ResizeImageGet( $arItem['PREVIEW_PICTURE'], array('width' => 425, 'height' => 330), BX_RESIZE_IMAGE_EXACT, true );
			}
		}
		if($checkNoImage) $arAllItems['NOIMAGE'] = 'YES';

		return $arAllItems;
	}

	function getSectionChilds($PSID, &$arSections, &$arSectionsByParentSectionID, &$arItemsBySectionID, &$aMenuLinksExt){
		if($arSections && is_array($arSections)){
			foreach($arSections as $arSection){
				if($arSection['IBLOCK_SECTION_ID'] == $PSID){
					$arItem = array($arSection['NAME'], $arSection['SECTION_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => $arSection['DEPTH_LEVEL']));
					$arItem[3]['IS_PARENT'] = (isset($arItemsBySectionID[$arSection['ID']]) || isset($arSectionsByParentSectionID[$arSection['ID']]) ? 1 : 0);
					$aMenuLinksExt[] = $arItem;
					if($arItem[3]['IS_PARENT']){
						// subsections
						self::getSectionChilds($arSection['ID'], $arSections, $arSectionsByParentSectionID, $arItemsBySectionID, $aMenuLinksExt);
						// section elements
						if($arItemsBySectionID[$arSection['ID']] && is_array($arItemsBySectionID[$arSection['ID']])){
							foreach($arItemsBySectionID[$arSection['ID']] as $arItem){
								if(is_array($arItem['DETAIL_PAGE_URL'])){
									if(isset($arItem['CANONICAL_PAGE_URL'])){
										$arItem['DETAIL_PAGE_URL'] = $arItem['CANONICAL_PAGE_URL'];
									}
									else{
										$arItem['DETAIL_PAGE_URL'] = $arItem['DETAIL_PAGE_URL'][key($arItem['DETAIL_PAGE_URL'])];
									}
								}
								$aMenuLinksExt[] = array($arItem['NAME'], $arItem['DETAIL_PAGE_URL'], array(), array('FROM_IBLOCK' => 1, 'DEPTH_LEVEL' => ($arSection['DEPTH_LEVEL'] + 1), 'IS_ITEM' => 1));
							}
						}
					}
				}
			}
		}
	}

	function isChildsSelected($arChilds){
		if($arChilds && is_array($arChilds)){
			foreach($arChilds as $arChild){
				if($arChild['SELECTED']){
					return $arChild;
				}
			}
		}
		return false;
	}

	function SetJSOptions(){
		$arFrontParametrs = CDigital::GetFrontParametrsValues(SITE_ID);
		$tmp = $arFrontParametrs['DATE_FORMAT'];
		$DATE_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y'))));
		$VALIDATE_DATE_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4}$'))));
		$DATE_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON')))));
		$DATETIME_MASK = ($tmp == 'DOT' ? 'd.m.y' : ($tmp == 'HYPHEN' ? 'd-m-y' : ($tmp == 'SPACE' ? 'd m y' : ($tmp == 'SLASH' ? 'd/m/y' : 'd:m:y')))).' h:s';
		$DATETIME_PLACEHOLDER = ($tmp == 'DOT' ? GetMessage('DATE_FORMAT_DOT') : ($tmp == 'HYPHEN' ? GetMessage('DATE_FORMAT_HYPHEN') : ($tmp == 'SPACE' ? GetMessage('DATE_FORMAT_SPACE') : ($tmp == 'SLASH' ? GetMessage('DATE_FORMAT_SLASH') : GetMessage('DATE_FORMAT_COLON'))))).' '.GetMessage('TIME_FORMAT_COLON');
		$VALIDATE_DATETIME_MASK = ($tmp == 'DOT' ? '^[0-9]{1,2}\.[0-9]{1,2}\.[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'HYPHEN' ? '^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SPACE' ? '^[0-9]{1,2} [0-9]{1,2} [0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : ($tmp == 'SLASH' ? '^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$' : '^[0-9]{1,2}\:[0-9]{1,2}\:[0-9]{4} [0-9]{1,2}\:[0-9]{1,2}$'))));
		?>
		<script type='text/javascript'>
		var arBasketItems = {};
		var arDigitalOptions = ({
			'SITE_DIR' : '<?=SITE_DIR?>',
			'SITE_ID' : '<?=SITE_ID?>',
			'SITE_TEMPLATE_PATH' : '<?=SITE_TEMPLATE_PATH?>',
			'THEME' : ({
				'THEME_SWITCHER' : '<?=$arFrontParametrs['THEME_SWITCHER']?>',
				'BASE_COLOR' : '<?=$arFrontParametrs['BASE_COLOR']?>',
				'BASE_COLOR_CUSTOM' : '<?=$arFrontParametrs['BASE_COLOR_CUSTOM']?>',
				'TOP_MENU' : '<?=$arFrontParametrs['TOP_MENU']?>',
				'TOP_MENU_FIXED' : '<?=$arFrontParametrs['TOP_MENU_FIXED']?>',
				'COLORED_LOGO' : '<?=$arFrontParametrs['COLORED_LOGO']?>',
				'SIDE_MENU' : '<?=$arFrontParametrs['SIDE_MENU']?>',
				'SCROLLTOTOP_TYPE' : '<?=$arFrontParametrs['SCROLLTOTOP_TYPE']?>',
				'SCROLLTOTOP_POSITION' : '<?=$arFrontParametrs['SCROLLTOTOP_POSITION']?>',
				'CAPTCHA_FORM_TYPE' : '<?=$arFrontParametrs['CAPTCHA_FORM_TYPE']?>',
				'PHONE_MASK' : '<?=$arFrontParametrs['PHONE_MASK']?>',
				'VALIDATE_PHONE_MASK' : '<?=$arFrontParametrs['VALIDATE_PHONE_MASK']?>',
				'DATE_MASK' : '<?=$DATE_MASK?>',
				'DATE_PLACEHOLDER' : '<?=$DATE_PLACEHOLDER?>',
				'VALIDATE_DATE_MASK' : '<?=($VALIDATE_DATE_MASK)?>',
				'DATETIME_MASK' : '<?=$DATETIME_MASK?>',
				'DATETIME_PLACEHOLDER' : '<?=$DATETIME_PLACEHOLDER?>',
				'VALIDATE_DATETIME_MASK' : '<?=($VALIDATE_DATETIME_MASK)?>',
				'VALIDATE_FILE_EXT' : '<?=$arFrontParametrs['VALIDATE_FILE_EXT']?>',
				'SOCIAL_VK' : '<?=$arFrontParametrs['SOCIAL_VK']?>',
				'SOCIAL_FACEBOOK' : '<?=$arFrontParametrs['SOCIAL_FACEBOOK']?>',
				'SOCIAL_TWITTER' : '<?=$arFrontParametrs['SOCIAL_TWITTER']?>',
				'SOCIAL_YOUTUBE' : '<?=$arFrontParametrs['SOCIAL_YOUTUBE']?>',
				'SOCIAL_ODNOKLASSNIKI' : '<?=$arFrontParametrs['SOCIAL_ODNOKLASSNIKI']?>',
				'SOCIAL_GOOGLEPLUS' : '<?=$arFrontParametrs['SOCIAL_GOOGLEPLUS']?>',
				'BANNER_WIDTH' : '<?=$arFrontParametrs['BANNER_WIDTH']?>',
				'TEASERS_INDEX' : '<?=$arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_TEASERS_INDEX']?>',
				'CATALOG_INDEX' : '<?=$arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_CATALOG_INDEX']?>',
				'PORTFOLIO_INDEX' : '<?=$arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_PORTFOLIO_INDEX']?>',
				'INSTAGRAMM_INDEX' : '<?=(isset($arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_INSTAGRAMM_INDEX']) ? $arFrontParametrs[$arFrontParametrs['INDEX_TYPE'].'_INSTAGRAMM_INDEX'] : 'Y')?>',
				'BIGBANNER_ANIMATIONTYPE' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONTYPE']?>',
				'BIGBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['BIGBANNER_SLIDESSHOWSPEED']?>',
				'BIGBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['BIGBANNER_ANIMATIONSPEED']?>',
				'PARTNERSBANNER_SLIDESSHOWSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_SLIDESSHOWSPEED']?>',
				'PARTNERSBANNER_ANIMATIONSPEED' : '<?=$arFrontParametrs['PARTNERSBANNER_ANIMATIONSPEED']?>',
				'ORDER_VIEW' : '<?=$arFrontParametrs['ORDER_VIEW']?>',
				'ORDER_BASKET_VIEW' : '<?=$arFrontParametrs['ORDER_BASKET_VIEW']?>',
				'URL_BASKET_SECTION' : '<?=$arFrontParametrs['URL_BASKET_SECTION']?>',
				'URL_ORDER_SECTION' : '<?=$arFrontParametrs['URL_ORDER_SECTION']?>',
				'PAGE_WIDTH' : '<?=$arFrontParametrs['PAGE_WIDTH']?>',
				'PAGE_CONTACTS' : '<?=$arFrontParametrs['PAGE_CONTACTS']?>',
				'HEADER_TYPE' : '<?=$arFrontParametrs['HEADER_TYPE']?>',
				'HEADER_TOP_LINE' : '<?=$arFrontParametrs['HEADER_TOP_LINE']?>',
				'HEADER_FIXED' : '<?=$arFrontParametrs['HEADER_FIXED']?>',
				'HEADER_MOBILE' : '<?=$arFrontParametrs['HEADER_MOBILE']?>',
				'HEADER_MOBILE_MENU' : '<?=$arFrontParametrs['HEADER_MOBILE_MENU']?>',
				'HEADER_MOBILE_MENU_SHOW_TYPE' : '<?=$arFrontParametrs['HEADER_MOBILE_MENU_SHOW_TYPE']?>',
				'TYPE_SEARCH' : '<?=$arFrontParametrs['TYPE_SEARCH']?>',
				'PAGE_TITLE' : '<?=$arFrontParametrs['PAGE_TITLE']?>',
				'INDEX_TYPE' : '<?=$arFrontParametrs['INDEX_TYPE']?>',
				'FOOTER_TYPE' : '<?=$arFrontParametrs['FOOTER_TYPE']?>',
				'FOOTER_TYPE' : '<?=$arFrontParametrs['FOOTER_TYPE']?>',
				'PRINT_BUTTON' : '<?=$arFrontParametrs['PRINT_BUTTON']?>',
				'SHOW_SMARTFILTER' : '<?=$arFrontParametrs['SHOW_SMARTFILTER']?>',
				'FILTER_VIEW' : '<?=$arFrontParametrs['FILTER_VIEW']?>',
				'YA_GOLAS' : '<?=$arFrontParametrs['YA_GOLAS']?>',
				'YA_COUNTER_ID' : '<?=$arFrontParametrs['YA_COUNTER_ID']?>',
				'USE_FORMS_GOALS' : '<?=$arFrontParametrs['USE_FORMS_GOALS']?>',
				'USE_SALE_GOALS' : '<?=$arFrontParametrs['USE_SALE_GOALS']?>',
				'USE_DEBUG_GOALS' : '<?=$arFrontParametrs['USE_DEBUG_GOALS']?>',
				'IS_BASKET_PAGE' : '<?=CDigital::IsBasketPage($arFrontParametrs["URL_BASKET_SECTION"])?>',
				'IS_ORDER_PAGE' : '<?=CDigital::IsBasketPage($arFrontParametrs["URL_ORDER_SECTION"])?>',
			})
		});
		</script>
		<?
		Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('options-block');
		self::checkBasketItems();
		Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('options-block', '');
	}

	static function __ShowFilePropertyField($name, $arOption, $values){
		global $bCopy, $historyId;
		if(!is_array($values)){
			$values = array($values);
		}

		if($bCopy || empty($values)){
			$values = array('n0' => 0);
		}

		$optionWidth = $arOption['WIDTH'] ? $arOption['WIDTH'] : 200;
		$optionHeight = $arOption['HEIGHT'] ? $arOption['HEIGHT'] : 100;


		if($arOption['MULTIPLE'] == 'N'){
			foreach($values as $key => $val){
				if(is_array($val)){
					$file_id = $val['VALUE'];
				}
				else{
					$file_id = $val;
				}
				if($historyId > 0){
					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
								'W' => $optionWidth,
								'H' => $optionHeight,
							),
						)
					);
				}
				else{

					echo CFileInput::Show($name.'['.$key.']', $file_id,
						array(
							'IMAGE' => $arOption['IMAGE'],
							'PATH' => 'Y',
							'FILE_SIZE' => 'Y',
							'DIMENSIONS' => 'Y',
							'IMAGE_POPUP' => 'Y',
							'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
							),
						),
						array(
							'upload' => true,
							'medialib' => true,
							'file_dialog' => true,
							'cloud' => true,
							'del' => true,
							'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
						)
					);
				}
				break;
			}
		}
		else{
			$inputName = array();
			foreach($values as $key => $val){
				if(is_array($val)){
					$inputName[$name.'['.$key.']'] = $val['VALUE'];
				}
				else{
					$inputName[$name.'['.$key.']'] = $val;
				}
			}
			if($historyId > 0){
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false);
			}
			else{
				echo CFileInput::ShowMultiple($inputName, $name.'[n#IND#]',
					array(
						'IMAGE' => $arOption['IMAGE'],
						'PATH' => 'Y',
						'FILE_SIZE' => 'Y',
						'DIMENSIONS' => 'Y',
						'IMAGE_POPUP' => 'Y',
						'MAX_SIZE' => array(
							'W' => $optionWidth,
							'H' => $optionHeight,
						),
					),
				false,
					array(
						'upload' => true,
						'medialib' => true,
						'file_dialog' => true,
						'cloud' => true,
						'del' => true,
						'description' => $arOption['WITH_DESCRIPTION'] == 'Y',
					)
				);
			}
		}
	}

	function IsCompositeEnabled(){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					if(method_exists('CHTMLPagesCache', 'isOn')){
						if (CHTMLPagesCache::isOn()){
							if(isset($arHTMLCacheOptions['AUTO_COMPOSITE']) && $arHTMLCacheOptions['AUTO_COMPOSITE'] === 'Y'){
								return 'AUTO_COMPOSITE';
							}
							else{
								return 'COMPOSITE';
							}
						}
					}
					else{
						if($arHTMLCacheOptions['COMPOSITE'] === 'Y'){
							return 'COMPOSITE';
						}
					}
				}
			}
		}

		return false;
	}

	function EnableComposite($auto = false){
		if(class_exists('CHTMLPagesCache')){
			if(method_exists('CHTMLPagesCache', 'GetOptions')){
				if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
					$arHTMLCacheOptions['COMPOSITE'] = 'Y';
					$arHTMLCacheOptions['AUTO_UPDATE'] = 'Y'; // standart mode
					$arHTMLCacheOptions['AUTO_UPDATE_TTL'] = '0'; // no ttl delay
					$arHTMLCacheOptions['AUTO_COMPOSITE'] = ($auto ? 'Y' : 'N'); // auto composite mode
					CHTMLPagesCache::SetEnabled(true);
					CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
					bx_accelerator_reset();
				}
			}
		}
	}

	function GetCurrentElementFilter(&$arVariables, &$arParams){
        $arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
        if($arParams['CHECK_DATES'] == 'Y'){
            $arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
        }
        if($arVariables['ELEMENT_ID']){
            $arFilter['ID'] = $arVariables['ELEMENT_ID'];
        }
        elseif(strlen($arVariables['ELEMENT_CODE'])){
            $arFilter['CODE'] = $arVariables['ELEMENT_CODE'];
        }
		if($arVariables['SECTION_ID']){
			$arFilter['SECTION_ID'] = ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false);
		}
		if($arVariables['SECTION_CODE']){
			$arFilter['SECTION_CODE'] = ($arVariables['SECTION_CODE'] ? $arVariables['SECTION_CODE'] : false);
		}
        if(!$arFilter['SECTION_ID'] && !$arFilter['SECTION_CODE']){
            unset($arFilter['SECTION_GLOBAL_ACTIVE']);
        }
        return $arFilter;
    }

	function GetCurrentSectionFilter(&$arVariables, &$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if($arVariables['SECTION_ID']){
			$arFilter['ID'] = $arVariables['SECTION_ID'];
		}
		if(strlen($arVariables['SECTION_CODE'])){
			$arFilter['CODE'] = $arVariables['SECTION_CODE'];
		}
		if(!$arVariables['SECTION_ID'] && !strlen($arFilter['CODE'])){
			$arFilter['ID'] = 0; // if section not found
		}
		return $arFilter;
	}

	function GetCurrentSectionElementFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'N');
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'SECTION_GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false))){
			unset($arFilter['SECTION_GLOBAL_ACTIVE']);
		}
		if(strlen($arParams['FILTER_NAME'])){
			$GLOBALS[$arParams['FILTER_NAME']] = (array)$GLOBALS[$arParams['FILTER_NAME']];
			foreach($arUnsetFilterFields = array('SECTION_ID', 'SECTION_CODE', 'SECTION_ACTIVE', 'SECTION_GLOBAL_ACTIVE') as $filterUnsetField){
				foreach($GLOBALS[$arParams['FILTER_NAME']] as $filterField => $filterValue){
					if(($p = strpos($filterUnsetField, $filterField)) !== false && $p < 2){
						unset($GLOBALS[$arParams['FILTER_NAME']][$filterField]);
					}
				}
			}
			if($GLOBALS[$arParams['FILTER_NAME']]){
				$arFilter = array_merge($arFilter, $GLOBALS[$arParams['FILTER_NAME']]);
			}
		}
		return $arFilter;
	}

	function GetCurrentSectionSubSectionFilter(&$arVariables, &$arParams, $CurrentSectionID = false){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID']);
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'GLOBAL_ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(!$arFilter['SECTION_ID'] = ($CurrentSectionID !== false ? $CurrentSectionID : ($arVariables['SECTION_ID'] ? $arVariables['SECTION_ID'] : false))){
			$arFilter['INCLUDE_SUBSECTIONS'] = 'N';array_merge($arFilter, array('INCLUDE_SUBSECTIONS' => 'N', 'DEPTH_LEVEL' => '1'));
			$arFilter['DEPTH_LEVEL'] = '1';
			unset($arFilter['GLOBAL_ACTIVE']);
		}
		return $arFilter;
	}

	function GetIBlockAllElementsFilter(&$arParams){
		$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'INCLUDE_SUBSECTIONS' => 'Y');
		if($arParams['CHECK_DATES'] == 'Y'){
			$arFilter = array_merge($arFilter, array('ACTIVE' => 'Y', 'ACTIVE_DATE' => 'Y'));
		}
		if(strlen($arParams['FILTER_NAME']) && (array)$GLOBALS[$arParams['FILTER_NAME']]){
			$arFilter = array_merge($arFilter, (array)$GLOBALS[$arParams['FILTER_NAME']]);
		}
		return $arFilter;
	}

	function CheckSmartFilterSEF($arParams, $component){
		if($arParams['SEF_MODE'] === 'Y' && strlen($arParams['FILTER_URL_TEMPLATE']) && is_object($component)){
			$arVariables = $arDefaultUrlTemplates404 = $arDefaultVariableAliases404 = $arDefaultVariableAliases = array();
			$smartBase = ($arParams["SEF_URL_TEMPLATES"]["section"] ? $arParams["SEF_URL_TEMPLATES"]["section"] : "#SECTION_ID#/");
			$arParams["SEF_URL_TEMPLATES"]["smart_filter"] = $smartBase."filter/#SMART_FILTER_PATH#/apply/";
			$arComponentVariables = array("SECTION_ID", "SECTION_CODE", "ELEMENT_ID", "ELEMENT_CODE", "action");
			$engine = new CComponentEngine($component);
			$engine->addGreedyPart("#SECTION_CODE_PATH#");
			$engine->addGreedyPart("#SMART_FILTER_PATH#");
			$engine->setResolveCallback(array("CIBlockFindTools", "resolveComponentEngine"));
			$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
			$componentPage = $engine->guessComponentPath($arParams["SEF_FOLDER"], $arUrlTemplates, $arVariables);
			if($componentPage === 'smart_filter'){
				$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);
				CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
				return $arResult = array("FOLDER" => $arParams["SEF_FOLDER"], "URL_TEMPLATES" => $arUrlTemplates, "VARIABLES" => $arVariables, "ALIASES" => $arVariableAliases);
			}
		}

		return false;
	}

	function AddMeta($arParams = array()){
		self::$arMetaParams = array_merge((array)self::$arMetaParams, (array)$arParams);
	}

	function SetMeta(){
		global $APPLICATION, $arSite;

		$PageH1 = $APPLICATION->GetTitle();
		$PageMetaTitleBrowser = $APPLICATION->GetPageProperty('title');
		$DirMetaTitleBrowser = $APPLICATION->GetDirProperty('title');
		$PageMetaDescription = $APPLICATION->GetPageProperty('description');
		$DirMetaDescription = $APPLICATION->GetDirProperty('description');

		// set title
		if(!CSite::inDir(SITE_DIR.'index.php')){
			if(!strlen($PageMetaTitleBrowser)){
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $PageH1.((strlen($PageH1) && strlen($arSite['SITE_NAME'])) ? ' - ' : '' ).$arSite['SITE_NAME'];
					$APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
		}
		else{
			if(!strlen($PageMetaTitleBrowser)){
				if(!strlen($DirMetaTitleBrowser)){
					$PageMetaTitleBrowser = $arSite['SITE_NAME'].((strlen($arSite['SITE_NAME']) && strlen($PageH1)) ? ' - ' : '' ).$PageH1;
					$APPLICATION->SetPageProperty('title', $PageMetaTitleBrowser);
				}
			}
		}

		// check Open Graph required meta properties
		if(!strlen(self::$arMetaParams['og:title'])){
			self::$arMetaParams['og:title'] = $PageMetaTitleBrowser;
		}
		if(!strlen(self::$arMetaParams['og:type'])){
			self::$arMetaParams['og:type'] = 'article';
		}
		if(!strlen(self::$arMetaParams['og:image'])){
			self::$arMetaParams['og:image'] = SITE_DIR.'logo.png'; // site logo
		}
		if(!strlen(self::$arMetaParams['og:url'])){
			self::$arMetaParams['og:url'] = $_SERVER['REQUEST_URI'];
		}
		if(!strlen(self::$arMetaParams['og:description'])){
			self::$arMetaParams['og:description'] = (strlen($PageMetaDescription) ? $PageMetaDescription : $DirMetaDescription);
		}

		foreach(self::$arMetaParams as $metaName => $metaValue){
			if(strlen($metaValue = strip_tags($metaValue))){
				$APPLICATION->AddHeadString('<meta property="'.$metaName.'" content="'.$metaValue.'" />', true);
				if($metaName === 'og:image'){
					$APPLICATION->AddHeadString('<link rel="image_src" href="'.$metaValue.'"  />', true);
				}
			}
		}

	}

	public static function PrepareItemProps($arProps){
		if(is_array($arProps) && $arProps)
		{
			foreach($arProps as $PCODE => $arProperty)
			{
				if(in_array($PCODE, array('PERIOD', 'TITLE_BUTTON', 'LINK_BUTTON', 'REDIRECT', 'LINK_PROJECTS', 'LINK_REVIEWS', 'DOCUMENTS', 'FORM_ORDER', 'FORM_QUESTION', 'PHOTOPOS', 'TASK_PROJECT', 'PHOTOS', 'LINK_COMPANY', 'GALLEY_BIG', 'LINK_SERVICES', 'LINK_GOODS', 'LINK_STAFF', 'LINK_SALE')))
					unset($arProps[$PCODE]);
				elseif(!$arProperty["VALUE"])
					unset($arProps[$PCODE]);
			}
		}
		else
			$arProps = array();

		return $arProps;
	}

	public static function ShowCabinetLink($icon=true, $text=true, $class_icon='', $show_mess=false, $message=''){
		global $APPLICATION;
		$html = '';
		$userID = self::GetUserID();
		if(!$message)
				$message = GetMessage('CABINET_LINK');
		if($userID)
		{
			global $USER;

			$html .= '<a class="personal-link dark-color'.($text ? /*' with_dropdown'*/ '' : '').'" href="'.SITE_DIR.'cabinet/">';
			if($icon)
				$html .= '<i class="svg svg-cabinet '.$class_icon.'" aria-hidden="true"></i>';

			if($text)
				$html .= '<span class="wrap">';

				if ($text)
					$html .= '<span class="name">'.$USER->GetFirstName().'</span>';
				if($show_mess)
					$html .= '<span class="title">'.$message.'</span>';

			if($text)
				$html .= '</span>';

			$html .= '</a>';
		}
		else
		{
			$url = ((isset($_GET['backurl']) && $_GET['backurl']) ? $_GET['backurl'] : $APPLICATION->GetCurUri());
			$html .= '<a class="personal-link dark-color animate-load" data-event="jqm" data-param-type="auth" data-param-backurl="'.$url.'" data-name="auth" href="'.SITE_DIR.'cabinet/">';
			if($icon)
				$html .= '<i class="svg svg-cabinet-login '.$class_icon.'" aria-hidden="true"></i>';

			if($text)
				$html .= '<span class="wrap">';

				if($text)
					$html .= '<span class="name">'.GetMessage('LOGIN').'</span>';
				if($show_mess)
					$html .= '<span class="title">'.$message.'</span>';
			if($text)
				$html .= '</span>';

			$html .= '</a>';
		}

		return $html;
	}

	public static function ShowPrintLink($txt=''){
		$html = '';

		$arTheme = self::GetFrontParametrsValues(SITE_ID);
		if($arTheme['PRINT_BUTTON'] == 'Y')
		{
			if(!$txt)
				$txt = GetMessage('PRINT_LINK');
			$html = '<div class="print-link"><i class="icon"><svg id="Print.svg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path class="cls-1" d="M1553,287h-2v3h-8v-3h-2a2,2,0,0,1-2-2v-5a2,2,0,0,1,2-2h2v-4h8v4h2a2,2,0,0,1,2,2v5A2,2,0,0,1,1553,287Zm-8,1h4v-4h-4v4Zm4-12h-4v2h4v-2Zm4,4h-12v5h2v-3h8v3h2v-5Z" transform="translate(-1539 -274)"/></svg></i>';
			if($txt)
				$html .= '<span class="text">'.$txt.'</span>';
			$html .= '</div>';
		}
		return $html;
	}

	public static function ShowBasketLink($class_link='top-btn hover', $class_icon='', $txt='', $show_price = false){
		$html = '';
		$userID = self::GetUserID();

		$arTheme = self::GetFrontParametrsValues(SITE_ID);
		$arItems = ((isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && $_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) ? $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] : array());
		$count = ($arItems ? count($arItems) : 0 );
		$allSumm = 0;
		if($arItems)
		{
			foreach($arItems as $arItem)
			{
				if(strlen(trim($arItem['PROPERTY_PRICE_VALUE'])))
					$allSumm += floatval(str_replace(' ', '', $arItem['PROPERTY_FILTER_PRICE_VALUE'])) * $arItem['QUANTITY'];
			}
		}
		$title_text = GetMessage("TITLE_BASKET", array("#SUMM#" => self::FormatSumm($allSumm, 1)));
		$summ_text = GetMessage("BASKET_SUMM", array("#SUMM#" => self::FormatSumm($allSumm, 1)));
		if((int)$count <= 0)
			$title_text = GetMessage("EMPTY_BASKET");
		if($arTheme['ORDER_VIEW'] == 'Y' && $arTheme['ORDER_BASKET_VIEW'] == 'HEADER' && (!self::IsBasketPage($arTheme['URL_BASKET_SECTION']) && !self::IsOrderPage($arTheme['URL_ORDER_SECTION'])))
		{
			$html = '<!-- noindex --><a rel="nofollow" title="'.$title_text.'" href="'.$arTheme['URL_BASKET_SECTION'].'" class="basket-link '.$class_link.' '.$class_icon.($count ? ' basket-count' : '').'"><span class="js-basket-block"><i class="svg svg-basket '.$class_icon.'" aria-hidden="true"></i>';
			if($show_price)
				$html .= '<div class="wrap">';

				if($txt)
					$html .= '<span class="title dark_link">'.$txt.'</span>';

				if($show_price)
					$html .= '<span class="prices">'.($allSumm ? $summ_text : GetMessage('EMPTY_BASKET')).'</span>';
			if($show_price)
				$html .= '</div>';

			$html .= '<span class="count">'.$count.'</span>';
			$html .= '</span></a><!-- /noindex -->';
		}

		return $html;
	}

	static function ShowMobileMenuCabinet(){
		global $APPLICATION, $arTheme;

		if($arTheme['CABINET']['VALUE'] === 'Y'){
			?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"cabinet_mobile",
				Array(
					"COMPONENT_TEMPLATE" => "cabinet_mobile",
					"MENU_CACHE_TIME" => "3600000",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_USE_GROUPS" => "Y",
					"MENU_CACHE_GET_VARS" => array(
					),
					"DELAY" => "N",
					"MAX_LEVEL" => "4",
					"ALLOW_MULTI_SELECT" => "Y",
					"ROOT_MENU_TYPE" => "cabinet",
					"CHILD_MENU_TYPE" => "left",
					"USE_EXT" => "Y"
				)
			);?>
			<?
		}
	}

	static function ShowMobileMenuBasket(){
		global $arTheme;

		$basketUrl = trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_BASKET_SECTION']['VALUE']);
		$orderUrl = trim($arTheme['ORDER_VIEW']['DEPENDENT_PARAMS']['URL_ORDER_SECTION']['VALUE']);
		$bShowBasket = $arTheme['ORDER_VIEW']['VALUE'] === 'Y' && strlen($basketUrl) && (!CSite::inDir($basketUrl) && (strlen($orderUrl) ? !CSite::inDir($orderUrl) : true));
		$userID = CUser::GetID();
		$userID = $userID > 0 ? $userID : 0;
		$cntItems = isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) ? count($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) : 0;

		if($bShowBasket){
			?>
			<div class="menu middle">
				<ul>
					<li class="counters">
						<a class="dark-color ready" href="<?=$basketUrl?>">
							<i class="svg svg-basket"></i>
							<span><?=GetMessage('BASKET')?><span class="count<?=(!$cntItems ? ' empted' : '')?>"><?=$cntItems?></span></span>
						</a>
					</li>
				</ul>
			</div>
			<?
		}
	}

	static function ShowMobileMenuContacts(){
		global $APPLICATION;
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		?>
		<?if($arBackParametrs['HEADER_PHONES']): // count of phones?>
			<?
			$phone = $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0'];
			$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
			?>
			<div class="menu middle">
				<ul>
					<li>
						<a href="<?=$href?>" class="dark-color<?=($arBackParametrs['HEADER_PHONES'] > 1 ? ' parent' : '')?>">
							<i class="svg svg-phone"></i>
							<span><?=$phone?></span>
							<?if($arBackParametrs['HEADER_PHONES'] > 1):?>
								<span class="arrow"><i class="svg svg_triangle_right"></i></span>
							<?endif;?>
						</a>
						<?if($arBackParametrs['HEADER_PHONES'] > 1): // if more than one?>
							<ul class="dropdown">
								<li class="menu_back"><a href="" class="dark-color" rel="nofollow"><i class="svg svg-arrow-right"></i><?=GetMessage('DIGITAL_T_MENU_BACK')?></a></li>
								<li class="menu_title"><?=GetMessage('DIGITAL_T_MENU_CALLBACK')?></li>
								<?for($i = 0; $i < $arBackParametrs['HEADER_PHONES']; ++$i):?>
									<?
									$phone = $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_'.$i];
									$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
									?>
									<li><a href="<?=$href?>" class="dark-color"><?=$phone?></a></li>
								<?endfor;?>
								<li><a class="dark-color" href="" rel="nofollow" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0]?>" data-name="callback"><?=GetMessage('S_CALLBACK')?></a></li>
							</ul>
						<?endif;?>
					</li>
				</ul>
			</div>
		<?endif;?>
		<div class="contacts">
			<div class="title"><?=GetMessage('DIGITAL_T_MENU_CONTACTS_TITLE')?></div>
			<div class="address">
				<i class="svg svg-address"></i>
				<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-address.php", array(), array(
						"MODE" => "html",
						"NAME" => "Address",
						"TEMPLATE" => "include_area",
					)
				);?>
			</div>
			<div class="email">
				<i class="svg svg-email"></i>
				<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/site-email.php", array(), array(
						"MODE" => "html",
						"NAME" => "E-mail",
						"TEMPLATE" => "include_area",
					)
				);?>
			</div>
		</div>
		<?
	}

	static function ShowTopDetailBanner($arResult, $arParams){
		$bg = ((isset($arResult['PROPERTIES']['BNR_TOP_BG']) && $arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) ? CFile::GetPath($arResult['PROPERTIES']['BNR_TOP_BG']['VALUE']) : SITE_TEMPLATE_PATH.'/images/top-bnr.jpg');
		$bShowBG = (isset($arResult['PROPERTIES']['BNR_TOP_IMG']) && $arResult['PROPERTIES']['BNR_TOP_IMG']['VALUE']);
		$title = ($arResult['IPROPERTY_VALUES'] && strlen($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arResult['NAME']);
		$text_color_style = ((isset($arResult['PROPERTIES']['CODE_TEXT']) && $arResult['PROPERTIES']['CODE_TEXT']['VALUE']) ? 'style="color:'.$arResult['PROPERTIES']['CODE_TEXT']['VALUE'].'"' : '');
		?>
		<div class="banners-content">
			<div class="maxwidth-banner" style="background: url(<?=$bg;?>) 50% 50% no-repeat;">
				<div class="row">
					<div class="maxwidth-theme">
						<div class="col-md-<?=($bShowBG ? 5 : 12);?> text animated delay06 duration08 item_block fadeInUp">
							<h1 <?=$text_color_style;?>><?=$title?></h1>
							<div class="intro-text" <?=$text_color_style;?>>
								<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
									<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
								<?else:?>
									<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
								<?endif;?>
							</div>
							<p>
								<?if($arResult['DISPLAY_PROPERTIES']['FORM_ORDER']['VALUE_XML_ID'] == 'YES'):?>
									<span>
										<span class="btn btn-default animate-load" data-event="jqm" data-param-id="<?=($arParams["FORM_ID_ORDER_SERVISE"] ? $arParams["FORM_ID_ORDER_SERVISE"] : CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_order_services'][0]);?>" data-name="order_services" data-autoload-service="<?=$arResult['NAME']?>" data-autoload-project="<?=$arResult['NAME']?>" data-autoload-product="<?=$arResult['NAME']?>"><span><?=(strlen($arParams['S_ORDER_SERVISE']) ? $arParams['S_ORDER_SERVISE'] : \Bitrix\Main\Localization\Loc::getMessage('S_ORDER_SERVISE'))?></span></span>
									</span>
								<?endif;?>

								<?if($arResult['DISPLAY_PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES'):?>
									<span>
										<span class="btn btn-default white animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]['aspro_digital_form']['aspro_digital_question'][0]?>" data-autoload-need_product="<?=$arResult['NAME']?>" data-name="question"><span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : \Bitrix\Main\Localization\Loc::getMessage('S_ASK_QUESTION'))?></span></span>
									</span>
								<?endif;?>
							</p>
						</div>
						<?if($bShowBG):?>
							<div class="col-md-7 hidden-xs hidden-sm img animated delay09 duration08 item_block fadeInUp">
								<div class="inner">
									<img src="<?=CFile::GetPath($arResult['PROPERTIES']['BNR_TOP_IMG']['VALUE']);?>" alt="<?=$title;?>" title="<?=$title;?>" draggable="false">
								</div>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	<?}

	static function GetUserID(){
		static $userID;
		if($userID === NULL)
		{
			global $USER;
			$userID = CUser::GetID();
			$userID = ($userID > 0 ? $userID : 0);
		}
		return $userID;
	}

	function CheckAdditionalChainInMultiLevel(&$arResult, &$arParams, &$arElement){
		global $APPLICATION;
		$APPLICATION->arAdditionalChain = false;
		if($arParams['INCLUDE_IBLOCK_INTO_CHAIN'] == 'Y' && isset(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME']))
			$APPLICATION->AddChainItem(CCache::$arIBlocksInfo[$arParams['IBLOCK_ID']]['NAME'], $arElement['~LIST_PAGE_URL']);

		if($arParams['ADD_SECTIONS_CHAIN'] == 'Y')
		{
			if($arSection = CCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arElement['IBLOCK_ID']), 'MULTI' => 'N')), self::GetCurrentSectionFilter($arResult['VARIABLES'], $arParams), false, array('ID', 'NAME')))
			{
				$rsPath = CIBlockSection::GetNavChain($arParams['IBLOCK_ID'], $arSection['ID']);
				$rsPath->SetUrlTemplates('', $arParams['SECTION_URL']);
				while($arPath = $rsPath->GetNext())
				{
					$ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arPath['ID']);
					$arPath['IPROPERTY_VALUES'] = $ipropValues->getValues();
					$arSection['PATH'][] = $arPath;
					$arSection['SECTION_URL'] = $arPath['~SECTION_PAGE_URL'];
				}

				foreach($arSection['PATH'] as $arPath)
				{
					if($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'] != '')
						$APPLICATION->AddChainItem($arPath['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'], $arPath['~SECTION_PAGE_URL']);
					else
						$APPLICATION->AddChainItem($arPath['NAME'], $arPath['~SECTION_PAGE_URL']);
				}
			}
		}
		if($arParams['ADD_ELEMENT_CHAIN'] == 'Y')
		{
			$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arParams['IBLOCK_ID'], $arElement['ID']);
			$arElement['IPROPERTY_VALUES'] = $ipropValues->getValues();
			if($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != '')
				$APPLICATION->AddChainItem($arElement['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']);
			else
				$APPLICATION->AddChainItem($arElement['NAME']);
		}
	}

	function CheckDetailPageUrlInMultilevel(&$arResult){
		if($arResult['ITEMS']){
			$arItemsIDs = $arItems = array();
			$CurrentSectionID = false;
			foreach($arResult['ITEMS'] as $arItem)
				$arItemsIDs[] = $arItem['ID'];

			$arItems = CCache::CIBLockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arItemsIDs), false, false, array('ID', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL'));
			if($arResult['SECTION']['PATH'])
			{
				for($i = count($arResult['SECTION']['PATH']) - 1; $i >= 0; --$i)
				{
					if(CSite::InDir($arResult['SECTION']['PATH'][$i]['SECTION_PAGE_URL']))
					{
						$CurrentSectionID = $arResult['SECTION']['PATH'][$i]['ID'];
						break;
					}
				}
			}
			foreach($arResult['ITEMS'] as $i => $arItem)
			{
				if(is_array($arItems[$arItem['ID']]['DETAIL_PAGE_URL']))
				{
					if($arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID])
						$arResult['ITEMS'][$i]['DETAIL_PAGE_URL'] = $arItems[$arItem['ID']]['DETAIL_PAGE_URL'][$CurrentSectionID];
				}
				if(is_array($arItems[$arItem['ID']]['IBLOCK_SECTION_ID']))
					$arResult['ITEMS'][$i]['IBLOCK_SECTION_ID'] = $CurrentSectionID;
			}
		}
	}

	public static function Start($siteID = 's1'){
		global $APPLICATION;
		if(CModule::IncludeModuleEx(self::MODULE_ID) == 1)
		{
			$APPLICATION->SetPageProperty("viewport", "initial-scale=1.0, width=device-width");
			$APPLICATION->SetPageProperty("HandheldFriendly", "true");
			$APPLICATION->SetPageProperty("apple-mobile-web-app-capable", "yes");
			$APPLICATION->SetPageProperty("apple-mobile-web-app-status-bar-style", "black");
			$APPLICATION->SetPageProperty("SKYPE_TOOLBAR", "SKYPE_TOOLBAR_PARSER_COMPATIBLE");

			$arTheme = self::GetFrontParametrsValues($siteID);

			if(!$arTheme['FONT_STYLE'] || !self::$arParametrsList['MAIN']['OPTIONS']['FONT_STYLE']['LIST'][$arTheme['FONT_STYLE']])
				$font_family = 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext';
			else
				$font_family = self::$arParametrsList['MAIN']['OPTIONS']['FONT_STYLE']['LIST'][$arTheme['FONT_STYLE']]['LINK'];
			$APPLICATION->SetAdditionalCSS((CMain::IsHTTPS() ? 'https' : 'http').'://fonts.googleapis.com/css?family='.$font_family);			

			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/bootstrap.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/fonts/font-awesome/css/font-awesome.min.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/vendor/flexslider/flexslider.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jquery.fancybox.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/theme-elements.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/jqModal.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/theme-responsive.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/print.css');
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animation_ext.css');
			
			if ($arTheme['H1_STYLE']=='2') {  // 2 - Normal
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-normal.css');
			}elseif(1) {  // 1 - Bold 
				$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/h1-bold.css');
			}			

			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.actual.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.fancybox.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.easing.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.appear.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.cookie.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/bootstrap.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/flexslider/jquery.flexslider.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/vendor/jquery.validate.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.uniform.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jqModal.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.inputmask.bundle.min.js', true);
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/detectmobilebrowser.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/matchMedia.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.waypoints.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.counterup.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.alphanumeric.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.mobile.custom.touch.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/general.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/custom.js');


			if(strlen($arTheme['FAVICON_IMAGE'])){
				$APPLICATION->AddHeadString('<link rel="shortcut icon" href="'.$arTheme['FAVICON_IMAGE'].'" type="image/x-icon" />', true);
			}
			if(strlen($arTheme['APPLE_TOUCH_ICON_IMAGE'])){
				$APPLICATION->AddHeadString('<link rel="apple-touch-icon" sizes="180x180" href="'.$arTheme['APPLE_TOUCH_ICON_IMAGE'].'" />', true);
			}

			CJSCore::Init(array('jquery2', 'fx'));
			CAjax::Init();
		}
		else
		{
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/styles.css');
			$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE"));
			$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php", Array(), Array()); die();
		}
	}

	static function ShowPageProps($prop){
		/** @global CMain $APPLICATION */
		global $APPLICATION;
		$APPLICATION->AddBufferContent(array("CDigital", "GetPageProps"), $prop);
	}

	static function GetPageProps($prop){
		/** @global CMain $APPLICATION */
		global $APPLICATION;

		$val = $APPLICATION->GetProperty($prop);
		if(!empty($val))
			return $val;
		return '';
	}

	static function CopyFaviconToSiteDir($arValue, $siteID = ''){
		if(($siteID)){
			if(!is_array($arValue))
				$arValue=unserialize($arValue);
			if($arValue[0]){
				$imageSrc = $_SERVER['DOCUMENT_ROOT'].CFile::GetPath($arValue[0]);
			}
			else{
				if($arTemplate = self::GetSiteTemplate($siteID)){
					$imageSrc = str_replace('//', '/', $arTemplate['PATH'].'/images/favicon.ico');
				}
			}
			$arSite = CSite::GetByID($siteID)->Fetch();

			@unlink($imageDest = $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.ico');
			if(file_exists($imageSrc)){
				@copy($imageSrc, $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.ico');
			}else{
				@copy($arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/include/favicon.ico', $arSite['ABS_DOC_ROOT'].'/'.$arSite['DIR'].'/favicon.ico');
			}
		}
	}

	static function GetSiteTemplate($siteID = ''){
		$arTemplate = array();

		if(strlen($siteID)){
			$dbRes = CSite::GetTemplateList($siteID);
			while($arTemplate = $dbRes->Fetch()){
				if(!strlen($arTemplate['CONDITION'])){
					if(file_exists(($arTemplate['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/'.$arTemplate['TEMPLATE']))){
						break;
					}
					elseif(file_exists(($arTemplate['PATH'] = $_SERVER['DOCUMENT_ROOT'].'/local/templates/'.$arTemplate['TEMPLATE']))){
						break;
					}
				}
			}
		}

		return $arTemplate;
	}

	function FormatSumm($strPrice, $quantity){
		$strSumm = '';

		if(strlen($strPrice = trim($strPrice))){
			$currency = '';
			$price = floatval(str_replace(' ', '', $strPrice));
			$summ = $price * $quantity;

			$strSumm = str_replace(trim(str_replace($currency, '', $strPrice)), str_replace('.00', '', number_format($summ, 2, '.', ' ')), $strPrice);
		}

		return $strSumm;
	}

	function FormatPriceShema($strPrice = ''){
		if(strlen($strPrice = trim($strPrice))){
			$arCur = array(
				'$' => 'USD',
				GetMessage('DIGITAL_CUR_EUR1') => 'EUR',
				GetMessage('DIGITAL_CUR_RUB1') => 'RUB',
				GetMessage('DIGITAL_CUR_RUB2') => 'RUB',
				GetMessage('DIGITAL_CUR_UAH1') => 'UAH',
				GetMessage('DIGITAL_CUR_UAH2') => 'UAH',
				GetMessage('DIGITAL_CUR_RUB3') => 'RUB',
				GetMessage('DIGITAL_CUR_RUB4') => 'RUB',
				GetMessage('DIGITAL_CUR_RUB5') => 'RUB',
				GetMessage('DIGITAL_CUR_RUB6') => 'RUB',
				GetMessage('DIGITAL_CUR_RUB3') => 'RUB',
				GetMessage('DIGITAL_CUR_UAH3') => 'UAH',
				GetMessage('DIGITAL_CUR_RUB5') => 'RUB',
				GetMessage('DIGITAL_CUR_UAH6') => 'UAH',
			);

			foreach($arCur as $curStr => $curCode){
				if(strpos($strPrice, $curStr) !== false){
					$priceVal = str_replace($curStr, '', $strPrice);
					return str_replace(array($curStr, $priceVal), array('<span class="currency" itemprop="priceCurrency" content="'.$curCode.'">'.$curStr.'</span>', '<span itemprop="price" content="'.$priceVal.'">'.$priceVal.'</span>'), $strPrice);
				}
			}
		}
		return $strPrice;
	}

	function GetBannerStyle($bannerwidth, $topmenu){
        $style = "";

        if($bannerwidth == "WIDE"){
            $style = ".maxwidth-banner{max-width: 1550px;}";
        }
        elseif($bannerwidth == "MIDDLE"){
            $style = ".maxwidth-banner{max-width: 1450px;}";
        }
        elseif($bannerwidth == "NARROW"){
            $style = ".maxwidth-banner{max-width: 1343px; padding: 0 16px;}";
			if($topmenu !== 'LIGHT'){
				$style .= ".banners-big{margin-top:20px;}";
			}
        }
        else{
            $style = ".maxwidth-banner{max-width: auto;}";
        }

        return "<style>".$style."</style>";
    }

	function OnSearchGetURL($arFields)
    {
    	if(strpos($arFields["URL"], "#YEAR#") !== false)
    	{
			$arElement = CCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arFields['PARAM2']), 'MULTI' => 'N')), array('ID' => $arFields['ITEM_ID']), false, false, array('ID', 'ACTIVE_FROM'));
	    	if($arElement['ACTIVE_FROM'])
	    	{
	    		if($arDateTime = ParseDateTime($arElement['ACTIVE_FROM'], FORMAT_DATETIME))
	    		{
			        $url = str_replace("#YEAR#", $arDateTime['YYYY'], $arFields['URL']);
			        return $url;
	    		}
	    	}
    	}
		return $arFields["URL"];
    }

    static function FormatNewsUrl($arItem){
    	$url = $arItem['DETAIL_PAGE_URL'];
    	if(strlen($arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE']))
		{
			$url = $arItem['DISPLAY_PROPERTIES']['REDIRECT']['VALUE'];
			return $url;
		}
    	if($arItem['ACTIVE_FROM'])
    	{
    		if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
    		{
		        $url = str_replace("#YEAR#", $arDateTime['YYYY'], $arItem['DETAIL_PAGE_URL']);
		        return $url;
    		}
    	}
    	return $url;
    }

    static function GetItemsYear($arParams){
    	$arResult = array();
    	$arItems = CCache::CIBLockElement_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']))), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'), false, false, array('ID', 'NAME', 'ACTIVE_FROM'));
		if($arItems)
		{
			foreach($arItems as $arItem)
			{
				if($arItem['ACTIVE_FROM'])
				{
					if($arDateTime = ParseDateTime($arItem['ACTIVE_FROM'], FORMAT_DATETIME))
						$arResult[$arDateTime['YYYY']] = $arDateTime['YYYY'];
				}
			}
		}
		return $arResult;
    }

	function GetDirMenuParametrs($dir){
		if(strlen($dir)){
			$file = str_replace('//', '/', $dir.'/.section.php');
			if(file_exists($file)){
				@include($file);
				return $arDirProperties;
			}
		}

		return false;
	}

	static function IsBasketPage($url_link = ''){
		static $result;

		if(!isset($result)){
			if(!$url_link)
			{
				$arOptions = self::GetBackParametrsValues(SITE_ID);
				if(!strlen($arOptions["URL_BASKET_SECTION"]))
					$arOptions["URL_BASKET_SECTION"] = SITE_DIR."cart/";
				$url_link = $arOptions["URL_BASKET_SECTION"];
			}
			$result = CSite::InDir($url_link);
		}

		return $result;
	}

	static function IsOrderPage($url_link = ''){
		static $result;

		if(!isset($result)){
			if(!$url_link)
			{
				$arOptions = self::GetBackParametrsValues(SITE_ID);
				if(!strlen($arOptions["URL_ORDER_SECTION"]))
					$arOptions["URL_ORDER_SECTION"] = SITE_DIR."cart/order/";
				$url_link = $arOptions["URL_ORDER_SECTION"];
			}
			$result = CSite::InDir($url_link);
		}

		return $result;
	}

	function goto404Page(){
		global $APPLICATION;

		if($_SESSION['SESS_INCLUDE_AREAS']){
			echo '</div>';
		}
		echo '</div>';
		$APPLICATION->IncludeFile(SITE_DIR.'404.php', array(), array('MODE' => 'html'));
		die();
	}

	function checkRestartBuffer(){
		global $APPLICATION;
		static $bRestarted;

		if($bRestarted)
			die();


		if((isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == "xmlhttprequest") || (strtolower($_REQUEST['ajax']) == 'y'))
		{
			$APPLICATION->RestartBuffer();
			$bRestarted = true;
		}
	}

	function UpdateFormEvent(&$arFields){
		if($arFields['ID'] && $arFields['IBLOCK_ID'])
		{
			// find aspro form event for this iblock
			$arEventIDs = array('SECONTEXT_SEND_FORM_'.$arFields['IBLOCK_ID'], 'SECONTEXT_SEND_FORM_ADMIN_'.$arFields['IBLOCK_ID']);
			$arLangIDs = array('ru', 'en');
			static $arEvents;
			if($arEvents == NULL)
			{
				foreach($arEventIDs as $EVENT_ID)
				{
					foreach($arLangIDs as $LANG_ID)
					{
						$resEvents = CEventType::GetByID($EVENT_ID, $LANG_ID);
						$arEvents[$EVENT_ID][$LANG_ID] = $resEvents->Fetch();
					}
				}
			}
			if($arEventIDs)
			{
				foreach($arEventIDs as $EVENT_ID)
				{
					foreach($arLangIDs as $LANG_ID)
					{
						if($arEvent = &$arEvents[$EVENT_ID][$LANG_ID])
						{
							if(strpos($arEvent['DESCRIPTION'], $arFields['NAME'].': #'.$arFields['CODE'].'#') === false){
								$arEvent['DESCRIPTION'] = str_replace('#'.$arFields['CODE'].'#', '-', $arEvent['DESCRIPTION']);
								$arEvent['DESCRIPTION'] .= $arFields['NAME'].': #'.$arFields['CODE']."#\n";
								CEventType::Update(array('ID' => $arEvent['ID']), $arEvent);
							}
						}
					}
				}
			}
		}
	}

	static function ShowHeaderPhones($class){
		$arBackParametrs = self::GetBackParametrsValues(SITE_ID);
		?>
		<?if($arBackParametrs['HEADER_PHONES']): // count of phones?>
			<?
			$phone = $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_0'];
			$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
			?>
			<div class="phone<?=(intval($arBackParametrs['HEADER_PHONES']['VALUE']) > 1 ? ' with_dropdown' : '')?><?=($class ? ' '.$class : '')?>">
				<i class="svg svg-phone"></i>
				<a href="<?=$href?>"><?=$phone?></a>
				<?if($arBackParametrs['HEADER_PHONES'] > 1): // if more than one?>
					<div class="dropdown">
						<div class="wrap">
							<?for($i = 1; $i < $arBackParametrs['HEADER_PHONES']; ++$i):?>
								<?
								$phone = $arBackParametrs['HEADER_PHONES_array_PHONE_VALUE_'.$i];
								$href = 'tel:'.str_replace(array(' ', '-', '(', ')'), '', $phone);
								?>
								<div class="more_phone"><a href="<?=$href?>"><?=$phone?></a></div>
							<?endfor;?>
						</div>
					</div>
				<?endif;?>
			</div>
		<?endif;?>
		<?
	}

	static function checkBasketItems(){
		if(!defined(ADMIN_SECTION) && !CSite::inDir(SITE_DIR.'/ajax/')){
			?>
			<script>
				var arBasketItems = <?=CUtil::PhpToJSObject(self::getBasketItems(), false)?>;
			</script>
			<?
		}
	}

	static function getBasketItems(){
		global $APPLICATION, $arSite, $USER;
		CModule::IncludeModule('iblock');

		if(!defined(ADMIN_SECTION)){
			$userID = CUser::GetID();
			$userID = ($userID > 0 ? $userID : 0);
			$arBackParametrs = self::GetFrontParametrsValues(SITE_ID);
			$bOrderViewBasket = ($arBackParametrs['ORDER_VIEW'] == 'Y' ? true : false);

			if($bOrderViewBasket && isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && $_SESSION[SITE_ID][$userID]['BASKET_ITEMS']){
				$arIBlocks = $arBasketItemsIDs = array();

				foreach($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] as $arBasketItem){
					if(isset($arBasketItem['IBLOCK_ID']) && intval($arBasketItem['IBLOCK_ID']) > 0 && !in_array($arBasketItem['IBLOCK_ID'], $arIBlocks))
						$arIBlocks[] = $arBasketItem['IBLOCK_ID'];

					$arBasketItemsIDs[] = $arBasketItem['ID'];
				}

				$dbRes = CIBlockElement::GetList(array(), array('IBLOCK_ID' => $arIBlocks, 'ID' => $arBasketItemsIDs, 'PROPERTY_FORM_ORDER_VALUE' => false), false, false, array('ID'));
				while($arRes = $dbRes->Fetch()){
					unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$arRes['ID']]);
				}

				return $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'];
			}

			return array();
		}

		return false;
	}

	// DO NOT USE - FOR OLD VERSIONS
	function linkShareImage($previewPictureID = false, $detailPictureID = false){
		global $APPLICATION;

		if($linkSaherImageID = ($detailPictureID ? $detailPictureID : ($previewPictureID ? $previewPictureID : false)))
			$APPLICATION->AddHeadString('<link rel="image_src" href="'.CFile::GetPath($linkSaherImageID).'"  />', true);
	}

	static function processBasket(){
		global $USER;
		$userID = CUser::GetID();
		$userID = ($userID > 0 ? $userID : 0);

		if(isset($_REQUEST['itemData']) && is_array($_REQUEST['itemData']))
			$_REQUEST['itemData'] = array_map('self::conv', $_REQUEST['itemData']);

		if(isset($_REQUEST['removeAll']) && $_REQUEST['removeAll'] === 'Y')
		{
			unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']);
		}
		elseif(isset($_REQUEST['itemData']['ID']) && intval($_REQUEST['itemData']['ID']) > 0)
		{
			if(!is_array($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']))
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'] = array();


			if(isset($_REQUEST['remove']) && $_REQUEST['remove'] === 'Y')
			{
				if(isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS']) && isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']])){
					unset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]);
				}
			}
			elseif(isset($_REQUEST['quantity']) && floatval($_REQUEST['quantity']) > 0)
			{
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']] = (isset($_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]) ? $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']] : $_REQUEST['itemData']);
				$_SESSION[SITE_ID][$userID]['BASKET_ITEMS'][$_REQUEST['itemData']['ID']]['QUANTITY'] = $_REQUEST['quantity'];

			}
		}
		return $_SESSION[SITE_ID][$userID]['BASKET_ITEMS'];
	}

	public static function conv($n){
		return iconv('UTF-8', SITE_CHARSET, $n);
	}

	public static function getDataItem($el){
		$dataItem = array(
			"IBLOCK_ID" => $el['IBLOCK_ID'],
			"ID" => $el['ID'],
			"NAME" => $el['NAME'],
			"DETAIL_PAGE_URL" => $el['DETAIL_PAGE_URL'],
			"PREVIEW_PICTURE" => $el['PREVIEW_PICTURE']['ID'],
			"DETAIL_PICTURE" => $el['DETAIL_PICTURE']['ID'],
			"PROPERTY_FILTER_PRICE_VALUE" => $el['PROPERTIES']['FILTER_PRICE']['VALUE'],
			"PROPERTY_PRICE_VALUE" => $el['PROPERTIES']['PRICE']['VALUE'],
			"PROPERTY_PRICEOLD_VALUE" => $el['PROPERTIES']['PRICEOLD']['VALUE'],
			"PROPERTY_ARTICLE_VALUE" => $el['PROPERTIES']['ARTICLE']['VALUE'],
			"PROPERTY_STATUS_VALUE" => $el['PROPERTIES']['STATUS']['VALUE_ENUM_ID'],
		);

		global $APPLICATION;
		$dataItem = $APPLICATION->ConvertCharsetArray($dataItem, SITE_CHARSET, 'UTF-8');
		$dataItem = htmlspecialchars(json_encode($dataItem));
		return $dataItem;
	}

	static function utf8_substr_replace($original, $replacement, $position, $length){
		$startString = mb_substr($original, 0, $position, "UTF-8");
		$endString = mb_substr($original, $position + $length, mb_strlen($original), "UTF-8");

		$out = $startString.$replacement.$endString;

		return $out;
	}

	static function ShowRSSIcon($href){
		?>
		<style type="text/css">h1{padding-right:50px;}</style>
		<script type="text/javascript">
		$(document).ready(function () {
			$('h1').before('<a class="rss" href="<?=$href?>" title="rss" target="_blank">RSS</a>');
		});
		</script>
		<?
		$GLOBALS['APPLICATION']->AddHeadString('<link rel="alternate" type="application/rss+xml" title="rss" href="'.$href.'" />');
	}

	static function getFieldImageData(array &$arItem, array $arKeys, $entity = 'ELEMENT', $ipropertyKey = 'IPROPERTY_VALUES'){
		if (empty($arItem) || empty($arKeys))
            return;

        $entity = (string)$entity;
        $ipropertyKey = (string)$ipropertyKey;

        foreach ($arKeys as $fieldName)
        {
            if(!isset($arItem[$fieldName]) || (!isset($arItem['~'.$fieldName]) || !$arItem['~'.$fieldName]))
                continue;
            $imageData = false;
            $imageId = (int)$arItem['~'.$fieldName];
            if ($imageId > 0)
                $imageData = \CFile::getFileArray($imageId);
            unset($imageId);
            if (is_array($imageData))
            {
                if (isset($imageData['SAFE_SRC']))
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = $imageData['SAFE_SRC'];
                }
                else
                {
                    $imageData['UNSAFE_SRC'] = $imageData['SRC'];
                    $imageData['SRC'] = \CHTTP::urnEncode($imageData['SRC'], 'UTF-8');
                }
                $imageData['ALT'] = '';
                $imageData['TITLE'] = '';

                if ($ipropertyKey != '' && isset($arItem[$ipropertyKey]) && is_array($arItem[$ipropertyKey]))
                {
                    $entityPrefix = $entity.'_'.$fieldName;
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT']))
                        $imageData['ALT'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_ALT'];
                    if (isset($arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE']))
                        $imageData['TITLE'] = $arItem[$ipropertyKey][$entityPrefix.'_FILE_TITLE'];
                    unset($entityPrefix);
                }
                if ($imageData['ALT'] == '' && isset($arItem['NAME']))
                    $imageData['ALT'] = $arItem['NAME'];
                if ($imageData['TITLE'] == '' && isset($arItem['NAME']))
                    $imageData['TITLE'] = $arItem['NAME'];
            }
            $arItem[$fieldName] = $imageData;
            unset($imageData);
        }

        unset($fieldName);
	}

	static function get_banners_position($position) {
		$arTheme = self::GetFrontParametrsValues(SITE_ID);
		if ($arTheme["ADV_".$position] == 'Y') {
			global $APPLICATION;
			$APPLICATION->IncludeComponent(
				"bitrix:news.list",
				"banners",
				array(
					"IBLOCK_TYPE" => "aspro_digital_content",
					"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_content"]["aspro_digital_banners"][0],
					"POSITION"	=> $position,
					"PAGE"		=> $APPLICATION->GetCurPage(),
					"NEWS_COUNT" => "100",
					"SORT_BY1" => "SORT",
					"SORT_ORDER1" => "ASC",
					"SORT_BY2" => "ID",
					"SORT_ORDER2" => "ASC",
					"FIELD_CODE" => array(
						0 => "NAME",
						2 => "PREVIEW_PICTURE",
					),
					"PROPERTY_CODE" => array(
						0 => "LINK",
						1 => "TARGET",
						2 => "BGCOLOR",
						3 => "SHOW_SECTION",
						4 => "SHOW_PAGE",
						5 => "HIDDEN_XS",
						6 => "HIDDEN_SM",
						7 => "POSITION",
						8 => "SIZING",
					),
					"CHECK_DATES" => "Y",
					"FILTER_NAME" => "arFilterBanners",
					"DETAIL_URL" => "",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"AJAX_OPTION_HISTORY" => "N",
					"CACHE_TYPE" => "A",
					"CACHE_TIME" => "3600000",
					"CACHE_FILTER" => "Y",
					"CACHE_GROUPS" => "N",
					"PREVIEW_TRUNCATE_LEN" => "150",
					"ACTIVE_DATE_FORMAT" => "d.m.Y",
					"SET_TITLE" => "N",
					"SET_STATUS_404" => "N",
					"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
					"ADD_SECTIONS_CHAIN" => "N",
					"HIDE_LINK_WHEN_NO_DETAIL" => "N",
					"PARENT_SECTION" => "",
					"PARENT_SECTION_CODE" => "",
					"INCLUDE_SUBSECTIONS" => "Y",
					"PAGER_TEMPLATE" => ".default",
					"DISPLAY_TOP_PAGER" => "N",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"PAGER_TITLE" => "",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "3600000",
					"PAGER_SHOW_ALL" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"SHOW_DETAIL_LINK" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_META_KEYWORDS" => "N",
					"SET_META_DESCRIPTION" => "N",
					"COMPONENT_TEMPLATE" => "banners",
					"SET_LAST_MODIFIED" => "N",
					"COMPOSITE_FRAME_MODE" => "A",
					"COMPOSITE_FRAME_TYPE" => "AUTO",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"SHOW_404" => "N",
					"MESSAGE_404" => ""
				),
				false, array('ACTIVE_COMPONENT' => 'Y')
			);
		}
	}
}
?>