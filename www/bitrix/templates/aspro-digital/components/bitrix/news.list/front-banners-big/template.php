<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?if($arResult['ITEMS']):?>
	<?
	global $arTheme;
	$bHideOnNarrow = $arTheme['BIGBANNER_HIDEONNARROW']['VALUE'] === 'Y';
	$slideshowSpeed = abs(intval($arTheme['BIGBANNER_SLIDESSHOWSPEED']['VALUE']));
	$animationSpeed = abs(intval($arTheme['BIGBANNER_ANIMATIONSPEED']['VALUE']));
	$bAnimation = ($slideshowSpeed && strlen($arTheme['BIGBANNER_ANIMATIONTYPE']['VALUE']));
	if($arTheme['BIGBANNER_ANIMATIONTYPE']['VALUE'] === 'FADE'){
		$animationType = 'fade';
	}
	else{
		$animationType = 'slide';
		$animationDirection = 'horizontal';
		if($arTheme['BIGBANNER_ANIMATIONTYPE']['VALUE'] === 'SLIDE_VERTICAL'){
			$animationDirection = 'vertical';
		}
	}
	?>
	<div class="banners-big front<?=($bHideOnNarrow ? ' hidden_narrow' : '')?>">
		<div class="maxwidth-banner">
			<div class="flexslider unstyled <?=($animationDirection == 'vertical' ? 'vertical' : '')?>" data-plugin-options='{"directionNav": true, "customDirection": ".nav-carousel a", "controlNav": true, <?=($bAnimation ? '"slideshow": true,' : '"slideshow": false,')?> <?=($animationType ? '"animation": "'.$animationType.'",' : '')?> <?=($animationDirection ? '"direction": "'.$animationDirection.'",' : '')?> <?=($slideshowSpeed >= 0 ? '"slideshowSpeed": '.$slideshowSpeed.',' : '')?> <?=($animationSpeed >= 0 ? '"animationSpeed": '.$animationSpeed.',' : '')?> "animationLoop": true}'>
				<ul class="slides items">
					<?foreach($arResult['ITEMS'] as $i => $arItem):?>
						<?
						$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
						$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
						$imageBgSrc = is_array($arItem['DETAIL_PICTURE']) ? $arItem['DETAIL_PICTURE']['SRC'] : $this->GetFolder().'/images/background.jpg';
						$type = $arItem['PROPERTIES']['BANNERTYPE']['VALUE_XML_ID'];
						$bOnlyImage = $type == 'T1' || !$type;
						$bLinkOnName = strlen($arItem['PROPERTIES']['LINKIMG']['VALUE']);

						// video options
						$videoSource = strlen($arItem['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID']) ? $arItem['PROPERTIES']['VIDEO_SOURCE']['VALUE_XML_ID'] : 'LINK';
						$videoSrc = $arItem['PROPERTIES']['VIDEO_SRC']['VALUE'];
						if($videoFileID = $arItem['PROPERTIES']['VIDEO']['VALUE']){
							$videoFileSrc = CFile::GetPath($videoFileID);
						}
						$videoPlayer = $videoPlayerSrc = '';
						if($bShowVideo = $arItem['PROPERTIES']['SHOW_VIDEO']['VALUE_XML_ID'] === 'YES' && ($videoSource == 'LINK' ? strlen($videoSrc) : strlen($videoFileSrc))){
							$buttonVideoText = $arItem['PROPERTIES']['BUTTON_VIDEO_TEXT']['VALUE'];
							$bVideoLoop = $arItem['PROPERTIES']['VIDEO_LOOP']['VALUE_XML_ID'] === 'YES';
							$bVideoDisableSound = $arItem['PROPERTIES']['VIDEO_DISABLE_SOUND']['VALUE_XML_ID'] === 'YES';
							$bVideoAutoStart = $arItem['PROPERTIES']['VIDEO_AUTOSTART']['VALUE_XML_ID'] === 'YES';
							$bVideoCover = $arItem['PROPERTIES']['VIDEO_COVER']['VALUE_XML_ID'] === 'YES';
							$bVideoUnderText = $arItem['PROPERTIES']['VIDEO_UNDER_TEXT']['VALUE_XML_ID'] === 'YES';
							if(strlen($videoSrc) && $videoSource === 'LINK'){
								$videoPlayer = 'YOUTUBE';
								$videoSrc = htmlspecialchars_decode($videoSrc);
								if(strpos($videoSrc, 'iframe') !== false){
									$re = '/<iframe.*src=\"(.*)\".*><\/iframe>/isU';
									preg_match_all($re, $videoSrc, $arMatch);
									$videoSrc = $arMatch[1][0];
								}
								$videoPlayerSrc = $videoSrc;
								switch($videoSrc){
									case(($v = strpos($videoSrc, 'vimeo.com/')) !== false):
										$videoPlayer = 'VIMEO';
										break;
									case(strpos($videoSrc, 'watch?') !== false && ($v = strpos($videoSrc, 'v=')) !== false):
										$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 2, 11);
										break;
									case(strpos($videoSrc, 'youtu.be/') !== false && $v = strpos($videoSrc, 'youtu.be/')):
										$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 9, 11);
										break;
									case(strpos($videoSrc, 'embed/') !== false && $v = strpos($videoSrc, 'embed/')):
										$videoPlayerSrc = 'https://www.youtube.com/embed/'.substr($videoSrc, $v + 6, 11);
										break;
								}

								$bVideoPlayerYoutube = $videoPlayer === 'YOUTUBE';
								$bVideoPlayerVimeo = $videoPlayer === 'VIMEO';
								$bInitYoutubeJSApi |= $bVideoPlayerYoutube;

								if(strlen($videoPlayerSrc)){
									$videoPlayerSrc = trim($videoPlayerSrc.'?autoplay=1'.($bVideoPlayerYoutube ? '&enablejsapi=1&controls=0&showinfo=0&rel=0&disablekb=1&iv_load_policy=3' : ($bVideoPlayerVimeo ? '&api=1&badge=0&byline=0&portrait=0&title=0'.($bVideoLoop ? '&loop=1' : '') : '')));
								}
							}
							else{
								$videoPlayerSrc = $videoFileSrc;
							}
						}
						?>
						<li class="item" id="<?=$this->GetEditAreaId($arItem['ID'])?>" style="background:url('<?=$imageBgSrc?>') center center no-repeat !important;" data-slide_index="<?=$i?>" <?=($bShowVideo ? ' data-video_source="'.$videoSource.'"' : '')?><?=(strlen($videoPlayer) ? ' data-video_player="'.$videoPlayer.'"' : '')?><?=(strlen($videoPlayerSrc) ? ' data-video_src="'.$videoPlayerSrc.'"' : '')?><?=($bVideoAutoStart ? ' data-video_autoplay="1"' : '')?><?=($bVideoDisableSound ? ' data-video_disable_sound="1"' : '')?><?=($bVideoLoop ? ' data-video_loop="1"' : '')?><?=($bVideoCover ? ' data-video_cover="1"' : '')?>>
							<div class="maxwidth-theme<?=($bOnlyImage && $bLinkOnName ? ' fulla' : '')?>">
								<div class="row <?=$arItem['PROPERTIES']['TEXTCOLOR']['VALUE_XML_ID']?> <?=($type != 'T2' ? 'righttext' : '')?>">
									<?ob_start();?>
									<?if(!$bOnlyImage):?>
										<?
											$bShowButton1 = (strlen($arItem['PROPERTIES']['BUTTON1TEXT']['VALUE']) && strlen($arItem['PROPERTIES']['BUTTON1LINK']['VALUE']));
											$bShowButton2 = (strlen($arItem['PROPERTIES']['BUTTON2TEXT']['VALUE']) && strlen($arItem['PROPERTIES']['BUTTON2LINK']['VALUE']));
										?>
										<?if($bLinkOnName):?>
											<a href="<?=$arItem['PROPERTIES']['LINKIMG']['VALUE']?>" class="title-link">
												<div class="title"><?=$arItem['NAME']?></div>
											</a>
										<?else:?>
											<div class="title"><?=$arItem['NAME']?></div>
										<?endif;?>
										<div class="text-block">
											<?=$arItem['PREVIEW_TEXT']?>
										</div>
										<?if($bShowVideo && !$bVideoAutoStart && !$bShowButton1 && !$bShowButton2):?>
											<span class="play btn-video small" title="<?=$buttonVideoText?>"></span>
										<?elseif($bShowButton1 || $bShowButton2):?>
											<?if($bShowVideo && !$bVideoAutoStart):?>
												<span class="btn btn-default btn-video" title="<?=$buttonVideoText?>"><?=$buttonVideoText?></span>
											<?endif;?>
											<?if($bShowButton1):?>
												<a href="<?=$arItem['PROPERTIES']['BUTTON1LINK']['VALUE']?>" class="btn <?=(strlen($arItem['PROPERTIES']['BUTTON1CLASS']['VALUE']) ? $arItem['PROPERTIES']['BUTTON1CLASS']['VALUE'] : 'btn-default white')?>">
													<?=$arItem['PROPERTIES']['BUTTON1TEXT']['VALUE']?>
												</a>
											<?endif;?>
											<?if($bShowButton2):?>
												<a href="<?=$arItem['PROPERTIES']['BUTTON2LINK']['VALUE']?>" class="btn <?=(strlen($arItem['PROPERTIES']['BUTTON2CLASS']['VALUE'] ) ? $arItem['PROPERTIES']['BUTTON2CLASS']['VALUE'] : 'btn-default')?>">
													<?=$arItem['PROPERTIES']['BUTTON2TEXT']['VALUE']?>
												</a>
											<?endif;?>
										<?endif;?>
									<?endif;?>
									<?$text = ob_get_clean();?>

									<?ob_start();?>
									<?if(is_array($arItem['PREVIEW_PICTURE'])):?>
										<?if($bLinkOnName):?>
											<a href="<?=$arItem['PROPERTIES']['LINKIMG']['VALUE']?>" class="image">
												<img class="plaxy"  src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=($arItem['PREVIEW_PICTURE']['ALT'] ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE'] ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
											</a>
										<?else:?>
											<img class="plaxy" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=($arItem['PREVIEW_PICTURE']['ALT'] ? $arItem['PREVIEW_PICTURE']['ALT'] : $arItem['NAME'])?>" title="<?=($arItem['PREVIEW_PICTURE']['TITLE'] ? $arItem['PREVIEW_PICTURE']['TITLE'] : $arItem['NAME'])?>" />
										<?endif;?>
									<?endif;?>
									<?$img = ob_get_clean();?>

									<?if(!$bOnlyImage || (is_array($arItem['PREVIEW_PICTURE']) && !$bOnlyImage)):?>
										<div class="col-md-6 <?=$type == 'T2' ? 'text' : 'img'?>">
											<div class="inner">
												<?=$type == 'T2' ? $text : $img?>
											</div>
										</div>
										<div class="col-md-6 <?=$type == 'T2' ? 'img' : 'text'?>">
											<div class="inner">
												<?=$type == 'T2' ? $img : $text?>
											</div>
										</div>
									<?elseif($bOnlyImage && $bLinkOnName):?>
										<a href="<?=$arItem['PROPERTIES']['LINKIMG']['VALUE']?>"></a>
									<?elseif($bOnlyImage):?>
										<?if($bShowVideo && !$bVideoAutoStart):?>
											<div class="video_block">
												<div class="title"><?=$arItem['NAME']?></div>
												<span class="play btn-video" title="<?=$buttonVideoText?>"></span>
											</div>
										<?endif;?>
									<?endif;?>
								</div>
							</div>
						</li>
					<?endforeach;?>
				</ul>
				<div class="maxwidth-theme">
					<div class="col-md-12">
						<div class="nav-carousel">
							<ul class="flex-direction-nav">
								<li class="flex-nav-prev">
									<a href="javascript:void(0)" class="flex-prev"><span>Prev</span></a>
								</li>
								<li class="flex-nav-next">
									<a href="javascript:void(0)" class="flex-next"><span>Next</span></a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?if($bInitYoutubeJSApi):?>
		<script type="text/javascript">
		BX.ready(function(){
			var tag = document.createElement('script');
			tag.src = "https://www.youtube.com/iframe_api";
			var firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		});
		</script>
	<?endif;?>
<?endif;?>