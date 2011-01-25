<?php

class null {
	var $providers;
	function null(&$providers) {
		$this->providers = $providers;
		return true;
	}
	function perform() {
		return false;
	}
}
