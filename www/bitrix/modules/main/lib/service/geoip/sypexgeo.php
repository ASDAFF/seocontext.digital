<?
namespace Bitrix\Main\Service\GeoIp;

use Bitrix\Main\Error;
use Bitrix\Main\Result;
use Bitrix\Main\Text\Encoding;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class MaxMind
 * @package Bitrix\Main\Service\GeoIp
 */
final class SypexGeo extends Base
{
	public function getTitle()
	{
		return Loc::getMessage('MAIN_SRV_GEOIP_SG_TITLE');
	}

	public function getDescription()
	{
		return Loc::getMessage('MAIN_SRV_GEOIP_SG_DESCRIPTION');
	}

	/**
	 * @param string $ip Ip address
	 * @param string $key license key.
	 * @return Result
	 */
	protected function sendRequest($ip, $key)
	{
		$result = new Result();
		$httpClient = $this->getHttpClient();
		$url = 'http://api.sypexgeo.net/';

		if(strlen($key) > 0)
			$url .= $key.'/';

		$url .= "json/".$ip;

		$httpRes = $httpClient->get($url);
		$errors = $httpClient->getError();

		if (!$httpRes && !empty($errors))
		{
			$strError = "";

			foreach($errors as $errorCode => $errMes)
				$strError .= $errorCode.": ".$errMes;

			$result->addError(new \Bitrix\Main\Error($strError));
		}
		else
		{
			$status = $httpClient->getStatus();

			if ($status != 200)
			{
				$result->addError(new Error('Sypexgeo.net http status: '.$status));
			}
			else
			{
				$arRes = json_decode($httpRes, true);

				if(is_array($arRes))
				{
					if(strtolower(SITE_CHARSET) != 'utf-8')
						$arRes = Encoding::convertEncoding($arRes, 'UTF-8', SITE_CHARSET);

					$result->setData($arRes);
				}
				else
				{
					$result->addError(new Error('Can\'t decode json result'));
				}
			}
		}

		return $result;
	}

	protected static function getHttpClient()
	{
		return new HttpClient(array(
			"version" => "1.1",
			"socketTimeout" => 5,
			"streamTimeout" => 5,
			"redirect" => true,
			"redirectMax" => 5,
		));
	}

	public function getSupportedLanguages()
	{
		return array('en', 'ru');
	}

	public function getData($ip, $lang = '')
	{
		$result = new DataResult;

		$result->ip = $ip;
		$result->lang = $lang = strlen($lang) > 0 ? $lang : 'en';
		$key = !empty($this->config['KEY']) ? $this->config['KEY'] : '';
		$res = $this->sendRequest($ip, $key);

		if($res->isSuccess())
		{
			$data = $res->getData();

			$result->countryName = $data['country']['name_'.$lang];
			$result->countryCode = $data['country']['iso'];
			$result->regionName = $data['region']['name_'.$lang];
			$result->regionCode = $data['region']['iso'];
			$result->cityName = $data['city']['name_'.$lang];
			$result->latitude = $data['city']['lat'];
			$result->longitude = $data['city']['lon'];
			$result->timezone = $data['region']['timezone'];
		}
		else
		{
			$result->addErrors($res->getErrors());
		}

		return $result;
	}

	public function createConfigField(array $postFields)
	{
		return array(
			'KEY' => isset($postFields['KEY']) ? $postFields['KEY'] : ''
		);
	}

	public function getAdminConfigHtml()
	{
		return '
		 <tr>
		 	<td>'.Loc::getMessage('MAIN_SRV_GEOIP_SG_KEY').':</td>
		 	<td><input type="text" name="KEY" size="45" maxlength="255" value="'.htmlspecialcharsbx($this->config['KEY']).'" ></td>
	 	</tr>';
	}

	public function getProvidingInfo()
	{
		$result = new DataResult();
		$result->countryName = true;
		$result->countryCode = true;
		$result->regionName = true;
		$result->regionCode = true;
		$result->cityName = true;
		$result->latitude = true;
		$result->longitude = true;
		$result->timezone = true;
		return $result;
	}
}