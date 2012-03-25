<?php

class JWPlayerCaptionsPlugin extends AbstractJWPlayerPlugin {
	
	public function __construct(array $arrData) {
		parent::__construct('captions-2', $arrData);
	}
	
	public function getConfig(Multimedia $objMM = null) {
		$blnHasCaptions = $objMM instanceof MultimediaFeatureCaptions && $objMM->hasCaptions();
		if(!$blnHasCaptions) {
			return null;
		}
		
		$arrConfig = array();
		
		$arrConfig['back'] = $this->arrData['captions_back'] ? true : false;
		$arrConfig['state'] = $this->arrData['captions_state'] ? true : false;
		
		if(!$objMM->isCaptionsEmbedded()) {
			$arrCaptions = $objMM->getCaptions();
			if(count($arrCaptions) > 1) {
				$arrConfig['labels'] = implode(',', array_keys($arrCaptions));
				$arrConfig['files'] = implode(',', array_values($arrCaptions));
			} else {
				$arrConfig['file'] = reset($arrCaptions);
			}
		}
		
		return $arrConfig;
	}
	
}
