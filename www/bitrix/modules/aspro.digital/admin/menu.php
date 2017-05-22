<?
AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandlerDigital');
function OnBuildGlobalMenuHandlerDigital(&$arGlobalMenu, &$arModuleMenu){
	if(!defined('DIGITAL_MENU_INCLUDED')){
		define('DIGITAL_MENU_INCLUDED', true);

		IncludeModuleLangFile(__FILE__);
		$moduleID = 'aspro.digital';

		$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/menu.css");

		if($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R'){
			$arMenu = array(
				'menu_id' => 'global_menu_aspro_digital',
				'text' => GetMessage('DIGITAL_GLOBAL_MENU_TEXT'),
				'title' => GetMessage('DIGITAL_GLOBAL_MENU_TITLE'),
				'sort' => 1000,
				'items_id' => 'global_menu_aspro_digital_items',
				'items' => array(
					array(
						'text' => GetMessage('DIGITAL_MENU_CONTROL_CENTER_TEXT'),
						'title' => GetMessage('DIGITAL_MENU_CONTROL_CENTER_TITLE'),
						'sort' => 10,
						'url' => '/bitrix/admin/'.$moduleID.'_mc.php',
						'icon' => 'imi_control_center',
						'page_icon' => 'pi_control_center',
						'items_id' => 'control_center',
					),
					array(
						'text' => GetMessage('DIGITAL_MENU_TYPOGRAPHY_TEXT'),
						'title' => GetMessage('DIGITAL_MENU_TYPOGRAPHY_TITLE'),
						'sort' => 20,
						'url' => '/bitrix/admin/'.$moduleID.'_options.php?mid=main',
						'icon' => 'imi_typography',
						'page_icon' => 'pi_typography',
						'items_id' => 'main',
					),					
				),
			);

			$arGlobalMenu[] = $arMenu;
		}
	}
}
?>