<?php
define('_PHRONTEND_ROOT_',dirname(__FILE__).DIRECTORY_SEPARATOR);
class phrontend{
	
	private $plug_dir,$processor = null;
	private $xsl,$xml = '';
	function __construct() {
		$this->compatible();
		$this->plug_dir = _PHRONTEND_ROOT_.'plugins'.DIRECTORY_SEPARATOR;
		$this->_load_config();
		$this->_autoload_plugins();
	}
	
	public function load_plugin($pluginName,$conf = array()){
		$this->_load_plugin($pluginName,$conf);
	}
	
	public function render(){
		//return $this->xml;
		
		// processor
		$processor = new XSLTProcessor();
		
		// xsl DOMDoc
		$xslDoc = new DOMDocument();
		$xslDoc->loadXML('<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
		<xsl:output method="html" doctype-system="http://www.w3.org/TR/html4/strict.dtd" doctype-public="-//W3C//DTD HTML 4.01//EN" indent="yes"/>
		'.$this->xsl.'</xsl:stylesheet>');
		
		// xml DOMDoc
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($this->xml);
		
		$processor->importStyleSheet($xslDoc);
		return $processor->transformToXml($xmlDoc);
	}
	
	public function addXml($xml){
		$this->xml.=$xml;
	}
	
	public function addXsl($xsl){
		$this->xsl.=$xsl;
	}
	
	private function compatible(){
		if ( !class_exists('XSLTProcessor') ) {
			die('XSLTProcessor not found');
		}
		$this->processor = new XSLTProcessor();
		if ( !$this->processor->hasExsltSupport() ) {
			die('ExSLTsupport is not existing');
		}
	}
	
	private function _load_config(){
		require _PHRONTEND_ROOT_.DIRECTORY_SEPARATOR.'config.php';
		$this->conf = $_config;
		unset($_config);
	}
	
	private function _autoload_plugins(){
		foreach ($this->conf['plugin_load'] as $pluginKey => $pluginValue) {
			if ( is_numeric($pluginKey) ) {
				$this->_load_plugin($pluginValue);
				continue;
			}
			$this->_load_plugin($pluginKey,$pluginValue);
		}
	}
	
	private function _load_plugin($pluginName,$config = array()){
		$plugFile = $this->plug_dir.$pluginName.'.php';
		require $plugFile;
		$configFile = $this->plug_dir.$pluginName.'_config.php';
		if ( file_exists($configFile) ) {
			require $configFile;
			$config = array_merge($_config,$config);
		}
		$this->$pluginName = new $pluginName($config);
		$this->$pluginName->parent = &$this;
	}
}