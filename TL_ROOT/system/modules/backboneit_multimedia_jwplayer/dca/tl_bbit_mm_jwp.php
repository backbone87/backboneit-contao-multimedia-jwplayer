<?php

$GLOBALS['TL_DCA']['tl_bbit_mm_jwp'] = array(

	'config' => array(
		'dataContainer'		=> 'TableExtended',
		'ctable'			=> array('tl_bbit_mm_jwp_plugins'),
		'enableVersioning'	=> true,
		'onload_callback'	=> array(
		),
		'onsubmit_callback'	=> array(
			array('JWPlayerDCA', 'checkConfiguration')
		),
	),
	
	'list' => array(
		'sorting' => array(
			'mode'			=> 2,
			'fields'		=> array('title'),
			'panelLayout'	=> 'filter,limit;search,sort',
			'disableGrouping' => true,
		),
		'label' => array(
			'fields'		=> array('title'),
			'format'		=> '%s',
//			'label_callback' => array('JWPlayerDCA', 'renderLabel'),
		),
		'global_operations' => array(
			'all' => array(
				'label'	=> &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'	=> 'act=select',
				'class'	=> 'header_edit_all',
				'attributes' => 'onclick="Backend.getScrollOffset();" accesskey="e"'
			)
		),
		'operations' => array(
			'plugins' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['plugins'],
				'href'	=> 'table=tl_bbit_mm_jwp_plugins',
// 				'icon'	=> 'system/modules/backboneit_multimedia_jwplayer/images/plugins.gif'
			),
			'edit' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['edit'],
				'href'	=> 'act=edit',
				'icon'	=> 'edit.gif'
			),
			'copy' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['copy'],
				'href'	=> 'act=paste&amp;mode=copy',
				'icon'	=> 'copy.gif'
			),
			'delete' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['delete'],
				'href'	=> 'act=delete',
				'icon'	=> 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['show'],
				'href'	=> 'act=show',
				'icon'	=> 'show.gif'
			)
		),
	),
	
	'palettes' => array(
		'default'		=> '{general_legend},title,fallback;'
			. '{player_legend},jwplayer,html5,smoothing;'
			. '{behavior_legend},stretching,volume,mute,repeatplay,autoplay;'
			. '{appearence_legend},image,size,skin;'
			. '{logo_legend},logo'
	),
	
	'subpalettes' => array(
		'skin'	=> array(
			''		=> 'dock,icons,controlbar,backcolor,frontcolor,lightcolor,screencolor',
			'css'	=> 'css,icons',
			'xml'	=> 'dock,icons,skinxml,controlbar',
			'swf'	=> 'dock,icons,skinswf,controlbar,backcolor,frontcolor,lightcolor,screencolor'
		),
		'controlbar'=> array(
			'over'	=> 'controlbarIdlehide'
		),
		'logo'		=> 'logoLink,logoFile,logoMargin,logoPosition,logoOver,logoOut,logoHide',
		'logoLink'	=> 'logoLinkURL,logoLinkTarget',
		'logoHide'	=> 'logoTimeout'
	),
	
	'fields' => array(
	
		'title' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['title'],
			'exclude'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'mandatory'			=> true,
				'tl_class'			=> 'clr w50'
			)
		),
		'fallback' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['fallback'],
			'exclude'	=> true,
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'fallback'			=> true,
				'tl_class'			=> 'w50 cbx m12'
			)
		),
		
		
		'jwplayer' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['jwplayer'],
			'exclude'	=> true,
			'inputType'	=> 'fileTree',
			'eval'		=> array(
				'mandatory'			=> true,
				'fieldType'			=> 'radio',
				'files'				=> true,
				'extensions'		=> 'swf,zip',
				'tl_class'			=> 'clr'
			)
		),
		'html5' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['html5'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'clr w50 cbx'
			)
		),
		'smoothing' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['smoothing'],
			'default'		=> 1,
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'w50 cbx'
			)
		),
		
		
		'stretching' => array (
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['stretching'],
			'default'		=> 'uniform',
			'exclude'		=> true,
			'inputType'		=> 'select',
			'options'		=> array('none', 'exactfit', 'uniform', 'fill'),
			'reference'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['stretchingOptions'],
			'eval'			=> array(
				'mandatory'			=> true,
				'tl_class'			=> 'clr w50'
			)
		),
		'volume' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['volume'],
			'default'		=> 90,
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array(
				'mandatory'			=> true,
				'nospace'			=> true,
				'rgxp'				=> 'prcnt',
				'tl_class'			=> 'clr w50'
			)
		),
		'mute' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['mute'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'w50 cbx m12'
			)
		),
		'repeatplay' => array (
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['repeatplay'],
			'default'		=> 'none',
			'exclude'		=> true,
			'inputType'		=> 'select',
			'options'		=> array('none', 'list', 'always', 'single'),
			'reference'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['repeatplayOptions'],
			'eval'			=> array(
				'mandatory'			=> true,
				'tl_class'			=> 'clr w50'
			)
		),
		'autoplay' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['autoplay'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'w50 cbx m12'
			)
		),
		
		
		'image' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['image'],
			'exclude'		=> true,
			'inputType'		=> 'fileTree',
			'eval'			=> array(
				'fieldType'			=> 'radio',
				'files'				=> true,
				'filesOnly'			=> true,
				'extensions'		=> 'jpeg,jpg,gif,png',
				'tl_class'			=> 'clr'
			)
		),
		'size' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['size'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'default'		=> array(400, 300),
			'eval'			=> array(
				'mandatory'			=> true,
				'multiple'			=> true,
				'size'				=> 2,
				'rgxp'				=> 'digit',
				'tl_class'			=> 'clr w50'
			),
			'save_callback'	=> array(
				array('MultimediaDCA', 'validateSize')
			),
		),
		'skin' => array (
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['skin'],
			'exclude'		=> true,
			'inputType'		=> 'select',
			'options'		=> array(/*'css',*/ 'xml', 'swf'),
			'reference'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['skinOptions'],
			'eval'			=> array(
				'submitOnChange'	=> true,
				'includeBlankOption' => true,
				'blankOptionLabel'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['skinOptions']['default'],
				'tl_class'			=> 'w50'
			)
		),
		'css' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['css'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array(
				'rgxp'				=> 'alnum',
				'tl_class'			=> 'clr w50'
			)
		),
		'skinswf' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['skinswf'],
			'exclude'	=> true,
			'inputType'	=> 'fileTree',
			'eval'		=> array(
				'mandatory'			=> true,
				'fieldType'			=> 'radio',
				'files'				=> true,
				'filesOnly'			=> true,
				'extensions'		=> 'swf',
				'decodeEntities'	=> true,
				'tl_class'			=> 'clr'
			)
		),
		'skinxml' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['skinxml'],
			'exclude'	=> true,
			'inputType'	=> 'fileTree',
			'eval'		=> array(
				'mandatory'			=> true,
				'fieldType'			=> 'radio',
				'files'				=> true,
				'filesOnly'			=> true,
				'extensions'		=> 'zip',
				'decodeEntities'	=> true,
				'tl_class'			=> 'clr'
			)
		),
		'dock' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['dock'],
			'default'		=> 1,
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'clr w50 cbx'
			)
		),
		'icons' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['icons'],
			'default'		=> 1,
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'w50 cbx'
			)
		),
		'controlbar' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['controlbar'],
			'default'		=> 'over',
			'exclude'		=> true,
			'inputType'		=> 'select',
			'options'		=> array('bottom', 'top', 'over', 'none'),
			'reference'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['controlbarOptions'],
			'eval'			=> array(
				'mandatory'			=> true,
				'submitOnChange'	=> true,
				'tl_class'			=> 'clr w50'
			)
		),
		'controlbarIdlehide' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['controlbarIdlehide'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'w50 cbx m12'
			)
		),
		'backcolor' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['backcolor'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'save_callback'	=> array(
				array('JWPlayerDCA', 'validateColor')
			),
			'eval'			=> array(
				'maxlength'			=> 6,
				'rgxp'				=> 'alnum',
				'tl_class'			=> 'clr w50'
			)
		),
		'frontcolor' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['frontcolor'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'save_callback'	=> array(
				array('JWPlayerDCA', 'validateColor')
			),
			'eval'			=> array(
				'maxlength'			=> 6,
				'rgxp'				=> 'alnum',
				'tl_class'			=> 'w50'
			)
		),
		'lightcolor' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['lightcolor'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'save_callback'	=> array(
				array('JWPlayerDCA', 'validateColor')
			),
			'eval'			=> array(
				'maxlength'			=> 6,
				'rgxp'				=> 'alnum',
				'tl_class'			=> 'clr w50'
			)
		),
		'screencolor' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['screencolor'],
			'exclude'		=> true,
			'inputType'		=> 'text',
			'save_callback'	=> array(
				array('JWPlayerDCA', 'validateColor')
			),
			'eval'			=> array(
				'maxlength'			=> 6,
				'rgxp'				=> 'alnum',
				'tl_class'			=> 'w50'
			)
		),
		
		
		'logo' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logo'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'submitOnChange'	=> true,
				'tl_class'			=> 'clr w50 cbx'
			)
		),
		'logoLink' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoLink'],
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'submitOnChange'	=> true,
				'tl_class'			=> 'w50 cbx'
			)
		),
		'logoLinkURL' => array(
			'label'			=> &$GLOBALS['TL_LANG']['MSC']['url'],
			'exclude'		=> true,
			'search'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array(
				'mandatory'			=> true,
				'rgxp'				=> 'url',
				'decodeEntities'	=> true,
				'maxlength'			=> 255,
				'tl_class'			=> 'clr w50 wizard'
			),
			'wizard'		=> array(
				array('JWPlayerDCA', 'getPagePicker')
			)
		),
		'logoLinkTarget' => array(
			'label'			=> &$GLOBALS['TL_LANG']['MSC']['target'],
			'default'		=> 1,
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'tl_class'			=> 'w50 cbx m12'
			)
		),
		'logoFile' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoFile'],
			'exclude'		=> true,
			'inputType'		=> 'fileTree',
			'eval'			=> array(
				'mandatory'			=> true,
				'fieldType'			=> 'radio',
				'files'				=> true,
				'filesOnly'			=> true,
				'extensions'		=> 'jpeg,jpg,gif,png',
				'tl_class'			=> 'clr'
			)
		),
		'logoMargin' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoMargin'],
			'default'		=> 8,
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array(
				'rgxp'				=> 'digit',
				'tl_class'			=> 'clr w50'
			)
		),
		'logoPosition' => array (
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoPosition'],
			'default'		=> 'bottom-left',
			'exclude'		=> true,
			'inputType'		=> 'select',
			'options'		=> array('top-left', 'top-right', 'bottom-left', 'bottom-right'),
			'reference'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoPositionOptions'],
			'eval'			=> array(
				'tl_class'			=> 'w50'
			)
		),
		'logoOver' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoOver'],
			'default'		=> 100,
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array(
				'rgxp'				=> 'prcnt',
				'tl_class'			=> 'clr w50'
			)
		),
		'logoOut' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoOut'],
			'default'		=> 50,
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array(
				'rgxp'				=> 'prcnt',
				'tl_class'			=> 'w50'
			)
		),
		'logoHide' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoHide'],
			'default'		=> 1,
			'exclude'		=> true,
			'inputType'		=> 'checkbox',
			'eval'			=> array(
				'submitOnChange'	=> true,
				'tl_class'			=> 'clr w50 cbx m12'
			)
		),
		'logoTimeout' => array(
			'label'			=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['logoTimeout'],
			'default'		=> 3,
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> array(
				'rgxp'				=> 'digit',
				'tl_class'			=> 'w50'
			)
		),
	)
);
	