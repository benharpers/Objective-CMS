<?php

define('DIR_FS_SHARED_ACTIONS',DIR_FS_INCLUDES.'shared_actions'.DIRECTORY_SEPARATOR);

class action_provider {
	var $title = "Action";
	var $icon_class = 'gear';
	var $actions_dir = false;
	var $action = false;
	var $target = false;
	var $target_class = null;

	function start(&$providers = false) {
		$this->providers = &$providers;
		$action = $target = null;
		if (@$_REQUEST['action']) {
			$action = $this->action = $_REQUEST['action'];
			$providers->template->assign('action',@$this->action);
			if (empty($_REQUEST[$this->action])) {
				$target = $this->target = 'null';
			} else {
				$target = $this->target = $_REQUEST[$this->action];
			}
			$providers->template->assign('target',@$this->target);

			if ($providers->restrict == 'back') {
				define('DIR_FS_ACTIONS',DIR_FS_ADMIN.'actions'.DIRECTORY_SEPARATOR);
			} else {
				define('DIR_FS_ACTIONS',DIR_FS_TOP.'actions'.DIRECTORY_SEPARATOR);
			}

			$this->actions_dir = DIR_FS_ACTIONS;
			$this->shared_dir = DIR_FS_SHARED_ACTIONS;

			if (@file_exists($this->actions_dir.$target.'.php')) {
				@include($this->actions_dir.$target.'.php');
			} elseif (@file_exists($this->shared_dir.$target.'.php')) {
				@include($this->shared_dir.$target.'.php');
			} else trigger_error("target \"$target\" does not exist!",256);

			if (@class_exists($target)) {
				$this->target_class = new $target($this->providers);
			} else {
				trigger_error("action \"$file\" failed to load",256);
			}
			return true;
		}
	}
	
	function on_loaded() {
		if (is_object($this->target_class) && $this->target_class->perform($this->action)) {
			if (@$_REQUEST['redirect']) {
				redirect($_REQUEST['redirect']);
			} else {
				redirect();
			}
		} elseif (!empty($result)) {
			trigger_error("action \"$action\" failed to complete",256);
		}
		return true;
	}
}