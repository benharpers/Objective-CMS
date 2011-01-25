<?php

class file_get {
	function perform(&$providers) {
		if (file_exists(DIR_FS_CONTENT.$_REQUEST['file_id'])) {
			readfile(DIR_FS_CONTENT.$_REQUEST['file_id']);
			exit();
		}
	}
}
