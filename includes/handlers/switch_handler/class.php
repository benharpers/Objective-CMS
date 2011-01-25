<?php

return new switch_handler;

class switch_handler {
	var $title = "Switch";
	var $icon_class = 'switch';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = 'page_handler';

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		$value = $this->get($data['value']);
		switch ($format) {
			case 'text':
			case 'list':
			case 'html':	return $value;
			case 'field':	return '<div class="option"><input type="hidden" name="'.$data['name'].'" value="0" /><label class="checkbox"><input type="checkbox" class="switch_handler" name="'.$data['name'].'" value="1"'.($data['value'] == 1 ? ' checked="checked"' : '').' />Enabled</label></div>';
		}
	}
	
	function get_default($content) {
		return '0';
	}

	function set($value,$data = false) {
		return ($value == 1) ? 1 : '0';
	}

	function get($value) {
		return ($value == 1 ? 'On' : 'Off');
	}

	function import($value) {
		return (in_array(strtolower($value),array('on','true','enabled',1,true)) ? 1 : '0');
	}

	function export($value) {
		return ($value == 1 ? 'On' : 'Off');
	}
}