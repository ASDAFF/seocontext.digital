<?
namespace Bitrix\Main\Service\GeoIp;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Extension extends Base
{
	/**
	 * @return string Class title
	 */
	public function getTitle()
	{
		return Loc::getMessage('MAIN_SRV_GEOIP_EXT_TITLE');
	}

	/**
	 * @return string Class description
	 */
	public function getDescription()
	{
		return Loc::getMessage('MAIN_SRV_GEOIP_EXT_DESCRIPTION');
	}

	public function getSupportedLanguages()
	{
		return array('en');
	}
	
	/**
	 * @param string $ip Ip address
	 * @param string $lang Language identifier
	 * @return DataResult
	 */
	public function getData($ip, $lang = '')
	{
		$data = new DataResult;
		$data->lang = 'en';

		if (self::isAvailableBaseCountry())
		{
			$data->countryCode = geoip_country_code_by_name($ip);
			$data->countryName = geoip_country_name_by_name($ip);
		}

		if (self::isAvailableBaseCity())
		{
			$recordByName = geoip_record_by_name($ip);

			if (isset($recordByName['country_code']) && isset($recordByName['region']))
			{
				$data->timezone = geoip_time_zone_by_country_and_region(
					$recordByName['country_code'],
					$recordByName['region']
				);
			}

			$data->countryCode = $recordByName['country_code'];
			$data->countryName = $recordByName['country_name'];
			$data->regionCode = $recordByName['region'];
			$data->cityName = $recordByName['city'];
			$data->zipCode = $recordByName['postal_code'];
			$data->latitude = $recordByName['latitude'];
			$data->longitude = $recordByName['longitude'];
		}

		if (self::isAvailableBaseOrganization())
		{
			$data->organizationName = geoip_org_by_name($ip);
		}

		if (self::isAvailableBaseIsp())
		{
			$data->ispName = geoip_isp_by_name($ip);
		}

		if (self::isAvailableBaseAsn())
		{
			$data->asn = geoip_asnum_by_name($ip);
		}

		return $data;
	}

	/**
	 * Determine if GeoIP Database is available.
	 *
	 * @return bool
	 */
	protected static function isAvailable()
	{
		return function_exists('geoip_db_avail');
	}

	/**
	 * Determine if GeoIP Country Database is available (GEOIP_COUNTRY_EDITION).
	 *
	 * @return bool
	 */
	protected static function isAvailableBaseCountry()
	{
		return self::isAvailable() && geoip_db_avail(GEOIP_COUNTRY_EDITION);
	}

	/**
	 * Determine if GeoIP City Database is available (GEOIP_CITY_EDITION_REV0).
	 *
	 * @return bool
	 */
	protected static function isAvailableBaseCity()
	{
		return self::isAvailable() && geoip_db_avail(GEOIP_CITY_EDITION_REV0);
	}

	/**
	 * Determine if GeoIP Organization Database is available (GEOIP_ORG_EDITION).
	 *
	 * @return bool
	 */
	protected static function isAvailableBaseOrganization()
	{
		return self::isAvailable() && geoip_db_avail(GEOIP_ORG_EDITION);
	}

	/**
	 * Determine if GeoIP ISP Database is available (GEOIP_ISP_EDITION).
	 *
	 * @return bool
	 */
	protected static function isAvailableBaseIsp()
	{
		return self::isAvailable() && geoip_db_avail(GEOIP_ISP_EDITION) && function_exists('geoip_isp_by_name');
	}

	/**
	 * Determine if GeoIP ASN Database is available (GEOIP_ASNUM_EDITION).
	 *
	 * @return bool
	 */
	protected static function isAvailableBaseAsn()
	{
		return self::isAvailable() && geoip_db_avail(GEOIP_ASNUM_EDITION) && function_exists('geoip_asnum_by_name');
	}

	public function isInstalled()
	{
		return self::isAvailable();
	}

	public function getAdminConfigHtml()
	{
		return '
		<tr>	 	
		 	<td colspan="2">'.Loc::getMessage('MAIN_SRV_GEOIP_EXT_NOT_REQ').'</td>
		</tr>
		 <tr class="heading">
		 	<td colspan="2">'.Loc::getMessage('MAIN_SRV_GEOIP_EXT_DB_AVIALABLE').':</td>		 	
	 	</tr>
	 	<tr class="adm-detail-required-field">	 	
		 	<td width="40%">'.Loc::getMessage('MAIN_SRV_GEOIP_EXT_DB_COUNTRY').':</td>
		 	<td width="60%"><input type="checkbox"'.($this->isAvailableBaseCountry() ? ' checked' : '').' disabled></td>
		</tr>
		<tr class="adm-detail-required-field">	 	
		 	<td>'.Loc::getMessage('MAIN_SRV_GEOIP_EXT_DB_CITY').':</td>
		 	<td><input type="checkbox"'.($this->isAvailableBaseCity() ? ' checked' : '').' disabled></td>
		</tr>
		<tr class="adm-detail-required-field">	 	
		 	<td>'.Loc::getMessage('MAIN_SRV_GEOIP_EXT_DB_ORG').':</td>
		 	<td><input type="checkbox"'.($this->isAvailableBaseOrganization() ? ' checked' : '').' disabled></td>
		</tr>
		<tr class="adm-detail-required-field">	 	
		 	<td>'.Loc::getMessage('MAIN_SRV_GEOIP_EXT_DB_ISP').':</td>
		 	<td><input type="checkbox"'.($this->isAvailableBaseIsp() ? ' checked' : '').' disabled></td>
		</tr>
		<tr class="adm-detail-required-field">	 	
		 	<td>'.Loc::getMessage('MAIN_SRV_GEOIP_EXT_DB_ASN').':</td>
		 	<td><input type="checkbox"'.($this->isAvailableBaseAsn() ? ' checked' : '').' disabled></td>
		</tr>';
	}

	public function getProvidingInfo()
	{
		$result = new DataResult();

		if (self::isAvailableBaseCountry())
		{
			$result->countryCode = true;
			$result->countryName = true;
		}

		if (self::isAvailableBaseCity())
		{
			$result->timezone = true;
			$result->countryCode = true;
			$result->countryName = true;
			$result->regionCode = true;
			$result->cityName = true;
			$result->zipCode = true;
			$result->latitude = true;
			$result->longitude = true;
		}

		if (self::isAvailableBaseOrganization())
		{
			$result->organizationName = true;
		}

		if (self::isAvailableBaseIsp())
		{
			$result->ispName = true;
		}

		if (self::isAvailableBaseAsn())
		{
			$result->asn = true;
		}

		return $result;
	}
}