<?php

$GLOBALS['TL_DCA']['tl_bbit_mm_jwp_plugins'] = array(

	'config' => array(
		'dataContainer'		=> 'TableExtended',
		'ptable'			=> 'tl_bbit_mm_jwp',
		'enableVersioning'	=> true,
		'onload_callback'	=> array(
		),
		'onsubmit_callback'	=> array(
		),
	),

	'list' => array(
		'sorting' => array(
			'mode'			=> 2,
			'fields'		=> array('plugin'),
			'panelLayout'	=> 'limit',
			'disableGrouping' => true,
		),
		'label' => array(
			'fields'		=> array('id', 'plugin', 'enabled'),
			'format'		=> '%s %s %s',
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
			'edit' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['edit'],
				'href'	=> 'act=edit',
				'icon'	=> 'edit.gif'
			),
			'copy' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['copy'],
				'href'	=> 'act=paste&amp;mode=copy',
				'icon'	=> 'copy.gif'
			),
			'delete' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['delete'],
				'href'	=> 'act=delete',
				'icon'	=> 'delete.gif',
				'attributes' => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array(
				'label'	=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['show'],
				'href'	=> 'act=show',
				'icon'	=> 'show.gif'
			)
		),
	),

	'palettes' => array(
		'__selector__' => array('plugin'),

		'generic' => '{general_legend},plugin,enabled'
			. ';{generic_legend},generic_name,generic_params'
			,

		'captions' => '{general_legend},plugin,enabled'
			. ';{captions_legend},captions_state'
			. ';{captions_appearence_legend},captions_back'
// 			. ',captions_color,captions_fontFamily'
// 			. ',captions_fontSize,captions_fontStyle'
// 			. ',captions_fontWeight,captions_textDecoration'
			,

		'audiodesc' => '{general_legend},plugin,enabled'
			. ';{audiodesc_legend},audiodesc_state,audiodesc_volume'
			. ',audiodesc_ducking,audiodesc_debug'
			,

	),

	'subpalettes' => array(
	),

	'fields' => array(

		'plugin' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['plugin'],
			'inputType'	=> 'select',
			'default'	=> 'generic',
			'options'	=> array_keys($GLOBALS['BBIT_MM_JWP_PLUGINS']),
			'reference' => &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['plugins'],
			'eval'		=> array(
				'mandatory'			=> true,
				'submitOnChange'	=> true,
				'tl_class'			=> 'clr w50'
			)
		),
		'enabled' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['enabled'],
			'inputType'	=> 'checkbox',
			'default'	=> 1,
			'eval'		=> array(
				'tl_class'			=> 'w50 cbx m12'
			)
		),


		'generic_name' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['generic_name'],
			'exclude'	=> true,
			'inputType'	=> 'text',
			'eval'		=> array(
				'mandatory'			=> true,
				'nospace'			=> true,
				'decodeEntities'	=> true,
				'tl_class'			=> 'clr'
			)
		),
		'generic_params' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['generic_params'],
			'inputType'	=> 'textarea',
			'eval' 		=> array(
				'cols'				=> 40,
				'rows'				=> 4,
				'decodeEntities'	=> true,
				'tl_class'			=> 'clr',
				'style'				=> 'height: 150px;'
			),
			'save_callback' => array(
				array('JWPlayerDCA', 'validateJSON'),
			),
		),


		'captions_state' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['captions_state'],
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'tl_class'			=> 'cbx'
			)
		),
		'captions_back' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['captions_back'],
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'tl_class'			=> 'cbx'
			)
		),


		'audiodesc_state' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['audiodesc_state'],
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'tl_class'			=> 'clr w50 cbx m12'
			)
		),
		'audiodesc_volume' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['audiodesc_volume'],
			'exclude'	=> true,
			'inputType'	=> 'text',
			'default'	=> 90,
			'eval'		=> array(
				'mandatory'			=> true,
				'nospace'			=> true,
				'rgxp'				=> 'prcnt',
				'tl_class'			=> 'w50'
			),
		),
		'audiodesc_ducking' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['audiodesc_ducking'],
			'inputType'	=> 'checkbox',
			'default'	=> 1,
			'eval'		=> array(
				'tl_class'			=> 'clr w50 cbx'
			)
		),
		'audiodesc_debug' => array(
			'label'		=> &$GLOBALS['TL_LANG']['tl_bbit_mm_jwp_plugins']['audiodesc_debug'],
			'inputType'	=> 'checkbox',
			'eval'		=> array(
				'tl_class'			=> 'w50 cbx'
			)
		),
	)
);
	