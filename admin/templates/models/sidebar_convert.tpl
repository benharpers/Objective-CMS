{db_query assign=db_query1 table="model" parent_id=$smarty.request.parent model_id=$smarty.request.id}
{db_fetch_one query=$db_query1 assign=data}
{db_query assign=db_query2 table="model" model_id=$smarty.request.parent}
{db_fetch_one query=$db_query2 assign=parent}
<h1>Convert Model</h1>
<form id="sidebar_edit" name="sidebar_edit" action="index?{get_params}" method="post" accept-charset="UTF-8">
	<fieldset>
		<input type="hidden" name="action" value="convert" />
		<input type="hidden" name="convert" value="model" />
		<input type="hidden" name="model[parent_id]" value="{$smarty.request.parent|default:"0"}" />
		<input type="hidden" name="model[model_id]" value="{$smarty.request.id}" />
		<div class="field">
			<div class="title"><label for="model[handler]">Handler</label></div>
			<div class="content">{select_handler name="model[handler]" parent=$parent.handler selected=$data.handler is_convertable=true}</div>
		</div>
	</fieldset>
</form>
<div class="buttons right">
	<div class="button right default"><a href="javascript:document.forms.sidebar_edit.submit();">{if $action eq "insert"}Save{else}Update{/if}</a></div>
	<div class="button right"><a href="index?{get_params id=false}">Cancel</a></div>
</div>