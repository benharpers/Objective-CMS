<?php

return new story_handler;

class story_handler {
	var $title = "Story";
	var $icon_class = 'story';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = array('tabs_handler','page_handler');
	var $template = 'field';
	var $formats = array('text'=>'{value}',
						 'list'=>'{value_list}',
						 'html'=>'<div class="story_handler">{value}</div>',
						 'field'=>'<textarea class="story_handler" name="{name}">{value_html}</textarea>');

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		if (!$data['name']) $data['name'] = '';
		if (!$data['value']) $data['value'] = '';
		$data['value_html'] = trim($data['value']);
		$data['value_list'] = strlen($data['value']) > 50 ? substr($data['value'],0,49-strpos(strrev(substr($data['value'],0,50)),' ')).'...' : $data['value'];
		return str_replace(preg_replace('/(.+)/','{\1}',array_keys($data)),
						   array_values($data),
						   @$this->formats[$this->formats[$format] ? $format : 'text']);
	}
	
	function get_default($content) {
		return '';
	}

	function set($value,$data = false) {
		return str_replace('"','&quot;',$value);
	}

	function get($value) {
		return ($value);
	}

	function import($value) {
		return $this->set($value);
	}

	function export($value) {
		return $this->get($value);
	}
}