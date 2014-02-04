<?php

class JWPlayerPluginFactory extends Controller {

	public function createByID($intID) {
		$intID = intval($intID);
		if($intID < 1) {
			return null;
		}

		$objResult = $this->Database->prepare(
			'SELECT * FROM tl_bbit_mm_jwp_plugins WHERE id = ?'
		)->execute($intID);

		if(!$objResult->numRows) {
			return null;
		}

		return $this->create($objResult->row());
	}

	public function create(array $arrData) {
		try {
			$strClass = $this->getClass($arrData['plugin']);
			return new $strClass($arrData);

		} catch(Exception $e) {
			if($GLOBALS['TL_CONFIG']['debug']) {
				throw $e;
			}
			return null;
		}
	}

	private $arrClasses = array();

	public function getClass($strPlugin) {
		if(!isset($this->arrClasses[$strPlugin])) {
			$this->arrClasses[$strPlugin] = $this->findClass($strPlugin);
		}

		if(is_string($this->arrClasses[$strPlugin])) {
			return $this->arrClasses[$strPlugin];

		} else {
			throw $this->arrClasses[$strPlugin];
		}
	}

	protected function findClass($strPlugin) {
		if(!isset($GLOBALS['BBIT_MM_JWP_PLUGINS'][$strPlugin])) {
			return new Exception(sprintf('No plugin class registered for plugin type [%s].', $strPlugin));
		}

		$strClass = $GLOBALS['BBIT_MM_JWP_PLUGINS'][$strPlugin];

		if(!$this->classFileExists($strClass)) {
			return new Exception(sprintf('Class [%s] for plugin type [%s] not found.', $strClass, $strPlugin));
		}

		if(!is_subclass_of($strClass, 'JWPlayerPlugin')) {
			return new Exception(sprintf('Class [%s] is not of type "JWPlayerPlugin".', $strClass));
		}

		return $strClass;
	}

	protected function __construct() {
		parent::__construct();
		$this->import('Database');
	}

	private function __clone() {
	}

	private static $objInstance;

	public static function getInstance() {
		if(!isset(self::$objInstance)) {
			return self::$objInstance = new self();
		}
		return self::$objInstance;
	}

}
