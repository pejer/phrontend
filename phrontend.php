<?php
define('_PHRONTEND_ROOT_',dirname(__FILE__).DIRECTORY_SEPARATOR);
class phrontend{
	
	var $plug_dir = null;
	
	function __construct() {
		$this->plug_dir = _PHRONTEND_ROOT_.'plugins'.DIRECTORY_SEPARATOR;
		$this->_load_config();
		$this->_autoload_plugins();
	}
	
	public function load_plugin($pluginName,$conf = array()){
		$this->_load_plugin($pluginName,$conf);
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
			var_dump($pluginKey);
			var_dump($pluginValue);
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
	}
}