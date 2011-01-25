<div class="contents">
	<div class="page">
		{db_query assign=model_query table="model" parent_id=$parent_model.model_id}
		{db_fetch query=$model_query assign=model}
			{db_query assign=content_data_query table="content_data" content_id=$smarty.request.id model_id=$model.model_id}
			{db_fetch_one query=$content_data_query assign=content_data}
			{if $content_data.model_id eq $model.model_id}
				<div class="field {$content_data.handler}">
					<div class="title"><label for="content[data][{$model.model_id}]">{$model.title}</label></div>
					<div class="content">
						{handler_format format=field content=$content_data prefix="content[data]" name=$model.model_id}
					</div>
				</div>
			{else}
				<div class="field {$content_data.handler}">
					<div class="title"><label for="content[data][{$model.model_id}]">{$model.title}</label></div>
					<div class="content">
						{handler_format format=field content=$model prefix="content[data]" name=$model.model_id}
					</div>
				</div>
			{/if}
		{/db_fetch}
		<br />
		<div class="buttons">
			<div class="button right default"><a href="javascript:document.forms.page_edit.sendTo('index?{get_params model=false}');">Save</a></div>
			<div class="button right"><a href="index?{get_params model=false}">Back</a></div>
		</div>
		<div class="clearer"></div>
	</div>
</div>
