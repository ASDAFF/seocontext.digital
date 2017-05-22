<div class="mobilemenu-v1 scroller">
	<div class="wrap">
		<?$APPLICATION->IncludeComponent(
			"bitrix:menu",
			"top_mobile",
			Array(
				"COMPONENT_TEMPLATE" => "top_mobile",
				"MENU_CACHE_TIME" => "3600000",
				"MENU_CACHE_TYPE" => "A",
				"MENU_CACHE_USE_GROUPS" => "N",
				"MENU_CACHE_GET_VARS" => array(
				),
				"DELAY" => "N",
				"MAX_LEVEL" => "4",
				"ALLOW_MULTI_SELECT" => "Y",
				"ROOT_MENU_TYPE" => "top",
				"CHILD_MENU_TYPE" => "left",
				"USE_EXT" => "Y"
			)
		);?>
		<?
		// show cabinet item
		CDigital::ShowMobileMenuCabinet();

		// show basket item
		CDigital::ShowMobileMenuBasket();

		// use module options for change contacts
		CDigital::ShowMobileMenuContacts();
		?>
		<?$APPLICATION->IncludeComponent(
			"aspro:social.info.digital",
			"mobile",
			array(
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "3600000",
				"CACHE_GROUPS" => "N",
				"COMPONENT_TEMPLATE" => ".default"
			),
			false
		);?>
	</div>
</div>