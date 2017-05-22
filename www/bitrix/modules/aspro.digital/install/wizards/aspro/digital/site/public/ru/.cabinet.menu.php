<?
$aMenuLinks = Array(
	Array(
		"Персональные данные", 
		"#SITE_DIR#cabinet/index.php", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Подписки", 
		"#SITE_DIR#cabinet/subscribe/", 
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"Материалы", 
		"#SITE_DIR#cabinet/news/", 
		Array(), 
		Array(), 
		"\$GLOBALS['USER']->IsAuthorized()" 
	),
	Array(
		"Написать директору", 
		"#SITE_DIR#cabinet/form/", 
		Array(), 
		Array(), 
		"\$GLOBALS['USER']->IsAuthorized()" 
	),
	Array(
		"Выйти", 
		"?logout=yes&login=yes", 
		Array(), 
		Array("class"=>"exit", "BLOCK"=>"<i class='icons'><svg id='Exit.svg' xmlns='http://www.w3.org/2000/svg' width='8' height='8.031' viewBox='0 0 8 8.031'><path id='Rounded_Rectangle_82_copy_2' data-name='Rounded Rectangle 82 copy 2' class='cls-1' d='M333.831,608.981l2.975,2.974a0.6,0.6,0,0,1-.85.85l-2.975-2.974-2.974,2.974a0.6,0.6,0,0,1-.85-0.85l2.974-2.974-2.974-2.975a0.6,0.6,0,0,1,.85-0.849l2.974,2.974,2.975-2.974a0.6,0.6,0,0,1,.85.849Z' transform='translate(-329 -604.969)'/></svg></i>"), 
		"\$GLOBALS['USER']->IsAuthorized()" 
	)
);
?>