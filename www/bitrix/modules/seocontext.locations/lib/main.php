<?php
namespace Seocontext\Locations {

    class Main
    {
        public static function getBaseServerFolder()
        {
            // todo: handle case when module is located in local folder
            return $_SERVER['DOCUMENT_ROOT'] . self::getBaseFolder();
        }

        public static function getBaseFolder()
        {
            // todo: handle case when module is located in local folder
            return '/bitrix/modules/seocontext.locations';
        }
    }

}
