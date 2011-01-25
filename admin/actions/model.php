<?php

class model {
	var $providers;
	
	function model(&$providers) {
		$this->providers = $providers;
	}
	
	function perform($action) {
		if ($_REQUEST[$_REQUEST[$action]] && method_exists($this,$action)) {
			if ($this->$action($_REQUEST[$_REQUEST[$action]])) {
				redirect('index?'.make_url_params($_GET,array('action'=>false,'go'=>false,$action=>false,$_REQUEST[$action]=>false),'&'));
			}
		}
		return false;
	}

	function insert($model) {
		if ($model) {
			if (empty($model['enabled'])) $model['enabled'] = 1;
			if (!$model['sort_order']) $model['sort_order'] = 99999;
			if (!$model['parent_id']) $model['parent_id'] = $_REQUEST['parent'];
			$model['date_created'] = $model['date_modified'] = UNIXTIME_NOW;
			if ($id = $this->providers->db->get_insert_id($this->providers->db->query($this->providers->db->insert('model',$model)))) {
				redirect('index?'.make_url_params($_GET,array('action'=>false,'go'=>false,'id'=>$id),'&'));
			}
		}
		return false;
	}

	function update($model) {
		if ($model) {
			$model['date_modified'] = UNIXTIME_NOW;
			return $this->providers->db->query($this->providers->db->update('model',$model,array('model_id'=>$model['model_id'])));
		}
		return false;
	}

	function delete($id) {
		if ($id) {
			$this->delete_tree($id);
			redirect('index?'.make_url_params($_GET,array('action'=>false,'delete'=>false,'model'=>false,'id'=>false),'&'));
		}
		return false;
	}
	
	function delete_tree($id) {
		if ($id) {
			$this->providers->db->query($this->providers->db->delete('model',array('model_id'=>$id)));
			if ($model_query = $this->providers->db->query($this->providers->db->select('model','model_id',array('parent_id'=>$id)))) {
				while ($model = $this->providers->db->fetch($model_query)) $this->delete_tree($model['model_id']);
			}
			$this->providers->db->query($this->providers->db->delete('content',array('model_id'=>$id)));
			$this->providers->db->query($this->providers->db->delete('content_data',array('model_id'=>$id)));
		}
	}

	function sort($direction) {
		if ($direction) {
			if ($sort_order = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('model','sort_order',array('model_id'=>$_REQUEST['id']))))) {
				$this->providers->db->query($this->providers->db->update('model',array('sort_order'=>$sort_order['sort_order']+($direction == 'up' ? -15 : 15)),array('model_id'=>$_REQUEST['id'])));
				if ($model_query = $this->providers->db->query($this->providers->db->select('model','model_id',array('parent_id'=>$_REQUEST['parent']),'sort_order'))) {
					$x = 0;
					while ($model = $this->providers->db->fetch($model_query)) {
						$x+=10;
						$this->providers->db->query($this->providers->db->update('model',array('sort_order'=>$x),array('model_id'=>$model['model_id'],'parent_id'=>$_REQUEST['parent'])));
					}
					return true;
				}
			}
		}
		return false;
	}

	function status($status) {
		if ($status) {
			$this->providers->db->query($this->providers->db->update('model',array('enabled'=>($status == 'enable' ? 1 : '0')),array('model_id'=>$_REQUEST['id'])));
			return true;
		}
		return false;
	}

	function convert($model) {
		if ($model['model_id'] && $model['handler']) {
			if ($old_data = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('model','handler',array('model_id'=>$model['model_id']))))) {
				if ($this->providers->handlers->handlers[$old_data['handler']]) {
					$old = &$this->providers->handlers->handlers[$old_data['handler']];
				} else {
					$old = &$this->providers->handlers->handlers['title_handler'];
				}
				$new = &$this->providers->handlers->handlers[$model['handler']];
				if ($new->is_convertable && $old->is_convertable) {
					if ($this->providers->db->query($this->providers->db->update('model',array('handler'=>$model['handler'],'date_modified'=>UNIXTIME_NOW),array('model_id'=>$model['model_id'])))) {
						if ($data_query = $this->providers->db->query($this->providers->db->select('content_data','*',array('model_id'=>$model['model_id'])))) {
							while ($data = $this->providers->db->fetch($data_query)) {
								$this->providers->db->query($this->providers->db->update('content_data',array('value'=>($new->import($old->export($data['value'])))),$data));
							}
						} else {
							trigger_error('No Data!',256);
						}
						return true;
					} else {
						print_r($model);die();
					}
				} else {
					trigger_error('Cannot convert from '.$old->title.' to '.$new->title.'!',256);
				}
			}
		}
		return false;
	}
}
