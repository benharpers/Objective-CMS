<?php

return new styled_handler;

class styled_handler {
	var $title = "Styled Story";
	var $icon_class = 'styled_story';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = array('tabs_handler','page_handler');
	var $template = 'field';
	var $formats = array('text'=>'{value}',
						 'list'=>'{value_list}',
						 'html'=>'<div class="styled_handler">{value}</div>',
						 'field'=>'<textarea class="styled_handler" id="{name}" name="{name}">{value_html}</textarea>');
	var $loaded = false;

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		$head = '';
		if (!$data['name']) $data['name'] = '';
		if (!$data['value']) $data['value'] = '';
		$data['value_html'] = str_replace(array('<','>'),array('&lt;','&gt;'),trim($data['value']));
		$data['value_list'] = strlen($data['value']) > 50 ? substr($data['value'],0,49-strpos(strrev(substr($data['value'],0,50)),' ')).'...' : $data['value'];
		$html = str_replace(preg_replace('/(.+)/','{\1}',array_keys($data)),
							array_values($data),
							@$this->formats[$this->formats[$format] ? $format : 'text']);
		if ($format == 'field') {
			if (!$this->loaded) {
				$head = '<script type="text/javascript" src="'.DIR_WS_TOP.'/includes/handlers/styled_handler/tiny_mce/tiny_mce.js"></script>'."\r\n";
				$this->loaded = true;
			}
			$head .= '<script type="text/javascript">'."\r\n".'tinyMCE.init({mode:"exact",elements:"'.$data['name'].'",theme:"advanced"});'."\r\n".'</script>'."\r\n";
		}
		return $head.$html;
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