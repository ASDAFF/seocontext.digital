<?

require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Seocontext\Locations\Content;
use Seocontext\Locations\Manager;

Loader::includeModule('seocontext.locations');

$includeId = $_REQUEST['includeId'];
$locationCode = $_REQUEST['code'];
$useDefault = isset($_REQUEST['useDefault']) ? $_REQUEST['useDefault'] : false;

$action = $_REQUEST['action'];

if ($action == 'addloc')
{
    $code = $_POST["Code"];
    $res_array = Manager::addFavouriteLocation($code); // return array(name, code)
    echo json_encode($res_array);
}
else
{
    if($action == 'delloc')
    {
        $code = $_POST["Code"];
        Manager::deleteFavouriteLocation($code);
    }
    else
    {
        if ($action == 'save') {
            $content = $_REQUEST['content'];
            Content::save($locationCode, $content, $includeId);
        } else {
            AddMessage2Log($_REQUEST);
            $content = Content::load($locationCode, $includeId, $useDefault);
            echo $content;
        }
    }
}
