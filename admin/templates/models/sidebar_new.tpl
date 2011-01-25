{db_query assign=db_query table="model" model_id=$smarty.request.parent order="sort_order"}
{db_fetch_one query=$db_query assign=parent_model}
<h1>New Model</h1>
<form id="sidebar_edit" name="sidebar_edit" action="index?{get_params id=false}" method="post" accept-charset="UTF-8">
	<fieldset>
		<input type="hidden" name="action" value="insert" />
		<input type="hidden" name="insert" value="model" />
		<input type="hidden" name="parent_id" value="{$smarty.request.parent|default:"0"}" />
		<div class="field title_handler">
			<div class="title"><label for="model[title]">Title</label></div>
			<div class="content">
				<input name="model[title]" type="text" value="" />
			</div>
		</div>
		<div class="field title_handler">
			<div class="title"><label for="model[name]">Class</label></div>
			<div class="content">
				<input name="model[name]" type="text" value="" />
			</div>
		</div>
		<div class="field option_handler">
			<div class="title"><label for="model[handler]">Handler</label></div>
			<div class="content">{select_handler name="model[handler]" parent=$parent_model.handler selected='title_handler' content_parent_id=$smarty.request.parent}</div>
		</div>
	</fieldset>
</form>
<div class="buttons right">
	<div class="button right default"><a href="javascript:document.forms.sidebar_edit.submit();">Save</a></div>
	<div class="button right"><a href="index?{get_params id=false}">Cancel</a></div>
</div>