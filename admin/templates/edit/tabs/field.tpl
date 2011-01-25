{db_query assign=content_data_query table="content_data" content_id=$smarty.request.id model_id=$parent_model.model_id}
{db_fetch_one query=$content_data_query assign=content_data}
{if $content_data.model_id eq $parent_model.model_id}
	<div class="contents">
		<div class="page">
			{handler_format format=field content=$content_data prefix="content[data]" name=$parent_model.model_id}
			<br />
			<div class="buttons">
				<div class="button right default"><a href="javascript:document.forms.page_edit.submit();">Save</a></div>
				<div class="button right"><a href="index?{get_params action="edit" model=false}">Back</a></div>
			</div>
			<div class="clearer"></div>
		</div>
	</div>
{/if}