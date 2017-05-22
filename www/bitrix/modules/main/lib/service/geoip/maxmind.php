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
final class MaxMind extends Base
{
	public function getTitle()
	{
		return 'MaxMind';
	}

	public function getDescription()
	{
		return Loc::getMessage('MAIN_SRV_GEOIP_MM_DESCRIPTION');
	}

	/**
	 * @param string $ipAddress Ip address
	 * @param string $userId user identifier obtained from www.maxmind.com
	 * @param string $licenseKey
	 * @return Result
	 */
	protected function sendRequest($ipAddress, $userId, $licenseKey)
	{
		$result = new Result();
		$httpClient = $this->getHttpClient();
		$httpClient->setHeader('Authorization', 'Basic '.base64_encode($userId.':'.$licenseKey));
		$httpRes = $httpClient->get("https://geoip.maxmind.com/geoip/v2.1/city/".$ipAddress.'?pretty');

		$errors = $httpClient->getError();

		if (!$httpRes && !empty($errors))
		{
			$strError = "";

			foreach($errors as $errorCode => $errMes)
				$strError .= $errorCode.": ".$errMes;

			$result->addError(new Error($strError));
		}
		else
		{
			$status = $httpClient->getStatus();

			if ($status != 200)
				$result->addError(new Error('Http status: '.$status));

			$arRes = json_decode($httpRes, true);

			if(is_array($arRes))
			{
				if(strtolower(SITE_CHARSET) != 'utf-8')
					$arRes = Encoding::convertEncoding($arRes, 'UTF-8', SITE_CHARSET);

				if ($status == 200)
				{
					$result->setData($arRes);
				}
				else
				{
					$result->addError(new Error('['.$arRes['code'].'] '.$arRes['error']));
				}
			}
			else
			{
				$result->addError(new Error('Can\'t decode json result'));
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
		return array('de', 'en', 'es', 'fr', 'ja', 'pt-BR', 'ru', 'zh-CN');
	}

	public function getData($ipAddress, $lang = '')
	{
		$result = new DataResult;
		$result->ip = $ipAddress;
		$result->lang = $lang = strlen($lang) > 0 ? $lang : 'en';

		if(strlen($this->config['USER_ID']) <=0 || strlen($this->config['LICENSE_KEY']) <= 0)
		{
			$result->addError(new Error(Loc::getMessage('MAIN_SRV_GEOIP_MM_SETT_EMPTY')));
			return $result;
		}

		$res = $this->sendRequest($ipAddress, $this->config['USER_ID'], $this->config['LICENSE_KEY']);

		if($res->isSuccess())
		{
			$data = $res->getData();

			if(!empty($data['country']['names'][$lang]))
				$result->countryName = $data['country']['names'][$lang];

			if(!empty($data['country']['iso_code']))
				$result->countryCode = $data['country']['iso_code'];

			if(!empty($data['subdivisions'][0]['names'][$lang]))
				$result->regionName = $data['subdivisions'][0]['names'][$lang];

			if(!empty($data['subdivisions'][0]['iso_code']))
				$result->regionCode = $data['subdivisions'][0]['iso_code'];

			if(!empty($data['city']['names'][$lang]))
				$result->cityName = $data['city']['names'][$lang];

			if(!empty($data['location']['latitude']))
				$result->latitude = $data['location']['latitude'];

			if(!empty($data['location']['longitude']))
				$result->longitude = $data['location']['longitude'];

			if(!empty($data['location']['time_zone']))
				$result->timezone = $data['location']['time_zone'];

			if(!empty($data['postal']['code']))
				$result->zipCode = $data['postal']['code'];

			if(!empty($data['traits']['isp']))
				$result->ispName = $data['traits']['isp'];

			if(!empty($data['traits']['organization']))
				$result->organizationName = $data['traits']['organization'];
		}
		else
		{
			$result->addErrors($res->getErrors());
		}

		return $result;
	}

	public function isInstalled()
	{
		return !empty($this->config['USER_ID']) && !empty($this->config['LICENSE_KEY']);
	}

	public function createConfigField(array $postFields)
	{
		return array(
			'USER_ID' => isset($postFields['USER_ID']) ? $postFields['USER_ID'] : '',
			'LICENSE_KEY' => isset($postFields['LICENSE_KEY']) ? $postFields['LICENSE_KEY'] : '',
		);
	}

	public function getAdminConfigHtml()
	{
		return '
		 <tr class="adm-detail-required-field">
		 	<td>'.Loc::getMessage('MAIN_SRV_GEOIP_MM_F_USER_ID').':</td>
		 	<td><input type="text" name="USER_ID" size="45" maxlength="255" value="'.htmlspecialcharsbx($this->config['USER_ID']).'" ></td>
	 	</tr>
	 	<tr class="adm-detail-required-field">	 	
		 	<td>'.Loc::getMessage('MAIN_SRV_GEOIP_MM_F_LICENSE_KEY').':</td>
		 	<td><input type="text" name="LICENSE_KEY" size="45" maxlength="255" value="'.htmlspecialcharsbx($this->config['LICENSE_KEY']).'" ></td>
		 </tr>
		';
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
		$result->zipCode = true;
		$result->ispName = true;
		$result->organizationName = true;
		return $result;
	}
}