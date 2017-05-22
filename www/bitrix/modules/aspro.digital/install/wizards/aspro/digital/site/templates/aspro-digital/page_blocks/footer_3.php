<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<footer id="footer" class="compact">	
	<div class="container">
		<div class="row bottom-middle">
			<div class="maxwidth-theme">
				<div class="col-md-3 col-sm-3 copy-block">
					<div class="copy blocks">
						<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/copy.php", Array(), Array(
								"MODE" => "php",
								"NAME" => "Copyright",
							)
						);?>
					</div>
					<div class="print-block blocks"><?=CDigital::ShowPrintLink();?></div>
					<div id="bx-composite-banner" class="blocks"></div>
				</div>
				<div class="col-md-6 col-sm-6">
					<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/contacts-title.php", array(), array(
							"MODE" => "html",
							"NAME" => "Title",
							"TEMPLATE" => "include_area",
						)
					);?>
					<div class="row info">
						<div class="col-md-6">
							<div class="phone blocks">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-phone.php", array(), array(
										"MODE" => "html",
										"NAME" => "Phone",
										"TEMPLATE" => "include_area",
									)
								);?>
							</div>
							<div class="email blocks">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/footer/site-email.php", array(), array(
										"MODE" => "html",
										"NAME" => "E-mail",
										"TEMPLATE" => "include_area",
									)
								);?>
							</div>	
						</div>
						<div class="col-md-6">
							<div class="address blocks">
								<?$APPLICATION->IncludeFile(SITE_DIR."include/header/site-address.php", array(), array(
										"MODE" => "html",
										"NAME" => "Address",
										"TEMPLATE" => "include_area",
									)
								);?>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-3 col-sm-3">
					<div class="social-block">
						<?$APPLICATION->IncludeComponent(
							"aspro:social.info.digital",
							".default",
							array(
								"CACHE_TYPE" => "A",
								"CACHE_TIME" => "3600000",
								"CACHE_GROUPS" => "N",
								"COMPONENT_TEMPLATE" => ".default",
								"SOCIAL_TITLE" => GetMessage("SOCIAL_TITLE")
							),
							false
						);?>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>