<?php
namespace Seocontext\Locations {

    use Bitrix\Main\Localization\Loc;
    use Bitrix\Sale\Location\GroupLocationTable;
    use Bitrix\Main\Loader;
    use Bitrix\Sale\Location\LocationTable;
    use Bitrix\Sale\Location\TypeTable;


    Loader::includeModule('sale');
    Loc::loadMessages(__FILE__);

    class Manager
    {

        public function __construct()
        {

        }

        public function getLocationUsingIp($ip)
        {

        }

        public static function getCurrentLocationCode()
        {
            // 1. Check cookie
            // 2. Detect using IP

            //AddMessage2Log($_COOKIE);

            return $_COOKIE['SEOCONTEXT_LOCATION_COOKIE'];
        }

        public static function getLocationTypes()
        {
            //TODO: put $items in cache
            $res = TypeTable::getList(array(
                'select' => array('*', 'TYPE_NAME' => 'NAME.NAME'),
                'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID)
            ));
            $items = array();
            while ($item = $res->fetch()) {
                $items[$item['ID']] = $item;
            }

            return $items;
        }

        public static function addFavouriteLocation($code)
        {
            // add fav location to group table
            $res = \Bitrix\Sale\Location\LocationTable::getList(array(
                'select' => array('ID'),
                'filter' => array('=CODE' => $code)
            ));
            $res = $res->fetchAll();
            $id = $res[0]['ID'];

            $groupId = self::getGroupId();
            \Bitrix\Sale\Location\GroupLocationTable::add(array(
                'LOCATION_ID' => $id,
                'LOCATION_GROUP_ID' => $groupId
            ));

            $name = \Bitrix\Sale\Location\Name\LocationTable::getList(array(
                'select' => array('NAME'),
                'filter' => array('=LOCATION_ID' => $id, 'LANGUAGE_ID' => LANGUAGE_ID)
            ));
            $name = $name->fetchAll();
            return array('name' => $name[0]['NAME'], 'code' => $code);
        }

        public static function deleteFavouriteLocation($code)
        {
            // delete fav location from group table
            $res = \Bitrix\Sale\Location\LocationTable::getList(array(
                'select' => array('ID'),
                'filter' => array('=CODE' => $code)
            ));
            $res = $res->fetchAll();
            $id = $res[0]['ID'];

            global $DB;
            $query = "delete from b_sale_location2location_group where LOCATION_ID=" . $id;
            $DB->Query($query);
        }

        public static function getSelectedLocations()
        {
            //\Bitrix\Sale\Location\LocationTable::getByCode();

            //todo: fix it. In admin (.parameters) SITE_ID is equal to LANGUAGE_ID, so we have to handle this case
            $siteId = SITE_ID;
            //        AddMessage2Log("SITE_ID=".SITE_ID);
            //        AddMessage2Log("LANG_ID=".LANGUAGE_ID);
            //        AddMessage2Log("ADMIN_SECTION=".ADMIN_SECTION);
            if (defined("ADMIN_SECTION") && ADMIN_SECTION == 1)
                $siteId = "s1";
            //===================================

            $res = \Bitrix\Sale\Location\DefaultSiteTable::getList(array(
                'select' => array('CODE' => 'LOCATION_CODE',
                    'NAME' => 'LOCATION.NAME.NAME',
                    'TYPE' => 'LOCATION.TYPE.CODE'),
                'filter' => array('=SITE_ID' => $siteId, '=LOCATION.NAME.LANGUAGE_ID' => LANGUAGE_ID),
                'order' => array('SORT' => 'ASC')
            ));
            return $res->fetchAll();

        }

        public static function getFavouriteLocations()
        {
            $groupId = self::getGroupId();

            $dataFromFavGroup = \Bitrix\Sale\Location\GroupLocationTable::getList(array(
                'select' => array(
                    'CODE' => 'LOCATION.CODE',
                    'NAME' => 'LOCATION.NAME.NAME',
                    'TYPE' => 'LOCATION.TYPE.CODE'),
                'filter' => array('=LOCATION.NAME.LANGUAGE_ID' => LANGUAGE_ID, 'LOCATION_GROUP_ID' => $groupId)
            ));

            return $dataFromFavGroup->fetchAll();
        }

        public static function getGroupId()
        {
            $res = \Bitrix\Sale\Location\GroupTable::getList(array(
                'select' => array('ID'),
                'filter' => array('=CODE' => "Favourite_locations")
            ));
            $res = $res->fetchAll();
//        return '9999';
            return $res[0]["ID"];
        }

        /**
         * Returns location name with all parent locations names
         * @param string $locationCode
         * @return array
         */
        public static function getFullName($locationCode)
        {
            $types = static::getLocationTypes();
            $path = LocationTable::getPathToNodeByCode($locationCode)->fetchAll();

            // Obtain location names for location codes in path
            $locationCodes = array();
            foreach ($path as $part) {
                $locationCodes[] = $part['CODE'];
            }

            $res = LocationTable::getList(array(
                'select' => array('CODE', 'LOCATION_NAME' => 'NAME.NAME'),
                'filter' => array('=NAME.LANGUAGE_ID' => LANGUAGE_ID,
                    '=CODE' => $locationCodes)
            ));

            $locationNames = array();
            while ($item = $res->fetch()) {
                $locationNames[$item['CODE']] = $item['LOCATION_NAME'];
            }
            //=================

            $locationParts = array();
            foreach ($path as $part) {
                $type = $types[$part['TYPE_ID']];
                $locationParts[$type['CODE']] = array(
                    'TYPE_NAME' => $type['TYPE_NAME'],
                    'CODE' => $part['CODE'],
                    'NAME' => $locationNames[$part['CODE']],
                    'ID' => $part['ID'],
                    'PARENT_ID' => $part['PARENT_ID'],
                );
            }

            return $locationParts;
        }

        public static function findLocations($searchPhrase)
        {
            \CBitrixComponent::includeComponentClass("bitrix:sale.location.selector.search");
            $parameters = array(
                'select' =>
                    array(
                        1 => 'CODE',
                        2 => 'TYPE_ID',
                        'VALUE' => 'ID',
                        'DISPLAY' => 'NAME.NAME',
                    ),
                'additionals' =>
                    array(
                        1 => 'PATH',
                    ),
                'filter' =>
                    array(
                        '=PHRASE' => $searchPhrase,
                        '=NAME.LANGUAGE_ID' => 'ru' //LANGUAGE_ID,
                    ),
                'version' => '2',
                'PAGE_SIZE' => '10',
                'PAGE' => '0',
            );
            //LocationTable::getList($parameters);

            $data = \CBitrixLocationSelectorSearchComponent::processSearchRequestV2($parameters);
            return $data;
        }

        /**
         * Formats data received from findLocations function
         * @param $data
         * @return array
         */
        public static function formatFoundLocations($data)
        {
            $items = $data['ITEMS'];
            $pathItems = $data['ETC']['PATH_ITEMS'];

            $formattedItems = array();
            foreach ($items as $key => $item) {
                $formattedItems[$key] = array(
                    'CODE' => $item['CODE'],
                    'NAME' => $item['DISPLAY'],
                    'FULLNAME' => self::composeFullLocationName($item, $pathItems),
                );
            }

            return $formattedItems;
        }

        /**
         * @param $location
         * @param $pathItems
         * @return string
         */
        private static function composeFullLocationName($location, $pathItems)
        {
            $chunks = array($location['DISPLAY']);
            $path = $location['PATH'];
            if (is_array($path)) {
                foreach ($path as $locationId) {
                    $chunks [] = $pathItems[$locationId]['DISPLAY'];
                }
            }
            return implode(', ', $chunks);
        }


        public static function getLocation()
        {
            if (isset($_POST["Locality"]) || isset($_POST["SubAdministrativeArea"]) || isset($_POST["AdministrativeArea"])) {
                $locality = $_POST["Locality"];
                $subAdministrativeArea = $_POST["SubAdministrativeArea"];
                $administrativeArea = $_POST["AdministrativeArea"];

                //    echo '\''.$subAdministrativeArea.'\'';
                //    echo '\''.$locality.'\'';
                //    echo '\''.$administrativeArea.'\'';

                $upLocality = strtoupper($locality);
                $upSubAdministrativeArea = strtoupper($subAdministrativeArea);
                $upAdministrativeArea = strtoupper($administrativeArea);

                $LOCATION_CODE = self::getCode($upLocality, $upSubAdministrativeArea, $upAdministrativeArea);

                //    echo $LOCATION_CODE;
                //    $current_location["LOC_NAME"] = $administrativeArea . ', ' . $subAdministrativeArea . ', ' . $locality;

                $LOCATION_NAME = self::getNameByCode($LOCATION_CODE);

                $content = array($LOCATION_CODE, $LOCATION_NAME);
                echo json_encode($content);
            }

        }

        public static function getId($upSearchName)
        {
            $res = \Bitrix\Sale\Location\Name\LocationTable::getList(array(
                'select' => array('LOCATION_ID'),
                'filter' => array('=NAME_UPPER' => $upSearchName, 'LANGUAGE_ID' => LANGUAGE_ID)
            ));
            $res = $res->fetchAll();
            //    echo '['.$upSearchName.'] = ('.$res[0]["LOCATION_ID"].')';
            return $res[0]["LOCATION_ID"];
        }

        public static function getIdFromLocName($upSearchName)
        {
            $id = self::getId($upSearchName);

            if ($id == null) {
                $clip_words = array(
                    Loc::getMessage("SEOCONTEXT_LOCATIONS_CLIPPING_WORD_1"),
                    Loc::getMessage("SEOCONTEXT_LOCATIONS_CLIPPING_WORD_2"),
                    Loc::getMessage("SEOCONTEXT_LOCATIONS_CLIPPING_WORD_3"),
                    Loc::getMessage("SEOCONTEXT_LOCATIONS_CLIPPING_WORD_4")
                );
                $upSearchName = str_replace($clip_words, "", $upSearchName);
                $id = self::getId($upSearchName);
            }

            return $id;
        }

        public static function getLocationId($locality, $subAdministrativeArea, $administrativeArea)
        {
            $id = self::getIdFromLocName($locality);

            if ($id === null) {
                $id = self::getIdFromLocName($subAdministrativeArea);
            }

            if ($id === null) {
                $id = self::getIdFromLocName($administrativeArea);
            }
            //    echo $result;
            return $id;
        }

        public static function getCode($locality, $subAdministrativeArea, $administrativeArea)
        {
            $id = self::getLocationId($locality, $subAdministrativeArea, $administrativeArea);

            $res = \Bitrix\Sale\Location\LocationTable::getList(array(
                'select' => array('CODE'),
                'filter' => array('=ID' => $id)
            ));
            $res = $res->fetchAll();
            //    echo $res[0]["CODE"];
            return $res[0]["CODE"];
        }

        public static function getNameByCode($code)
        {
            $res_id = \Bitrix\Sale\Location\LocationTable::getList(array(
                'select' => array('ID'),
                'filter' => array('=CODE' => $code)
            ));
            $res_id = $res_id->fetchAll();

            $res_name = \Bitrix\Sale\Location\Name\LocationTable::getList(array(
                'select' => array('NAME'),
                'filter' => array('=LOCATION_ID' => $res_id[0]['ID'], 'LANGUAGE_ID' => LANGUAGE_ID)
            ));
            $res_name = $res_name->fetchAll();

            return $res_name[0]['NAME'];
        }

    }
}