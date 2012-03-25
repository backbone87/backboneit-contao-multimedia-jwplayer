<?php

$this->loadLanguageFile('bbit_mm_jwp');

foreach($GLOBALS['TL_DCA']['tl_layout']['palettes'] as $strKey => &$strPalette) {
	if($strKey != '__selector__') {
		$strPalette .= ';{bbit_mm_jwp_legend},bbit_mm_jwp';
	}
}

$GLOBALS['TL_DCA']['tl_layout']['fields']['bbit_mm_jwp'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['bbit_mm_jwp']['jwplayer'],
	'exclude'		=> true,
	'inputType'		=> 'select',
	'options_callback' => array('JWPlayerDCA', 'getJWPlayers'),
	'eval'			=> array(
		'submitOnChange'	=> true,
		'includeBlankOption' => true,
		'blankOptionLabel'	=> &$GLOBALS['TL_LANG']['MSC']['blankOption'],
		'tl_class'			=> 'clr'
	),
	'wizard'		=> array(
		array('JWPlayerDCA', 'getEditJWPlayerWizard')
	)
);
