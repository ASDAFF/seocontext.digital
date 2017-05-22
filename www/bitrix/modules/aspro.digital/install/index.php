<?php
/**
 * Digital module
 * @copyright 2017 Aspro
 */

IncludeModuleLangFile(__FILE__);

class aspro_digital extends CModule {
	const solutionName	= 'digital';
	const partnerName = 'aspro';
	const moduleClass = 'CDigital';
	const moduleClassEvents = 'CDigitalEvents';

	var $MODULE_ID = 'aspro.digital';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $MODULE_GROUP_RIGHTS = 'R';

	function aspro_digital(){
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$path = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($path.'/version.php');

		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = GetMessage('DIGITAL_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('DIGITAL_MODULE_DESC');
		$this->PARTNER_NAME = GetMessage('DIGITAL_PARTNER');
		$this->PARTNER_URI = GetMessage('DIGITAL_PARTNER_URI');
	}

	function checkValid(){
		return true;
	}

	function InstallDB($install_wizard = true){
		global $DB, $DBType, $APPLICATION;

		RegisterModule($this->MODULE_ID);
		COption::SetOptionString($this->MODULE_ID, 'GROUP_DEFAULT_RIGHT', $this->MODULE_GROUP_RIGHTS);

		if(preg_match('/.bitrixlabs.ru/', $_SERVER['HTTP_HOST'])){
			RegisterModuleDependences('main', 'OnBeforeProlog', $this->MODULE_ID, self::moduleClass, 'correctInstall');
		}

		if(CModule::IncludeModule($this->MODULE_ID)){
			$moduleClass = self::moduleClass;
			$instance = new $moduleClass();
			$instance::sendAsproBIAction('install');
		}

		return true;
	}

	function UnInstallDB($arParams = array()){
		global $DB, $DBType, $APPLICATION;

		if(CModule::IncludeModule($this->MODULE_ID)){
			$moduleClass = self::moduleClass;
			$instance = new $moduleClass();
			$instance::sendAsproBIAction('delete');
		}

		COption::RemoveOption($this->MODULE_ID, 'GROUP_DEFAULT_RIGHT');
		UnRegisterModule($this->MODULE_ID);

		return true;
	}

	function InstallEvents(){
		
		RegisterModuleDependences('main', 'OnBeforeUserRegister', $this->MODULE_ID, $this->moduleClassEvents, 'OnBeforeUserUpdateHandler');
		RegisterModuleDependences('main', 'OnBeforeUserAdd', $this->MODULE_ID, $this->moduleClassEvents, 'OnBeforeUserUpdateHandler');
		RegisterModuleDependences('main', 'OnBeforeUserUpdate', $this->MODULE_ID, $this->moduleClassEvents, 'OnBeforeUserUpdateHandler');
		RegisterModuleDependences('main', 'OnAfterUserRegister', $this->MODULE_ID, $this->moduleClassEvents, 'OnAfterUserRegisterHandler');

		RegisterModuleDependences("iblock", "OnAfterIBlockAdd", $this->MODULE_ID, "CCache", "ClearTagIBlock");
		RegisterModuleDependences("iblock", "OnAfterIBlockUpdate", $this->MODULE_ID, "CCache", "ClearTagIBlock");
		RegisterModuleDependences("iblock", "OnBeforeIBlockDelete", $this->MODULE_ID, "CCache", "ClearTagIBlockBeforeDelete");

		RegisterModuleDependences("iblock", "OnAfterIBlockElementAdd", $this->MODULE_ID, "CCache", "ClearTagIBlockElement");
		RegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CCache", "ClearTagIBlockElement");
		RegisterModuleDependences("iblock", "OnAfterIBlockElementDelete", $this->MODULE_ID, "CCache", "ClearTagIBlockElement");

		RegisterModuleDependences("iblock", "OnAfterIBlockSectionAdd", $this->MODULE_ID, "CCache", "ClearTagIBlockSection");
		RegisterModuleDependences("iblock", "OnAfterIBlockSectionUpdate", $this->MODULE_ID, "CCache", "ClearTagIBlockSection");
		RegisterModuleDependences("iblock", "OnBeforeIBlockSectionDelete", $this->MODULE_ID, "CCache", "ClearTagIBlockSectionBeforeDelete");

		RegisterModuleDependences('iblock', 'OnAfterIBlockPropertyUpdate', $this->MODULE_ID, 'CDigital', 'UpdateFormEvent');
		RegisterModuleDependences('iblock', 'OnAfterIBlockPropertyAdd', $this->MODULE_ID, 'CDigital', 'UpdateFormEvent');

		RegisterModuleDependences('search', 'OnSearchGetURL', $this->MODULE_ID, 'CDigital', 'OnSearchGetURL');
		RegisterModuleDependences('main', 'OnEndBufferContent', $this->MODULE_ID, 'CDigitalEvents', 'OnEndBufferContentHandler');

		return true;
	}

	function UnInstallEvents(){		
		UnRegisterModuleDependences('main', 'OnBeforeUserRegister', $this->MODULE_ID, $this->moduleClassEvents, 'OnBeforeUserUpdateHandler');
		UnRegisterModuleDependences('main', 'OnBeforeUserAdd', $this->MODULE_ID, $this->moduleClassEvents, 'OnBeforeUserUpdateHandler');
		UnRegisterModuleDependences('main', 'OnBeforeUserUpdate', $this->MODULE_ID, $this->moduleClassEvents, 'OnBeforeUserUpdateHandler');
		UnRegisterModuleDependences('main', 'OnAfterUserRegister', $this->MODULE_ID, $this->moduleClassEvents, 'OnAfterUserRegisterHandler');

		UnRegisterModuleDependences("iblock", "OnAfterIBlockAdd", $this->MODULE_ID, "CCache", "ClearTagIBlock");
		UnRegisterModuleDependences("iblock", "OnAfterIBlockUpdate", $this->MODULE_ID, "CCache", "ClearTagIBlock");
		UnRegisterModuleDependences("iblock", "OnBeforeIBlockDelete", $this->MODULE_ID, "CCache", "ClearTagIBlockBeforeDelete");

		UnRegisterModuleDependences("iblock", "OnAfterIBlockElementAdd", $this->MODULE_ID, "CCache", "ClearTagIBlockElement");
		UnRegisterModuleDependences("iblock", "OnAfterIBlockElementUpdate", $this->MODULE_ID, "CCache", "ClearTagIBlockElement");
		UnRegisterModuleDependences("iblock", "OnAfterIBlockElementDelete", $this->MODULE_ID, "CCache", "ClearTagIBlockElement");

		UnRegisterModuleDependences("iblock", "OnAfterIBlockSectionAdd", $this->MODULE_ID, "CCache", "ClearTagIBlockSection");
		UnRegisterModuleDependences("iblock", "OnAfterIBlockSectionUpdate", $this->MODULE_ID, "CCache", "ClearTagIBlockSection");
		UnRegisterModuleDependences("iblock", "OnBeforeIBlockSectionDelete", $this->MODULE_ID, "CCache", "ClearTagIBlockSectionBeforeDelete");

		UnRegisterModuleDependences('iblock', 'OnAfterIBlockPropertyUpdate', $this->MODULE_ID, 'CDigital', 'UpdateFormEvent');
		UnRegisterModuleDependences('iblock', 'OnAfterIBlockPropertyAdd', $this->MODULE_ID, 'CDigital', 'UpdateFormEvent');

		UnRegisterModuleDependences('search', 'OnSearchGetURL', $this->MODULE_ID, 'CDigital', 'OnSearchGetURL');
		UnRegisterModuleDependences('main', 'OnEndBufferContent', $this->MODULE_ID, 'CDigitalEvents', 'OnEndBufferContentHandler');

		return true;
	}

	function InstallPublic(){
	}

	function InstallFiles(){
		CopyDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin', true);
		CopyDirFiles(__DIR__.'/components/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/components', true, true);
		CopyDirFiles(__DIR__.'/wizards/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/wizards', true, true);

		CopyDirFiles(__DIR__.'/css/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/css/'.self::partnerName.'.'.self::solutionName, true, true);
		CopyDirFiles(__DIR__.'/js/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.self::partnerName.'.'.self::solutionName, true, true);
		CopyDirFiles(__DIR__.'/images/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/images/'.self::partnerName.'.'.self::solutionName, true, true);

		if(preg_match('/.bitrixlabs.ru/', $_SERVER['HTTP_HOST'])){
			@set_time_limit(0);
			include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/fileman/include.php');
			CFileMan::DeleteEx(array('s1', '/bitrix/modules/'.$this->MODULE_ID.'/install/wizards'));
		}

		$this->InstallGadget();

		return true;
	}

	function UnInstallFiles(){
		DeleteDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');
		DeleteDirFilesEx('/bitrix/wizards/'.self::partnerName.'/'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/css/'.self::partnerName.'.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/js/'.self::partnerName.'.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/images/'.self::partnerName.'.'.self::solutionName.'/');

		$this->UnInstallComponents();
		$this->UnInstallGadget();

		return true;
	}

	function InstallGadget(){
		CopyDirFiles(__DIR__.'/gadgets/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/gadgets/', true, true);

		$gadget_id = strtoupper(self::solutionName);
		$gdid = $gadget_id."@".rand();
		if(class_exists('CUserOptions')){
			$arUserOptions = CUserOptions::GetOption('intranet', '~gadgets_admin_index', false, false);
			if(is_array($arUserOptions) && isset($arUserOptions[0])){
				foreach($arUserOptions[0]['GADGETS'] as $tempid => $tempgadget){
					$p = strpos($tempid, '@');
					$gadget_id_tmp = ($p === false ? $tempid : substr($tempid, 0, $p));

					if($gadget_id_tmp == $gadget_id){
						return false;
					}
					if($tempgadget['COLUMN'] == 0){
						++$arUserOptions[0]['GADGETS'][$tempid]['ROW'];
					}
				}
				$arUserOptions[0]['GADGETS'][$gdid] = array('COLUMN' => 0, 'ROW' => 0);
				CUserOptions::SetOption('intranet', '~gadgets_admin_index', $arUserOptions, false, false);
			}
		}

		return true;
	}

	function UnInstallGadget(){
		$gadget_id = strtoupper(self::solutionName);
		if(class_exists('CUserOptions')){
			$arUserOptions = CUserOptions::GetOption('intranet', '~gadgets_admin_index', false, false);
			if(is_array($arUserOptions) && isset($arUserOptions[0])){
				foreach($arUserOptions[0]['GADGETS'] as $tempid => $tempgadget){
					$p = strpos($tempid, '@');
					$gadget_id_tmp = ($p === false ? $tempid : substr($tempid, 0, $p));

					if($gadget_id_tmp == $gadget_id){
						unset($arUserOptions[0]['GADGETS'][$tempid]);
					}
				}
				CUserOptions::SetOption('intranet', '~gadgets_admin_index', $arUserOptions, false, false);
			}
		}

		DeleteDirFilesEx('/bitrix/gadgets/'.self::partnerName.'/'.self::solutionName.'/');

		return true;
	}
	function UnInstallComponents(){
		DeleteDirFilesEx('/bitrix/components/'.self::partnerName.'/auth.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/components/'.self::partnerName.'/basket.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/components/'.self::partnerName.'/form.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/components/'.self::partnerName.'/instargam.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/components/'.self::partnerName.'/social.info.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/components/'.self::partnerName.'/tabs.'.self::solutionName.'/');
		DeleteDirFilesEx('/bitrix/components/'.self::partnerName.'/theme.'.self::solutionName.'/');

		return true;
	}

	function DoInstall(){
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();

		$APPLICATION->IncludeAdminFile(GetMessage('DIGITAL_INSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/step.php');
	}

	function DoUninstall(){
		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
		$APPLICATION->IncludeAdminFile(GetMessage('DIGITAL_UNINSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/unstep.php');
	}
}