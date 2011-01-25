{db_query assign=file_query table="file" group_id=$smarty.request.group_id file_id=$smarty.request.file_id}
{db_fetch_one query=$file_query assign=file}
<body id="popup_editor">
	<form name="file_info_edit" class="main container" action="fileset_upload_complete?group_id={$smarty.request.group_id}&amp;fileset={$smarty.request.file_id}&amp;action=update&amp;update=fileset" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
		<fieldset class="contents">
			<input type="hidden" name="file[default]" value="0" />
			<div class="page">
				<div class="field">
					<div class="title"><label for="file[name]">File Name</label></div>
					<div class="content">
						<div class="title_handler">
							<input type="text" title="File Name" size="20" name="file[name]" value="{$file.name}" />
						</div>
					</div>
				</div>
				<div class="field">
					<div class="title"><label for="file[title]">Title</label></div>
					<div class="content">
						<div class="title_handler">
							<input type="text" title="Title" size="20" name="file[title]" value="{$file.title}" />
						</div>
					</div>
				</div>
				<br />
				<div class="field">
					<div class="title"><label for="file[keywords]">Keywords</label></div>
					<div class="content">
						<div class="story_handler">
							<textarea title="Keywords" name="file[keywords]">{$file.keywords}</textarea>
						</div>
					</div>
				</div>
				<br />
				<div class="field">
					<div class="title"><label for="file[description]">Description</label></div>
					<div class="content">
						<div class="story_handler">
							<textarea title="Description" name="file[description]">{$file.description}</textarea>
						</div>
					</div>
				</div>
				<br />
				<div class="field notitle">
					<div class="content">
						<div class="switch_handler">
							<label class="checkbox"><input type="checkbox" title="Set as default" name="file[default]" value="1" id="is_default" {if $file.default eq 1}checked="checked" {/if}/>Set as default</label>
						</div>
					</div>
				</div>
			</div>
			<div class="buttons">
				<div class="button right default"><a href="javascript:document.forms.file_info_edit.submit();">Update</a></div>
				<div class="button right"><a href="javascript:window.close();">Cancel</a></div>
			</div>
		</fieldset>
	</form>
</body>