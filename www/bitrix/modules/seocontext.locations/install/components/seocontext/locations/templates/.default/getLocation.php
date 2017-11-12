<?php

require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

use Seocontext\Locations\Manager;
use Bitrix\Main\Loader;

Loader::includeModule('seocontext.locations');

Manager::getLocation();

?>