<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
if($arResult["SEARCH"] )
{
	foreach($arResult["SEARCH"] as $key => $arSearch)
	{
		if(strpos($arSearch["URL_WO_PARAMS"], "#YEAR#") !== false)
		{
			if($arSearch["DATE_CHANGE"])
			{
				if($arDateTime = ParseDateTime($arSearch["DATE_CHANGE"], FORMAT_DATETIME))
				{
					$url = str_replace("#YEAR#", $arDateTime['YYYY'], $arSearch["URL_WO_PARAMS"]);
					if($arResult["NAV_RESULT"]->url_add_params)
						$url.= "?".implode("&", $arResult["NAV_RESULT"]->url_add_params);
					$arResult["SEARCH"][$key]["URL"] = $url;
				}
			}
		}
	}
}
?>