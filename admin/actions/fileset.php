<?php

class fileset {
	var $providers;
	
	function fileset(&$providers) {
		$this->providers = $providers;
	}
	
	function perform($action) {
		if ($_REQUEST[$_REQUEST[$action]] && method_exists($this,$action)) {
			if ($this->$action($_REQUEST[$_REQUEST[$action]])) {
				redirect('index?'.make_url_params($_GET,array('action'=>false,$action=>false,$_REQUEST[$action]=>false),'&'));
			}
		}
		return false;
	}

	function get($group_id) {
		if ($files_query = $this->providers->db->query($this->providers->db->select('file','*',array('group_id'=>$group_id),'sort_order'))) {
			$files = array();
			while ($file = $this->providers->db->fetch($files_query)) {
				$ext = preg_replace('/(.+)\.([a-z0-9]{3,4})/','\2',$file['name']);
				switch ($ext) {
					case 'jpeg':
					case 'jpg':		$file['type'] = 'JPEG Image'; break;
					case 'psb':
					case 'psd':		$file['type'] = 'Photoshop Document'; break;
					case 'png':		$file['type'] = 'PNG Image'; break;
					case 'gif':		$file['type'] = 'GIF Image'; break;
					case 'flv':		$file['type'] = 'Flash Video'; break;
					case 'fla':		$file['type'] = 'Flash Audio'; break;
					case 'swf':		$file['type'] = 'Flash Movie'; break;
					case 'fp6':
					case 'fp7':		$file['type'] = 'FileMaker Pro Database'; break;
					case 'xls':		$file['type'] = 'Microsoft Excel Document'; break;
					case 'doc':		$file['type'] = 'Microsoft Word Document'; break;
					case 'tsv':
					case 'tab':		$file['type'] = 'Tab Delimited Text Document'; break;
					case 'csv':		$file['type'] = 'Comma Delimited Text Document'; break;
					case 'txt':
					case 'text':	$file['type'] = 'Text Document'; break;
					case 'htm':
					case 'html':	$file['type'] = 'HTML Document'; break;
					case 'xml':		$file['type'] = 'XML Document'; break;
					case 'plist':	$file['type'] = 'Parameter List Document'; break;
					case 'css':		$file['type'] = 'Stylesheet'; break;
					case 'jar':		$file['type'] = 'Java Applet'; break;
					case 'js':		$file['type'] = 'Javascript'; break;
					case 'sit':		$file['type'] = 'StuffIt! Archive'; break;
					case 'dmg':		$file['type'] = 'Disk Image'; break;
					case 'zip':		$file['type'] = 'Zip Archive'; break;
					case 'gz':		$file['type'] = 'GZip Archive'; break;
					case 'tar':		$file['type'] = 'Tar Archive'; break;
					case 'tgz':		$file['type'] = 'GZipped Tar Archive'; break;
					default:		$file['type'] = 'Document'; break;
				}
				$files[] = $file;
			}
			echo '('.js_serialize($files).')';
			exit();
		}
		echo '-1';
		exit();
	}

	function upload($group_id) {
		$file = array_pop($_FILES);
		if ($file['tmp_name'] && $tmp_id = $_REQUEST['tmp_id']) {
			if ($contents = file_get_contents($file['tmp_name'])) {
				if ($out = fopen(DIR_FS_CONTENT_CACHE.$tmp_id,'wb')) {
					fwrite($out,$contents);
					fclose($out);
					if (@$_REQUEST['jupart']) {
						echo 'SUCCESS';
						exit();
					} else {
						$this->upload_complete($file, $group_id, $tmp_id);
					}
				} else {
					fclose($out);
					unlink(DIR_FS_CONTENT_CACHE.$tmp_id);
					echo 'ERROR: Could not write file data.';
				}
			} else {
				echo 'ERROR: Could not read file data.';
			}
		} else {
			echo 'ERROR: No file data.';
		}
		exit();
	}
	
	function upload_complete($file, $group_id, $tmp_id) {
		$file_array = array('group_id'=>$group_id,'name'=>urldecode(rawurldecode($file['name'])));
		if ($file_id = $this->providers->db->get_insert_id($this->providers->db->query($this->providers->db->insert('file',$file_array)))) {
			rename(DIR_FS_CONTENT_CACHE.$tmp_id,DIR_FS_CONTENT.$file_id);
			echo 'SUCCESS';
			exit();
		} else {
			echo 'ERROR: Could not save completed file.';
		}
	}

	function update($file_id) {
		if ($_REQUEST['file']) {
			if ($this->providers->db->query($this->providers->db->update('file',$_REQUEST['file'],array('group_id'=>$_REQUEST['group_id'],'file_id'=>$file_id)))) {
				return '';
			}
		}
		echo 'ERROR: File not found';
		exit();
	}

	function sort($direction) {
		$order = 0;
		$file_query = $this->providers->db->query($this->providers->db->select('file','*',array('group_id'=>$_REQUEST['group_id']),'sort_order'));
		while ($file = $this->providers->db->fetch($file_query)) {
			$this->providers->db->query($this->providers->db->update('file',array('sort_order'=>++$order),array('group_id'=>$_REQUEST['group_id'],'file_id'=>$file['file_id'])));
		}
		$file_query = $this->providers->db->select('file','*',array('group_id'=>$_REQUEST['group_id'],'file_id'=>$_REQUEST['file_id']));
		if ($file = $this->providers->db->fetch($this->providers->db->query($file_query))) {
			$switch_query = $this->providers->db->select('file','*',array('group_id'=>$_REQUEST['group_id'],'sort_order'=>($direction == 'up' ? $file['sort_order']-1 : $file['sort_order']+1)));
			if ($switch = $this->providers->db->fetch($this->providers->db->query($switch_query))) {
				$this->providers->db->query($this->providers->db->update('file',array('sort_order'=>$switch['sort_order']),array('group_id'=>$_REQUEST['group_id'],'file_id'=>$file['file_id'])));
				$this->providers->db->query($this->providers->db->update('file',array('sort_order'=>$file['sort_order']),array('group_id'=>$_REQUEST['group_id'],'file_id'=>$switch['file_id'])));
				echo $_REQUEST['group_id'];
				exit();
			}
		}
		echo '-1';
		exit();
	}

	function remove($file_id) {
		if ($files_query = $this->providers->db->query($this->providers->db->delete('file',array('group_id'=>$_REQUEST['group_id'],'file_id'=>$file_id)))) {
			echo $_REQUEST['group_id'];
			exit();
		}
		echo '-1';
		exit();
	}
}
