<?php

class search {
	var $providers;
	
	function search(&$providers) {
		$this->providers = $providers;
	}
	
	function perform($action) {
		if ($_REQUEST[$_REQUEST[$action]] && method_exists($this,$action)) {
			$this->$action($_REQUEST[$_REQUEST[$action]]);
		}
		return false;
	}

	function find(&$text) {
		$results = array();
		if ($_REQUEST['model'] && $text && $query = $this->providers->db->query($this->providers->db->select('content_data','value',array('(locate(\''.soundex(addslashes($text)).'\',concat(\' \',soundex(value))) > 0 or soundex(value) like \''.addslashes(soundex($text)).'\' or concat(\' \',value,\' \') like \'%'.addslashes($text).'%\')','model_id'=>$_REQUEST['model'])))) {
			while ($result = $this->providers->db->fetch($query)) $results[] = $result['value'];
			asort($results); $first = false;
			foreach ($results as $k=>$v) {
				if (strtolower(substr($v,0,strlen($text))) == strtolower($text)) {
					$first = true;
					echo trim($text.(substr($v,strlen($text))))."\x15";
					unset($results[$k]);
					break;
				}
			}
			if (!$first) echo "-1\x15";
			echo implode("\x15",$results);
		}
		exit();
	}
}
