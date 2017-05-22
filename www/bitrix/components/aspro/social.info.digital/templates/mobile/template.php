<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<div class="social-icons">
	<!-- noindex -->
	<ul>
		<?if(!empty($arResult['SOCIAL_FACEBOOK'])):?>
			<li class="facebook">
				<a href="<?=$arResult['SOCIAL_FACEBOOK']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_FACEBOOK')?>">
					<i class="svg svg-social-fb"></i>
					<?=GetMessage('TEMPL_SOCIAL_FACEBOOK')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_VK'])):?>
			<li class="vk">
				<a href="<?=$arResult['SOCIAL_VK']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_VK')?>">
					<i class="svg svg-social-vk"></i>
					<?=GetMessage('TEMPL_SOCIAL_VK')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_TWITTER'])):?>
			<li class="twitter">
				<a href="<?=$arResult['SOCIAL_TWITTER']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_TWITTER')?>">
					<i class="svg svg-social-twitter"></i>
					<?=GetMessage('TEMPL_SOCIAL_TWITTER')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_INSTAGRAM'])):?>
			<li class="instagram">
				<a href="<?=$arResult['SOCIAL_INSTAGRAM']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_INSTAGRAM')?>">
					<i class="svg svg-social-instagram"></i>
					<?=GetMessage('TEMPL_SOCIAL_INSTAGRAM')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_YOUTUBE'])):?>
			<li class="ytb">
				<a href="<?=$arResult['SOCIAL_YOUTUBE']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_YOUTUBE')?>">
					<i class="svg svg-social-ytb"></i>
					<?=GetMessage('TEMPL_SOCIAL_YOUTUBE')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_ODNOKLASSNIKI'])):?>
			<li class="odn">
				<a href="<?=$arResult['SOCIAL_ODNOKLASSNIKI']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_ODNOKLASSNIKI')?>">
					<i class="svg svg-social-odn"></i>
					<?=GetMessage('TEMPL_SOCIAL_ODNOKLASSNIKI')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_GOOGLEPLUS'])):?>
			<li class="gplus">
				<a href="<?=$arResult['SOCIAL_GOOGLEPLUS']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_GOOGLEPLUS')?>">
					<i class="svg svg-social-gplus"></i>
					<?=GetMessage('TEMPL_SOCIAL_GOOGLEPLUS')?>
				</a>
			</li>
		<?endif;?>
		<?if(!empty($arResult['SOCIAL_MAIL'])):?>
			<li class="mail">
				<a href="<?=$arResult['SOCIAL_MAIL']?>" class="dark-color" target="_blank" rel="nofollow" title="<?=GetMessage('TEMPL_SOCIAL_MAILRU')?>">
					<i class="svg svg-social-mail"></i>
					<?=GetMessage('TEMPL_SOCIAL_MAILRU')?>
				</a>
			</li>
		<?endif;?>
	</ul>
	<!-- /noindex -->
</div>