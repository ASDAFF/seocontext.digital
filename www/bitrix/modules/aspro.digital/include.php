<?php
/**
 * Digital module
 * @copyright 2017 Aspro
 */

CModule::AddAutoloadClasses(
	'aspro.digital',
	array(
		'digital' => 'install/index.php',
		'CDigital' => 'classes/general/CDigital.php',
		'CCache' => 'classes/general/CCache.php',
		'CDigitalTools' => 'classes/general/CDigitalTools.php',
		'CDigitalEvents' => 'classes/general/CDigitalEvents.php',
		'CInstargramDigital' => 'classes/general/CInstargramDigital.php',
	)
);

if(!defined('ADMIN_SECTION') && !$_REQUEST['test'])
{
	// include common aspro functions
	include_once __DIR__ .'/classes/general/CCache.php';

	UnRegisterModuleDependences('main', 'OnEndBufferContent', 'aspro.digital', 'CDigitalEvents', 'OnEndBufferContentHandler');
	CDigital::UpdateFrontParametrsValues();
	CDigital::GenerateThemes();
	RegisterModuleDependences('main', 'OnEndBufferContent', 'aspro.digital', 'CDigitalEvents', 'OnEndBufferContentHandler');
}