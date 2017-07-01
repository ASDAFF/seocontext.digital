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
	<script data-skip-moving="true">
        (function(w,d,u,b){
                s=d.createElement('script');r=(Date.now()/1000|0);s.async=1;s.src=u+'?'+r;
                h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://24.seocontext.su/upload/crm/site_button/loader_4_oo7zwd.js');
</script>
                    <!-- Yandex.Metrika counter -->
                    <script type="text/javascript">
                        (function (d, w, c) {
                            (w[c] = w[c] || []).push(function() {
                                try {
                                    w.yaCounter45102351 = new Ya.Metrika({
                                        id:45102351,
                                        clickmap:true,
                                        trackLinks:true,
                                        accurateTrackBounce:true,
                                        webvisor:true
                                    });
                                } catch(e) { }
                            });

                            var n = d.getElementsByTagName("script")[0],
                                s = d.createElement("script"),
                                f = function () { n.parentNode.insertBefore(s, n); };
                            s.type = "text/javascript";
                            s.async = true;
                            s.src = "https://mc.yandex.ru/metrika/watch.js";

                            if (w.opera == "[object Opera]") {
                                d.addEventListener("DOMContentLoaded", f, false);
                            } else { f(); }
                        })(document, window, "yandex_metrika_callbacks");
                    </script>
                    <noscript><div><img src="https://mc.yandex.ru/watch/45102351" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                    <!-- /Yandex.Metrika counter -->
	</body>
</html>