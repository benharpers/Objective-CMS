{db_query assign=content_query table="content" content_id=$smarty.request.id}
{db_fetch_one query=$content_query assign=content}
{db_query assign=model_query table="model" parent_id=$content.model_id order="sort_order"}
<body id="main">
	<script src="{$relative_path}/scripts/scripts.js" type="text/javascript"></script>
	<div class="body">
		<form id="page_edit" name="page_edit" action="index?{get_params}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
			<fieldset>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="update" value="content" />
				<input type="hidden" name="content[content_id]" value="{$smarty.request.id}" />
				<div class="large main container tabbed">
					<div class="contents">
						<div class="page">
							{db_fetch query=$model_query assign=model}{if $model.enabled}
							{get_handler handler=$model.handler assign=handler}
							{db_query assign=content_data_query table="content_data" content_id=$smarty.request.id model_id=$model.model_id}
							{db_fetch_one query=$content_data_query assign=content_data}
							{if $content_data.model_id eq $model.model_id}
							<div class="field {$content_data.handler}">
								<div class="title"><label for="content[data][{$model.model_id}]">{$model.title}</label></div>
								<div class="content">
									{handler_format format=field content=$content_data prefix="content[data]" name=$model.model_id}
								</div>
							</div>{/if}{/if}{/db_fetch}
							<br />
							<div class="buttons">
								<div class="button right default"><a href="javascript:document.forms.page_edit.submit();">Save</a></div>
								<div class="button right"><a href="index?{get_params}">Back</a></div>
							</div>
							<div class="clearer"></div>
						</div>
					</div>
					{include file="tabs.tpl"}
				</div>
			</fieldset>
		</form>
		{include file="navigation.tpl"}
	</div>
	<script type="text/javascript">/* No Page Flash */</script>
</body>