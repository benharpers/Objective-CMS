<?php

class image {
	var $providers;
	var $max_size = 1920;
	
	function image(&$providers) {
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

	function get($file_id) {
		$file = DIR_FS_CONTENT.$file_id;
		if (@file_exists($file) && $type = @exif_imagetype($file)) {
			header("Content-type: ".image_type_to_mime_type($type));

			switch($type) {
				case IMAGETYPE_GIF:		if (function_exists('imagecreatefromgif')) $img = @imagecreatefromgif($file); break;
				case IMAGETYPE_JPEG:	if (function_exists('imagecreatefromjpeg')) $img = @imagecreatefromjpeg($file); break;
				case IMAGETYPE_PNG:		if (function_exists('imagecreatefrompng')) $img = @imagecreatefrompng($file); break;
				default:				$this->getfile($file_id); exit();
			}

			if (!$img) { exit(readfile($file)); }

			list($width,$height,$swidth,$sheight,$cwidth,$cheight,$cleft) = $this->getsize(@$_REQUEST['width'],@$_REQUEST['height'],imagesx($img),imagesy($img));

			$modified = filemtime($file);
			$modified_date = gmdate(DATETIME_FORMAT, $modified);
			$etag = md5($modified_date);
			$cache = DIR_FS_CONTENT_CACHE.abs(crc32($file.$cleft.$width.$height.$cwidth.$cheight.$modified));

			if (!@file_exists($cache)) {
				$canvas = imagecreatefromgd2part(DIR_FS_INCLUDES.'transparency.gd2',0,0,$cwidth,$cheight);
				imagecopyresampled($canvas,$img,0,0,$cleft,0,$width,$height,$swidth,$sheight);
				imagesavealpha($canvas,true);
				switch ($type) {
					case IMAGETYPE_PNG:	imagepng($canvas,$cache); break;
					case IMAGETYPE_GIF:	imagegif($canvas,$cache); break;
					default:			imagejpeg($canvas,$cache,75); break;
				}
			}

			header('Date: '.DATETIME_NOW.' GMT');
			header('Last-Modified: '.$modified_date.' GMT');
			header('Expires: '.gmdate(DATETIME_FORMAT, strtotime('+86400 seconds',UNIXTIME_NOW)).' GMT');
			header("Pragma: cache");
			header("Cache-Control: max-age=86400,public");
			header('ETag: '.$etag);

			if (@$_SERVER['HTTP_IF_MODIFIED_SINCE'] == $modified_date || @$_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
				if (php_sapi_name()=='cgi') header('Status: 304 Not Modified');
				else header('HTTP/1.1 304 Not Modified');
			}
			header('Content-length: '.filesize($cache));
			readfile($cache);

			exit();
		} else {
			$this->getfile($file_id);
		}
	}
	
	function getfile($fileid) {
			$file_info = $this->providers->db->fetch($this->providers->db->query($this->providers->db->select('file','*',array('file_id'=>$fileid))));
			preg_match('/(.*)[.]([a-zA-Z0-9]{3,4})$/',$file_info['name'],$filename);

			list($width,$height,$swidth,$sheight,$cwidth,$cheight,$cleft) = $this->getsize(@$_REQUEST['width'],@$_REQUEST['height'],128,128);

			if (@$filename[2] && file_exists(DIR_FS_ICONS.$filename[2].'.png')) {
				$img = imagecreatefrompng(DIR_FS_ICONS.$filename[2].'.png');
			} else {
				$img = imagecreatefrompng(DIR_FS_ICONS.'unknown.png');
			}

			$canvas = imagecreatefromgd2part(DIR_FS_INCLUDES.'transparency.gd2',0,0,$cwidth,$cheight);

			imagecopyresampled($canvas,$img,0,0,$cleft,0,$width,$height,$swidth,$sheight);
			imagesavealpha($canvas,true);
			header("Content-type: image/png");
			imagepng($canvas);
			exit();
	}

	function getsize($reqwidth,$reqheight,$imgwidth,$imgheight) {
		if ($reqheight > $this->max_size) {
			$reqwidth = $reqwidth/$reqheight*$this->max_size;
			$reqheight = $this->max_size;
		}
		if ($imgheight > $this->max_size) {
			$imgwidth = $imgwidth/$imgheight*$this->max_size;
			$imgheight = $this->max_size;
		}
		if ($reqwidth > $this->max_size) $reqwidth = $this->max_size;
		if ($imgwidth > $this->max_size) $imgwidth = $this->max_size;
		if ($reqwidth) {
			$width = $reqwidth;
			$swidth = $imgwidth;
			if (!$reqheight) $height = round(($width/$swidth)*$imgheight);
			else $height = $reqheight;
			$sheight = round(($swidth/$width)*$height);
		} elseif ($reqheight) {
			$height = $reqheight;
			$sheight = $imgheight;
			$width = round(($height/$sheight)*$imgwidth);
			$swidth = round(($sheight/$height)*$width);
		} else {
			$width = $swidth = $imgwidth;
			$height = $sheight = $imgheight;
		}
		$cwidth = $width;
		$cheight = $height;
		if (($imgheight/$imgwidth) < ($height/$width)) {
			$sheight = $imgheight;
			$swidth = round(($width/$height)*$imgheight);
		} else {
			$swidth = $imgwidth;
			$height = round(($imgheight/$imgwidth)*$width);
			$sheight = round(($imgheight/$imgwidth)*$swidth);
		}
		$cleft = round(($imgwidth/2)-($swidth/2));
		$x= array($width,$height,$swidth,$sheight,$cwidth,$cheight,$cleft);
		return $x;
	}
}
