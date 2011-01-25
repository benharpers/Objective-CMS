<?php

class session_provider {
	var $title = "Session";
	var $icon_class = 'people';
	var $session_id = false;

	function start(&$providers = false) {
		$this->providers = &$providers;
		return true;
	}
}