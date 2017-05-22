<?
namespace Bitrix\Main\Update;
use \Bitrix\Main\Web\Json;
use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Context;
use \Bitrix\Main\Localization\Loc;

/**
 * Class Stepper
 * @package Bitrix\Main\Update
 * This class can be used if only:
 * 1. you do not alter tables in DB. Agent will not be executed if module is not installed;
 * 2. you do not use Stepper for main module because of agent would not bind in updater.php on bitrix24.
 * Code to bind agent in updater:
 * if($updater->CanUpdateDatabase()) {
	$basePath = $updater->CanUpdateKernel() ? $updater->curModulePath.'/lib/update/' : BX_ROOT.'/modules/lists/lib/update/';
	if(include_once($_SERVER["DOCUMENT_ROOT"].$basePath."ecrmpropertyupdate.php"))
		\Bitrix\Lists\Update\EcrmPropertyUpdate::bind();
	}
 */
abstract class Stepper
{
	protected static $moduleId = "main";
	protected $deleteFile = false;
	private static $filesToUnlink = array();
	private static $countId = 0;
	/**
	 * Returns HTML to show updates.
	 * @param array|string $ids
	 * @param string $title
	 * @return string
	 */
	public static function getHtml($ids = array(), $title = "")
	{
		$return = array();
		$count = 0;
		$steps = 0;
		if (is_string($ids))
		{
			if (is_array($title))
			{
				$ids = array($ids => $title);
				$title = "";
			}
			else
				$ids = array($ids => null);
		}

		foreach($ids as $moduleId => $classesId)
		{
			if (is_string($classesId))
				$classesId = array($classesId);
			if (is_array($classesId))
			{
				foreach($classesId as $classId)
				{
					if (($option = Option::get("main.stepper.".$moduleId, $classId, "")) !== "")
					{
						$option = unserialize($option);
						if (is_array($option))
						{
							$return[] = array(
								"moduleId" => $moduleId,
								"class" => $classId,
								"steps" => $option["steps"],
								"count" => $option["count"]
							);
							$count += $option["count"];
							$steps += ($option["count"] > $option["steps"] ? $option["steps"] : $option["count"]);
						}
					}
				}
			}
			else if (is_null($classesId))
			{
				$options = Option::getForModule("main.stepper.".$moduleId);
				foreach($options as $classId => $option)
				{
					$option = unserialize($option);
					if (is_array($option))
					{
						$return[] = array(
							"moduleId" => $moduleId,
							"class" => $classId,
							"steps" => $option["steps"],
							"count" => $option["count"]
						);
						$count += $option["count"];
						$steps += ($option["count"] > $option["steps"] ? $option["steps"] : $option["count"]);
					}
				}
			}
		}
		$result = '';
		if (!empty($return) && $count > 0)
		{
			$id = ++self::$countId;
			\CJSCore::Init(array('update_stepper'));
			$title = empty($title) ? Loc::getMessage("STEPPER_TITLE") : $title;
			$progress = intval( $steps * 100 / $count);
			$result .= <<<HTML
<div class="main-stepper main-stepper-show" id="{$id}-container">
	<div class="main-stepper-info" id="{$id}-title">{$title}</div>
	<div class="main-stepper-inner">
		<div class="main-stepper-bar">
			<div class="main-stepper-bar-line" id="{$id}-bar" style="width:{$progress}%;"></div>
		</div>
		<div class="main-stepper-steps"><span id="{$id}-steps">{$steps}</span> / <span id="{$id}-count">{$count}</span></div>
	</div>
</div>
HTML;
			$return = \CUtil::PhpToJSObject($return);
			$result = <<<HTML
<div class="main-stepper-block">{$result}
<script>BX.ready(function(){ if (BX && BX["UpdateStepperRegister"]) { BX.UpdateStepperRegister({$id}, {$return}); }});</script>
</div>
HTML;
		}
		return $result;
	}
	/**
	 * Execute an agent
	 * @return string
	 */
	public static function execAgent()
	{
		$updater = self::createInstance();
		$className = get_class($updater);

		$result = array();

		$option = Option::get("main.stepper.".$updater->getModuleId(), $className, "");
		if ($option !== "" )
			$option = unserialize($option);
		$option = is_array($option) ? $option : array();
		if ($updater->execute($result) === true)
		{
			$option["steps"] = intval($result["steps"]);
			$option["count"] = intval($result["count"]);

			Option::set("main.stepper.".$updater->getModuleId(), $className, serialize($option));
			return $className . '::execAgent();';
		}
		if ($updater->deleteFile === true && \Bitrix\Main\ModuleManager::isModuleInstalled("bitrix24") !== true)
		{
			$res = new \ReflectionClass($updater);
			self::$filesToUnlink[] = $res->getFileName();
		}
		Option::delete("main.stepper.".$updater->getModuleId(), array("name" => $className));

		return '';
	}

	public function __destruct()
	{
		if (!empty(self::$filesToUnlink))
		{
			while ($file = array_pop(self::$filesToUnlink))
			{
				$file = \CBXVirtualIo::GetInstance()->GetFile($file);

				$langDir = $fileName = "";
				$filePath = $file->GetPathWithName();
				while(($slashPos = strrpos($filePath, "/")) !== false)
				{
					$filePath = substr($filePath, 0, $slashPos);
					$langPath = $filePath."/lang";
					if(is_dir($langPath))
					{
						$langDir = $langPath;
						$fileName = substr($file->GetPathWithName(), $slashPos);
						break;
					}
				}
				if ($langDir <> "" && ($langDir = \CBXVirtualIo::GetInstance()->GetDirectory($langDir)) &&
					$langDir->IsExists())
				{
					$languages = $langDir->GetChildren();
					foreach ($languages as $language)
					{
						if ($language->IsDirectory() &&
							($f = \CBXVirtualIo::GetInstance()->GetFile($language->GetPathWithName().$fileName)) &&
							$f->IsExists())
						{
							$f->unlink();
						}
					}
					unset($f);
				}
				$file->unlink();
			}
			unset($file);
		}
	}
	/**
	 * Executes some action.
	 * @param array $result
	 * @return boolean
	 */
	abstract function execute(array &$result);
	/**
	 * Just fabric method.
	 * @return Stepper
	 */
	public static function createInstance()
	{
		return new static;
	}
	/**
	 * Wrap-function to get moduleId.
	 * @return string
	 */
	public static function getModuleId()
	{
		return static::$moduleId;
	}
	/**
	 * Adds agent for current class.
	 * @return void
	 */
	public static function bind()
	{
		$c = get_called_class();
		\CAgent::AddAgent(
			$c.'::execAgent();',
				$c::getModuleId(),
				"Y",
				1,
				"",
				"Y",
				\ConvertTimeStamp(time()+\CTimeZone::GetOffset() + 60, "FULL"),
				100,
				false,
				false
			);
	}
	/**
	 * Just method to check request.
	 * @return void
	 */
	public static function checkRequest()
	{
		$result = array();
		$data = Context::getCurrent()->getRequest()->getPost("stepper");
		if (is_array($data))
		{
			foreach ($data as $stepper)
			{
				if (($option = Option::get("main.stepper.".$stepper["moduleId"], $stepper["class"], "")) !== "" &&
					($res = unserialize($option)) && is_array($res))
				{
					$r = array(
						"moduleId" => $stepper["moduleId"],
						"class" => $stepper["class"],
						"steps" => $res["steps"],
						"count" => $res["count"]
					);
					$result[] = $r;
				}
			}
		}
		self::sendJson($result);
	}
	/**
	 * Sends json.
	 * @param $result
	 * @return void
	 */
	private static function sendJson($result)
	{
		global $APPLICATION;
		$APPLICATION->RestartBuffer();
		while(ob_end_clean());

		header('Content-Type:application/json; charset=UTF-8');

		echo Json::encode($result);
		\CMain::finalActions();
		die;
	}
}
?>