<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2017 Bitrix
 */
namespace Bitrix\Main\Service\GeoIp;

use Bitrix\Main\Event;
use Bitrix\Main\Loader;
use Bitrix\Main\EventResult;

/**
 * Class for working with geolocation information.
 * @package Bitrix\Main\Service\GeoIp
 */
final class Manager
{
	/** @var array | null  */
	private static $handlers = null;

	/** @var DataResult */
	private static $data = array();

	/** @var bool */
	private static $logErrors = false;

	/** @var bool */
	private static $useCookie = false;

	/**
	 * Constant for parameters who information not available.
	 */
	const INFO_NOT_AVAILABLE = null;

	const COOKIE_NAME = 'BX_MAIN_GEO_IP_DATA';
	const COOKIE_EXPIRED = 86400; //day

	/**
	 * Get the two letter country code.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getCountryCode($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('countryCode'))->countryCode;
	}

	/**
	 * Get the full country name.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getCountryName($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('countryName'))->countryName;
	}

	/**
	 * Get the full city name.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getCityName($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('cityName'))->cityName;
	}

	/**
	 * Get the Postal Code, FSA or Zip Code.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getCityPostCode($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('zipCode'))->zipCode;
	}

	/**
	 * Get geo-position attribute.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return array|null
	 */
	public static function getGeoPosition($ip = '', $lang = '')
	{
		$data = self::getData($ip, $lang, array('latitude', 'longitude'));

		if (
			$data->latitude != self::INFO_NOT_AVAILABLE
			&&  $data->longitude != self::INFO_NOT_AVAILABLE
		)
		{
			$result = Array(
				'latitude' => $data->latitude,
				'longitude' => $data->longitude,
			);
		}
		else
		{
			$result = self::INFO_NOT_AVAILABLE;
		}
		
		return $result;
	}

	/**
	 * Get the Latitude as signed double.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getGeoPositionLatitude($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('latitude'))->latitude;
	}
	
	/**
	 * Get the Longitude as signed double.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getGeoPositionLongitude($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('longitude'))->longitude;
	}
	
	/**
	 * Get the organization name.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getOrganizationName($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('organizationName'))->organizationName;
	}
	
	/**
	 * Get the Internet Service Provider (ISP) name.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getIspName($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('ispName'))->ispName;
	}

	/**
	 * Get the time zone for country and region code combo.
	 * @param string $ip Ip address.
	 * @param string $lang Language identifier.
	 * @return string|null
	 */
	public static function getTimezoneName($ip = '', $lang = '')
	{
		return self::getData($ip, $lang, array('timezone'))->timezone;
	}
	
	/**
	 * Get the all available information about geolocation.
	 *
	 * @param string  $ip Ip address.
	 * @param string  $lang Language identifier.
	 * @param array $required Required fields for result data.
	 * @return DataResult | null
	 */
	public static function getData($ip = '', $lang = '', array $required = array())
	{
		$result = null;

		if(strlen($ip) <= 0)
			$ip = self::getRealIp();

		if(self::$useCookie && !isset(self::$data[$ip]))
			self::$data[$ip] = self::getCookie($ip);

		if(isset(self::$data[$ip]) && is_array(self::$data[$ip]))
		{
			/** @var DataResult $geoData */
			foreach(self::$data[$ip] as $geoData)
				if(empty($required) || self::hasDataAllRequiredFields($required, $geoData))
					if(strlen($lang) <= 0 || $geoData->lang == $lang)
						$result = $geoData;
		}
		else
		{
			self::$data[$ip] = array();
		}

		if(!$result)
		{
			if(self::$handlers === null)
				self::initHandlers();

			/** @var Base $handler */
			foreach(self::$handlers as $handler)
			{
				if(!$handler->isInstalled())
					continue;

				if(!$handler->isActive())
					continue;

				if(strlen($lang) > 0)
					if(!in_array($lang, $handler->getSupportedLanguages()))
						continue;

				if(!empty($required) && !self::hasDataAllRequiredFields($required, $handler->getProvidingInfo()))
					continue;

				$data = $handler->getData($ip, $lang);

				if(!$data)
					continue;

				if(!$data->isSuccess() && self::$logErrors)
				{
					$eventLog = new \CEventLog;

					$eventLog->Add(array(
						"SEVERITY" => \CEventLog::SEVERITY_ERROR,
						"AUDIT_TYPE_ID" => 'MAIN_SERVICES_GEOIP_GETDATA_ERROR',
						"MODULE_ID" => "main",
						"ITEM_ID" => $ip.'('.$lang.')',
						"DESCRIPTION" => 'Handler id: '.$handler->getId()."\n<br>".implode("\n<br>",$data->getErrorMessages()),
					));

					continue;
				}

				$data->handlerClass = get_class($handler);
				$result = $data;
				self::$data[$ip][] = $data;

				if(self::$useCookie)
					self::setCookie($ip, self::$data[$ip]);

				break;
			}
		}

		return $result;
	}

	private static function getCookie($ip)
	{
		$result = false;
		$name = self::getCookieName($ip);

		if(isset($_COOKIE[$name]))
			$result = unserialize($_COOKIE[$name]);

		return $result;
	}

	private static function setCookie($ip, $locationData)
	{
		return setcookie(
			self::getCookieName($ip),
			serialize($locationData),
			time()+self::COOKIE_EXPIRED
		);
	}

	private static function getCookieName($ip)
	{
		return self::COOKIE_NAME.'_'.str_replace('.', '_',$ip);
	}

	private static function hasDataAllRequiredFields(array $required, DataResult $result)
	{
		if(empty($required))
			return true;

		$vars = get_object_vars($result);

		foreach($required as $field)
			if($vars[$field] === self::INFO_NOT_AVAILABLE)
				return false;

		return true;
	}

	private static function initHandlers()
	{
		if(self::$handlers !== null)
			return;

		self::$handlers = array();
		$handlersList = array();
		$buildInHandlers = array(
			'\Bitrix\Main\Service\GeoIp\MaxMind' => 'lib/service/geoip/maxmind.php',
			'\Bitrix\Main\Service\GeoIp\Extension' => 'lib/service/geoip/extension.php',
			'\Bitrix\Main\Service\GeoIp\SypexGeo' => 'lib/service/geoip/sypexgeo.php'
		);

		Loader::registerAutoLoadClasses('main', $buildInHandlers);

		$handlersFields = array();
		$res = HandlerTable::getList();

		while($row = $res->fetch())
			$handlersFields[$row['CLASS_NAME']] = $row;

		foreach($buildInHandlers as $class => $file)
		{
			if(self::isHandlerClassValid($class))
			{
				$fields = isset($handlersFields[$class]) ? $handlersFields[$class] : array();
				$handlersList[$class] = new $class($fields);
				$handlersSort[$class] = $handlersList[$class]->getSort();
			}
		}

		$event = new Event('main', 'onMainGeoIpHandlersBuildList');
		$event->send();
		$resultList = $event->getResults();

		if (is_array($resultList) && !empty($resultList))
		{
			$customClasses = array();

			foreach ($resultList as $eventResult)
			{
				/** @var  EventResult $eventResult*/
				if ($eventResult->getType() != EventResult::SUCCESS)
					continue;

				$params = $eventResult->getParameters();

				if(!empty($params) && is_array($params))
					$customClasses = array_merge($customClasses, $params);
			}

			if(!empty($customClasses))
			{
				Loader::registerAutoLoadClasses(null, $customClasses);

				foreach($customClasses as $class => $file)
				{
					if(self::isHandlerClassValid($class))
					{
						$fields = isset($handlersFields[$class]) ? $handlersFields[$class] : array();
						$handlersList[$class] = new $class($fields);
						$handlersSort[$class] = $handlersList[$class]->getSort();
					}
				}
			}
		}

		asort($handlersSort, SORT_NUMERIC);

		foreach($handlersSort as $class => $sort)
			self::$handlers[$class] = $handlersList[$class];
	}

	private static function isHandlerClassValid($className)
	{
		if(!class_exists($className))
			return false;

		if(!is_subclass_of($className, '\Bitrix\Main\Service\GeoIp\Base'))
			return false;

		return true;
	}

	/**
	 * @return string | false Ip address.
	 */
	public static function getRealIp()
	{
		$ip = false;

		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			$fCount = count($ips);

			for ($i = 0; $i < $fCount; $i++)
			{
				if (!preg_match("/^(10|172\\.16|192\\.168)\\./", $ips[$i]))
				{
					$ip = $ips[$i];
					break;
				}
			}
		}

		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}

	/**
	 * @return Base[] Handlers list.
	 */

	public static function getHandlers()
	{
		if(self::$handlers === null)
			self::initHandlers();

		return self::$handlers;
	}

	/**
	 * @param string $className. Class name of handler.
	 * @return Base | null Handler.
	 */
	public static function getHandlerByClassName($className)
	{
		if(self::$handlers === null)
			self::initHandlers();

		return isset(self::$handlers[$className]) ? self::$handlers[$className] : null;
	}

	/**
	 * Turn on / off error logging for debugging purposes.
	 * @param bool $isLog
	 */
	public static function setLogErrors($isLog)
	{
		self::$logErrors = $isLog;
	}

	/**
	 * Turn on / off storing geolocation info in cookie for performance purposes.
	 * @param bool $isUse
	 */
	public static function useCookieToStoreInfo($isUse)
	{
		self::$useCookie = $isUse;
	}
}