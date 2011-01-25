<?php

ini_set('display_errors','on');
error_reporting(1);

preg_match('/^(.{0,}[\/]admin[\/]index.php){0,1}([^?]+)(.+){0,1}$/',$_SERVER['REQUEST_URI'],$uri);
define('REQUEST_PATH', $uri[2]);

define('DIR_FS_TOP',dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);
define('DIR_FS_INCLUDES',DIR_FS_TOP.'includes'.DIRECTORY_SEPARATOR);
define('DIR_FS_PROVIDERS',DIR_FS_INCLUDES.'providers'.DIRECTORY_SEPARATOR);
define('DIR_FS_ICONS',DIR_FS_INCLUDES.'icons'.DIRECTORY_SEPARATOR);
define('DIR_FS_IMAGES',DIR_FS_TOP.'images'.DIRECTORY_SEPARATOR);
define('DIR_FS_CONTENT',DIR_FS_TOP.'content'.DIRECTORY_SEPARATOR);
define('DIR_FS_CONTENT_CACHE',DIR_FS_TOP.'content_c'.DIRECTORY_SEPARATOR);
define('DIR_FS_ADMIN',DIR_FS_TOP.'admin'.DIRECTORY_SEPARATOR);

define('RECORDS_PAGE',10);

define('TIME_ZONE','US/Pacific');
define('DATE_FORMAT','D, d M Y');
define('TIME_FORMAT','H:i:s');
define('DATETIME_FORMAT',DATE_FORMAT.' '.TIME_FORMAT);

if (function_exists('date_default_timezone_set')) date_default_timezone_set(TIME_ZONE);

define('UNIXTIME_NOW',time());
define('TIME_NOW',gmdate(TIME_FORMAT,UNIXTIME_NOW));
define('DATETIME_NOW',gmdate(DATETIME_FORMAT,UNIXTIME_NOW));

return new providers;

class providers {
	var $load_order = array();
	var $loaded = array();
	var $on_loaded = array();
	var $providers = array();
	var $top_dir_path = false;
	var $restrict = false;

	function __construct() {
		$dir = dir(DIR_FS_PROVIDERS);
		while ($provider = basename($dir->read())) {
			if (!preg_match('/^([-_a-zA-Z0-9]+)$/',$provider) || !is_dir(DIR_FS_PROVIDERS.$provider)) continue;
			$ini = parse_ini_file(DIR_FS_PROVIDERS.$provider.DIRECTORY_SEPARATOR."info.ini");
			$this->load_order[$ini['provides']] = array($provider,$ini['provides'],@$ini['requires']?explode(',',preg_replace('/[,\s]+/',',',$ini['requires'])):false,@$ini['restrict']?$ini['restrict']:false);
			$this->providers[$ini['provides']] = $ini;
		}
	}
	
	function start() {
		$this->top_dir_path = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR;
		if (basename(dirname($_SERVER['SCRIPT_NAME'])) == 'admin') {
			$url_path = dirname($_SERVER['SCRIPT_NAME']);
			$this->restrict = 'back';
		} else {
			$url_path = $_SERVER['PHP_SELF'];
			$this->restrict = 'front';
		}

		define('DIR_WS_TOP', dirname($url_path) == '/' ? '' : dirname($url_path));
		define('DIR_WS_DIR', $url_path);

		for ($x = 0; $x < 250; $x++) {
			if (!$provider = array_shift($this->load_order)) break;
			if ($this->restrict && $provider[3] && $provider[3] != $this->restrict) continue;
			if ($provider[2]) foreach ($provider[2] as $require) if (!@$this->loaded[$require]) { $this->load_order[$provider[1]] = $provider; continue 2; }
			$file = DIR_FS_PROVIDERS.$provider[0].DIRECTORY_SEPARATOR.'class.php';
			if (is_dir(dirname($file)) && file_exists($file)) @include_once($file);
			if ($this->$provider[1] = new $provider[0]) {
				$this->providers[$provider[1]]['class'] = &$this->$provider[1];
				if (method_exists($this->$provider[1],'start')) {
					$this->loaded[$provider[1]] = $this->$provider[1]->start($this)?1:0;
				}
				if (method_exists($this->$provider[1],'on_loaded')) $this->on_loaded[] = $provider[1];
			}
		}
		foreach ($this->on_loaded as $provider) {
			$this->$provider->on_loaded();
		}
		return true;
	}
}

function redirect($url) {
	header("Location: $url");
	exit();
}