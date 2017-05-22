<?
function custom_mb_in_array(array $_hayStack,$_needle) {
    foreach ($_hayStack as $value) {
        if((mb_strtolower($value)) == (mb_strtolower($_needle))) {
            return true;
        }
    }
	return false;   
}

if($arResult['ITEMS'])
{
	$arResult['PROPS'] = array();
	$arHideProps = array('PRICE', 'FORM_ORDER', 'FILTER_PRICE');
	$arPlusValue = array('+', 1, 'true', 'y', GetMessage('YES'), GetMessage('TRUE'));
	$arMinusValue = array('-', 0, 'false', 'n', GetMessage('NO'), GetMessage('FALSE'));
	
	foreach($arResult['ITEMS'] as $key_main => $arItem){
		CDigital::getFieldImageData($arResult['ITEMS'][$key_main], array('PREVIEW_PICTURE'));
		if(isset($arItem['PROPERTIES']) && $arItem['PROPERTIES'])
		{
			foreach($arItem['PROPERTIES'] as $key => $arProp){
				if($arProp['VALUE'] && !in_array($arProp['CODE'], $arHideProps))
				{
					$arResult['PROPS'][$key]['NAME'] = $arProp['NAME'];
					
					if(custom_mb_in_array($arPlusValue, $arProp['VALUE']))
						$arResult['ITEMS'][$key_main]['PROPERTIES'][$key]['TYPE'] = 'Y';
					elseif(custom_mb_in_array($arMinusValue, $arProp['VALUE']))
						$arResult['ITEMS'][$key_main]['PROPERTIES'][$key]['TYPE'] = 'N';
				}
			}			
		}
	}
}
?>