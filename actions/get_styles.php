<?php

class get_styles {
	function perform(&$providers) {
		$directory = DIR_FS_STYLES.'front'.DIRECTORY_SEPARATOR;
		$modified = time();

		if ($dir = @dir($directory)) {
			while ($file = $dir->read()) {
				if (substr($file,-3) == 'css') {
					$files[$file] = file_get_contents($directory.$file);
					$time = filemtime($directory.$file);
					if ($time < $modified) $modified = $time;
				}
			}
			ksort($files);
			$out = implode('',$files);

			header('Date: '.gmdate('D, d M Y H:i:s').' GMT');
			header('Last-Modified: '.$modified.' GMT');
			header('Expires: '.gmdate('D, d M Y H:i:s', strtotime('+1 day')).' GMT');
			header("Pragma: cache");
			header("Cache-Control: max-age=86400,public");
			header('ETag: '.md5($out));
			header("Content-type: text/css");
			header("Content-length: ".strlen($out));

			echo $out;
			exit();
		}
	}
}
