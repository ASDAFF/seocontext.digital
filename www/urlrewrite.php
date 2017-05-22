<?
$arUrlRewrite = array(
	array(
		"CONDITION" => "#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#",
		"RULE" => "alias=\$1",
		"ID" => "bitrix:im.router",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/bitrix/services/ymarket/#",
		"RULE" => "",
		"ID" => "",
		"PATH" => "/bitrix/services/ymarket/index.php",
	),
	array(
		"CONDITION" => "#^/online/(/?)([^/]*)#",
		"RULE" => "",
		"ID" => "bitrix:im.router",
		"PATH" => "/desktop_app/router.php",
	),
	array(
		"CONDITION" => "#^/company/licenses/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/licenses/index.php",
	),
	array(
		"CONDITION" => "#^/company/partners/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/partners/index.php",
	),
	array(
		"CONDITION" => "#^/stssync/calendar/#",
		"RULE" => "",
		"ID" => "bitrix:stssync.server",
		"PATH" => "/bitrix/services/stssync/calendar/index.php",
	),
	array(
		"CONDITION" => "#^/company/reviews/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/reviews/index.php",
	),
	array(
		"CONDITION" => "#^/company/vacancy/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/vacancy/index.php",
	),
	array(
		"CONDITION" => "#^/company/staff/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/company/staff/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/cabinet/news/index.php",
	),
	array(
		"CONDITION" => "#^/projects/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/projects/index.php",
	),
	array(
		"CONDITION" => "#^/services/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/services/index.php",
	),
	array(
		"CONDITION" => "#^/articles/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/articles/index.php",
	),
	array(
		"CONDITION" => "#^/product/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/product/index.php",
	),
	array(
		"CONDITION" => "#^/cabinet/#",
		"RULE" => "",
		"ID" => "aspro:auth.digital",
		"PATH" => "/cabinet/index.php",
	),
	array(
		"CONDITION" => "#^/news/#",
		"RULE" => "",
		"ID" => "bitrix:news",
		"PATH" => "/news/index.php",
	),
);

?>