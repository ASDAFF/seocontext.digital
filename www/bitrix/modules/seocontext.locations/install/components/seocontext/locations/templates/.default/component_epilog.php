<?php
\Bitrix\Main\Page\Asset::getInstance()->addJs('/bitrix/js/seocontext.locations/require.min.js');
\Bitrix\Main\Page\Asset::getInstance()->addCss('/bitrix/js/seocontext.locations/popup/magnific-popup.css');
\Bitrix\Main\Page\Asset::getInstance()->addCss('/bitrix/js/seocontext.locations/devbridge/autocomplete.css');
\Bitrix\Main\Page\Asset::getInstance()->addString(
'<!--[if lt IE 9]>
    <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
<![endif]-->', true);