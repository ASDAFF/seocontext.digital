<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
// get element
$arItemFilter = CDigital::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);

global $APPLICATION;
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animate.min.css');

if($arParams['CACHE_GROUPS'] == 'Y')
{
	$arItemFilter['CHECK_PERMISSIONS'] = 'Y';
	$arItemFilter['GROUPS'] = $GLOBALS["USER"]->GetGroups();
}

$arElement = CCache::CIblockElement_GetList(array('CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'N')), $arItemFilter, false, false, array('ID', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'ACTIVE_FROM', 'DETAIL_PICTURE', 'DETAIL_PAGE_URL', 'LIST_PAGE_URL', 'PROPERTY_LINK_PROJECTS', 'PROPERTY_LINK_GOODS', 'PROPERTY_LINK_REVIEWS', 'PROPERTY_LINK_STAFF', 'PROPERTY_LINK_SERVICES', 'PROPERTY_FORM_QUESTION'));

if($arParams["SHOW_NEXT_ELEMENT"] == "Y")
{
	$arSort=array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"]);
	$arElementNext = array();

	$arAllElements = CCache::CIblockElement_GetList(array($arParams["SORT_BY1"] => $arParams["SORT_ORDER1"], $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"], 'CACHE' => array('TAG' => CCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'MULTI' => 'Y')), array("IBLOCK_ID" => $arParams["IBLOCK_ID"], "ACTIVE" => "Y", "SECTION_ID" => $arElement["IBLOCK_SECTION_ID"]/*, ">ID" => $arElement["ID"]*/ ), false, false, array('ID', 'DETAIL_PAGE_URL', 'IBLOCK_ID', 'SORT'));
	if($arAllElements)
	{
		$url_page = $APPLICATION->GetCurPage();
		$key_item = 0;
		foreach($arAllElements as $key => $arItemElement)
		{
			if($arItemElement["DETAIL_PAGE_URL"] == $url_page)
			{
				$key_item = $key;
				break;
			}
		}
		if(strlen($key_item))
		{
			$arElementNext = $arAllElements[$key_item+1];
		}
		if($arElementNext)
		{
			if($arElementNext["DETAIL_PAGE_URL"] && is_array($arElementNext["DETAIL_PAGE_URL"])){
				$arElementNext["DETAIL_PAGE_URL"]=current($arElementNext["DETAIL_PAGE_URL"]);
			}
		}
	}
}
?>
<?if($arParams['USE_FILTER'] == 'Y'){
	if(isset($arResult['VARIABLES']['YEAR']))
	{
		if($arElement['ACTIVE_FROM'])
		{
			if($arDateTime = ParseDateTime($arElement['ACTIVE_FROM'], FORMAT_DATETIME))
    		{
		        if($arDateTime['YYYY'] != (int)$arResult['VARIABLES']['YEAR'])
		        {
		        	echo GetMessage('ELEMENT_NOTFOUND');
					CHTTP::SetStatus(404);
					return;
		        }
    		}
		}
	}
}?>
<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CDigital::goto404Page();?>
<?else:?>
	<?// rss
	if($arParams['USE_RSS'] !== 'N'){
		CDigital::ShowRSSIcon($arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']);
	}?>
	<?CDigital::AddMeta(
		array(
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
		)
	);?>
	<div class="detail <?=($templateName = $component->{'__template'}->{'__name'})?> fixed_wrapper">
		<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
			<div class="share top <?=($arParams['USE_RSS'] !== 'N' ? 'rss-block' : '');?>">
				<div class="shares-block">
					<script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" charset="utf-8"></script>
					<div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
				</div>
			</div>
			<?
				/*<style type="text/css">h1{padding-right:300px;}</style>*/
			?>
				
			<script type="text/javascript">
				$('h1').addClass('shares');
				$(document).ready(function(){

					if($('a.rss').length)
						$('a.rss').after($('.share.top'));
					else
						$('h1').before($('.share.top'));
				})
			</script>
			<?if($arParams['USE_RSS'] !== 'N'):?>
				<style type="text/css">body h1{padding-right:360px;}</style>
			<?endif;?>
		<?endif;?>

		<?//element?>
		<?$sViewElementTemplate = ($arParams["ELEMENT_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["NEWS_PAGE_DETAIL"]["VALUE"] : $arParams["ELEMENT_TYPE_VIEW"]);?>
		<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>
		
	</div>
	<?
	if(is_array($arElement['IBLOCK_SECTION_ID']) && count($arElement['IBLOCK_SECTION_ID']) > 1){
		CDigital::CheckAdditionalChainInMultiLevel($arResult, $arParams, $arElement);
	}
	?>

	<?if(in_array('FORM_QUESTION', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_FORM_QUESTION_VALUE']):?>
		<div class="row">
			<div class="col-md-9">
	<?endif;?>
	
	<div style="clear:both"></div>
	<hr class="bottoms" />

	<?if($arParams["SHOW_NEXT_ELEMENT"] == "Y"):?>
		<div class="row links-block">
			<div class="col-md-12 links">
				<a class="back-url url-block" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>"><i class="fa fa-angle-left"></i><span><?=($arParams["T_PREV_LINK"] ? $arParams["T_PREV_LINK"] : GetMessage('BACK_LINK'));?></span></a>
				<?if($arElementNext):?>
					<a class="next-url url-block" href="<?=$arElementNext['DETAIL_PAGE_URL']?>"><i class="fa fa-angle-right"></i><span><?=($arParams["T_NEXT_LINK"] ? $arParams["T_NEXT_LINK"] : GetMessage('NEXT_LINK'));?></span></a>
				<?endif;?>
			</div>
		</div>
	<?else:?>
		<div class="row">
			<div class="col-md-6 share">
				<?if($arParams["USE_SHARE"] == "Y" && $arElement):?>
					<div class="shares-block">
						<span class="text"><?=GetMessage('SHARE_TEXT')?></span>
						<script type="text/javascript" src="//yastatic.net/share2/share.js" async="async" charset="utf-8"></script>
						<div class="ya-share2" data-services="vkontakte,facebook,twitter,viber,whatsapp,odnoklassniki,moimir"></div>
					</div>
				<?endif;?>
			</div>
			<div class="col-md-6">
				<a class="back-url url-block" href="<?=$arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']?>"><i class="fa fa-angle-left"></i><span><?=($arParams["T_PREV_LINK"] ? $arParams["T_PREV_LINK"] : GetMessage('BACK_LINK'));?></span></a>
			</div>
		</div>
	<?endif;?>
	<?if(in_array('FORM_QUESTION', $arParams['DETAIL_PROPERTY_CODE']) && $arElement['PROPERTY_FORM_QUESTION_VALUE']):?>
		</div></div>
	<?endif;?>
<?endif;?>