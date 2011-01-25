<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>Objective CMS :: Administration</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="{$relative_path}/styles/general.css" type="text/css" />
	<link rel="stylesheet" href="{$relative_path}/styles/forms.css" type="text/css" />
	<link rel="stylesheet" href="{$relative_path}/styles/nav.css" type="text/css" />
	<link rel="stylesheet" href="{$relative_path}/styles/buttons.css" type="text/css" />
	<link rel="stylesheet" href="{$relative_path}/styles/icons.css" type="text/css" />
	<link rel="stylesheet" href="{$relative_path}/styles/tabs.css" type="text/css" />
	<link rel="stylesheet" href="{$relative_path}/styles/listings.css" type="text/css" />
	<!--[if gt IE 6]><link rel="stylesheet" href="{$relative_path}/styles/ie.css" type="text/css"></link><![endif]-->
	<link rel="shortcut icon" href="{$relative_path}/images/favicon.ico" type="image/x-icon" />
	<script type="text/javascript" src="{$relative_path}/scripts/jquery.js"></script>
</head>
{if $path}
{include file="`$path`/`$page`.tpl"}
{else}
{include file="home.tpl"}
{/if}
</html>