<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="top-block top-block-v1 row">
	<div class="maxwidth-theme">
		<div class="top-block-item col-md-5">
			
		</div>
		<div class="top-block-item show-fixed top-ctrl col-md-1 pull-right text-right">
			<button class="top-btn inline-search-show twosmallfont">
				<i class="svg svg-search" aria-hidden="true"></i>
				<span class="dark-color"><?=GetMessage('SEARCH_TITLE')?></span>
			</button>
		</div>
		<div class="top-block-item col-md-3 pull-right">
			<div class="address twosmallfont inline-block">
				<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-address.php", array(), array(
						"MODE" => "html",
						"NAME" => "Address",
						"TEMPLATE" => "include_area",
					)
				);?>
			</div>
		</div>
		<div class="top-block-item col-md-3 pull-right">
			<div class="phone-block">
				<div class="inline-block">
					<div class="phone inline-block with-dropdown">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-phone.php", array(), array(
								"MODE" => "html",
								"NAME" => "Phone",
								"TEMPLATE" => "include_area",
							)
						);?>
					</div>
				</div>
				<div class="inline-block">
					<span class="callback-block animate-load twosmallfont colored" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0]?>" data-name="callback"><?=GetMessage("S_CALLBACK")?></span>
				</div>
			</div>
		</div>
	</div>
</div>