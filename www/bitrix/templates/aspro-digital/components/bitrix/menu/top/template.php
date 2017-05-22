<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?$this->setFrameMode(true);?>
<?
global $arTheme, $orderViewBasketHtml;
?>
<?if($arResult):?>
	<div class="table-menu">
		<div class="marker-nav"></div>
		<table>
			<tr>
				<?foreach($arResult as $arItem):?>					
					<?$bShowChilds = $arParams["MAX_LEVEL"] > 1;?>
					<td class="menu-item unvisible <?=($arItem["CHILD"] ? "dropdown" : "")?>  <?=($arItem["SELECTED"] ? "active" : "")?>">
						<div class="wrap">
							<a class="<?=($arItem["CHILD"] && $bShowChilds ? "dropdown-toggle" : "")?>" href="<?=$arItem["LINK"]?>">
								<?=$arItem["TEXT"]?>
								<div class="line-wrapper"><span class="line"></span></div>
							</a>
							<?if($arItem["CHILD"] && $bShowChilds):?>
								<span class="tail"></span>
								<ul class="dropdown-menu">
									<?foreach($arItem["CHILD"] as $arSubItem):?>
										<?$bShowChilds = $arParams["MAX_LEVEL"] > 2;?>
										<li class="<?=($arSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubItem["SELECTED"] ? "active" : "")?>">
											<a href="<?=$arSubItem["LINK"]?>" title="<?=$arSubItem["TEXT"]?>"><?=$arSubItem["TEXT"]?><?=($arSubItem["CHILD"] && $bShowChilds ? '<span class="arrow"><i></i></span>' : '')?></a>
											<?if($arSubItem["CHILD"] && $bShowChilds):?>
												<ul class="dropdown-menu">
													<?foreach($arSubItem["CHILD"] as $arSubSubItem):?>
														<?$bShowChilds = $arParams["MAX_LEVEL"] > 3;?>
														<li class="<?=($arSubSubItem["CHILD"] && $bShowChilds ? "dropdown-submenu" : "")?> <?=($arSubSubItem["SELECTED"] ? "active" : "")?>">
															<a href="<?=$arSubSubItem["LINK"]?>" title="<?=$arSubSubItem["TEXT"]?>"><?=$arSubSubItem["TEXT"]?></a>
															<?if($arSubSubItem["CHILD"] && $bShowChilds):?>
																<ul class="dropdown-menu">
																	<?foreach($arSubSubItem["CHILD"] as $arSubSubSubItem):?>
																		<li class="<?=($arSubSubSubItem["SELECTED"] ? "active" : "")?>">
																			<a href="<?=$arSubSubSubItem["LINK"]?>" title="<?=$arSubSubSubItem["TEXT"]?>"><?=$arSubSubSubItem["TEXT"]?></a>
																		</li>
																	<?endforeach;?>
																</ul>
																
															<?endif;?>
														</li>
													<?endforeach;?>
												</ul>
											<?endif;?>
										</li>
									<?endforeach;?>
								</ul>
							<?endif;?>
						</div>
					</td>
				<?endforeach;?>

				<td class="dropdown js-dropdown nosave unvisible">
					<div class="wrap">
						<a class="dropdown-toggle more-items" href="#">
							<span><?=GetMessage("S_MORE_ITEMS");?></span>
						</a>
						<span class="tail"></span>
						<ul class="dropdown-menu"></ul>
					</div>
				</td>

			</tr>
		</table>
	</div>
<?endif;?>

<?
	/*function update_menu() {			
	
		//return; 
		var tablemenu = document.getElementsByClassName("table-menu")[0];
		list = tablemenu.getElementsByClassName("menu-item");
		
		function getCookie(cname) {
			var name = cname + "=";
			var ca = document.cookie.split(';');
			for(var i = 0; i < ca.length; i++) {
				var c = ca[i];
				while (c.charAt(0) == ' ') {
					c = c.substring(1);
				}
				if (c.indexOf(name) == 0) {
					return c.substring(name.length, c.length);
				}
			}
			return false;
		}
		
		
		var menu_items_limit = getCookie('mil');
		var menu_items_show_more = getCookie('mism');

		showmore = tablemenu.getElementsByClassName("js-dropdown")[0];	
		
		if ((menu_items_show_more !== false) && ( menu_items_limit !== false)) {
			console.log('go limit: '+ menu_items_limit  + '; show more:' + menu_items_show_more);
			if (menu_items_show_more == 1) {
				showmore.classList.remove("unvisible");
			} else {
				showmore.classList.add("unvisible");		
			}
			
			for (var i = 0; i < list.length; i++) {
				if (i<menu_items_limit) {
					list[i].classList.remove("unvisible");
				} else {
					list[i].classList.add("unvisible");
				}
				
				if (i==menu_items_limit-1) {
					if (list[i].nextSibling) {
					  list[i].parentNode.insertBefore(showmore, list[i].nextSibling);
					}
					else {
					  list[i].parentNode.appendChild(showmore);
					}
				}
			}
		}
		
		tablemenu.classList.remove("unvisible");
		tablemenu.style.visibility = "";
	}
	
	
	update_menu(); */
?>

