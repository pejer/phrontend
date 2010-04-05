<?php
/**
* 
*/
class js{
	private  $conf, $files = array();
	function __construct($conf){
		$this->conf = $conf;
	}
	
	// ====================
	// = Public Functions =
	// ====================
	public function load($file_url,$sourceLocation = false, $fileType = false,$includeType = false){
		$sourceLocation = $sourceLocation === false?$this->conf['sourceLocation']:$sourceLocation;
		$fileType = $fileType === false?$this->conf['fileType']:$fileType;
		$includeType = $includeType === false?$this->conf['includeType']:$includeType;
		if ( !isset($this->files[$sourceLocation]) ) {
			$this->files[$sourceLocation] = array();
		}
		$this->files[$sourceLocation][$this->conf['js_base_url'].$file_url] = array($fileType, $includeType);
	}
	
	public function scripts($sourceLocation = false){
		if ( $sourceLocation === false ) {
			return $this->files;
		}
		if ( !isset($this->files[$sourceLocation]) ) {
			return false;
		}
		return $this->files[$sourceLocation];
	}
	
	public function script_html($sourceLocation = false){
		if ( $sourceLocation === false ) {
			return '';
		}
		$return = '';
		foreach ($this->files[$sourceLocation] as $file_url => $include_values) {
			switch($include_values[1]){
				case 'include':
					$return.="<script type=\"{$include_values[0]}\" src=\"{$file_url}\"></script>";
				break;
			}
		}
		return $return;
	}
	
	
	// =====================
	// = Private Functions =
	// =====================
	private function _hash_files($sourceLocation){
		
	}
}
