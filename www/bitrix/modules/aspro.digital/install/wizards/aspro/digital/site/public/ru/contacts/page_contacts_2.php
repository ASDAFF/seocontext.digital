<?
$bUseMap = CDigital::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
$bUseFeedback = CDigital::GetFrontParametrValue('CONTACTS_USE_FEEDBACK', SITE_ID) != 'N';
?>

<?CDigital::ShowPageType('page_title');?>

<div class="row">
	<div class="contacts maxwidth-theme" itemscope itemtype="http://schema.org/Organization">
		<div class="<?=($bUseMap ? 'col-md-4' : 'col-md-12')?>">
			<div>
				<span itemprop="description"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-about.php", Array(), Array("MODE" => "html", "NAME" => "Contacts about"));?></span>
			</div>
			<br />
			<br />
			<table>
				<tbody>
					<tr>
						<td align="left" valign="top"><i class="fa big-icon fa-map-marker"></i></td><td align="left" valign="top"><span class="dark_table">Адрес</span>
							<br />
							<span itemprop="address"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-address.php", Array(), Array("MODE" => "html", "NAME" => "Address"));?></span>
						</td>
					</tr>
					<tr>
						<td align="left" valign="top"><i class="fa big-icon  fa-phone"></i></td><td align="left" valign="top"> <span class="dark_table">Телефон</span>
							<br />
							<span itemprop="telephone"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-phone.php", Array(), Array("MODE" => "html", "NAME" => "Phone"));?></span>
						</td>
					</tr>
					<tr>
						<td align="left" valign="top"><i class="fa big-icon  fa-envelope"></i></td><td align="left" valign="top"> <span class="dark_table">E-mail</span>
							<br />
							<span itemprop="email"><?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-email.php", Array(), Array("MODE" => "html", "NAME" => "Email"));?></span>
						</td>
					</tr>
					<tr>
						<td align="left" valign="top"><i class="fa big-icon  fa-clock-o"></i></td><td align="left" valign="top"> <span class="dark_table">Режим работы</span>
							<br />
							<?$APPLICATION->IncludeFile(SITE_DIR."include/contacts-site-schedule.php", Array(), Array("MODE" => "html", "NAME" => "Schedule"));?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?if($bUseMap):?>
			<div class="col-md-8">
				<?$APPLICATION->IncludeFile("/include/contacts-site-map.php", Array(), Array("MODE" => "html", "TEMPLATE" => "include_area.php", "NAME" => "Карта"));?>
			</div>
		<?endif;?>
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
				"IBLOCK_TYPE" => "#IBLOCK_DIGITAL_FORM_TYPE#",
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