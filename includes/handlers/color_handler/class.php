<?php

return new color_handler;

class color_handler {
	var $title = "Color";
	var $icon_class = 'colors';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = 'page_handler';
	var $loaded = -1;

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		$model = $this->handlers->providers->content->get_model(array('model_id'=>$data['model_id'])); $out='';
		switch ($format) {
			case 'list':
			case 'text':	return $this->get($data['value']);
			case 'html':	return '<div class="color"><span class="color_preview" style="background-color: #'.$data['value'].';"></span>'.$data['title'].'</div>';
			case 'field':	if (++$this->loaded == 0) $out = '<script type="text/javascript" src="'.DIR_WS_TOP.'/includes/handlers/color_handler/farbtastic.js"></script>'."\r\n";
							return '<link rel="stylesheet" href="'.DIR_WS_TOP.'/includes/handlers/color_handler/farbtastic.css" type="text/css" />'."\r\n".
								   '<link rel="stylesheet" href="'.DIR_WS_TOP.'/includes/handlers/color_handler/styles.css" type="text/css" />'."\r\n".
								   $out.
								   '<script type="text/javascript">$(document).ready(function() { var picker = $.farbtastic(\'#picker-'.$data['model_id'].'\',function(color) { $(\'#picker-preview-'.$data['model_id'].'\').attr({style:(\'background-color: \'+color)}); $(\'#'.$data['model_id'].'\').attr({value:color}); }); picker.setColor(\'#'.$data['value'].'\'); });</script>'."\r\n".
								   '<div class="color">'."\r\n".
								       '<div onclick="$(\'#picker-'.$data['model_id'].'\').toggle();"><span id="picker-preview-'.$data['model_id'].'" class="color_preview" style="background-color: #'.$data['value'].';"></span>'.$data['title'].'</div>'."\r\n".
								       '<div id="picker-'.$data['model_id'].'" style="display: none"></div>'."\r\n".
								       '<input type="hidden" id="'.$data['model_id'].'" name="'.$data['name'].'" value="#'.$data['value'].'" />'."\r\n".
								       '<div class="clearer"></div>'."\r\n".
								   '</div>';
			default:		return $this->get($data['value'],$format);
		}
	}
	
	function get_default($content) {
		return 'ffffff';
	}

	function set($value,$data = false) {
		return preg_replace('/[#]{0,1}([0-9A-Fa-f]{3,6})/','\1',$value);
	}

	function get($value,$format = false) {
		return $value;
	}

	function import($value) {
		return preg_replace('/[^#]{0,}[#]{0,1}([^0-9A-Fa-f]{,6}).*/','\1',$value);
	}

	function export($value) {
		return '<div style="background-color: #'.preg_replace('/[#]{0,1}([0-9A-Fa-f]{3,6})/','\1',$value).'; padding: 8px; border: 1px solid #000;"></div>';
	}
}
