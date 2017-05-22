<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
global $USER;

if($_GET["auth_service_error"]){
	LocalRedirect(SITE_DIR.'cabinet/');
}
if(!$USER->IsAuthorized()){?>
	<?if(isset($_REQUEST['backurl']) && $_REQUEST['backurl']) // fix ajax url
	{
		if($_REQUEST['backurl'] != $_SERVER['REQUEST_URI'])
		{
			$_SERVER['QUERY_STRING'] = '';
			$_SERVER['REQUEST_URI'] = $_REQUEST['backurl'];
			$APPLICATION->reinitPath();
		}
	}?>
	<div id="wrap_ajax_auth" class="form popup">
		<div class="form-header">
			<div class="text"><div class="title"><?=\Bitrix\Main\Localization\Loc::getMessage('AUTHORIZE_TITLE');?></div></div>
		</div>
		<?
		$APPLICATION->IncludeComponent(
			"bitrix:system.auth.form",
			"main",
			Array(
				"REGISTER_URL" => SITE_DIR."cabinet/registration/?register=yes",
				"PROFILE_URL" => SITE_DIR."cabinet/",
				"FORGOT_PASSWORD_URL" => SITE_DIR."cabinet/forgot-password/?forgot-password=yes",
				"AUTH_URL" => SITE_DIR."cabinet/",
				"SHOW_ERRORS" => "Y",
				"POPUP_AUTH" => "Y",
				"AJAX_MODE" => "Y",
				"BACKURL" => ((isset($_REQUEST['backurl']) && $_REQUEST['backurl']) ? $_REQUEST['backurl'] : "")
			)
		);?>
	</div>
<?}
elseif(strlen($_REQUEST['backurl'])){
	LocalRedirect($_REQUEST['backurl']);
}
else{
	if(strpos($_SERVER['HTTP_REFERER'], SITE_DIR.'cabinet/') === false && strpos($_SERVER['HTTP_REFERER'], SITE_DIR.'ajax/form.php') === false){
		$APPLICATION->ShowHead();
		?>
		<script>
			jsAjaxUtil.ShowLocalWaitWindow( 'id', 'wrap_ajax_auth', true );
			BX.reload(false)
		</script>
		<?
	}
	else{
		LocalRedirect(SITE_DIR.'cabinet/');
	}
}