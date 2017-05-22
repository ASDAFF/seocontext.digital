<?php
/**
 * CDigital module
 * @copyright 2017 Aspro
 */

IncludeModuleLangFile(__FILE__);
$moduleClass = 'CDigital';
$solution = 'aspro.digital';

// initialize module parametrs list and default values
$moduleClass::$arParametrsList = array(
	'MAIN' => array(
		'TITLE' => GetMessage('MAIN_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'THEME_SWITCHER' =>	array(
				'TITLE' => GetMessage('THEME_SWITCHER'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'BASE_COLOR' => array(
				'TITLE' => GetMessage('BASE_COLOR'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'CUSTOM' => array('COLOR' => '', 'TITLE' => GetMessage('BASE_COLOR_CUSTOM')),
					'1' => array('COLOR' => '#ffad00', 'TITLE' => GetMessage('BASE_COLOR_1')),
					'2' => array('COLOR' => '#ff6d00', 'TITLE' => GetMessage('BASE_COLOR_2')),
					'3' => array('COLOR' => '#e65100', 'TITLE' => GetMessage('BASE_COLOR_3')),
					'4' => array('COLOR' => '#de002b', 'TITLE' => GetMessage('BASE_COLOR_4')),
					'5' => array('COLOR' => '#b41818', 'TITLE' => GetMessage('BASE_COLOR_5')),
					'6' => array('COLOR' => '#bd1c3c', 'TITLE' => GetMessage('BASE_COLOR_6')),
					'7' => array('COLOR' => '#d75cb6', 'TITLE' => GetMessage('BASE_COLOR_7')),
					'8' => array('COLOR' => '#5f58ac', 'TITLE' => GetMessage('BASE_COLOR_8')),
					'9' => array('COLOR' => '#00569c', 'TITLE' => GetMessage('BASE_COLOR_9')),
					'10' => array('COLOR' => '#0088cc', 'TITLE' => GetMessage('BASE_COLOR_10')),
					'11' => array('COLOR' => '#107bb1', 'TITLE' => GetMessage('BASE_COLOR_11')),
					'12' => array('COLOR' => '#497c9d', 'TITLE' => GetMessage('BASE_COLOR_12')),
					'13' => array('COLOR' => '#0fa8ae', 'TITLE' => GetMessage('BASE_COLOR_13')),
					'14' => array('COLOR' => '#0d897f', 'TITLE' => GetMessage('BASE_COLOR_14')),
					'15' => array('COLOR' => '#1b9e77', 'TITLE' => GetMessage('BASE_COLOR_15')),
					'16' => array('COLOR' => '#188b30', 'TITLE' => GetMessage('BASE_COLOR_16')),
					'17' => array('COLOR' => '#48a216', 'TITLE' => GetMessage('BASE_COLOR_17')),

				),
				'DEFAULT' => '4',
				'TYPE_EXT' => 'colorpicker',
				'THEME' => 'Y',
			),
			'BASE_COLOR_CUSTOM' => array(
				'TITLE' => GetMessage('BASE_COLOR_CUSTOM'),
				'TYPE' => 'text',
				'DEFAULT' => 'de002b',
				'PARENT_PROP' => 'BASE_COLOR',
				'THEME' => 'Y',
			),
			'COLORED_LOGO' => array(
				'TITLE' => GetMessage('COLORED_LOGO'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'LOGO_IMAGE' => array(
				'TITLE' => GetMessage('LOGO_IMAGE'),
				'TYPE' => 'file',
				'DEFAULT' => serialize(array()),
				'THEME' => 'N',
			),
			'FAVICON_IMAGE' => array(
				'TITLE' => GetMessage('FAVICON_IMAGE'),
				'TYPE' => 'file',
				'DEFAULT' => serialize(array()),
				'THEME' => 'N',
			),
			'APPLE_TOUCH_ICON_IMAGE' => array(
				'TITLE' => GetMessage('APPLE_TOUCH_ICON_IMAGE'),
				'TYPE' => 'file',
				'DEFAULT' => serialize(array()),
				'THEME' => 'N',
			),
			'FONT_STYLE' => array(
				'TITLE' => GetMessage('FONT_STYLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'TITLE' => '15px Open Sans',
						'GROUP' => 'Open Sans',
						'LINK' => 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext',
						'VALUE' => '15 px',
					),
					'2' => array(
						'TITLE' => '14px Open Sans',
						'GROUP' => 'Open Sans',
						'LINK' => 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext',
						'VALUE' => '14 px',
					),
					'3' => array(
						'TITLE' => '13px Open Sans',
						'GROUP' => 'Open Sans',
						'LINK' => 'Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,500,600,700,800&subset=latin,cyrillic-ext',
						'VALUE' => '13 px',
					),
					'4' => array(
						'TITLE' => '15px PT Sans Caption',
						'GROUP' => 'PT Sans',
						'LINK' => 'PT+Sans+Caption:400italic,700italic,400,700&subset=latin,cyrillic-ext',
						'VALUE' => '15 px',
					),
					'5' => array(
						'TITLE' => '14px PT Sans Caption',
						'GROUP' => 'PT Sans',
						'LINK' => 'PT+Sans+Caption:400italic,700italic,400,700&subset=latin,cyrillic-ext',
						'VALUE' => '14 px',
					),
					'6' => array(
						'TITLE' => '13px PT Sans Caption',
						'GROUP' => 'PT Sans',
						'LINK' => 'PT+Sans+Caption:400italic,700italic,400,700&subset=latin,cyrillic-ext',
						'VALUE' => '13 px',
					),
					'7' => array(
						'TITLE' => '15px Ubuntu',
						'GROUP' => 'Ubuntu',
						'LINK' => 'Ubuntu:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '15 px',
					),
					'8' => array(
						'TITLE' => '14px Ubuntu',
						'GROUP' => 'Ubuntu',
						'LINK' => 'Ubuntu:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '14 px',
					),
					'9' => array(
						'TITLE' => '13px Ubuntu',
						'GROUP' => 'Ubuntu',
						'LINK' => 'Ubuntu:300italic,400italic,500italic,700italic,400,300,500,700subset=latin,cyrillic-ext',
						'VALUE' => '13 px',
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
				'GROUPS' => 'Y',
			),
			'H1_STYLE' => array(
				'TITLE' => GetMessage('H1FONT'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'TITLE' => 'Bold',
						'GROUP' => GetMessage('H1FONT_STYLE'),						
						'VALUE' => 'Bold',
					),
					'2' => array(
						'TITLE' => 'Normal',
						'GROUP' => GetMessage('H1FONT_STYLE'),
						'VALUE' => 'Normal',
					)
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
				'GROUPS' => 'Y',
			),
			'PAGE_WIDTH' => array(
				'TITLE' => GetMessage('PAGE_WIDTH'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => '1 700 px',
					'2' => '1 500 px',
					'3' => '1 344 px',
					'4' => '1 200 px'
				),
				'DEFAULT' => '3',
				'THEME' => 'Y',
			),
			'TYPE_SEARCH' => array(
				'TITLE' => GetMessage('TYPE_SEARCH'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'corp' => '1',
					'fixed' => '2',
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					)
				),
				'DEFAULT' => 'fixed',
				'THEME' => 'Y',
			),
			'PAGE_TITLE' => array(
				'TITLE' => GetMessage('PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '3',
				'THEME' => 'Y',
			),
			'HOVER_TYPE_IMG' => array(
				'TITLE' => GetMessage('HOVER_TYPE_IMG_TITLE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'shine' => GetMessage('HOVER_TYPE_IMG_SHINE'),
					'blink' => GetMessage('HOVER_TYPE_IMG_BLINK'),
					'none' => GetMessage('HOVER_TYPE_IMG_NONE'),
				),
				'DEFAULT' => 'shine',
				'THEME' => 'Y',
			),
			'SHOW_LICENCE' => array(
				'TITLE' => GetMessage('SHOW_LICENCE_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'ONE_ROW' => 'Y',
				'HINT' => GetMessage('LICENCE_TEXT_VALUE_HINT'),
				'DEPENDENT_PARAMS' => array(
					'LICENCE_TEXT' => array(
						'TITLE' => GetMessage('LICENCE_TEXT_TITLE'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'includefile',
						'INCLUDEFILE' => '#SITE_DIR#include/licenses_text.php',
						'CONDITIONAL_VALUE' => 'Y',
						'PARAMS' => array(
							'WIDTH' => '100%'
						),
						'DEFAULT' => GetMessage('LICENCE_TEXT_VALUE'),
						'THEME' => 'N',
					),
				),
				'THEME' => 'Y',
			),
			'SIDE_MENU' => array(
				'TITLE' => GetMessage('SIDE_MENU'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'LEFT' => GetMessage('SIDE_MENU_LEFT'),
					'RIGHT' => GetMessage('SIDE_MENU_RIGHT'),
				),
				'DEFAULT' => 'LEFT',
				'THEME' => 'Y',
			),
			'PRINT_BUTTON' => array(
				'TITLE' => GetMessage('PRINT_BUTTON'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'ONE_ROW' => 'Y',
				'THEME' => 'Y',
			),
			'SCROLLTOTOP_TYPE' => array(
				'TITLE' => GetMessage('SCROLLTOTOP_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NONE' => GetMessage('SCROLLTOTOP_TYPE_NONE'),
					'ROUND_COLOR' => GetMessage('SCROLLTOTOP_TYPE_ROUND_COLOR'),
					'ROUND_GREY' => GetMessage('SCROLLTOTOP_TYPE_ROUND_GREY'),
					'ROUND_WHITE' => GetMessage('SCROLLTOTOP_TYPE_ROUND_WHITE'),
					'RECT_COLOR' => GetMessage('SCROLLTOTOP_TYPE_RECT_COLOR'),
					'RECT_GREY' => GetMessage('SCROLLTOTOP_TYPE_RECT_GREY'),
					'RECT_WHITE' => GetMessage('SCROLLTOTOP_TYPE_RECT_WHITE'),
				),
				'DEFAULT' => 'ROUND_COLOR',
				'THEME' => 'N',
			),
			'SCROLLTOTOP_POSITION' => array(
				'TITLE' => GetMessage('SCROLLTOTOP_POSITION'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'TOUCH' => GetMessage('SCROLLTOTOP_POSITION_TOUCH'),
					'PADDING' => GetMessage('SCROLLTOTOP_POSITION_PADDING'),
					'CONTENT' => GetMessage('SCROLLTOTOP_POSITION_CONTENT'),
				),
				'DEFAULT' => 'PADDING',
				'THEME' => 'N',
			),
		),
	),
	'FORMS' => array(
		'TITLE' => GetMessage('FORMS_OPTIONS'),
		'OPTIONS' => array(
			'CAPTCHA_FORM_TYPE' => array(
				'TITLE' => GetMessage('CAPTCHA_FORM_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'NONE' => GetMessage('VALIDATION_NONE'),
					'IMG' => GetMessage('VALIDATION_IMG'),
					'HIDE' => GetMessage('VALIDATION_HIDE'),
				),
				'DEFAULT' => 'HIDE',
				'THEME' => 'N',
			),
			'PHONE_MASK' => array(
				'TITLE' => GetMessage('PHONE_MASK'),
				'TYPE' => 'text',
				'DEFAULT' => '+7 (999) 999-99-99',
				'THEME' => 'N',
			),
			'VALIDATE_PHONE_MASK' => array(
				'TITLE' => GetMessage('VALIDATE_PHONE_MASK'),
				'TYPE' => 'text',
				'DEFAULT' => '^[+][0-9] [(][0-9]{3}[)] [0-9]{3}[-][0-9]{2}[-][0-9]{2}$',
				'THEME' => 'N',
			),
			'DATE_FORMAT' => array(
				'TITLE' => GetMessage('DATE_FORMAT'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'DOT' => GetMessage('DATE_FORMAT_DOT'),
					'HYPHEN' => GetMessage('DATE_FORMAT_HYPHEN'),
					'SPACE' => GetMessage('DATE_FORMAT_SPACE'),
					'SLASH' => GetMessage('DATE_FORMAT_SLASH'),
					'COLON' => GetMessage('DATE_FORMAT_COLON'),
				),
				'DEFAULT' => 'DOT',
				'THEME' => 'N',
			),
			'VALIDATE_FILE_EXT' => array(
				'TITLE' => GetMessage('VALIDATE_FILE_EXT'),
				'TYPE' => 'text',
				'DEFAULT' => 'png|jpg|jpeg|gif|doc|docx|xls|xlsx|txt|pdf|odt|rtf',
				'THEME' => 'N',
			),
		),
	),
	'SOCIAL' => array(
		'TITLE' => GetMessage('SOCIAL_OPTIONS'),
		'OPTIONS' => array(
			'SOCIAL_VK' => array(
				'TITLE' => GetMessage('SOCIAL_VK'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_FACEBOOK' => array(
				'TITLE' => GetMessage('SOCIAL_FACEBOOK'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_TWITTER' =>	array(
				'TITLE' => GetMessage('SOCIAL_TWITTER'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_INSTAGRAM' => array(
				'TITLE' => GetMessage('SOCIAL_INSTAGRAM'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_YOUTUBE' => array(
				'TITLE' => GetMessage('SOCIAL_YOUTUBE'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_ODNOKLASSNIKI' => array(
				'TITLE' => GetMessage('SOCIAL_ODNOKLASSNIKI'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_GOOGLEPLUS' => array(
				'TITLE' => GetMessage('SOCIAL_GOOGLEPLUS'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
			'SOCIAL_MAIL' => array(
				'TITLE' => GetMessage('SOCIAL_MAILRU'),
				'TYPE' => 'text',
				'DEFAULT' => '',
				'THEME' => 'N',
			),
		),
	),
	'INDEX_PAGE' => array(
		'TITLE' => GetMessage('INDEX_PAGE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'BANNER_WIDTH' => array(
				'TITLE' => GetMessage('BANNER_WIDTH'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'AUTO' => GetMessage('BANNER_WIDTH_AUTO'),
					'WIDE' => GetMessage('BANNER_WIDTH_WIDE'),
					'MIDDLE' => GetMessage('BANNER_WIDTH_MIDDLE'),
					'NARROW' => GetMessage('BANNER_WIDTH_NARROW'),
				),
				'DEFAULT' => 'SECOND',
				'THEME' => 'Y',
			),
			'BIGBANNER_ANIMATIONTYPE' => array(
				'TITLE' => GetMessage('BIGBANNER_ANIMATIONTYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'SLIDE_HORIZONTAL' => GetMessage('ANIMATION_SLIDE_HORIZONTAL'),
					'SLIDE_VERTICAL' => GetMessage('ANIMATION_SLIDE_VERTICAL'),
					'FADE' => GetMessage('ANIMATION_FADE'),
				),
				'DEFAULT' => 'SLIDE_HORIZONTAL',
				'THEME' => 'N',
			),
			'BIGBANNER_SLIDESSHOWSPEED' => array(
				'TITLE' => GetMessage('BIGBANNER_SLIDESSHOWSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '5000',
				'THEME' => 'N',
			),
			'BIGBANNER_ANIMATIONSPEED' => array(
				'TITLE' => GetMessage('BIGBANNER_ANIMATIONSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '600',
				'THEME' => 'N',
			),
			'BIGBANNER_HIDEONNARROW' => array(
				'TITLE' => GetMessage('BIGBANNER_HIDEONNARROW'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'N',
			),
			'PARTNERSBANNER_SLIDESSHOWSPEED' => array(
				'TITLE' => GetMessage('PARTNERSBANNER_SLIDESSHOWSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '5000',
				'THEME' => 'N',
			),
			'PARTNERSBANNER_ANIMATIONSPEED' => array(
				'TITLE' => GetMessage('PARTNERSBANNER_ANIMATIONSPEED'),
				'TYPE' => 'text',
				'DEFAULT' => '600',
				'THEME' => 'N',
			),
			'API_TOKEN_INSTAGRAMM' => array(
				'TITLE' => GetMessage('API_TOKEN_INSTAGRAMM_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => '1056017790.9b6cbfe.4dfb9d965b5c4c599121872c23b4dfd0',
				'THEME' => 'N',
			),
			'INDEX_TYPE' => array(
				'TITLE' => GetMessage('INDEX_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'index1' => '1',
					'index2' => '2',
					'index3' => '3',
					'index4' => '4',
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'index1',
				'THEME' => 'Y',
				'REFRESH' => 'Y',
				'PREVIEW' => array(
					'URL' => ''
				),
				'SUB_PARAMS' => array(
					/*'index1' => array(
						'TOP_SERVICES_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'TEASERS_INDEX' => array(
							'TITLE' => GetMessage('TEASERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PORTFOLIO_INDEX' => array(
							'TITLE' => GetMessage('PORTFOLIO_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'CATALOG_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'INSTAGRAMM_INDEX' => array(
							'TITLE' => GetMessage('INSTAGRAMM_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'NEWS_INDEX' => array(
							'TITLE' => GetMessage('NEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'REVIEWS_INDEX' => array(
							'TITLE' => GetMessage('REVIEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'CONSULT_INDEX' => array(
							'TITLE' => GetMessage('CONSULT_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'COMPANY_INDEX' => array(
							'TITLE' => GetMessage('COMPANY_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'TEAM_INDEX' => array(
							'TITLE' => GetMessage('TEAM_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						)
					),*/
					/*'index2' => array(
						'TOP_SERVICES_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'N',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
					),
					'index3' => array(
						'TOP_SERVICES_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'N',
							'THEME' => 'N',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
					),*/
					'index4' => array(
						'TOP_SERVICES_INDEX' => array(
							'TITLE' => GetMessage('TOP_SERVICES_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PARTNERS_INDEX' => array(
							'TITLE' => GetMessage('PARTNERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'TEASERS_INDEX' => array(
							'TITLE' => GetMessage('TEASERS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'PORTFOLIO_INDEX' => array(
							'TITLE' => GetMessage('PORTFOLIO_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'CATALOG_INDEX' => array(
							'TITLE' => GetMessage('CATALOG_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'INSTAGRAMM_INDEX' => array(
							'TITLE' => GetMessage('INSTAGRAMM_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'NEWS_INDEX' => array(
							'TITLE' => GetMessage('NEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'REVIEWS_INDEX' => array(
							'TITLE' => GetMessage('REVIEWS_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'CONSULT_INDEX' => array(
							'TITLE' => GetMessage('CONSULT_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'COMPANY_INDEX' => array(
							'TITLE' => GetMessage('COMPANY_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						),
						'TEAM_INDEX' => array(
							'TITLE' => GetMessage('TEAM_INDEX'),
							'TYPE' => 'checkbox',
							'DEFAULT' => 'Y',
							'THEME' => 'Y',
							'ONE_ROW' => 'Y',
							'SMALL_TOGGLE' => 'Y',
						)
					),
				)
			),
		),
	),
	'HEADER' => array(
		'TITLE' => GetMessage('HEADER_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'TOP_MENU_FIXED' => array(
				'TITLE' => GetMessage('TOP_MENU_FIXED'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'HEADER_FIXED' => array(
						'TITLE' => GetMessage('HEADER_FIXED'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'1' => array(
								'IMG' => '/bitrix/images/'.$solution.'/themes/fixed_header1.png',
								'TITLE' => '1',
								'POSITION_BLOCK' => 'block',
								'POSITION_TITLE' => 'left',
							),
							'2' => array(
								'IMG' => '/bitrix/images/'.$solution.'/themes/fixed_header2.png',
								'TITLE' => '2',
								'POSITION_BLOCK' => 'block',
								'POSITION_TITLE' => 'left',
							),
							'custom' => array(
								'TITLE' => 'Custom',
								'POSITION_BLOCK' => 'block',
								'HIDE' => 'Y'
							),
						),
						'CONDITIONAL_VALUE' => 'Y',
						'DEFAULT' => '2',
						'THEME' => 'Y',
					),
				)
			),
			'HEADER_TYPE' => array(
				'TITLE' => GetMessage('HEADER_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header1.png',
						'TITLE' => '1',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'2' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header2.png',
						'TITLE' => '2',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'3' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header3.png',
						'TITLE' => '3',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'4' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header4.png',
						'TITLE' => '4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'5' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header5.png',
						'TITLE' => '5',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'6' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header6.png',
						'TITLE' => '6',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'7' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header7.png',
						'TITLE' => '7',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'8' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header8.png',
						'TITLE' => '8',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'9' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header9.png',
						'TITLE' => '9',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'10' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header10.png',
						'TITLE' => '10',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'11' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header11.png',
						'TITLE' => '11',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'12' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header12.png',
						'TITLE' => '12',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'13' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header13.png',
						'TITLE' => '13',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'14' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header14.png',
						'TITLE' => '14',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'15' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header15.png',
						'TITLE' => '15',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'POSITION_BLOCK' => 'block',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'HEADER_PHONES' => array(
				'TITLE' => GetMessage('HEADER_PHONES_OPTIONS_TITLE'),
				'TYPE' => 'array',
				'THEME' => 'N',
				'OPTIONS' => $arContactOptions = array(
					'PHONE_VALUE' => array(
						'TITLE' => GetMessage('HEADER_PHONE_OPTION_VALUE_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '',
						'THEME' => 'N',
						'REQUIRED' => 'Y',
					),
				),
			),
		),
	),
	'CATALOG_PAGE' => array(
		'TITLE' => GetMessage('CATALOG_PAGE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'SHOW_SMARTFILTER' => array(
				'TITLE' => GetMessage('SHOW_FILTER_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'FILTER_VIEW' => array(
						'TITLE' => GetMessage('M_FILTER_VIEW'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'VERTICAL' => GetMessage('M_FILTER_VIEW_VERTICAL'),
							'HORIZONTAL' => GetMessage('M_FILTER_VIEW_HORIZONTAL'),
							// 'NONE' => GetMessage('M_FILTER_VIEW_NONE'),
						),
						'DEFAULT' => 'VERTICAL',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'Y',
					),
				)
			),
			'ORDER_VIEW' => array(
				'TITLE' => GetMessage('ORDER_VIEW_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'ORDER_BASKET_VIEW' => array(
						'TITLE' => GetMessage('ORDER_BASKET_VIEW_TITLE'),
						'HIDE_TITLE' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'HEADER' => GetMessage('ORDER_BASKET_VIEW_HEADER_TITLE'),
							'FLY' => GetMessage('ORDER_BASKET_VIEW_FLY_TITLE'),
							'FLY2' => GetMessage('ORDER_BASKET_VIEW_FLY2_TITLE'),
						),
						'DEFAULT' => 'FLY',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'Y',
					),
					'URL_BASKET_SECTION' => array(
						'TITLE' => GetMessage('URL_BASKET_SECTION_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '#SITE_DIR#cart/',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'N',
					),
					'URL_ORDER_SECTION' => array(
						'TITLE' => GetMessage('URL_ORDER_SECTION_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '#SITE_DIR#cart/order/',
						'CONDITIONAL_VALUE' => 'Y',
						'THEME' => 'N',
					),
				)
			),
		),
	),
	'SECTION' => array(
		'TITLE' => GetMessage('SECTION_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'PAGE_CONTACTS' => array(
				'TITLE' => GetMessage('PAGE_CONTACTS'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'1' => array(
						'TITLE' => GetMessage('PAGE_CONTACT1'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'2' => array(
						'TITLE' => GetMessage('PAGE_CONTACT2'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'3' => array(
						'TITLE' => GetMessage('PAGE_CONTACT3'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'4' => array(
						'TITLE' => GetMessage('PAGE_CONTACT4'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact4.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'5' => array(
						'TITLE' => GetMessage('PAGE_CONTACT5'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/contact5.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'contacts/'
				),
			),
			'CONTACTS_EDIT_LINK_NOTE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_EDIT_LINK_NOTE'),
				'TYPE' => 'note',
				'THEME' => 'N',
			),
			'CONTACTS_ADDRESS' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_ADDRESS_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-address.php',
				'THEME' => 'N',
			),
			'CONTACTS_PHONE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_PHONE_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-phone.php',
				'THEME' => 'N',
			),
			'CONTACTS_REGIONAL_PHONE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_REGIONAL_PHONE_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-phone-one.php',
				'THEME' => 'N',
			),
			'CONTACTS_EMAIL' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_EMAIL_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-email.php',
				'THEME' => 'N',
			),
			'CONTACTS_SCHEDULE12' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_SCHEDULE12_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-schedule.php',
				'THEME' => 'N',
			),
			'CONTACTS_DESCRIPTION12' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_DESCRIPTION12_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-about.php',
				'THEME' => 'N',
			),
			'CONTACTS_REGIONAL_DESCRIPTION34' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_REGIONAL_DESCRIPTION34_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-regions-title.php',
				'THEME' => 'N',
			),
			'CONTACTS_REGIONAL_DESCRIPTION5' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_REGIONAL_DESCRIPTION5_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-regions.php',
				'THEME' => 'N',
			),
			'CONTACTS_USE_FEEDBACK' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_USE_FEEDBACK_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'CONTACTS_USE_MAP' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_USE_MAP_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'N',
			),
			'CONTACTS_MAP' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_MAP_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/contacts-site-map.php',
				'THEME' => 'N',
			),
			'CONTACTS_MAP_NOTE' => array(
				'TITLE' => GetMessage('CONTACTS_OPTIONS_MAP_NOTE'),
				'TYPE' => 'note',
				'ALIGN' => 'center',
				'THEME' => 'N',
			),
			'BLOG_PAGE' => array(
				'TITLE' => GetMessage('BLOG_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/blog2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/blog1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom-blog' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'list_elements_2',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'articles/'
				),
			),
			'PROJECTS_PAGE' => array(
				'TITLE' => GetMessage('PROJECTS_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_4' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_SECTION'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK_YEAR'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/projects2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom-projects' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'list_elements_2',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'projects/'
				),
			),
			'NEWS_PAGE' => array(
				'TITLE' => GetMessage('NEWS_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'list_elements_1' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/news1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_2' => array(
						'TITLE' => GetMessage('PAGE_TILE'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/news2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list_elements_3' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/blog_news.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom-news' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'list_elements_2',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'news/'
				),
			),
			'STAFF_PAGE' => array(
				'TITLE' => GetMessage('STAFF_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'staff-items' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_employees1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'items-list2' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_employees2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom-staff' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'staff-items',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/staff/'
				),
			),
			'PARTNERS_PAGE' => array(
				'TITLE' => GetMessage('PARTNERS_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'items-list' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_partners1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'items-list2' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_partners2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'items-list3' => array(
						'TITLE' => GetMessage('PAGE_LOGO'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_partners3.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom-partners' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'items-list',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/partners/'
				),
			),
			'VACANCY_PAGE' => array(
				'TITLE' => GetMessage('VACANCY_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'items-accordion' => array(
						'TITLE' => GetMessage('PAGE_ACCORDION'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_vacancy1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'items-list4' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_vacancy2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom-vacancy' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'items-accordion',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/vacancy/'
				),
			),
			'LICENSES_PAGE' => array(
				'TITLE' => GetMessage('LICENSES_PAGE_TITLE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'table' => array(
						'TITLE' => GetMessage('PAGE_BLOCK'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_licenses1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'list' => array(
						'TITLE' => GetMessage('PAGE_LIST'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/company_licenses2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'custom-licenses' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => 'list',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'URL' => 'company/licenses/'
				),
			),
		)
	),
	'FOOTER' => array(
		'TITLE' => GetMessage('FOOTER_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'FOOTER_TYPE' => array(
				'TITLE' => GetMessage('FOOTER_TYPE'),
				'TYPE' => 'selectbox',
				'LIST' => array(
					'1' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer1.png',
						'TITLE' => '1',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'2' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer2.png',
						'TITLE' => '2',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'3' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer3.png',
						'TITLE' => '3',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'4' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/footer4.png',
						'TITLE' => '4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'POSITION_BLOCK' => 'block',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
				'PREVIEW' => array(
					'SCROLL_BLOCK' => '#footer'
				),
			),
		)
	),

	'ADV' => array(
		'TITLE' => GetMessage('ADV_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'ADV_TOP_HEADER' => array(
				'TITLE' => GetMessage('ADV_TOP_HEADER_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position1.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => GetMessage('ADV_GROUP_TITLE'),
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_TOP_UNDERHEADER' => array(
				'TITLE' => GetMessage('ADV_TOP_UNDERHEADER_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position2.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => GetMessage('ADV_GROUP_TITLE'),
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_SIDE' => array(
				'TITLE' => GetMessage('ADV_SIDE_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position5.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => GetMessage('ADV_GROUP_TITLE'),
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_CONTENT_TOP' => array(
				'TITLE' => GetMessage('ADV_CONTENT_TOP_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position3.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => GetMessage('ADV_GROUP_TITLE'),
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_CONTENT_BOTTOM' => array(
				'TITLE' => GetMessage('ADV_CONTENT_BOTTOM_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position4.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => GetMessage('ADV_GROUP_TITLE'),
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			),
			'ADV_FOOTER' => array(
				'TITLE' => GetMessage('ADV_FOOTER_TITLE'),
				'IMG' => '/bitrix/images/'.$solution.'/themes/banner_position6.png',
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'THEME' => 'Y',
				'GROUP' => GetMessage('ADV_GROUP_TITLE'),
				'ROW_CLASS' => 'col-md-6',
				'POSITION_BLOCK' => 'block',
				'IS_ROW' => 'Y',
				'SMALL_TOGGLE' => 'Y',
			)
		),
	),
	'MOBILE' => array(
		'TITLE' => GetMessage('MOBILE_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'HEADER_MOBILE' => array(
				'TITLE' => GetMessage('HEADER_MOBILE'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'N',
				'LIST' => array(
					'1' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header_mobile_white.png',
						'TITLE' => GetMessage('HEADER_MOBILE_WHITE'),
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
						'TITLE_WIDTH' => '75px',
					),
					'2' => array(
						'IMG' => '/bitrix/images/'.$solution.'/themes/header_mobile_color.png',
						'TITLE' => GetMessage('HEADER_MOBILE_COLOR'),
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
						'TITLE_WIDTH' => '75px',
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
						'POSITION_TITLE' => 'left',
						'TITLE_WIDTH' => '75px',
						'HIDE' => 'Y'
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'HEADER_MOBILE_MENU' => array(
				'TITLE' => GetMessage('HEADER_MOBILE_MENU'),
				'TYPE' => 'selectbox',
				// 'IS_ROW' => 'Y',
				'LIST' => array(
					'1' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_FULL'),
					),
					'2' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_TOP'),
					),
					'custom' => array(
						'TITLE' => 'Custom',
						'HIDE' => 'Y',
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
			'HEADER_MOBILE_MENU_OPEN' => array(
				'TITLE' => GetMessage('HEADER_MOBILE_MENU_OPEN'),
				'TYPE' => 'selectbox',
				'IS_ROW' => 'Y',
				'LIST' => array(
					'1' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_OPEN_LEFT'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/mobile_menu1.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
					'2' => array(
						'TITLE' => GetMessage('HEADER_MOBILE_MENU_OPEN_TOP'),
						'IMG' => '/bitrix/images/'.$solution.'/themes/mobile_menu2.png',
						'ROW_CLASS' => 'col-md-4',
						'POSITION_BLOCK' => 'block',
					),
				),
				'DEFAULT' => '1',
				'THEME' => 'Y',
			),
		)
	),
	'LK' => array(
		'TITLE' => GetMessage('LK_OPTIONS'),
		'THEME' => 'Y',
		'OPTIONS' => array(
			'CABINET' => array(
				'TITLE' => GetMessage('CABINET'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
				'THEME' => 'Y',
				'ONE_ROW' => 'Y',
				'DEPENDENT_PARAMS' => array(
					'PERSONAL_ONEFIO' => array(
						'TITLE' => GetMessage('PERSONAL_ONEFIO_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
						'THEME' => 'Y',
						'ONE_ROW' => 'Y',
						'CONDITIONAL_VALUE' => 'Y',
					),
				)
			),
		)
	),
	'COUNTERS_GOALS' => array(
		'TITLE' => GetMessage('COUNTERS_GOALS_OPTIONS'),
		'THEME' => 'N',
		'OPTIONS' => array(
			'ALL_COUNTERS' => array(
				'TITLE' => GetMessage('ALL_COUNTERS_TITLE'),
				'TYPE' => 'includefile',
				'INCLUDEFILE' => '#SITE_DIR#include/invis-counter.php',
			),
			'YA_GOLAS' => array(
				'TITLE' => GetMessage('YA_GOLAS_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'N',
				'DEPENDENT_PARAMS' => array(
					'YA_COUNTER_ID' => array(
						'TITLE' => GetMessage('YA_COUNTER_ID_TITLE'),
						'TYPE' => 'text',
						'DEFAULT' => '',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_FORMS_GOALS' => array(
						'TITLE' => GetMessage('USE_FORMS_GOALS_TITLE'),
						'TYPE' => 'selectbox',
						'LIST' => array(
							'NONE' => GetMessage('USE_FORMS_GOALS_NONE'),
							'COMMON' => GetMessage('USE_FORMS_GOALS_COMMON'),
							'SINGLE' => GetMessage('USE_FORMS_GOALS_SINGLE'),
						),
						'DEFAULT' => 'COMMON',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_FORMS_GOALS_NOTE' => array(
						'NOTE' => GetMessage('USE_FORM_GOALS_NOTE_TITLE'),
						'TYPE' => 'note',
						'THEME' => 'N',
						// 'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_SALE_GOALS' => array(
						'TITLE' => GetMessage('USE_SALE_GOALS_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_SALE_GOALS_NOTE' => array(
						'NOTE' => GetMessage('USE_SALE_GOALS_NOTE_TITLE'),
						'TYPE' => 'note',
						'THEME' => 'N',
						// 'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_DEBUG_GOALS' => array(
						'TITLE' => GetMessage('USE_DEBUG_GOALS_TITLE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'N',
						'THEME' => 'N',
						'CONDITIONAL_VALUE' => 'Y',
					),
					'USE_DEBUG_GOALS_NOTE' => array(
						'NOTE' => GetMessage('USE_DEBUG_GOALS_NOTE_TITLE'),
						'TYPE' => 'note',
						'THEME' => 'N',
						// 'CONDITIONAL_VALUE' => 'Y',
					),
				)
			)
		)
	),
);
?>