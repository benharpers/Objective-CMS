<?php

class content {
	var $providers;
	
	function content(&$providers) {
		$this->providers = $providers;
	}
	
	function perform($action) {
		if ($_REQUEST[$_REQUEST[$action]] && method_exists($this,$action)) {
			if ($this->$action($_REQUEST[$_REQUEST[$action]])) {
				redirect('index?'.make_url_params($_GET,array('go'=>'edit',$action=>false,$_REQUEST[$action]=>false),'&'));
			}
		}
		return false;
	}

	function insert($content) {
		if ($content) {
			if (empty($content['enabled'])) $content['enabled'] = 1;
			if (!$content['sort_order']) $content['sort_order'] = 99999;
			if (!$content['parent_id']) $content['parent_id'] = $_REQUEST['parent'];
			$content['date_created'] = $content['date_modified'] = UNIXTIME_NOW;
			if ($id = $this->providers->db->get_insert_id($this->providers->db->query($this->providers->db->insert('content',$content)))) {
				$this->insert_tree($content['model_id'], $id);
				redirect('index?'.make_url_params($_GET,array('id'=>$id),'&'));
			}
		}
		return false;
	}

	function insert_tree($model_id, $content_id) {
		if ($model = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('model','*',array('model_id'=>$model_id))))) {
			if (@$model['handler'] && $handler = &$this->providers->handlers->handlers[$model['handler']]) {
				if ($handler->has_children) {
					if ($submodel_query = $this->providers->db->query($this->providers->db->select('model','*',array('parent_id'=>$model_id)))) {
						while($submodel = $this->providers->db->fetch($submodel_query)) $this->insert_tree($submodel['model_id'], $content_id);
					}
				} else {
					$content_data = array('content_id'=>$content_id,'model_id'=>$model_id,'value'=>$handler->get_default(''));
					$this->providers->db->query($this->providers->db->insert('content_data',$content_data));
				}
			} else {
				trigger_error('The handler '.$model['handler'].' used by "'.$model['title'].'" is missing!',256);
			}
		}
	}

	function update($content) {
		if (@$content['data']) {
			foreach ($content['data'] as $model_id=>$value) {
				if ($model = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('model','handler',array('model_id'=>$model_id))))) {
					if ($model['handler'] && $this->providers->handlers->handlers[$model['handler']]) {
						$value = $this->providers->handlers->handlers[$model['handler']]->set($value);
					}
					if ($value !== false) $this->providers->db->query($this->providers->db->replace('content_data',array('content_id'=>$content['content_id'],'model_id'=>$model_id,'value'=>$value)));
				}
			}
			unset($content['data']);
		}
		if (!$_REQUEST['model']) return true;
		return false;
	}

	function delete($id) {
		if ($id) {
			$this->delete_tree($id);
			return true;
		}
		return false;
	}
	
	function delete_tree($id) {
		if ($id) {
			$this->providers->db->query($this->providers->db->delete('content',array('content_id'=>$id)));
			$this->providers->db->query($this->providers->db->delete('content_data',array('content_id'=>$id)));
			if ($content_query = $this->providers->db->query($this->providers->db->select('content','content_id',array('parent_id'=>$id)))) {
				while ($content = $this->providers->db->fetch($content_query)) $this->delete_tree($content['content_id']);
			}
		}
	}

	function sort($direction) {
		if ($direction) {
			if ($sort_order = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('content','sort_order',array('content_id'=>$_REQUEST['id']))))) {
				$this->providers->db->query($this->providers->db->update('content',array('sort_order'=>$sort_order['sort_order']+($direction == 'up' ? -15 : 15)),array('content_id'=>$_REQUEST['id'])));
				if ($content_query = $this->providers->db->query($this->providers->db->select('content','content_id',array('parent_id'=>$_REQUEST['parent']),'sort_order'))) {
					$x = 0;
					while ($content = $this->providers->db->fetch($content_query)) {
						$x+=10;
						$this->providers->db->query($this->providers->db->update('content',array('sort_order'=>$x),array('content_id'=>$content['content_id'],'parent_id'=>$_REQUEST['parent'])));
					}
					return true;
				}
			}
		}
		return false;
	}

	function status($status) {
		if ($status) {
			$this->providers->db->query($this->providers->db->update('content',array('enabled'=>($status == 'enable' ? 1 : '0')),array('content_id'=>$_REQUEST['id'])));
			return true;
		}
		return false;
	}
}