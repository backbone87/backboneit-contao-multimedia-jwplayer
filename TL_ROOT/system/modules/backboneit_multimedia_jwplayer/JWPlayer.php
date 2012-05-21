<?php

class JWPlayer extends AbstractMultimediaPlayer {
	
	protected static $arrSupported = array(
		'MultimediaYoutube'			=> true,
		'MultimediaVideoHTTP'		=> true,
// 		'MultimediaAudio'			=> true,
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
			
			$objTemplate = new FrontendTemplate($this->objConfig->getTemplate());
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
		
		if($objMM instanceof MultimediaYoutube) {
			$arrConfig['provider'] = 'youtube';
			$arrConfig['file'] = $objMM->getSource();
			
		} elseif($objMM instanceof MultimediaVideoHTTP) {
			$arrConfig['provider'] = 'video';
			$arrSources = $objMM->getSource();
			foreach($arrSources as $arrSource) {
				$arrConfig['levels'][] = array(
					'file' => $arrSource['url'],
					'type' => $arrSource['mime']
				);
			}
			
		} elseif($objMM instanceof MultimediaRTMP) {
			throw new Exception('RTMP streaming not implemented.');
			$arrConfig['provider'] = 'rtmp';
			$arrConfig['streamer'] = $objMM->getStreamer();
			$arrConfig['rtmp.loadbalance'] = $objMM->isLoadbalanced();
			$arrConfig['rtmp.dvr'] = $objMM->isDVRStream();
			$arrConfig['rtmp.subscribe'] = $objMM->isSubscriptionStream();
			
		} elseif($objMM instanceof MultimediaHTTP) {
			throw new Exception('HTTP streaming not implemented.');
			$arrConfig['provider'] = 'http';
			$arrConfig['http.startparam'] = $objMM->getStartParam();
			
		} elseif($objMM instanceof AbstractMultimediaAudio) {
			throw new Exception('Audio streaming not implemented.');
			$arrConfig['provider'] = 'sound';
			
		} else {
			throw new Exception(sprintf('Multimedia type [%s] not supported', get_class($objMM)));
		}
		
		foreach($this->objConfig->getPlugins() as $objPlugin) {
			if(!$objPlugin->isEnabled()) {
				continue;
			}
			$arrPluginConfig = $objPlugin->getConfig($objMM);
			$arrPluginConfig && $arrConfig['plugins'][$objPlugin->getName()] = $arrPluginConfig;
		}
		
		return $arrConfig;
	}
	
}
