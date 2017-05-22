<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="subscribe-edit-main border_block form">
<?if($arResult["MESSAGE"] || $arResult["ERROR"]):?>
	<div class="top-form messages">
		<?foreach($arResult["MESSAGE"] as $itemID=>$itemValue)
			echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"OK"));
		foreach($arResult["ERROR"] as $itemID=>$itemValue)
			echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"ERROR"));?>
	</div>
<?endif;?>
<?
//whether to show the forms
if($arResult["ID"] == 0 && empty($_REQUEST["action"]) || CSubscription::IsAuthorized($arResult["ID"]))
{
	//show confirmation form
	if($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y")
	{
		include("confirmation.php");
	}
	//setting section
	include("setting.php");
	//status and unsubscription/activation section
	if($arResult["ID"]>0)
	{
		include("status.php");
	}?>
<?}?>
</div>