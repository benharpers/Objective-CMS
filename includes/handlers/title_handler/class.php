<?php

return new title_handler;

class title_handler {
	var $title = "Title";
	var $icon_class = 'text';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = array('page_handler','options_handler');
	var $formats = array('text'=>'{value}',
						 'list'=>'{value_short}',
						 'html'=>'<div class="title_handler">{value}</div>',
						 'field'=>'<input type="text" class="title_handler" name="{name}" value="{value_html}" />');

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		if (!$data['name']) $data['name'] = '';
		if (!$data['value']) $data['value'] = '';
		$val = strip_tags($data['value']);
		$data['value_html'] = ($val != html_entity_decode($val) ? $val : htmlentities($val));
		$data['value_short'] = strlen($data['value']) > 56 ? substr($data['value'],0,49-strpos(strrev(substr($data['value'],0,50)),' ')).'...' : $data['value'];
		return str_replace(preg_replace('/(.+)/','{\1}',array_keys($data)),
						   array_values($data),
						   @$this->formats[$this->formats[$format] ? $format : 'text']);
	}
	
	function get_default($content) {
		return '';
	}

	function set($value,$data = false) {
		return $value;
	}

	function get($value) {
		return $value;
	}

	function import($value) {
		return $value;
	}

	function export($value) {
		return $value;
	}
}