<?php

class JWPlayer extends AbstractMultimediaPlayer {

	protected static $arrSupported = array(
		'MultimediaYoutube'	=> true,
		'MultimediaVideo'	=> true,
// 		'MultimediaAudio'	=> true,
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
				'mm'		=> $objMM,
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

		} elseif($objMM instanceof MultimediaVideo) {
			$arrConfig['provider'] = 'video';

			foreach($objMM->getSourceByType('http') as $objSource) {
				$arrLevel = array();
				$arrLevel['file'] = $objSource->getURL();
				if($objSource->isValid()) $arrLevel['type'] = $objSource->getMIME();
				$arrConfig['levels'][] = $arrLevel;
			}

			$arrFlash = $this->compileFlash($objMM);
			if($arrFlash) {
				if($arrConfig['levels']) {
					$this->setModeConfig($arrConfig, JWPlayerConfiguration::MODE_FLASH, $arrFlash);
				} else {
					$arrConfig = array_merge($arrConfig, $arrFlash);
				}
			}

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

	protected function setModeConfig(array &$arrConfig, $strMode, array $arrModeConfig) {
		foreach($arrConfig['modes'] as &$arrMode) {
			if($arrMode['type'] == $strMode) {
				$arrMode['config'] = $arrModeConfig;
				return;
			}
		}
	}

	protected function compileFlash(MultimediaVideo $objMM) {
		// use smil file source first
		$arrFlash = array();
		foreach($objMM->getSourceByType('smil') as $objSource) {
			$arrFlash['provider'] = 'rtmp';
			$arrFlash['file'] = $objSource->getURL();
			$arrFlash['rtmp.loadbalance'] = true;
			return $arrFlash;
		}

		// use rtmp source
		$arrFlash = array();
		$arrFlash['provider'] = 'rtmp';
		$arrLevelsByStreamer = array();
		foreach($objMM->getSourceByType('rtmp') as $objSource) {
			$arrFlash['file'] = $objSource->getFile();
			$arrFlash['streamer'] = $objSource->getStreamer();
			if($objSource->getBitrate()) {
				$arrLevel = array();
				$arrLevel['file'] = $arrFlash['file'];
				$arrLevel['bitrate'] = $objSource->getBitrate();
				$objSource->getWidth() && $arrLevel['width'] = $objSource->getWidth();
				$arrLevelsByStreamer[$arrFlash['streamer']][] = $arrLevel;
			}
		}
		if($arrFlash['file']) {
			if(count($arrLevelsByStreamer)) {
				$intMax = 0;
				$strMax = '';
				foreach($arrLevelsByStartParam as $strStreamer => $arrLevels) {
					if(count($arrLevels) > $intMax) {
						$intMax = count($arrLevels);
						$strMax = $strStreamer;
					}
				}
				if($intMax > 1) {
					$arrFlash['streamer'] = $strMax;
					$arrFlash['levels'] = $arrLevels[$strMax];
					unset($arrFlash['file']);
				}
			}
			return $arrFlash;
		}

		// use http pseudo streaming source last
		$arrFlash = array();
		$arrFlash['provider'] = 'http';
		$arrLevelsByStartParam = array();

		foreach($objMM->getSourceByType('httpStream') as $objSource) {
			$arrFlash['file'] = $objSource->getURL();
			$arrFlash['http.startparam'] = $objSource->getStartParam();
			if($objSource->getBitrate()) {
				$arrLevel = array();
				$arrLevel['file'] = $arrFlash['file'];
				$arrLevel['bitrate'] = $objSource->getBitrate();
				$objSource->getWidth() && $arrLevel['width'] = $objSource->getWidth();
				$arrLevelsByStartParam[$arrFlash['http.startparam']][] = $arrLevel;
			}
		}

		if($arrFlash['file']) {
			if(count($arrLevelsByStartParam)) {
				$intMax = 0;
				$strMax = '';
				foreach($arrLevelsByStartParam as $strStartParam => $arrLevels) {
					if(count($arrLevels) > $intMax) {
						$intMax = count($arrLevels);
						$strMax = $strStartParam;
					}
				}
				if($intMax > 1) {
					$arrFlash['http.startparam'] = $strMax;
					$arrFlash['levels'] = $arrLevels[$strMax];
					unset($arrFlash['file']);
				}
			}
			return $arrFlash;
		}
	}

}
