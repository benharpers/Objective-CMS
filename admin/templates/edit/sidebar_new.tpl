<h1>Insert Node</h1>
<form id="sidebar_edit" name="sidebar_edit" action="index?{get_params}" method="post">
	<fieldset>
		<input type="hidden" name="action" value="insert" />
		<input type="hidden" name="insert" value="content" />
		<input type="hidden" name="content[model_id]" value="{$smarty.request.model}" />
		<div class="field">
			<div class="title"><label for="content[title]">Node Name</label></div>
			<div class="content">
				<input type="text" name="content[title]" />
			</div>
		</div>
		<div class="field">
			<div class="title"><label for="content[model_id]">Model</label></div>
			<div class="content">
				{db_query table="content" content_id=$smarty.request.parent assign="parent_info_query"}
				{db_fetch_one query=$parent_info_query assign="parent_info"}
				{if $parent_info.model_id}
					{db_query table="model" parent_id=$parent_info.model_id assign="parent_model_query"}
				{else}
					{db_query table="model" parent_id="0" assign="parent_model_query"}
				{/if}
				<select class="type" name="content[model_id]">{db_fetch query=$parent_model_query assign="parent_model"}
						<option value="{$parent_model.model_id}">{$parent_model.title}</option>{/db_fetch}
				</select>
			</div>
		</div>
	</fieldset>
</form>
<div class="buttons right">
	<div class="button right default"><a href="javascript:document.forms.sidebar_edit.submit();">Continue</a></div>
	<div class="button right"><a href="index?{get_params id=false}">Cancel</a></div>
</div>