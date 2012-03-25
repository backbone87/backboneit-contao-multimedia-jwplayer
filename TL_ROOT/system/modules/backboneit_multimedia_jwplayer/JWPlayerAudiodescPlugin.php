<?php

class JWPlayerAudiodescPlugin extends AbstractJWPlayerPlugin {
	
	public function __construct(array $arrData) {
		parent::__construct('audiodescription-2', $arrData);
	}
	
	public function getConfig(Multimedia $objMM = null) {
		$blnHasAudiodesc = $objMM instanceof MultimediaFeatureAudiodesc && $objMM->hasAudiodesc();
		if(!$blnHasAudiodesc) {
			return null;
		}
		
		$arrConfig = array();
		
		$arrConfig['state'] = $this->arrData['audiodesc_state'] ? true : false;
		$arrConfig['volume'] = $objMM->getAudiodescVolume();
		$arrConfig['volume'] || $arrConfig['volume'] = $this->arrData['audiodesc_volume'];
		$arrConfig['ducking'] = $this->arrData['audiodesc_ducking'] ? true : false;
		$arrConfig['debug'] = $this->arrData['audiodesc_debug'] ? true : false;
		
		$arrConfig['file'] = $objMM->getAudiodesc();
		
		return $arrConfig;
	}
	
}
