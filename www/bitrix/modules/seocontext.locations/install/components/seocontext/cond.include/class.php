<?php

//include($_SERVER["DOCUMENT_ROOT"].'/include/trace.php');

use \Bitrix\Main;
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use Seocontext\Locations\Manager;
use Seocontext\Locations\Content;


class CondInclude extends CBitrixComponent
{
    var $test;

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
        if (!isset($this->arParams['CACHE_TIME'])) {
            $this->arParams['CACHE_TIME'] = 36000;
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
        $this->arResult['id'] = self::generateId();

        $includeId = $this->arParams["INCLUDE_ID"];

        if ($this->arParams['ALL_ON_PAGE']=='Y')
        {
            // Load all content blocks (they will be handled by js)
            $this->arResult['content'] = Content::loadAll($includeId);
            if (!isset($this->arResult['content'][Content::DEFAULT_CONTENT_FILENAME]))
                $this->arResult['content'][Content::DEFAULT_CONTENT_FILENAME] = '';
        }
        else
        {
            // We need not load content, because it will be auto loaded using ajax-request

            // $locCode = Manager::getCurrentLocationCode();
            // $this->arResult['location-code'] = $locCode;
            // $this->arResult['content'] = array($locCode => Content::load($locCode, $dir));
        }


    }

    
    protected function executeEpilog()
    {

    }

    static $id = 0;
    static function generateId()
    {
        ++self::$id;
        //return self::$id.'_'.time(); // breaks bitrix composite mode
        return 'seocontext_content_'.self::$id;
    }
}

;