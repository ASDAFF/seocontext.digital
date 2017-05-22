<?

namespace Bitrix\Main\UI\Filter;

class Theme
{
	const DEFAULT_FILTER = "DEFAULT";
	const ROUNDED = "ROUNDED";

	public static function getList()
	{
		$reflection = new \ReflectionClass(__CLASS__);
		return $reflection->getConstants();
	}
}