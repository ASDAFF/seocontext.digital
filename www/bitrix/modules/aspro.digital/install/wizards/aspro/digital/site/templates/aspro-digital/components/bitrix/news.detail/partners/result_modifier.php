<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die;?>
<?if($arResult['DISPLAY_PROPERTIES'])
{
	$arResult['DISPLAY_PROPERTIES'] = CDigital::PrepareItemProps($arResult['DISPLAY_PROPERTIES']);
}?>