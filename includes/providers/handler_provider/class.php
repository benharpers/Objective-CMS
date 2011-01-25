<?php

define('DIR_FS_HANDLERS',DIR_FS_INCLUDES.'handlers'.DIRECTORY_SEPARATOR);

class handler_provider {
	var $title = "Handler";
	var $icon_class = 'info';
	var $handlers_dir = false;
	var $handlers = array();
	var $handlers_list = array();

	function start(&$providers = false) {
		$this->providers = &$providers;
		$this->handlers_dir = DIR_FS_HANDLERS;
		$dir = dir($this->handlers_dir);
		$handlers = array();
		while ($handler = basename($dir->read())) {
			if (substr($handler,0,1) == '.') continue;
			$path = "$this->handlers_dir$handler".DIRECTORY_SEPARATOR.'class.php';
			if (!file_exists($path)) trigger_error("handler \"$handler\" does not have a class.php file",256);
			elseif ($this->handlers[$handler] = @include_once($path)) {
				$this->handlers[$handler]->class = $handler;
				$this->handlers[$handler]->start($this);
			} else trigger_error("handler \"$handler\" failed to load",256);
		}
		$providers->template->assign('handlers',$this->handlers);
		$providers->template->registerPlugin('function','handler_format',array(&$this,'handler_format'));
		$providers->template->registerPlugin('function','get_handler',array(&$this,'get_handler'));
		$providers->template->registerPlugin('function','select_handler',array(&$this,'select_handler'));
		return true;
	}
	
	function format($handler, $data, $format = 'text') {
		if ($this->handlers[$handler])
			return $this->handlers[$handler]->format($data, $format);
		else
			trigger_error("handler \"$handler\" does not exist",256);
	}
	
	function handler_format($params, &$smarty) {
		if (!$params['content'] || !$params['format']) trigger_error('format tag requires "content" and "format" parameters',256);
		elseif (!$params['content']['handler']) {
			$model = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('model','*',array('model_id'=>$params['content']['model_id']))));
			foreach ($model as $k=>$v) $params['content'][$k] = $v;
		}
		foreach ($params as $key=>$value) $params['content'][$key] = $value;
		if ($prefix = $params['prefix']) $params['content']['name'] = $prefix.'['.$params['content']['name'].']';
		$output = $this->format($params['content']['handler'],$params['content'],$params['format']);
		if (@$params['assign']) $smarty->assign($params['assign'],$output);
		else return $output;
	}

	function get_handler($params, &$smarty) {
		if (@$params['handler']) {
			$handler = @$params['handler'];
		} elseif ($params['data']) {
			$model = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('model','*',array('model_id'=>$params['data']['model_id']))));
			$handler = $model['handler'];
		}
		if (@$params['assign'] && $this->handlers[$handler]) {
			$smarty->assign($params['assign'],$this->handlers[$handler]);
		}
	}

	function select_handler($params, &$smarty) {
		if (!$params['name']) trigger_error('select_handler tag requires "name" parameter',256);
		$output = '<select name="'.$params['name'].'">';
		foreach ($this->handlers as $key=>$handler) {
			if ($params['is_convertable'] && !$handler->is_convertable) continue;
			if (in_array($params['parent'],(array) $handler->valid_parents)) {
				$output .= '<option value="'.$key.'"'.(@$params['selected'] == $key ? ' selected="selected"' : '').'>'.$handler->title.'</option>';
			}
		}
		$output .= '</select>';
		if (@$params['assign']) $smarty->assign($params['assign'],$output);
		else return $output;
	}
}