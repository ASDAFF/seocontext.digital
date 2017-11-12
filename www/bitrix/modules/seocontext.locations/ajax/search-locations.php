<?php
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NO_AGENT_CHECK", true);

use Bitrix\Main;
use Bitrix\Main\Loader;
use Seocontext\Locations\Manager;

require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule('seocontext.locations');

$result = true;
$errors = array();
$data = array();

function formatData($items)
{
    $formatted = array('suggestions' => array());
    foreach ($items as $item)
    {
        $formatted['suggestions'][] = array(
            'value' => $item['FULLNAME'],
            'data' => $item['CODE']
        );
    }
    return $formatted;
}

try
{
    CUtil::JSPostUnescape();
    $request = Main\Context::getCurrent()->getRequest()->getQueryList();

    $searchPhrase = $request['query'];
    $searchPhrase = str_replace(',', ' ', $searchPhrase);
    $data = Manager::formatFoundLocations(Manager::findLocations($searchPhrase));

    // format data for jquery autocomplete from devbridge
    $data = formatData($data);
    $data['query'] = $searchPhrase;
}
catch (Main\SystemException $e)
{
    $result = false;
    $errors[] = $e->getMessage();
}

header('Content-Type: application/x-javascript; charset=' . LANG_CHARSET);
print(str_replace("'", '"', CUtil::PhpToJSObject($data, false, false, true)));
