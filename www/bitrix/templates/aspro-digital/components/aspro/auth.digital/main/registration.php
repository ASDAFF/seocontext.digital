<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true ) die();?>
<?\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);?>
<?$APPLICATION->AddChainItem(GetMessage("TITLE"));?>
<?$APPLICATION->SetTitle(GetMessage("TITLE"));?>
<?$APPLICATION->SetPageProperty("TITLE_CLASS", "center");?>
<style type="text/css">
	.left-menu-md, body .container.cabinte-page .maxwidth-theme .left-menu-md, .right-menu-md, body .container.cabinte-page .maxwidth-theme .right-menu-md{display:none !important;}
	.content-md{width:100%;}
</style>
<?global $USER, $APPLICATION;
if( !$USER->IsAuthorized() ){?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:main.register",
		"main",
		Array(
			"USER_PROPERTY_NAME" => "",
			"SHOW_FIELDS" => array( "LAST_NAME", "NAME", "SECOND_NAME", "EMAIL", "PERSONAL_PHONE" ),
			"REQUIRED_FIELDS" => array( "NAME","PERSONAL_PHONE", "EMAIL" ),
			"AUTH" => "Y",
			"USE_BACKURL" => "Y",
			"SUCCESS_PAGE" => "",
			"SET_TITLE" => "N",
			"USER_PROPERTY" => array()
		)
	);?>
<?}else{
	LocalRedirect( $arParams["SEF_FOLDER"] );
}?>