<body id="popup_editor" onunload="window.opener.filesets.update({$smarty.request.group_id});">
	<applet id="jupload" code="wjhk.jupload2.JUploadApplet" archive="{$top_path}/includes/handlers/file_handler/jupload.jar" mayscript="mayscript">
		<param name="postURL" value="{$relative_path}/edit/fileset_upload?action=upload&amp;upload=fileset&amp;fileset={$smarty.request.group_id}&amp;tmp_id={make_id}" />
		<param name="maxChunkSize" value="1048576" />
	</applet>
</body>