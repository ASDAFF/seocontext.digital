<?php

//include($_SERVER["DOCUMENT_ROOT"].'/include/trace.php');

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Seocontext\Locations\Manager;

class Locations extends CBitrixComponent
{

    protected function checkModules()
    {
        if (!Loader::includeModule('seocontext.locations')) {
            //ShowError(Loc::getMessage('ACADEMY_D7_MODULE_NOT_INSTALLED'));
            throw new Main\LoaderException(Loc::getMessage('SEOCONTEXT_MODULE_NOT_INSTALLED'));
        }
    }

    public function executeComponent()
    {
        $this->includeComponentLang('class.php');

        try {
            $this->checkModules();
            $this->checkParams();
            $this->executeProlog();

            if ($res = !$this->readDataFromCache()) {
                $this->populateArResult();
                
                //$this->putDataToCache();
                $this->includeComponentTemplate();
            }
            $this->executeEpilog();
        } catch (Exception $e) {
            $this->abortResultCache();
            ShowError($e->getMessage());
        }

    }

    protected function readDataFromCache()
    {
    	return false;
        if ($this->arParams['CACHE_TYPE'] == 'N')
            return false;


        return !($this->StartResultCache(false));
    }

    
    protected function checkParams()
    {
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 36000;
        }

        //Trace($this->arParams);
//        if ($this -> arParams['IBLOCK_ID'] <= 0)
//            throw new Main\ArgumentNullException('IBLOCK_ID');
    }

    
    protected function executeProlog()
    {        

    }

    protected function populateArResult()
    {
        $this->arResult['selected'] = Manager::getSelectedLocations();
    }

    
    protected function executeEpilog()
    {
        \Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/seocontext.locations/loc-conf.js');
    }
}

;