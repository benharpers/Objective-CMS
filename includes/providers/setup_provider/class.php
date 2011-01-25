<?php

class setup_provider {
	var $title = "Setup Initialization";
	var $icon_class = 'tool';

	function start(&$providers) {
		$this->providers = &$providers;
		$this->ini_dir = DIR_FS_TOP.'ini'.DIRECTORY_SEPARATOR;

		if ($dir = dir($this->ini_dir)) {
			while ($file = $dir->read()) {
				if (preg_match_all('/^([^.]+)\.ini$/',$file,$file_class)) {
					$data = parse_ini_file($this->ini_dir.$file,true);
					$this->$file_class[1][0] = $data;
				}
			}
		}

		return true;
	}
}