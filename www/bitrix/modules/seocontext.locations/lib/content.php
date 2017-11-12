<?php
namespace Seocontext\Locations {
    use Bitrix\Main\Config\Option;
    class Content
    {
        const DEFAULT_CONTENT_FILENAME = 'default';
        const CONTENT_FILE_EXT = '.html';

        /**
         * Returns content associated with specified $code and $includeId
         * @param $code
         * @param $includeId
         * @param bool|true $useDefault Specified whether return default content if content for $code isn't set
         * @return bool|string
         */
        public static function load($code, $includeId, $useDefault = true)
        {
            $file = self::getContentFile($code, $includeId);
            $content = '';
            if (file_exists($file))
                $content = file_get_contents($file);
            else if ($useDefault) {
                $defaultFile = self::getContentFile(self::DEFAULT_CONTENT_FILENAME, $includeId);
                if (file_exists($defaultFile))
                    $content = file_get_contents($defaultFile);
            }
            return $content;
        }

        public static function loadAll($includeId)
        {
            $content = array();
            $files = glob(self::getContentDir($includeId) . '/*' . self::CONTENT_FILE_EXT);
            foreach ($files as $file) {
                $code = basename($file, self::CONTENT_FILE_EXT);
                $content[$code] = file_get_contents($file);
            }
            return $content;
        }


        public static function save($code, $content, $includeId)
        {
            $file = self::getContentFile($code, $includeId);

            self::file_force_contents($file, $content);
        }


        public static function saveDefault($content, $includeId)
        {
            $file = self::getContentFile('default', $includeId);
            self::file_force_contents($file, $content);
        }

        public static function getAllIncludeIds() {
            $basePath = self::getBasePath();
            $dirs = glob($basePath.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR);
            $includeIds = array();
            foreach ($dirs as $dir) {
                $includeIds[]=basename($dir);
            }

            return $includeIds;
        }

        static function getBasePath() {
            return $_SERVER['DOCUMENT_ROOT'] . rtrim(trim(Option::get('seocontext.locations', 'base_path')), DIRECTORY_SEPARATOR);
        }

        static function getContentDir($includeId)
        {
            $basePath = self::getBasePath();
            if (strpos($includeId, '..') !== false) {
                $error = 'includeId is not allowed to contain chars ..';
                throw new \Bitrix\Main\ArgumentException($error);
                die($error);
            }
            if ($includeId == "") {
                $error = 'includeId is not allowed to be empty';
                throw new \Bitrix\Main\ArgumentException($error);
                die($error);
            }
            $includeId = rtrim($includeId, DIRECTORY_SEPARATOR);
            return $basePath . DIRECTORY_SEPARATOR . $includeId;
        }

        static function getContentFile($code, $includeId)
        {
            $contentDir = self::getContentDir($includeId);
            return $contentDir . '/' . $code . self::CONTENT_FILE_EXT;
        }

        public static function file_force_contents($fullpath, $contents)
        {
            $parts = explode(DIRECTORY_SEPARATOR, $fullpath);
            $file = array_pop($parts);
            $fullpath = '';
            foreach ($parts as $part)
                if (!is_dir($fullpath .= "/$part")) mkdir($fullpath);
            file_put_contents("$fullpath/$file", $contents);
        }

    }
}