<?php

class JWPlayer extends AbstractMultimediaPlayer {
	
	protected static $arrSupported = array(
		'MultimediaStreamRTMP' 	=> true,
		'MultimediaStreamHTTP'	=> true,
		'MultimediaYoutube'		=> true,
		'MultimediaVideo'		=> true,
		'MultimediaAudio'		=> true
	);
	
	public static function create(array $arrData = null) {
		return new self(JWPlayerConfiguration::create($arrData['bbit_mm_jwp']));
	}
	
	public static function canPlay(Multimedia $objMM) {
		$strClass = get_class($objMM);
	    do {
	    	if(isset(self::$arrSupported[$strClass])) {
	    		return true;
	    	}
	    } while($strClass = get_parent_class($strClass));
 
		return false;
	}
	
	protected static $intUID = 0;
	
	protected $objConfig;
	
	public function __construct(JWPlayerConfiguration $objConfig) {
		parent::__construct($objConfig->getSize());
		
		$this->objConfig = $objConfig;
	}
	
	public function embed(Multimedia $objMM) {
		try {
			$arrData = array(
				'id'		=> 'jwplayer' . self::$intUID++,
				'config'	=> $this->buildConfig($objMM)
			);
			
			$GLOBALS['TL_JAVASCRIPT'][] = $this->objConfig->getEmbedderPath();
			
			$objTemplate = new FrontendTemplate('bbit_jwp');
			$objTemplate->setData($arrData);
			return $objTemplate->parse();
			
		} catch(Exception $e) {
			if($GLOBALS['TL_CONFIG']['debug']) {
				throw $e;
			}
			$this->log($e->getMessage(), __CLASS__ . '::' . __METHOD__ . '()', TL_ERROR);
			return '';
		}
	}
	
	protected function buildConfig(Multimedia $objMM) {
		$arrConfig = $this->objConfig->getConfig();
		
		$arrSize = $this->getSizeFor($objMM);
		$arrConfig['width'] = $arrSize[0];
		$arrConfig['height'] = $arrSize[1];
		
		$arrConfig['autostart'] = AbstractMultimediaPlayer::getAutoplay($arrConfig['autostart']);
		
		$strImage = $objMM->getPreviewImage();
		$strImage && $arrConfig['image'] = $strImage;
		 
		foreach($this->getProvider($objMM) as $strKey => $varOption) {
			$arrConfig[$strKey] = $varOption;
		}
		
		$arrConfig['file'] = $this->getFile($objMM);
		
		foreach($this->objConfig->getPlugins() as $objPlugin) {
			if(!$objPlugin->isEnabled()) {
				continue;
			}
			$arrPluginConfig = $objPlugin->getConfig($objMM);
			$arrPluginConfig && $arrConfig['plugins'][$objPlugin->getName()] = $arrPluginConfig;
		}
		
		return $arrConfig;
	}
	
	protected function getProvider(Multimedia $objMM) {
		$arrConfig = array();
		
		switch(get_class($objMM)) {
			case 'MultimediaStreamRTMP':
				$arrConfig['provider'] = 'rtmp';
				$arrConfig['streamer'] = $objMM->getStreamer();
				$arrConfig['rtmp.loadbalance'] = $objMM->isLoadbalanced();
				$arrConfig['rtmp.dvr'] = $objMM->isDVRStream();
				$arrConfig['rtmp.subscribe'] = $objMM->isSubscriptionStream();
				break;
				
			case 'MultimediaStreamHTTP':
				$arrConfig['provider'] = 'http';
				$arrConfig['http.startparam'] = $objMM->getStartParam();
				break;
				
			case 'MultimediaYoutube':
				$arrConfig['provider'] = 'youtube';
				break;
		}
		
		if(!$arrConfig['provider']) {
			switch($objMM->getMIMEType(true)) {
				case 'audio':
					$arrConfig['provider'] = 'sound';
					break;
			
				case 'video':
					$arrConfig['provider'] = 'video';
					break;
			
				case 'image':
					$arrConfig['provider'] = 'image';
					break;
						
				default:
// 					$arrConfig['provider'] = 'file';
					break;
			}
		}
		
		return $arrConfig;
	}
	
	protected function getFile(Multimedia $objMM) {
		return $objMM->getSource();
	}
	
}
