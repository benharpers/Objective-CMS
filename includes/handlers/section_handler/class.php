<?php

return new section_handler;

class section_handler {
	var $title = "Section";
	var $icon_class = 'folder';
	var $has_children = true;
	var $is_child = false;
	var $is_convertable = false;
	var $valid_parents = array('','section_handler');
	var $formats = array('text'=>'{value}',
						 'list'=>'{value}',
						 'html'=>'<div class="section_handler">{value}</div>',
						 'field'=>'<input type="text" class="section_handler" name="{name}" value="{value_html}" />');

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		if (!$data['name']) $data['name'] = '';
		if (!$data['value']) $data['value'] = '';
		$val = strip_tags($data['value']);
		$data['value_html'] = ($val != html_entity_decode($val) ? $val : htmlentities($val));
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