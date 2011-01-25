{db_query assign=db_query table="content" content_id=$smarty.request.id parent_id=$smarty.request.parent order="sort_order"}
{db_fetch_one query=$db_query assign=content}{if $content}
{get_handler data=$content assign=handler}
<h1>Edit Node</h1>
<form id="sidebar_edit" name="sidebar_edit" action="index?{get_params}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
	<fieldset>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="update" value="content" />
		<input type="hidden" name="content[content_id]" value="{$smarty.request.id}" />
		<div class="field {$content.handler}">
			<div class="title"><label for="content[title]">Node Name</label></div>
			<div class="content">
				{handler_format format=field content=$content value=$content.title name="content[title]"}
			</div>
		</div>
	</fieldset>
</form>
<div class="buttons right">
	<div class="button right default"><a href="javascript:document.forms.sidebar_edit.submit();">Update</a></div>
	<div class="button right"><a href="index?{get_params id=false}">Cancel</a></div>
</div>
{/if}