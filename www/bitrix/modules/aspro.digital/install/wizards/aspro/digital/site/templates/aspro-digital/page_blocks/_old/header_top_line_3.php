<div class="top-block">
	<div class="maxwidth-theme">			
		<div class="top-block-item muted pull-left text-line hidden-sm hidden-xs">										
			<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-address.php", array(), array(
					"MODE" => "html",
					"NAME" => "Address",
					"TEMPLATE" => "include_area",
				)
			);?>					
		</div>
		
		<div class="top-block-item pull-right hidden-xs">
			<button class="top-btn hover inline-search-show">
				<i class="svg svg-search" aria-hidden="true"></i>
			</button>
		</div>

		<div class="top-block-item pull-right">
			<?=CDigital::showBasketLink('top-btn hover');?>
		</div>			
		
		<div class="top-block-item pull-right hidden-xs">
			<button class="top-btn callback-block hover animate-load" data-event="jqm" data-param-id="<?=CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_callback"][0]?>" data-name="callback">
				<?=GetMessage("S_CALLBACK")?>
			</button>
		</div>

		<div class="top-block-item muted pull-right inner-padding">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-phone.php", array(), array(
					"MODE" => "html",
					"NAME" => "Phone",
					"TEMPLATE" => "include_area",
				)
			);?>
		</div>
		
		<div class="clearfix"></div>
	</div>
</div>