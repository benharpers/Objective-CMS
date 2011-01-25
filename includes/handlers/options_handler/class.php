<?php

return new options_handler;

class options_handler {
	var $title = "Option List";
	var $icon_class = 'option';
	var $has_children = false;
	var $is_child = false;
	var $is_convertable = false;
	var $valid_parents = array('page_handler');

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		if (!$data['name']) $data['name'] = '';
		if (!$data['value']) $data['value'] = '';
		$value_title = $this->handlers->providers->db->fetch($this->handlers->providers->db->query($this->handlers->providers->db->select('model','*',array('parent_id'=>$data['model_id'],'name'=>$data['value']),false,false,1)));
		switch ($format) {
			case 'field':	$values = array();
							$values_query = $this->handlers->providers->db->query($this->handlers->providers->db->select('model','*',array('parent_id'=>$data['model_id']),'sort_order'));
							while ($value = $this->handlers->providers->db->fetch($values_query)) $values[] = '<option value="'.htmlentities($value['name']).'"'.($value['name'] == $data['value'] ? ' selected="selected"' : '').'>'.htmlentities($value['title']).'</option>';
							return '<select class="options_handler" name="'.$data['name'].'">'.implode('',$values).'</select>';
			case 'html':	return '<div class="options_handler">'.htmlentities($value_title).'</div>';
			default:		return $value_title;
		}
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