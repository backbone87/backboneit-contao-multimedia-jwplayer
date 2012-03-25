<?php

class JWPlayerGenericPlugin extends AbstractJWPlayerPlugin {
	
	public function __construct(array $arrData) {
		parent::__construct($arrData['generic_name'], $arrData);
	}
	
	public function getConfig(Multimedia $objMM = null) {
		return json_decode($this->arrData['generic_config'], true);
	}
	
}
