<?php

class error_provider {
	var $title = "Error Reporting";
	var $icon_class = 'tool';

	function start(&$providers) {
		set_error_handler(array($this,'handleError'), E_ALL ^ E_NOTICE);
		return true;
	}

	function handleError($errno, $errstr, $errfile, $errline, $errcontext ) {
		echo "<div class=\"error\"><h1>Error in $errfile on line $errline</h1><p>$errstr ($errno).</p></div>";
		return true;
	}
}