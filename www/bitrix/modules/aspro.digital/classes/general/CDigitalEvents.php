<?
if(!defined('DIGITAL_MODULE_ID'))
	define('DIGITAL_MODULE_ID', 'aspro.digital');

class CDigitalEvents{
	const MODULE_ID = DIGITAL_MODULE_ID;

	function OnBeforeUserUpdateHandler(&$arFields){
		$bTmpUser = false;

		if(strlen($arFields['NAME']))
			$arFields['NAME'] = trim($arFields['NAME']);

		if(strlen($arFields['NAME']) && !strlen($arFields['LAST_NAME']) && !strlen($arFields['SECOND_NAME']))
		{
			$siteID = SITE_ID;
			if($siteID == 'ru')
				$siteID = 's1';
			if(defined('ADMIN_SECTION') && ADMIN_SECTION === true)
				$bOneFIO = COption::GetOptionString(DIGITAL_MODULE_ID, 'PERSONAL_ONEFIO', 'Y', $siteID);
			else{
				$arFrontParametrs = CDigital::GetFrontParametrsValues($siteID);
				$bOneFIO = $arFrontParametrs['PERSONAL_ONEFIO'] !== 'N';
			}

			if($bOneFIO)
			{
				$arName = explode(' ', $arFields['NAME']);
				if($arName)
				{
					$arFields['NAME'] = '';
					$arFields['SECOND_NAME'] = '';
					foreach($arName as $i => $name)
					{
						if(!$i)
						{
							$arFields['LAST_NAME'] = $name;
						}
						else
						{
							if(!strlen($arFields['NAME']))
								$arFields['NAME'] = $name;

							elseif(!strlen($arFields['SECOND_NAME']))
								$arFields['SECOND_NAME'] = $name;

						}
					}
				}
			}
		}

		if(strlen($arFields['EMAIL']))
		{
			$bEmailError = false;
			$rsUser = CUser::GetList($by = 'ID', $order = 'ASC', array('=EMAIL' => $arFields['EMAIL'], '!ID' => $arFields['ID']));
			if(!$bEmailError = $rsUser->SelectedRowsCount() > 0)
			{
				$rsUser = CUser::GetList($by = 'ID', $order = 'ASC', array('LOGIN_EQUAL' => $arFields['EMAIL'], '!ID' => $arFields['ID']));
				$bEmailError = $rsUser->SelectedRowsCount() > 0;
			}

			if($bEmailError){
				global $APPLICATION;
				// $message = COption::GetOptionString(DIGITAL_MODULE_ID, 'EMAIL_IS_ALREADY_EXISTS_MESSAGE', GetMessage('PRIME_OPTIONS_EMAIL_IS_ALREADY_EXISTS_MESSAGE_DEFAULT'), SITE_ID);
				$APPLICATION->throwException(str_replace('#EMAIL#', $arFields['EMAIL'], GetMessage('EMAIL_IS_ALREADY_EXISTS_MESSAGE_DEFAULT')));
				return false;
			}
			else{
				// !admin
				if (!isset($GLOBALS['USER']) || !is_object($GLOBALS['USER']))
					$GLOBALS['USER'] = new \CUser;

				if(defined('ADMIN_SECTION') && ADMIN_SECTION === true)
				{
					if(isset($arFields['ID']) && $arFields['ID'])
					{
						if(!in_array(1, CUser::GetUserGroup($arFields['ID'])))
							$arFields['LOGIN'] = $arFields['EMAIL'];
					}
					elseif(isset($arFields['GROUP_ID']) && $arFields['GROUP_ID'])
					{
						$arUserGroups = array();
						$arTmpGroups = (array)$arFields['GROUP_ID'];
						foreach($arTmpGroups as $arGroup)
						{
							if(is_array($arGroup))
								$arUserGroups[] = $arGroup['GROUP_ID'];
							else
								$arUserGroups[] = $arGroup;
						}

						if(count(array_intersect($arUserGroups, array(1)))<=0)
							$arFields['LOGIN'] = $arFields['EMAIL'];
					}
					else
						$arFields['LOGIN'] = $arFields['EMAIL'];
				}
				else
				{
					if(!$GLOBALS['USER']->IsAdmin())
						$arFields['LOGIN'] = $arFields['EMAIL'];
				}
			}
		}

		if ($bTmpUser)
			unset($GLOBALS["USER"]);

		return $arFields;
	}

	static function OnAfterUserRegisterHandler($arFields){

	}

	static function OnEndBufferContentHandler(&$content)
	{
		if(!defined('ADMIN_SECTION'))
		{
			global $SECTION_BNR_CONTENT;
			if($SECTION_BNR_CONTENT)
			{
				$start = strpos($content, '<!--title_content-->');
				if($start>0)
				{
					$end = strpos($content, '<!--end-title_content-->');

					if(($end>0) && ($end>$start))
					{
						if(defined("BX_UTF") && BX_UTF === true)
							$content = CDigital::utf8_substr_replace($content, "", $start, $end-$start);
						else
							$content = substr_replace($content, "", $start, $end-$start);
					}
				}
				$content = str_replace("body class=\"", "body class=\"with_banners ", $content);
			}
		}
	}

	static function OnBeforePrologHandler(){

	}
}
?>