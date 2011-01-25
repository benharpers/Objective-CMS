<?php

require 'libs/Smarty.class.php';

class template_provider extends Smarty {
	var $title = "Template";
	var $compile_check = true;
	var $cache_modified_check = true;
	var $debugging = false;
	var $icon_class = 'smarty';
	var $fields = array('model' => array('parent_id'=>'m','model_id'=>'m','handler'=>'m','name'=>'m'),
						'content' => array('parent_id'=>'c','content_id'=>'c','model_id'=>'cd','handler'=>'m','name'=>'m'));
	var $counters = array();

	function start(&$providers) {
		$this->providers = &$providers;
		
		if ($providers->restrict == 'back') {
			$this->template_dir = DIR_FS_ADMIN.'templates';
			$this->compile_dir = DIR_FS_ADMIN.'templates_c';
		} else {
			$this->template_dir = DIR_FS_TOP.'templates';
			$this->compile_dir = DIR_FS_TOP.'templates_c';
		}

		$this->request_path = substr(REQUEST_PATH,-1) == '/' ? REQUEST_PATH.'index' : REQUEST_PATH;

		if (dirname($this->request_path) == '/') $path = '';
		else $path = preg_replace('/^[\/]{0,1}(.*)$/','$1',dirname($this->request_path));
		
		$this->assign('path',$path);
		$this->assign('relative_path',DIR_WS_DIR);
		$this->assign('top_path',DIR_WS_TOP);
		$this->assign('page',basename($this->request_path));
		$this->assign('date',date(DATE_FORMAT));
		$this->assign('providers',$this->providers->providers);
		$this->registerPlugin('modifier','date_format',array(&$this,'date_format'));
		$this->registerPlugin('function','make_id',array(&$this,'make_id'));
		$this->registerPlugin('function','request_params',array(&$this,'request_params'));
		$this->registerPlugin('function','post_params',array(&$this,'post_params'));
		$this->registerPlugin('function','get_params',array(&$this,'get_params'));
		$this->registerPlugin('function','hidden_request_fields',array(&$this,'hidden_request_fields'));
		$this->registerPlugin('function','hidden_post_fields',array(&$this,'hidden_post_fields'));
		$this->registerPlugin('function','hidden_get_fields',array(&$this,'hidden_get_fields'));
		$this->registerPlugin('function','dump_vars',array(&$this,'dump_vars'));
		//$this->load_filter('output','trimwhitespace');
		return true;
	}

	function date_format($date, $inc_time = true) {
		if ($date && $inc_time && date('his',$date) != '120000') $inc_time = ' @ g:i A';
		if (strlen($date) < 6) return 'New record';
		if (date('Ymd',$date) == date('Ymd')) return 'Today'.($inc_time ? date($inc_time,$date) : '');
		elseif (date('Ymd',$date) > date('Ymd')) {
			if (date('Ymd',$date) == date('Ymd',strtotime("tomorrow"))) return 'Tomorrow '.($inc_time ? date($inc_time,$date) : '');
			elseif (date('Ymd',$date) < date('Ymd',strtotime("+1 week"))) return 'Next '.date("l$inc_time",$date);
			elseif (date('Ymd',$date) < date('Ym01',strtotime("next month"))) return date("jS$inc_time",$date).', this Month';
			else return date(DATE_FORMAT.$inc_time,$date);
		} elseif (date('Ymd',$date) == date('Ymd',strtotime("yesterday"))) return 'Yesterday'.($inc_time ? date($inc_time,$date) : '');
		elseif (date('Ymd',$date) > date('Ymd',strtotime("-1 week"))) return 'Last '.date("l$inc_time",$date);
		elseif (date('Ymd',$date) >= date('Ym01',strtotime("this month"))) return date("jS$inc_time",$date).', this Month';
		elseif (date('Y',$date) == date('Y')) return date("l, F jS$inc_time",$date);
		else return date(DATE_FORMAT.$inc_time,$date);
	}
	
	function make_id($params, &$smarty) {
		if (@$params['from']) $number = abs(crc32(serialize($params['from'])));
		else $number = abs(crc32(time().rand(1000,9999)));
		if (@$params['assign']) $smarty->assign($params['assign'],$number);
		else return $number;
	}

	function request_params($params, &$smarty) {
		return make_url_params($_REQUEST,$params);
	}

	function post_params($params, &$smarty) {
		return make_url_params($_POST,$params);
	}

	function get_params($params, &$smarty) {
		return make_url_params($_GET,$params);
	}

	function hidden_request_fields($params, &$smarty) {
		return make_hidden_fields($_REQUEST,$params);
	}

	function hidden_post_fields($params, &$smarty) {
		return make_hidden_fields($_POST,$params);
	}

	function hidden_get_fields($params, &$smarty) {
		return make_hidden_fields($_GET,$params);
	}

	function dump_vars($params, &$smarty, $tab = "<br />\r\n") {
		$out = '';
		foreach ($params as $key=>$value) if ($value) $out .= is_array($value) ? ("$tab$key => Array(".$this->dump_vars($value, $smarty, "$tab &nbsp; &nbsp; &nbsp;")."$tab)") : "$tab$key => $value";
		return $out;
	}
}

function make_url_params($params, $replace = false, $amp = '&amp;') {
	if ($replace) foreach ($replace as $k=>$v) if ($v === false) unset($params[$k]); else $params[$k] = $v;
	if (!@$replace['action'] && @$params['action']) unset($params['action']);
	if (!@$replace['go'] && @$params['go']) unset($params['go']);
	if (!@$replace['page'] && @$params['page']) unset($params['page']);
	return array_to_params($params, false, $amp);
}

function array_to_params($params = false, $parent_key = false, $amp = '&amp;') {
	$out = array();
	if ($params) foreach ($params as $key=>$value) $out[] = (is_array($value) ? array_to_params($value,$key,$amp) : ($parent_key ? $parent_key."[$key]=$value" : "$key=$value"));
	return implode($amp,$out);
}

function make_hidden_fields($params, $replace = false) {
	if ($replace) foreach ($replace as $k=>$v) if ($v === false) unset($params[$k]); else $params[$k] = $v;
	return array_to_fields($params);
}

function array_to_fields($params = false, $parent_key = false, $type = 'hidden') {
	$out = '';
	if ($params) foreach ($params as $key=>$value) $out .= (is_array($value) ? array_to_fields($value,$key,$type) : ("<input type=\"$type\" name=\"".($parent_key ? $parent_key.'['.$key.']' : $key)."\" value=\"$value\" />"));
	return $out;
}

function js_serialize($array) {
	$out = array();
	foreach ($array as $k=>$v) {
		if (is_numeric($k)) $key = $k;
		else $key = "'$k'";
		if (is_array($v)) $out[] = $key.':'.js_serialize($v);
		elseif (is_numeric($v)) $out[] = "$key:$v";
		else $out[] = $key.':"'.rawurlencode(utf8_encode($v)).'"';
	}
	return '{'.implode(',',$out).'}';
}