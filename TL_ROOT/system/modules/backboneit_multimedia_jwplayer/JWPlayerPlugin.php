<?php

interface JWPlayerPlugin {
	
	function getName();
	
	function setData(array $arrData = null);
	
	function getData();
	
	function getConfig(Multimedia $objMM = null);
	
	function setEnabled($blnEnabled);
	
	function isEnabled();
	
}
