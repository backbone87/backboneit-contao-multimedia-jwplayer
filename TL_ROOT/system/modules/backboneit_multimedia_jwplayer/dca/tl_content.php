<?php

$this->loadLanguageFile('bbit_mm_jwp');

foreach(array('bbit_mm', 'bbit_mm_mediabox') as $strPaletteKey) {
	$GLOBALS['TL_DCA']['tl_content']['palettes'][$strPaletteKey . 'bbit_mm_jwp'] = str_replace(
		',bbit_mm_player',
		',bbit_mm_player,bbit_mm_jwp',
		$GLOBALS['TL_DCA']['tl_content']['palettes'][$strPaletteKey]
	);
}

$GLOBALS['TL_DCA']['tl_content']['fields']['bbit_mm_jwp'] = array(
	'label'			=> &$GLOBALS['TL_LANG']['bbit_mm_jwp']['jwplayer'],
	'exclude'		=> true,
	'inputType'		=> 'select',
	'options_callback' => array('JWPlayerDCA', 'getJWPlayers'),
	'eval'			=> array(
		'submitOnChange'	=> true,
		'includeBlankOption'=> true,
		'blankOptionLabel'	=> &$GLOBALS['TL_LANG']['MSC']['blankOption'],
		'tl_class'			=> 'w50 wizard'
	),
	'wizard'		=> array(
		array('JWPlayerDCA', 'getEditJWPlayerWizard')
	)
);
