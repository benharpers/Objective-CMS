<?php

$path = $_SERVER['REQUEST_URI'];
$file = basename($path);
if (file_exists($file)) {
	$modified = filemtime($file);
	$modified_date = gmdate('D, d M Y H:i:s T', $modified);
	header('Expires: ' . gmdate("D, d M Y H:i:s T",strtotime("+1 year")));
	header("Last-Modified: $modified_date");
	header('ETag: "'.preg_replace('/(.{5})(.{4})(.{8}).+/','\1-\2-\3',md5($file)).'"');
	if (@$_SERVER['HTTP_IF_MODIFIED_SINCE'] == $modified_date) {
		if (php_sapi_name()=='cgi') header('Status: 304 Not Modified');
		else header('HTTP/1.1 304 Not Modified');
	} else {
		$size = getimagesize($file);
		header("Content-Type: ".$size['mime']);
		header("Content-Length: ".filesize($file));
		header("Accept-Ranges: bytes");
		header("Cache-control: store, cache, max-age=28800");
		header("Pragma: cache");
		readfile($file);
	}
} else {
	header("HTTP/1.1 404 Not Found");
	$host = $_SERVER['SERVER_NAME'];
	$port = $_SERVER['SERVER_PORT'];
	echo <<<eof
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<HTML><HEAD>
<TITLE>404 Not Found</TITLE>
</HEAD><BODY>
<H1>Not Found</H1>
The requested URL {$path} was not found on this server.<P>
<HR>
<ADDRESS>Apache/1.3.33 Server at {$host} Port {$port}</ADDRESS>
</BODY></HTML>
eof;
}

exit();
