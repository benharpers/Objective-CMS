{db_query assign=content_query table="content" content_id=$smarty.request.id}
{db_fetch_one query=$content_query assign=content}
{if $smarty.request.model}
	{db_query assign=parent_model_query table="model" model_id=$smarty.request.model}
{else}
	{db_query assign=parent_model_query table="model" parent_id=$content.model_id}
{/if}
{db_fetch_one query=$parent_model_query assign=parent_model}
{get_handler handler=$parent_model.handler assign=handler}
<body id="main">
	<script src="{$relative_path}/scripts/scripts.js" type="text/javascript"></script>
	<div class="body">
		<form id="page_edit" name="page_edit" action="tabbed_page?{get_params}" method="post" accept-charset="UTF-8" enctype="multipart/form-data">
			<fieldset>
				<input type="hidden" name="action" value="update" />
				<input type="hidden" name="update" value="content" />
				<input type="hidden" name="content[content_id]" value="{$smarty.request.id}" />
				<div class="large main container tabbed">
					{if $handler->template}{include file="edit/tabs/`$handler->template`.tpl"}{/if}
					<div class="tabs">
						<div class="tab right"><div><a href="{$relative_path}/models/">Models</a></div></div>
						{if "`$path`/`$page`" eq 'edit/index'}
							<div class="tab front"><div><span>Content</span></div></div>
						{else}
							<div class="tab"><div><a href="{$relative_path}/edit/index?{get_params model=false}">Content</a></div></div>
						{/if}
						{db_query assign=model_query table="model" parent_id=$content.model_id}
						{db_fetch query=$model_query assign=model}
							{if $model.model_id eq $parent_model.model_id}
								<div class="tab front"><div><span>{$model.title}</span></div></div>
							{else}
								<div class="tab"><div><a href="javascript:document.forms.page_edit.sendTo('{$relative_path}/edit/tabbed_page?{get_params model=$model.model_id}');">{$model.title}</a></div></div>
							{/if}
						{/db_fetch}
					</div>
				</div>
			</fieldset>
		</form>
		{include file="navigation.tpl"}
	</div>
	<script type="text/javascript">/* No Page Flash */</script>
</body>