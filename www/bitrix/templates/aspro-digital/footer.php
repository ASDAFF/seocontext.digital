					<?if(!$isIndex):?>
						<?CDigital::checkRestartBuffer();?>
					<?endif;?>
					<?IncludeTemplateLangFile(__FILE__);?>
					<?global $arTheme, $isIndex, $is404;?>
					<?if(!$isIndex):?>
							<?if($is404):?>
								</div>
							<?else:?>
									<?if(!$isMenu):?>
										</div><?// class=col-md-12 col-sm-12 col-xs-12 content-md?>
									<?elseif($isMenu && $arTheme["SIDE_MENU"]["VALUE"] == "LEFT" && !$isBlog):?>
										<?CDigital::get_banners_position('CONTENT_BOTTOM');?>
										</div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
									<?elseif($isMenu && ($arTheme["SIDE_MENU"]["VALUE"] == "RIGHT" || $isBlog)):?>
										<?CDigital::get_banners_position('CONTENT_BOTTOM');?>
										</div><?// class=col-md-9 col-sm-9 col-xs-8 content-md?>
										<div class="col-md-3 col-sm-3 hidden-xs hidden-sm right-menu-md">
											<?$APPLICATION->IncludeComponent("bitrix:menu", "left", array(
												"ROOT_MENU_TYPE" => "left",
												"MENU_CACHE_TYPE" => "A",
												"MENU_CACHE_TIME" => "3600",
												"MENU_CACHE_USE_GROUPS" => "Y",
												"MENU_CACHE_GET_VARS" => array(
												),
												"MAX_LEVEL" => "4",
												"CHILD_MENU_TYPE" => "subleft",
												"USE_EXT" => "Y",
												"DELAY" => "N",
												"ALLOW_MULTI_SELECT" => "Y"
												),
												false
											);?>
											<div class="sidearea">
												<?$APPLICATION->ShowViewContent('under_sidebar_content');?>
												<?CDigital::get_banners_position('SIDE');?>
												<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "sect", "AREA_FILE_SUFFIX" => "sidebar", "AREA_FILE_RECURSIVE" => "Y"), false);?>
											</div>
										</div>
									<?endif;?>					
								<?endif;?>					
							<?if($APPLICATION->GetProperty("FULLWIDTH")!=='Y'):?>
								</div><?// class="maxwidth-theme?>
							<?endif;?>
						</div><?// class=row?>						
					<?else:?>
						<?CDigital::ShowPageType('indexblocks');?>
					<?endif;?>
				</div><?// class=container?>
				<?CDigital::get_banners_position('FOOTER');?>
			</div><?// class=main?>			
		</div><?// class=body?>		
		<?CDigital::ShowPageType('footer');?>
		<div class="bx_areas">
			<?CDigital::ShowPageType('bottom_counter');?>
		</div>
		<?CDigital::SetMeta();?>
		<?CDigital::ShowPageType('search_title_component');?>
		<?CDigital::ShowPageType('basket_component');?>
		<?CDigital::AjaxAuth();?>
	</body>
</html>