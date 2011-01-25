<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<title>Example Site</title>
	</head>
	<body>
	{if $page ne index}
		{include file="`$page`.tpl"}
	{else}
		{get_content name="document" assign="documents" order="sort_order"}
		<h1>Documents</h1>
		<ul class="thumbnails">
			{foreach from=$documents item="document"}
			<a href="document?document={$document.content_id}">
			<li style="padding-bottom: 10px;">
				<b>{$document.title}</b><br />
				{"F jS, Y"|date:$document.date}
			</li>
			</a>
			{/foreach}
		</ul>
		<div class="description">Displaying <b>1</b>&thinsp;&ndash;&thinsp;<b>{$count}</b> of <b>{$count}</b> <b>Documents</b></div>
	{/if}
	</body>
</html>