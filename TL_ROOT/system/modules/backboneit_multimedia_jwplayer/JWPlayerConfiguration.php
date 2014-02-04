<?php

class JWPlayerConfiguration extends System {

	const MODE_HTML5 = 'html5';

	const MODE_FLASH = 'flash';

	const MODE_DOWNLOAD = 'download';

	public static function create($intID = null, $blnUncached = false) {
		if(is_numeric($intID)) {
			$objCfg = self::createByID(intval($intID), $blnUncached);
			if($objCfg) {
				return $objCfg;
			}
		}

		if($GLOBALS['objPage'] && $GLOBALS['objPage']->layout) {
			$objCfg = self::createByLayoutID($GLOBALS['objPage']->layout, $blnUncached);
			if($objCfg) {
				return $objCfg;
			}
		}

		return self::createDefault($blnUncached);
	}

	public static function createByID($intID, $blnUncached = false) {
		if(!$intID) {
			return null;
		}

		$strMethod = $blnUncached ? 'executeUncached' : 'execute';

		$objConfig = Database::getInstance()->prepare(
			'SELECT * FROM tl_bbit_mm_jwp WHERE id = ?'
		)->$strMethod($intID);

		if(!$objConfig->numRows) {
			return null;
		}

		$arrConfig = $objConfig->row();

		$objPlugins = Database::getInstance()->prepare(
			'SELECT * FROM tl_bbit_mm_jwp_plugins WHERE pid = ?'
		)->$strMethod($intID);

		$arrConfig['plugins'] = $objPlugins->fetchAllAssoc();

		return new self($arrConfig);
	}

	public static function createByLayoutID($intID, $blnUncached = false) {
		$strMethod = $blnUncached ? 'executeUncached' : 'execute';

		$objResult = Database::getInstance()->prepare(
			'SELECT bbit_mm_jwp AS id, pid FROM tl_layout WHERE id = ?'
		)->$strMethod($intID);

		if(!$objResult->numRows) {
			return null;
		}

		$objCfg = self::createByID($objResult->id, $blnUncached);
		if($objCfg) {
			return $objCfg;
		}

		return self::createByThemeID($objResult->pid, $blnUncached);
	}

	public static function createByThemeID($intID, $blnUncached = false) {
		$strMethod = $blnUncached ? 'executeUncached' : 'execute';

		$objResult = Database::getInstance()->prepare(
			'SELECT bbit_mm_jwp AS id FROM tl_theme WHERE id = ?'
		)->$strMethod($intID);

		if($objResult->numRows) {
			return null;
		}

		return self::createByID($objResult->id, $blnUncached);
	}

	public static function createDefault($blnUncached = false) {
		$strMethod = $blnUncached ? 'executeUncached' : 'execute';

		$objResult = Database::getInstance()->prepare(
			'SELECT id FROM tl_bbit_mm_jwp ORDER BY fallback DESC'
		)->limit(1)->$strMethod();

		if(!$objResult->numRows) {
			return null;
		}

		return self::createByID($objResult->id, $blnUncached);
	}

	protected $arrData;

	protected $arrConfig;

	private $strPlayerPath;

	private $strEmbedderPath;

	public function __construct(array $arrData) {
		parent::__construct();
		$this->arrData = $arrData;
		$this->arrData['size'] = deserialize($this->arrData['size'], true);
	}

	public function reset() {
		unset($this->arrConfig);
	}

	public function getConfig() {
		if(!isset($this->arrConfig)) {
			$this->arrConfig = $this->buildConfig();
		}

		return $this->arrConfig;
	}

	public function getData() {
		return $this->arrData;
	}

	public function getSize() {
		return $this->arrData['size'];
	}

	public function getTemplate() {
		return $this->arrData['template'] ? $this->arrData['template'] : 'bbit_jwp';
	}

	public function getPlugins() {
		if(!$this->arrData['plugins']) {
			return array();
		}

		$objFactory = JWPlayerPluginFactory::getInstance();
		$arrPlugins = array();

		foreach($this->arrData['plugins'] as $arrPluginData) {
			$objPlugin = $objFactory->create($arrPluginData);
			$objPlugin && $arrPlugins[] = $objPlugin;
		}

		return $arrPlugins;
	}

	public function getPlayerPath($blnRecreate = false) {
		if(!isset($this->strPlayerPath) || $blnRecreate) {
			$strPath = $this->arrData['jwplayer'];

			if(is_dir(TL_ROOT . '/' . $strPath)) {
				$strPath = $strPath . '/player.swf';

			} elseif(substr($strPath, -4) === '.zip') {
				$strPath = $this->unzipPlayer($strPath);
			}

			$this->strPlayerPath = $strPath && is_file(TL_ROOT . '/' . $strPath)
				? $strPath
				: new Exception(sprintf('JW Player [%s] not found.', $strPath));
		}

		if(!is_string($this->strPlayerPath)) {
			throw $this->strPlayerPath;
		}

		return $this->strPlayerPath;
	}

	public function getEmbedderPath($blnRecreate = false) {
		if(!isset($this->strEmbedderPath) || $blnRecreate) {
			$strPath = dirname($this->getPlayerPath($blnRecreate)) . '/jwplayer.js';
			$this->strEmbedderPath = $strPath && is_file(TL_ROOT . '/' . $strPath)
				? $strPath
				: new Exception(sprintf('JW Embedder [%s] not found.', $strPath));
		}

		if(!is_string($this->strEmbedderPath)) {
			throw $this->strEmbedderPath;
		}

		return $this->strEmbedderPath;
	}

	public function getSkinPath($blnRecreate = false) {
		switch($this->arrData['skin']) {
			case 'xml':
				return $this->unzipXMLSkin($this->arrData['skinxml'], $blnRecreate);
				break;

			case 'swf':
				return $this->arrData['skinswf'];
				break;
		}

		return '';
	}

	protected function unzipPlayer($strPath, $blnRecreate = false) {
		if(!is_file(TL_ROOT . '/' . $strPath)) {
			throw new Exception(sprintf('Cannot find file [%s] to unzip JW Player. ', $strPath));
		}

		$strTempPath = 'system/html/jwp-' . substr(md5($strPath), 0, 8);
		$blnExists = is_dir(TL_ROOT . '/' . $strTempPath);

		if($blnRecreate && $blnExists) {
			$objTempDir = new Folder($strTempPath);
			$objTempDir->delete();
			unset($objTempDir);
			$blnExists = false;
		}

		if(!$blnExists) {
			$arrFiles = array(
					'player.swf'	=> true,
					'yt.swf'		=> true,
					'jwplayer.js'	=> true
			);

			$objZip = new ZipReader($strPath);
			$objZip->first();
			do {
				$strFile = basename($objZip->file_name);
				if(isset($arrFiles[$strFile])) {
					$objFile = new File($strTempPath . '/' . $strFile);
					try {
						$objFile->write($objZip->unzip());
						unset($objFile); // finally statement missing in PHP...
					} catch(Exception $e) {
						unset($objFile); // finally statement missing in PHP...
						throw new Exception(sprintf('Error while unzipping JW Player file [%s] from archive [%s].', $objZip->file_name, $strPath));
					}
				}
			} while($objZip->next());
		}

		return $strTempPath . '/player.swf';
	}

	protected function unzipXMLSkin($strPath, $blnRecreate = false) {
		if(!is_file(TL_ROOT . '/' . $strPath)) {
			throw new Exception(sprintf('Cannot find file [%s] to unzip JW Player skin.', $strPath));
		}

		$strTempPath = 'system/html/jwp-skin-' . substr(md5($strPath), 0, 8);
		$strSkinFile = $strTempPath . '/' . basename($strPath);
		$blnExists = is_file(TL_ROOT . '/' . $strSkinFile);

		if($blnRecreate || !$blnExists) {
			$objTempDir = new Folder($strTempPath);
			$objTempDir->delete();
			unset($objTempDir);
			$blnExists = false;
		}

		if(!$blnExists) {
			$objZip = new ZipReader($strPath);
			$objZip->first();
			do {
				if(substr($objZip->file_name, -3) == 'xml') {
					$strBase = dirname($objZip->file_name);
					break;
				}
			} while($objZip->next());


			if(!strlen($strBase)) {
				throw new Exception(sprintf('No skin XML found in archive [%s].', $strPath));
			}

			if($strBase == '.') {
				$strBase = '';
				$intBaseLen = 0;
			} else {
				$strBase .= '/';
				$intBaseLen = strlen($strBase);
			}

			$objZip->first();
			do {
				if(strncmp($strBase, $objZip->file_name, $intBaseLen) != 0) {
					continue;
				}

				$objFile = new File($strTempPath . '/' . substr($objZip->file_name, $intBaseLen));
				try {
					$objFile->write($objZip->unzip());
					unset($objFile); // finally statement missing in PHP...
				} catch(Exception $e) {
					unset($objFile); // finally statement missing in PHP...
					throw new Exception(sprintf('Error while unzipping JW Player skin file [%s] from archive [%s].', $objZip->file_name, $strPath));
				}
			} while($objZip->next());

			Files::getInstance()->copy($strPath, $strSkinFile);
		}

		return $strSkinFile;
	}

	protected function buildConfig() {
		$arrConfig = array();

		$this->arrData['html5'] && $arrConfig['modes'][] = array('type' => self::MODE_HTML5);
		$arrConfig['modes'][] = array('type' => self::MODE_FLASH, 'src' => $this->getPlayerPath());
		$this->arrData['html5'] || $arrConfig['modes'][] = array('type' => self::MODE_HTML5);
		$arrConfig['modes'][] = array('type' => self::MODE_DOWNLOAD);

		$arrConfig['width']			= $this->arrData['size'][0];
		$arrConfig['height']		= $this->arrData['size'][1];

		$arrConfig['image']			= $this->arrData['image'];
		$arrConfig['stretching']	= $this->arrData['stretching'];
		$arrConfig['smoothing']		= $this->arrData['smoothing'] ? true : false;
		$arrConfig['volume']		= intval($this->arrData['volume']);
		$arrConfig['mute']			= $this->arrData['mute'] ? true : false;
		$arrConfig['repeat']		= $this->arrData['repeatplay'];
		$arrConfig['autostart']		= $this->arrData['autoplay'] ? true : false;
		$arrConfig['dock']			= $this->arrData['dock'] ? true : false;
		$arrConfig['icons']			= $this->arrData['icons'] ? true : false;

		$arrConfig['controlbar']	= $this->arrData['controlbar'];
		$arrConfig['controlbar.idlehide'] = $this->arrData['controlbarIdlehide'];

		if($this->arrData['logo']) {
			$arrConfig['logo.file']		= $this->arrData['logoFile'];

			if($this->arrData['logoLink']) {
				$arrConfig['logo.link']	= $this->arrData['logoLinkURL'];
				$arrConfig['logo.linktarget'] = $this->arrData['logoLinkTarget'] ? '_blank' : '_self';
			}

			$arrConfig['logo.hide']		= $this->arrData['logoHide'] ? true : false;
			$arrConfig['logo.margin']	= $this->arrData['logoMargin'];
			$arrConfig['logo.position']	= $this->arrData['logoPosition'];
			$arrConfig['logo.timeout']	= intval($this->arrData['logoTimeout']);
			$arrConfig['logo.over']		= intval($this->arrData['logoOver']) / 100;
			$arrConfig['logo.out']		= intval($this->arrData['logoOut']) / 100;
		}

		$arrConfig['skin'] 			= $this->getSkinPath();

		$arrConfig['backcolor']		= $this->arrData['backcolor'];
		$arrConfig['frontcolor']	= $this->arrData['frontcolor'];
		$arrConfig['lightcolor']	= $this->arrData['lightcolor'];
		$arrConfig['screencolor']	= $this->arrData['screencolor'];

		$arrConfig['netstreambasepath'] = Environment::getInstance()->base;
		$arrConfig['wmode'] = 'opaque';

		return $arrConfig;
	}

}
