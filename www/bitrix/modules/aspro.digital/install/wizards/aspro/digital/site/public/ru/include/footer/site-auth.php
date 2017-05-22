<!-- noindex -->
<div class="ajax_auth hidden">
	<?$APPLICATION->IncludeComponent(
		"bitrix:system.auth.form",
		"aspro",
		Array(
			"REGISTER_URL" => SITE_DIR."cabinet/registration/",
			"PROFILE_URL" => SITE_DIR."cabinet/",
			"FORGOT_PASSWORD_URL" => SITE_DIR."cabinet/forgot-password/",
			"AUTH_URL" => SITE_DIR."ajax/show_auth_popup.php",
			"SHOW_ERRORS" => "Y",
			"POPUP_AUTH" => "Y",
			"AJAX_MODE" => "Y"
		)
	);?>
</div>
<!-- /noindex -->