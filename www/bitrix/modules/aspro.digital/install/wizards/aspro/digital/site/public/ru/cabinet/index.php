<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");?>
<?$APPLICATION->IncludeComponent("aspro:auth.digital", "main", array(
	"SEF_MODE" => "Y",
	"SEF_FOLDER" => "#SITE_DIR#cabinet/",
	"SEF_URL_TEMPLATES" => array(
		"auth" => "",
		"registration" => "registration/",
		"forgot" => "forgot-password/",
		"change" => "change-password/",
		"confirm" => "confirm-password/",
		"confirm_registration" => "confirm-registration/",
	),
	"PERSONAL" => "#SITE_DIR#personal/"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>