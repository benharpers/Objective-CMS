<?php

return new fileset_handler;

class fileset_handler {
	var $title = "File Collection";
	var $icon_class = 'upload';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = false;
	var $valid_parents = array('tabs_handler','page_handler');
	var $template = 'field';

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		$group_id = $data['content_id'] ? $this->getID($data) : abs(crc32(time()));
		if ($format == 'field') {
			if ($GLOBALS['fileset_loaded']++ < 2) $out = '<script type="text/javascript" src="'.DIR_WS_TOP.'/includes/handlers/file_handler/scripts.js"></script>';
			return $out.'<input type="hidden" name="'.$data['name'].'" value="'.$group_id.'" />'."\r\n".
				   '<div class="listing"><div class="contents"><div class="table"><table id="fileset'.$group_id.'" class="file_handler_list listing_content" cellpadding="0" cellspacing="0"><tr class="header"><th colspan="5">File</th><th class="type">Type</th><th class="desc">Description</th></tr></table><script type="text/javascript">filesets.init('.$group_id.');</script>'."\r\n".
				   '<div class="icon upload file_handler" onclick="popup_editor(\''.DIR_WS_TOP.'/admin/edit/fileset_upload?group_id='.$group_id.'\');">Add Files...</div></div></div></div>';
		} else {
			return implode(', ',$files);
		}
	}
	
	function get_default($content) {
		return '';
	}

	function set($value,$data = false) {
		if ($_REQUEST['action'] == 'insert_content') $this->handlers->providers->db->query($this->handlers->providers->db->update('file',array('group_id'=>$this->getID($data)),array('group_id'=>$value)));
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
	
	function getID($data) {
		return abs(crc32($data['content_id'].$data['model_id']));
	}
}