<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
$this->setFrameMode(true);
?>

<div class="seocontext-detecting-location">
    <div>
        <span class="seocontext-detected-location"></span>
        <span class="seocontext-detected-location-question">
            <?= GetMessage('SEOCONTEXT_LOCATIONS_IS_YOUR_POSITION') ?>
        </span>
    </div>

    <a href="#" class="seocontext-detected-location-yes"><?= GetMessage('SEOCONTEXT_LOCATIONS_YES') ?></a>
    <a href="#" class="seocontext-detected-location-no seocontext-locations-show"
       data-mfp-src="div.seocontext-locations">
        <?= GetMessage('SEOCONTEXT_LOCATIONS_NO') ?>
    </a>
</div>

<a href="#" class="seocontext-selected-location seocontext-locations-show" data-mfp-src="div.seocontext-locations"
   data-choose-message="<?= GetMessage('SEOCONTEXT_LOCATIONS_CHOOSE_LOCATION') ?>">
    <?= GetMessage('SEOCONTEXT_LOCATIONS_CHOOSE_LOCATION') ?>
</a>

<div class="seocontext-detecting-progress">
    <div id="fountainG">
        <div id="fountainG_1" class="fountainG"></div>
        <div id="fountainG_2" class="fountainG"></div>
        <div id="fountainG_3" class="fountainG"></div>
        <div id="fountainG_4" class="fountainG"></div>
        <div id="fountainG_5" class="fountainG"></div>
        <div id="fountainG_6" class="fountainG"></div>
        <div id="fountainG_7" class="fountainG"></div>
        <div id="fountainG_8" class="fountainG"></div>
    </div>
</div>

<div class="seocontext-locations mfp-hide container">
    <? if ($arParams['RELOAD_PAGE'] == 'Y'): ?>
        <input type="hidden" id="seocontext_locations_reload" value="true">
    <? endif; ?>
    <div class="row">
        <div class="location col-md-12">
            <div class="header-text">
                <?= GetMessage('SEOCONTEXT_LOCATIONS_CHOOSE_LOCATION_POPUP') ?>
                <button title="<?= GetMessage('SEOCONTEXT_LOCATIONS_CLOSE_POPUP') ?>" type="button" class="mfp-close"><i class="fa fa-times"></i>
                </button>
            </div>
            <input type="text" name="location" value="" placeholder="<?= GetMessage('SEOCONTEXT_LOCATIONS_CHOOSE_LOCATION_POPUP') ?>">
            <span class="reset-location" title="<?= GetMessage('SEOCONTEXT_LOCATIONS_CLEAR_INPUT') ?>"><i
                    class="fa fa-times"></i></span>
            <ul class="selected-locations">
                <? foreach ($arResult['selected'] as $location): ?>
                    <li class="col-sm-6 col-md-4" data-code="<?= $location['CODE'] ?>">
                        <a href="#"><?= $location['NAME'] ?></a>
                    </li>
                <? endforeach; ?>
            </ul>
            <button class="save-location"><?= GetMessage('SEOCONTEXT_LOCATIONS_SAVE') ?></button>
        </div>
    </div>
</div>



