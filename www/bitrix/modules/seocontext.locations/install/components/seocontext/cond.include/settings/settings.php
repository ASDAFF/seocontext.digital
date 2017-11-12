<?
define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_STATISTIC", true);
define("NO_AGENT_CHECK", true);

use Bitrix\Main;
use Bitrix\Main\Loader;
use Seocontext\Locations\Manager;
use \Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"] . '/bitrix/modules/main/include/prolog_before.php');
Loader::includeModule('seocontext.locations');

Loc::loadLanguageFile(__FILE__);
$groupLocations = Manager::getFavouriteLocations();
$favLocations = Manager::getSelectedLocations();
$locations = null;
foreach ($groupLocations as $loc) {
    if (!in_array($loc, $favLocations)) {
        $locations[] = $loc;
    }
}
$default_name = \Seocontext\Locations\Content::DEFAULT_CONTENT_FILENAME;
?>

<div class="seocontext-cond-include-params">
    <select id="select-location" size="5">
        <?
        echo "<option deletable='N' value='{$default_name}'>" . Loc::getMessage("SEOCONTEXT_CONTENT_DEFAULT") . "</option>";

        foreach ($favLocations as $location) {
            echo "<option deletable='N' value='{$location['CODE']}'>{$location['NAME']}</option>";
        }

        foreach ($locations as $location) {
            echo "<option deletable='Y' value='{$location['CODE']}'>{$location['NAME']}</option>";
        }
        ?>
    </select>
    <button id="add-loc" class="btn-loc"><<</button>
    <button id="del-loc" class="btn-loc">>></button>
    <input id="search-field" type="text"/>
    <textarea id="seocontext_content_editor"></textarea>
    <button id="save-btn"><?= Loc::getMessage("SEOCONTEXT_CONTENT_SAVE_BUTTON") ?></button>
</div>

<style>
    #search-field {
        min-width: 30% !important;
        width: 30% !important;
    }

    #select-location {
        width: 40% !important;
    }

    .btn-loc {
        width: 30px;
        height: 30px;
    }
</style>