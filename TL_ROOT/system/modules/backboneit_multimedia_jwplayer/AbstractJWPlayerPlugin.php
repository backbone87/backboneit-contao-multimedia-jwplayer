<?php

abstract class AbstractJWPlayerPlugin extends Controller implements JWPlayerPlugin {
	
	protected $strName;
	
	protected $arrData;
	
	protected $blnEnabled;
	
	protected function __construct($strName, array $arrData = null) {
		parent::__construct();
		
		if(!strlen($strName)) {
			throw new Exception('Plugin name must not be empty');
		}
		
		$this->strName = $strName;
		$this->setEnabled($arrData['enabled']);
		$this->setData($arrData);
	}
	
	public function getName() {
		return $this->strName;
	}
	
	public function setData(array $arrData = null) {
		$this->arrData = (array) $arrData;
	}
	
	public function getData() {
		return $this->arrData;
	}
	
	public function setEnabled($blnEnabled) {
		$this->blnEnabled = $blnEnabled ? true : false;
	}
	
	public function isEnabled() {
		return $this->blnEnabled;
	}
	
}
