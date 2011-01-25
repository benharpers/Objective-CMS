<div class="tabs">
	{if "`$path`/`$page`" eq 'models/index'}<div class="tab front right"><div><span>Models</span></div></div>{else}<div class="tab right"><div><a href="{$relative_path}/models/">Models</a></div></div>{/if}
	{if "`$path`/`$page`" eq 'edit/page'}
		<div class="tab"><div><a href="javascript:document.forms.page_edit.sendTo('{$relative_path}/edit/index?{get_params}');">Content</a></div></div>
		<div class="tab front"><div><span>Edit</span></div></div>{elseif "`$path`/`$page`" eq 'edit/new'}<div class="tab front"><div><span>Insert</span></div></div>{elseif $content.handler eq page_handler}<div class="tab"><div><a href="{$relative_path}/edit/edit?{get_params}">Edit</a></div></div>
	{elseif "`$path`/`$page`" eq 'edit/index'}
		<div class="tab front"><div><span>Content</span></div></div>
	{else}
		<div class="tab"><div><a href="{$relative_path}/edit/">Content</a></div></div>
	{/if}
</div>
