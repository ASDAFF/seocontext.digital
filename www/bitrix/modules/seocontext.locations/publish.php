<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
//require($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/classes/general/charset_converter.php');

const MODULE_NAME = "seocontext.locations";
$path = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . MODULE_NAME;
$targetPath = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/tmp/" . MODULE_NAME;

function getFiles($path)
{
    $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    $files = array();

    foreach ($rii as $file) {
        if ($file->isDir()) {
            continue;
        }
        $file->getFilename();
        $files[] = $file->getPathname();
    }
    return $files;
}
function GetStringCharset($str)
{
    $str = strtolower($str);
    if (preg_match("/[\xe0-\xff]/", $str))
        return 'cp1251';
    $str0 = CharsetConverter::ConvertCharset($str, 'utf8', 'cp1251');
    if (preg_match("/[\xe0-\xff]/", $str0, $regs))
        return 'utf8';
    return 'ascii';
}

\Bitrix\Main\IO\Directory::deleteDirectory($targetPath);
$targetPath .= '/.last_version';
CopyDirFiles($path, $targetPath, true, true);
\Bitrix\Main\IO\Directory::deleteDirectory($targetPath . '/.git');
\Bitrix\Main\IO\File::deleteFile($targetPath . '/publish.php');

$files = getFiles($targetPath);
foreach ($files as $file) {
    $info = $file;

    // skip components files
    //if (strpos($file, $targetPath . "/install/components") === 0) {
    //    echo $info . '<br/>';
    //    continue;
    // }

    if (substr($file, -4) == '.php') {
        $str = file_get_contents($file);
        $encode=mb_detect_encoding($str);
        if ($encode == 'UTF-8') {
            $str = CharsetConverter::ConvertCharset($str, 'utf8', 'cp1251');
            if (false === file_put_contents($file, $str))
                $info .= ':<span style="color:red;">convert error</span>';
            else
            {
                $pos = strpos($info, "/lang/");
                if($pos)
                    $info .= ':<span style="color:lawngreen;">converted</span>';
                else
                    $info .= ':<span style="color:#00a2df;">converted, but file isn\'t lang-file</span>';
            }
        }
         $info.='<span style="color:red;"> '.$encode.'</span>';
    }
    echo $info . '<br/>';
}

//zipping
$targetPath = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/tmp/" . MODULE_NAME;
exec("cd $targetPath; tar -zcvf last_version.tar.gz .last_version");