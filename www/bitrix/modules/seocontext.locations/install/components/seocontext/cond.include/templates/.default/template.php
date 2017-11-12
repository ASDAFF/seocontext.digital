<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 */
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
/** $arResult['location-code'] */
/** $arResult['content'] */
$this->setFrameMode(true);

if ($arParams['ALL_ON_PAGE']=='Y'): // js case (place all content blocks on page)
    foreach($arResult['content'] as $code=>$content):?>
    <div data-cond-code="<?=$code?>" data-cond-id="<?=$arResult['id']?>" style="display: none">
        <?=$content?>
    </div>
    <? endforeach;
else: // ajax case
?>
    <div data-cond-id="<?=$arResult['id']?>" style="display: none">
    </div>
<? endif; ?>

<script type="text/javascript">
    requirejs(['/bitrix/js/seocontext.locations/main.js'], function(){
        requirejs(['jquery', 'manager'], function ($, manager) {
            $(function(){
                manager.init({
                    id: '<?=$arResult['id']?>',
                    includeId:'<?=$arParams['INCLUDE_ID']?>',
                    type: '<?=$arParams['ALL_ON_PAGE']=='Y' ? 'js' : 'ajax'?>'
                });
            });
        });
    });
</script>
