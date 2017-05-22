<?
$bUseMap = CDigital::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
$bUseFeedback = CDigital::GetFrontParametrValue('CONTACTS_USE_FEEDBACK', SITE_ID) != 'N';
?>
<?if($bUseMap):?>
	<div class="contacts-page-map">
		<?$APPLICATION->IncludeFile("/include/contacts-site-map.php", Array(), Array("MODE" => "html", "TEMPLATE" => "include_area.php", "NAME" => "Карта"));?>
	</div>
<?endif;?>

<div class="contacts contacts-page-map-overlay maxwidth-theme" itemscope itemtype="http://schema.org/Organization">
	<div class="contacts-wrapper">
		<div class="row">
			<div class="col-md-3">
				<table cellpadding="0" cellspasing="0">
					<tr>
						<td align="left" valign="top"><i class="fa big-icon s45 fa-map-marker"></i></td><td align="left" valign="top"><span class="dark_table">Адрес</span>
							<br />
							<span itemprop="address"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-address.php", Array(), Array("MODE" => "html", "NAME" => "Address"));?></span>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-3">
				<table cellpadding="0" cellspasing="0">
					<tr>
						<td align="left" valign="top"><i class="fa big-icon s45 fa-phone"></i></td><td align="left" valign="top"> <span class="dark_table">Телефон</span>
							<br />
							<span itemprop="telephone"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-phone.php", Array(), Array("MODE" => "html", "NAME" => "Phone"));?></span>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-3">
				<table cellpadding="0" cellspasing="0">
					<tr>
						<td align="left" valign="top"><i class="fa big-icon s45 fa-envelope"></i></td><td align="left" valign="top"> <span class="dark_table">E-mail</span>
							<br />
							<span itemprop="email"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?></span>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-md-3">
				<table cellpadding="0" cellspasing="0">
					<tr>
						<td align="left" valign="top"><i class="fa big-icon s45 fa-clock-o"></i></td><td align="left" valign="top"> <span class="dark_table">Режим работы</span>
							<br />
							<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-schedule.php", Array(), Array("MODE" => "html", "NAME" => "Schedule"));?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="contacts maxwidth-theme <?=($bUseMap ? 'top-cart' : '');?>">
		<div class="col-md-12" itemprop="description">
			<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-about.php", Array(), Array("MODE" => "html", "NAME" => "Contacts about"));?>
		</div>
	</div>
</div>
<?if($bUseFeedback):?>
	<div class="row">
		<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("contacts-form-block");?>
		<?global $arTheme;?>
		<?$APPLICATION->IncludeComponent(
			"aspro:form.digital",
			"contacts",
			array(
				"IBLOCK_TYPE" => "aspro_digital_form",
				"IBLOCK_ID" => CCache::$arIBlocks[SITE_ID]["aspro_digital_form"]["aspro_digital_question"][0],
				"USE_CAPTCHA" => "Y",
				"IS_PLACEHOLDER" => "N",
				"SUCCESS_MESSAGE" => "<p>Спасибо! Ваше сообщение отправлено!</p>",
				"SEND_BUTTON_NAME" => "Отправить",
				"SEND_BUTTON_CLASS" => "btn btn-default",
				"DISPLAY_CLOSE_BUTTON" => "Y",
				"CLOSE_BUTTON_NAME" => "Обновить страницу",
				"CLOSE_BUTTON_CLASS" => "btn btn-default refresh-page",
				"SHOW_LICENCE" => $arTheme["SHOW_LICENCE"]["VALUE"],
				"LICENCE_TEXT" => $arTheme["SHOW_LICENCE"]["DEPENDENT_PARAMS"]["LICENCE_TEXT"]["VALUE"],
				"AJAX_MODE" => "Y",
				"AJAX_OPTION_JUMP" => "Y",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"CACHE_TYPE" => "A",
				"CACHE_TIME" => "100000",
				"AJAX_OPTION_ADDITIONAL" => ""
			),
			false
		);?>
		<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("contacts-form-block", "");?>
	</div>
<?endif;?>