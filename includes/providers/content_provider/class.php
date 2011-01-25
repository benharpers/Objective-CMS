<?php

class content_provider {
	var $title = "Content";
	var $icon_class = 'content_assembler';
	var $counters = array();
	var $field_list = array('model_id'=>1,'parent_id'=>1,'content_id'=>1,'file_id'=>1,'group_id'=>1,'name'=>1,'enabled'=>1,'sort_order'=>1,'handler'=>1,'date_created'=>1,'date_modified'=>1);

	function start(&$providers) {
		$this->providers = &$providers;
		
		$this->providers->template->registerPlugin('function','get_content',array(&$this,'smarty_get_content'));
		$this->providers->template->registerPlugin('function','get_model',array(&$this,'smarty_get_model'));
		$this->providers->template->registerPlugin('function','get_prev_next',array(&$this,'get_prev_next'));
		$this->providers->template->registerPlugin('function','get_files',array(&$this,'get_files'));
		$this->providers->template->registerPlugin('function','db_query',array(&$this,'db_query'));
		$this->providers->template->registerPlugin('function','db_fetch_one',array(&$this,'db_fetch_one'));
		$this->providers->template->registerPlugin('function','db_fetch_all',array(&$this,'db_fetch_all'));
		$this->providers->template->registerPlugin('block','db_fetch',array(&$this,'db_fetch'),true);
		
		return true;
	}
	
	function db_query($params, &$smarty) {
		$where = array();
		foreach ($params as $k=>$v) if ($this->field_list[$k] == 1) $where[$k] = $v;
		if ($query = $this->providers->db->query($this->providers->db->select($params['table'],'*',$where,$params['order']))) {
			if ($params['assign']) $this->providers->template->assign($params['assign'],$query);
			else return $query;
		}
	}
	
	function db_fetch($params, $content, &$smarty, &$repeat) {
		if ($data = $this->providers->db->fetch($params['query'])) {
			$repeat = 1;
			if ($params['assign']) $this->providers->template->assign($params['assign'],$data);
			else return $data;
		}
		return $content;
	}
	
	function db_fetch_one($params, &$smarty) {
		if ($data = $this->providers->db->fetch($params['query'])) {
			if ($params['assign']) $this->providers->template->assign($params['assign'],$data);
			else return $data;
		}
	}
	
	function db_fetch_all($params, &$smarty) {
		$data = array();
		while ($record = $this->providers->db->fetch($params['query'])) $data[] = $record;
		if ($params['assign']) $this->providers->template->assign($params['assign'],$data);
		else return $data;
	}
	                                                                                                                                                                
	function get_content($where, $order = 'sort_order') {
		if ($model_query = $this->providers->db->query($this->providers->db->select('model','*',$where))) {
			$model = $this->providers->db->fetch($model_query);
			if ($content_query = $this->providers->db->query($this->providers->db->select('content','*',array('model_id'=>$model['model_id']), $order ? $order : 'sort_order'))) {
				while ($content = $this->providers->db->fetch($content_query)) {
					$output[$content['content_id']] = $content;
					if ($content_data_query = $this->providers->db->query($this->providers->db->select(array('content_data cd','model m'=>array('m.model_id'=>'cd.model_id')),array('m.name as nodeName','cd.value as nodeValue'),array('content_id'=>$content['content_id'])))) {
						while ($content_data = $this->providers->db->fetch($content_data_query)) {
							$output[$content['content_id']][$content_data['nodeName']] = $content_data['nodeValue'];
						}
					}
				}
				$this->providers->template->assign('count',$this->providers->db->num_rows($content_query));
				if ($params['content_id']) $output = $output[$params['content_id']];
				if ($params['assign']) $this->providers->template->assign($params['assign'],$output);
				else return $output;
			}
		}
	}

	function smarty_get_content($params, &$smarty) {
		$where = array();
		foreach ($params as $k=>$v) if ($k != 'content_id' && $this->field_list[$k] == 1) $where[$k] = $v;
		if ($output = $this->get_content($where, @$params['order'])) {
			if ($params['content_id']) $output = $output[$params['content_id']];
			if ($params['assign']) $this->providers->template->assign($params['assign'],$output);
			else return $output;
		}
	}

	function get_model($where, $order = 'sort_order') {
		if ($model_query = $this->providers->db->query($this->providers->db->select('model','*',$where, $order ? $order : 'sort_order'))) {
			$this->providers->template->assign('count',$this->providers->db->num_rows($model_query));
			return $this->providers->db->fetch($model_query);
		}
		return false;
	}

	function smarty_get_model($params, &$smarty) {
		$where = array();
		foreach ($params as $k=>$v) if ($this->field_list[$k] == 1) $where[$k] = $v;
		if ($output = $this->get_model($where, @$params['sort_order'])) {
			if ($params['model_id']) $output = $output[$params['model_id']];
			if ($params['assign']) $this->providers->template->assign($params['assign'],$output);
			else return $output;
		}
	}

	function get_prev_next($params, &$smarty) {
		$next = 0; $count = 0;
		foreach ($params['list'] as $id=>$item) {
			$count++;
			if (!$prev) $prev = $id;
			if ($next == 1) break;
			if ($id == $params['current']) { $num = $count; $next = 1; }
			else $prev = $id;
		}
		$this->providers->template->assign($params['prev'],$prev);
		$this->providers->template->assign($params['next'],$id);
		$this->providers->template->assign($params['num'],$num);
	}
	
	function get_files($params, &$smarty) {
		$files = array();
		if ($file_query = $this->providers->db->query($this->providers->db->select('file','*',array('group_id'=>$params['group'])))) {
			while ($file = $this->providers->db->fetch($file_query)) {
				$files[] = $file;
			}
			if ($params['assign']) $this->providers->template->assign($params['assign'],$files);
			else return $output;
		}
	}
}

/*

select cd2.value as `name`, 
	   cd3.value as `date`, 
	   cd4.value as `bride` 
  from content_data cd2 
	   left join content_data cd3 on cd3.content_id = cd2.content_id 
	   left join content_data cd4 on cd4.content_id = cd3.content_id 
 where cd2.model_id = 2 
	   and cd3.model_id = 3 
	   and cd4.model_id = 4;

*/