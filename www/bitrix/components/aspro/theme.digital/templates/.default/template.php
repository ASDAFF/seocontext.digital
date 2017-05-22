<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(false);?>
<?$customColorExist = isset($arResult['BASE_COLOR']['LIST']['CUSTOM']) && isset($arResult['BASE_COLOR_CUSTOM']);?>
<div class="style-switcher new1 <?=$_COOKIE['styleSwitcher'] == 'open' ? 'active' : ''?>">
	<div class="switch animation-bg">
		<svg id="Options.svg" xmlns="http://www.w3.org/2000/svg" width="22" height="24" viewBox="0 0 22 24">
		  <defs>
		    <style>
		      .cls-2 {
		        fill: #333;
		        fill-rule: evenodd;
		      }
		    </style>
		  </defs>
		  <path id="Ellipse_12_copy_2" data-name="Ellipse 12 copy 2" class="cls-2" d="M743,214H732.858a3.981,3.981,0,0,1-7.717,0H723a1,1,0,1,1,0-2h2.141a3.981,3.981,0,0,1,7.717,0H743A1,1,0,1,1,743,214Zm-14-3a2,2,0,1,0,2,2A2,2,0,0,0,729,211Zm14-5h-2.142a3.981,3.981,0,0,1-7.717,0H723a1,1,0,0,1,0-2h10.141a3.981,3.981,0,0,1,7.717,0H743A1,1,0,0,1,743,206Zm-6-3a2,2,0,1,0,2,2A2,2,0,0,0,737,203Zm-14,17h10.141a3.982,3.982,0,0,1,7.717,0H743a1,1,0,0,1,0,2h-2.142a3.982,3.982,0,0,1-7.717,0H723A1,1,0,0,1,723,220Zm14,3a2,2,0,1,0-2-2A2,2,0,0,0,737,223Z" transform="translate(-722 -201)"/>
		</svg>		
	</div>
	<div class="header">
		<div class="header-inner animation-bg reset">
			<svg id="Spinner.svg" xmlns="http://www.w3.org/2000/svg" width="14" height="12" viewBox="0 0 14 12">
			  <defs>
			    <style>
			      .cls-1 {
			        fill: #fff;
			        fill-rule: evenodd;
			      }
			    </style>
			  </defs>
			  <path class="cls-1" d="M294,40h-3a1,1,0,1,1,0-2h0.43a3.951,3.951,0,0,0-6.367-.7l-2.132-.486A5.935,5.935,0,0,1,293,36.766V36a1,1,0,1,1,2,0v3A1,1,0,0,1,294,40Zm-8,1a1,1,0,0,1-1,1h-0.447a3.971,3.971,0,0,0,6.207.885l2.191,0.5A5.954,5.954,0,0,1,283,43.247V44a1,1,0,1,1-2,0V41a1,1,0,0,1,1-1h3A1,1,0,0,1,286,41Z" transform="translate(-281 -34)"/>
			</svg>
			<?=GetMessage('THEME_DEFAULT')?>
		</div>		
	</div>
	<form method="POST" name="style-switcher">
		<div class="left-block">
			<?$arParametrs = CDigital::$arParametrsList;
			$i = 0;?>
			<?foreach($arParametrs as $blockCode => $arBlock)
			{
				if(isset($arBlock['THEME'] ) && $arBlock['THEME'] == 'Y'):?>
					<?
					$active = '';
					if($_COOKIE['styleSwitcherTabIndex'])
					{
						if($i == $_COOKIE['styleSwitcherTabIndex'])
							$active = 'active';
					}
					elseif(!$i)
						$active = 'active';?>
					<div class="section-block <?=$active;?>"><?=$arBlock['TITLE']?></div>
					<?$i++;?>
				<?else:?>
					<?unset($arParametrs[$blockCode]);?>
				<?endif;?>
			<?}?>
		</div>
		<div class="right-block">
			<div class="content-body">
				<?if($arParametrs)
				{
					$i = 0;
					foreach($arParametrs as $blockCode => $arBlock):?>
						<?
						$active = '';
						if($_COOKIE['styleSwitcherTabIndex'])
						{
							if($i == $_COOKIE['styleSwitcherTabIndex'])
								$active = 'active';
						}
						elseif(!$i)
							$active = 'active';?>
						<div class="block-item <?=$active;?>">
							<?foreach($arResult as $optionCode => $arOption)
							{
								if($arOption['TYPE_BLOCK'] == $blockCode && (isset($arOption['THEME']) && $arOption['THEME'] == 'Y') && $optionCode !== 'BASE_COLOR_CUSTOM' && !isset($arOption['GROUPS_EXT'])):?>
									<div class="item">
										<?if($arOption['TYPE'] == 'checkbox' && (isset($arOption['ONE_ROW']) && $arOption['ONE_ROW'] == 'Y')):?>
											<div class="options pull-left" data-code="<?=$optionCode?>">
												<?=ShowOptions($optionCode, $arOption);?>
											</div>
											<?=ShowOptionsTitle($optionCode, $arOption);?>
										<?else:?>
											<?=ShowOptionsTitle($optionCode, $arOption);?>
											<div class="options <?=((isset($arOption['REFRESH']) && $arOption['REFRESH'] == 'Y') ? 'refresh-block' : '');?>" data-code="<?=$optionCode?>">
												<?if(isset($arOption['TYPE_EXT']) && $arOption['TYPE_EXT'] == 'colorpicker'):?>
													<input type="hidden" id="<?=$optionCode?>" name="<?=$optionCode?>" value="<?=$arOption['VALUE']?>" />
													<?foreach($arOption['LIST'] as $colorCode => $arColor):?>
														<?if($colorCode !== 'CUSTOM'):?>
															<div class="base_color <?=($arColor['CURRENT'] == 'Y' ? 'current' : '')?>" data-value="<?=$colorCode?>" data-color="<?=$arColor['COLOR']?>">
																<a href="javascript:;" class="animation-all" data-option-id="<?=$optionCode?>" data-option-value="<?=$colorCode?>" title="<?=$arColor['TITLE']?>"><span style="background-color: <?=$arColor['COLOR']?>;"></span></a>
															</div>
														<?endif;?>
													<?endforeach;?>
													<?if($customColorExist && (isset($arResult['BASE_COLOR_CUSTOM']['PARENT_PROP']) && $arResult['BASE_COLOR_CUSTOM']['PARENT_PROP'] == $optionCode)):?>
														<?$customColor = str_replace('#', '', (strlen($arResult['BASE_COLOR_CUSTOM']['VALUE']) ? $arResult['BASE_COLOR_CUSTOM']['VALUE'] : $arResult['BASE_COLOR']['LIST'][$arResult['BASE_COLOR']['DEFAULT']]['COLOR']));?>	
														<?$arColor = $arOption['LIST']['CUSTOM'];?>
														<div class="base_color base_color_custom <?=($arColor['CURRENT'] == 'Y' ? 'current' : '')?>" data-value="CUSTOM" data-color="#<?=$customColor?>">
															<a href="javascript:;" class="animation-all" data-option-id="<?=$optionCode?>" data-option-value="CUSTOM" title="<?=$arColor['TITLE']?>" ><span style="background-color: #<?=$customColor?>;"></span></a>
															<input type="hidden" id="custom_picker" name="BASE_COLOR_CUSTOM" value="<?=$customColor?>" />
														</div>
													<?endif;?>
												<?else:?>
													<?=ShowOptions($optionCode, $arOption);?>
												<?endif;?>
											</div>
											<?if(isset($arOption['SUB_PARAMS']) && $arOption['LIST'] && (isset($arOption['REFRESH']) && $arOption['REFRESH'] == 'Y')):?>
												<div>
													<?foreach($arOption['LIST'] as $key => $arListOption):?>
														<?if($arOption['SUB_PARAMS'][$key]):?>
															<div class="sup-params options refresh-block s_<?=$key;?> <?=($key == $arOption['VALUE'] ? 'active' : '');?>">
																<div class="block-title"><span class="dotted-block"><?=GetMessage('SUB_PARAMS')?></span></div>
																<div class="values">
																	<?$j = 1;?>
																	<?foreach($arOption['SUB_PARAMS'][$key] as $key2 => $arSubOptions):?>
																		<?$isRow = (($arSubOptions['TYPE'] == 'checkbox' && (isset($arSubOptions['ONE_ROW']) && $arSubOptions['ONE_ROW'] == 'Y')) ? true : false);?>
																		<div class="option-wrapper <?=(($arSubOptions['VALUE'] == 'N' && $isRow) ? "disabled" : "");?>">
																			<?if($isRow):?>
																				<table class="">
																					<tr>
																						<td><div class="blocks"><?=$j++;?></div></td>
																						<td><div class="blocks block-title"><?=$arSubOptions['TITLE'];?></div></td>
																						<td>
																							<div class="blocks value">
																								<?=ShowOptions($key.'_'.$key2, $arSubOptions, $arOption);?>
																							</div>
																						</td>
																					</tr>
																				</table>
																			<?else:?>
																				<div class="block-title"><?=$arSubOptions['TITLE'];?></div>
																				<div class="value">
																					<?=ShowOptions($key.'_'.$key2, $arSubOptions);?>
																				</div>
																			<?endif;?>
																		</div>
																	<?endforeach;?>
																	<div class="apply-block"><button class="btn btn-default white apply"><?=GetMessage("APPLY");?></button></div>
																</div>
															</div>
														<?endif;?>
													<?endforeach;?>
												</div>
											<?endif;?>											
										<?endif;?>
										<?if(isset($arOption['DEPENDENT_PARAMS']) && $arOption['DEPENDENT_PARAMS']) // show dependent options
										{
											foreach($arOption['DEPENDENT_PARAMS'] as $key => $arSubOptions)
											{
												if((!isset($arSubOptions['CONDITIONAL_VALUE']) || ($arSubOptions['CONDITIONAL_VALUE'] && $arResult[$optionCode]['VALUE'] == $arSubOptions['CONDITIONAL_VALUE'])) && $arSubOptions['THEME'] == 'Y')
												{?>
													<?if($arSubOptions['TYPE'] == 'checkbox' && (isset($arSubOptions['ONE_ROW']) && $arSubOptions['ONE_ROW'] == 'Y')):?>
														<div class="borders item">
															<div class="options dependent pull-left" data-code="<?=$key?>">
																<?=ShowOptions($key, $arSubOptions);?>
															</div>
															<?=ShowOptionsTitle($key, $arSubOptions);?>
														</div>
													<?else:?>
														<?=ShowOptionsTitle($key, $arSubOptions);?>
														<div class="options dependent" data-code="<?=$key;?>">
															<?echo ShowOptions($key, $arSubOptions);?>
														</div>
													<?endif;?>
												<?}
											}
										}?>
									</div>
								<?elseif((isset($arOption['OPTIONS']) && $arOption['OPTIONS']) && (isset($arOption['GROUPS_EXT']) && $arOption['GROUPS_EXT'] == 'Y') && $arOption['TYPE_BLOCK'] == $blockCode && (isset($arOption['THEME']) && $arOption['THEME'] == 'Y')): // show groups options?>
									<div class="item groups">
										<?=ShowOptionsTitle($blockCode, $arOption);?>
										<div class="rows options">
											<?foreach($arOption['OPTIONS'] as $key => $arValue):?>
												<?echo ShowOptions($key, $arValue);?>
											<?endforeach;?>
										</div>
									</div>
								<?endif;?>
							<?}?>
							<?$i++;?>
						</div>
					<?endforeach;?>
				<?}?>
			</div>
		</div>
	</form>
	<div class="clearfix"></div>
</div>