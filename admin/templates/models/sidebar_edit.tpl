{db_query assign=db_query table="model" model_id=$smarty.request.id parent_id=$smarty.request.parent order="sort_order"}
{db_fetch_one query=$db_query assign=edit_data}
<h1>Edit Model</h1>
<form id="sidebar_edit" name="sidebar_edit" action="index?{get_params}" method="post" accept-charset="UTF-8">
	<fieldset>
		<input type="hidden" name="action" value="update" />
		<input type="hidden" name="update" value="model" />
		<input type="hidden" name="model[model_id]" value="{$smarty.request.id}" />
		<div class="field title_handler">
			<div class="title"><label for="model[title]">Title</label></div>
			<div class="content">
				<input name="model[title]" type="text" value="{$edit_data.title}" />
			</div>
		</div>
		<div class="field title_handler">
			<div class="title"><label for="model[name]">Class</label></div>
			<div class="content">
				<input name="model[name]" type="text" value="{$edit_data.name}" />
			</div>
		</div>
	</fieldset>
</form>
<div class="buttons right">
	<div class="button right default"><a href="javascript:document.forms.sidebar_edit.submit();">Update</a></div>
	<div class="button right"><a href="index?{get_params id=false}">Cancel</a></div>
</div>