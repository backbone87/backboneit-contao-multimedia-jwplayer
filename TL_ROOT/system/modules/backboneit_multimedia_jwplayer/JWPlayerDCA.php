<?php

class JWPlayerDCA extends Backend {
	
	public function checkConfiguration($objDC) {
		$objCfg = new JWPlayerConfiguration($objDC->activeRecord->row());
		try {
			$objCfg->getPlayerPath();
			$objCfg->getEmbedderPath();
		} catch(Exception $e) {
			$objDC->addError(sprintf($GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['errPlayerApp'], $e->getMessage()), 'jwplayer');
		}
		try {
			$objCfg->getSkinPath();
		} catch(Exception $e) {
			$objDC->addError(sprintf($GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['errSkin'], $e->getMessage()), 'skin' . $objDC->activeRecord->skin);
		}
	}
	
	public function validateColor($strColor) {
		if(!$strColor) {
			return;
		}
		if(!preg_match('/^([0-9a-f]{3})([0-9a-f]{3})?$/', strtolower($strColor), $arrGroups)) {
			throw new Exception(sprintf($GLOBALS['TL_LANG']['tl_bbit_mm_jwp']['errColor'], $strColor));
		}
		if(!$arrGroups[2]) {
			return preg_replace('/(.)/', '$1$1', $strColor);
		}
			
		return $strColor;
	}
	
	public function getPagePicker(DataContainer $objDC) {
		$strField = 'ctrl_' . $objDC->inputName;
		return ' ' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top; cursor:pointer;" onclick="Backend.pickPage(\'' . $strField . '\')"');
	}
	
	public function getJWPlayers() {
		$objResult = $this->Database->execute(
			'SELECT id, title FROM tl_bbit_mm_jwp ORDER BY title'
		);
		
		$arrOptions = array();
		while($objResult->next()) {
			$arrOptions[$objResult->id] = $objResult->title;
		}
			
		return $arrOptions;
	}
	
	public function getTemplateOptions($objDC) {
	    $arrTpls = $this->getTemplateGroup('bbit_jwp_');
		array_unshift($arrTpls, 'bbit_jwp');
		return $arrTpls;
	}

	public function getEditJWPlayerWizard(DataContainer $objDC) {
		if($objDC->value < 1) {
			return '';
		}
			
		$objResult = $this->Database->prepare(
			'SELECT title FROM tl_bbit_mm_jwp WHERE id = ?'
		)->execute($objDC->value);
		
		if(!$objResult->numRows) {
			return '';
		}
		
		$strTitle = $GLOBALS['TL_LANG']['bbit_mm_jwp']['editJWPlayerWizard'];
		
		return sprintf(
			' <a href="contao/main.php?do=bbit_mm_jwp&amp;table=tl_bbit_mm_jwp&amp;act=edit&amp;id=%s" title="%s" style="padding-left:3px;">%s</a>',
			$objDC->value,
			specialchars(sprintf($strTitle, $objResult->title, $objDC->value)),
			$this->generateImage('alias.gif', $strTitle, 'style="vertical-align:top;"')
		);
	}
	
	
	protected function __construct() {
		parent::__construct();
	}
	
	private function __clone() {
	}
	
	private static $objInstance;
	
	public static function getInstance() {
		if(!isset(self::$objInstance)) {
			self::$objInstance = new self();
		}
		return self::$objInstance;
	}
	
}
