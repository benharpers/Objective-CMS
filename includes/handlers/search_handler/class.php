<?php

return new search_handler;

class search_handler {
	var $title = "Search Text";
	var $icon_class = 'text';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = 'page_handler';

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		switch ($format) {
			case 'field':	if ($GLOBALS['search_loaded']++ < 2) $out = '<script type="text/javascript" src="'.DIR_WS_TOP.'/includes/handlers/search_handler/http.js"></script>';
							return $out."\r\n".'<input type="text" class="search_handler_list" id="input'.$data['model_id'].'" name="'.$data['name'].'" onblur="clearThisSearch('.$data['model_id'].');" onkeyup="search_handler_get(event,this,'.$data['model_id'].');" autocomplete="off" value="'.$data['value'].'" />'."\r\n".
								   '<div class="search_handler_list"><ul id="search'.$data['model_id'].'"></ul></div>';
			default:		return $data['value'];
		}
	}
	
	function get_default($content) {
		return '';
	}

	function set($value,$data = false) {
		return str_replace('"','&quot;',$value);
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