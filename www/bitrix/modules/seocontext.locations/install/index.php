<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Maycat\D7dull\ExampleTable;

Loc::loadMessages(__FILE__);
Loader::includeModule('sale');

define("GROUP_FAV_SYMBOL_NAME", Loc::getMessage('SEOCONTEXT_LOCATIONS_GROUP_SYMBOL_NAME'));
define("GROUP_FAV_RU_NAME", Loc::getMessage('SEOCONTEXT_LOCATIONS_GROUP_RU_NAME'));
define("GROUP_FAV_EN_NAME", Loc::getMessage('SEOCONTEXT_LOCATIONS_GROUP_EN_NAME'));

class seocontext_locations extends CModule
{
    const MODULE_ID = 'seocontext.locations';
    /** @var string */
    var $MODULE_ID = 'seocontext.locations';

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    public function __construct()
    {
        $arModuleVersion = array();
        include(__DIR__ . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->MODULE_NAME = Loc::getMessage('SEOCONTEXT_LOCATIONS_MODULE_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('SEOCONTEXT_LOCATIONS_MODULE_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
        $this->PARTNER_NAME = Loc::getMessage('SEOCONTEXT_LOCATIONS_PARTNER_NAME');
        $this->PARTNER_URI = Loc::getMessage('SEOCONTEXT_LOCATIONS_PARTNER_URI');
    }

    public function DoInstall()
    {
        // TODO: check existence of 'sale' module
        global $APPLICATION;
        if ($this->isVersionD7()) {
            \Bitrix\Main\ModuleManager::registerModule($this->MODULE_ID);

            $this->InstallDB();
            $this->InstallEvents();
            $this->InstallFiles();
        } else {
            $APPLICATION->ThrowException(Loc::getMessage("SEOCONTEXT_INSTALL_ERROR_VERSION"));
        }

        $APPLICATION->IncludeAdminFile(Loc::getMessage("SEOCONTEXT_INSTALL_TITLE"), $this->GetPath() . "/install/step.php");
    }

    public function DoUninstall()
    {
        global $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();

        if ($request["step"] < 2) {
            $APPLICATION->IncludeAdminFile(Loc::getMessage("SEOCONTEXT_UNINSTALL_TITLE"), $this->GetPath() . "/install/unstep1.php");
        } elseif ($request["step"] == 2) {
            $this->UnInstallFiles();
            $this->UnInstallEvents();

            if ($request["savedata"] != "Y")
                $this->UnInstallDB();

            \Bitrix\Main\ModuleManager::unRegisterModule($this->MODULE_ID);

            $APPLICATION->IncludeAdminFile(Loc::getMessage("SEOCONTEXT_UNINSTALL_TITLE"), $this->GetPath() . "/install/unstep2.php");
        }
    }

    function InstallFiles($arParams = array())
    {
        $path = $this->GetPath() . "/install/components";

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path))
            CopyDirFiles($path, $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($path);

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            CopyDirFiles($this->GetPath() . "/install/admin/", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"); //
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles))
                        continue;
                    file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item,
                        '<' . '? require($_SERVER["DOCUMENT_ROOT"]."' . $this->GetPath(true) . '/admin/' . $item . '");?' . '>');
                }
                closedir($dir);
            }
        }

        $path_js = $this->GetPath() . "/install/js";
        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path_js))
            CopyDirFiles($path_js, $_SERVER["DOCUMENT_ROOT"] . "/bitrix/js", true, true);
        else
            throw new \Bitrix\Main\IO\InvalidPathException($path_js);

        return true;
    }

    function UnInstallFiles()
    {
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/js/seocontext.locations');
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/seocontext/locations');
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/seocontext/cond.include');
        $directory = $_SERVER["DOCUMENT_ROOT"] . '/bitrix/components/seocontext';
        $elements = scandir($directory);
        if (count($elements) <= 2) {
            \Bitrix\Main\IO\Directory::deleteDirectory($directory);            
        }

        if (\Bitrix\Main\IO\Directory::isDirectoryExists($path = $this->GetPath() . '/admin')) {
            DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . $this->GetPath() . '/install/admin/', $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin');
            if ($dir = opendir($path)) {
                while (false !== $item = readdir($dir)) {
                    if (in_array($item, $this->exclusionAdminFiles))
                        continue;
                    \Bitrix\Main\IO\File::deleteFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/' . $this->MODULE_ID . '_' . $item);
                }
                closedir($dir);
            }
        }
        return true;
    }

    public $group_fav_id;

    public function InstallDB()
    {
        $DB_id = $this->getGroupId();
        if(!is_numeric($DB_id))
        {
            // add group FAVOURITES to bitrix
            $result = \Bitrix\Sale\Location\GroupTable::add(array(
                'CODE' => GROUP_FAV_SYMBOL_NAME
            ));
            if ($result->isSuccess()) {
                $id = $result->getId();
                define("GROUP_FAV_ID", $id);
            }

            \Bitrix\Sale\Location\Name\GroupTable::add(array(
                'LOCATION_GROUP_ID' => GROUP_FAV_ID,
                'LID' => 'ru',
                'NAME' => GROUP_FAV_RU_NAME
            ));

            \Bitrix\Sale\Location\Name\GroupTable::add(array(
                'LOCATION_GROUP_ID' => GROUP_FAV_ID,
                'LID' => 'en',
                'NAME' => GROUP_FAV_EN_NAME
            ));
        }
    }

    public function UninstallDB()
    {
        // del group FAVOURITES
        $group_id = $this->getGroupId();
        $result = \Bitrix\Sale\Location\GroupTable::delete($group_id);
        if($result->isSuccess())
        {
            global $DB;
            $query = "delete from b_sale_location2location_group where LOCATION_GROUP_ID=".$group_id;
            $DB->Query($query);
        }
    }

    public function getGroupId()
    {
        $res = \Bitrix\Sale\Location\GroupTable::getList(array(
            'select' => array('ID'),
            'filter' => array('=CODE' => GROUP_FAV_SYMBOL_NAME)
        ));
        $res = $res->fetchAll();
        return $res[0]["ID"];
    }

    public function GetPath($notDocumentRoot = false)
    {
        if ($notDocumentRoot)
            return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
        else
            return dirname(__DIR__);
    }

    // D7
    public function isVersionD7()
    {
        return CheckVersion(\Bitrix\Main\ModuleManager::getVersion('main'), '14.00.00');
    }
}
