<?
use \Bitrix\Main\Localization\Loc;

if (!check_bitrix_sessid())
    return;

Loc::loadMessages(__FILE__);
?>
<form action="<? echo $APPLICATION->GetCurPage() ?>">
    <?= bitrix_sessid_post() ?>

    <input type="hidden" name="lang" value="<? echo LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="seocontext.locations">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <input type="hidden" id="savedataInput" name="savedata" value="Y">
    <? echo CAdminMessage::ShowMessage(Loc::getMessage("MOD_UNINST_WARN")) ?>
    <input type="radio" name="savedata" value="N"><? echo Loc::getMessage("SEOCONTEXT_LOCATIONS_GROUP_DELETE") ?>
    <br/>
    <input type="radio" name="savedata" value="Y"
           checked><? echo Loc::getMessage("SEOCONTEXT_LOCATIONS_GROUP_NOT_DELETE") ?>
    <br/><br/>
    <input type="button" onclick="history.back();" value="<? echo Loc::getMessage("MOD_BACK"); ?>"/>
    <input type="submit" name="" value="<? echo Loc::getMessage("MOD_UNINST_DEL") ?>">
</form>