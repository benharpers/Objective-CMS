<?php

return new image_handler;

class image_handler {
	var $title = "Image";
	var $icon_class = 'photo';
	var $has_children = false;
	var $is_child = true;
	var $is_convertable = true;
	var $valid_parents = 'page_handler';
	var $formats = array('text'=>'{value}',
						 'list'=>'{value}',
						 'html'=>'<img class="image_handler" src="{value}" alt="" />',
						 'field'=>'<div title="{value}"><input type="hidden" class="image_handler" name="{name}" value="{id}" /><input type="file" class="image_handler" name="{id}" value="{value}" /></div>');

	function start(&$handlers = false) {
		$this->handlers = &$handlers;
	}

	function format($data, $format = 'text') {
		if (!$data['name']) return false;
		if ($data['value'] && $value = $this->get($data['value'])) {
			$data['value'] = $value['name'];
			$data['id'] = $value['id'];
		} else {
			$data['value'] = '';
			$data['id'] = abs(crc32(time().rand(1000,9999)));
		}
		return str_replace(preg_replace('/(.+)/','{\1}',array_keys($data)),
						   array_values($data),
						   @$this->formats[$this->formats[$format] ? $format : 'text']);
	}
	
	function get_default($content) {
		return '';
	}

	function set($value,$data = false) {
		if ($_FILES[$value]) {
			$ext = substr($_FILES[$value]['name'],0-strpos(strrev($_FILES[$value]['name']),'.'));
			if (move_uploaded_file($_FILES[$value]['tmp_name'],DIR_FS_CONTENT.$value.'.'.$ext)) {
				$data = array('id'=>$value,'file'=>$value.'.'.$ext,'name'=>$_FILES[$value]['name']);
				return serialize($data);
			}
		}
		return false;
	}

	function get($value) {
		if ($data = unserialize($value)) return $data;
		return false;
	}

	function import($value) {
		$values = explode('::',$value);
		$ext = substr($value,0-strpos(strrev($value),'.'));
		$data = array('id'=>$values[0],'file'=>$values[0].'.'.$ext,'name'=>$values[1]);
		if (file_exists(DIR_FS_CONTENT.$data['file'])) {
			return serialize($data);
		}
		return false;
	}

	function export($value) {
		if ($data = unserialize($value)) {
			return $data['id'].'::'.$data['name'];
		}
		return false;
	}
}