<body id="main">
	<script src="{$relative_path}/scripts/scripts.js" type="text/javascript"></script>
	<div class="body">
		<div class="sidebar">
			<div class="container">
				<div class="contents side_navigation">
					<div class="page">
						{if $smarty.request.go eq "edit"}
							{include file="edit/sidebar_edit.tpl"}
						{elseif $smarty.request.go eq "new"}
							{include file="edit/sidebar_new.tpl"}
						{elseif $smarty.request.go eq "copy"}
							{include file="edit/sidebar_copy.tpl"}
						{else}
							{include file="edit/sidebar_search.tpl"}
						{/if}
						<div class="clearer"></div>
					</div>
				</div>
			</div>
		</div>
		<div class="medium main container tabbed">
			<div class="contents">
				<div class="page">
					{db_query assign=db_query table="content" parent_id=$smarty.request.parent order="sort_order"}
					{db_query assign=parent_query table="content" content_id=$smarty.request.parent}
					{db_fetch_one query=$parent_query assign=parent}
					<div class="listing">
						<div class="contents">
							<div class="table">
								<table cellpadding="0" cellspacing="0" class="listing_content" summary="List of content items.">
									<tr class="header">
										<th colspan="4" class="title">Nodes</th>
										<th class="date">Last Modified</th>
									</tr>{db_fetch query=$db_query assign=data}{if $data}{get_handler data=$data assign=handler}
									<tr class="{cycle values="even,odd"}{if $smarty.request.id eq $data.content_id} selected{/if}">
										<td class="icon {$handler->icon_class}{if $data.is_alias} alias{/if}">&nbsp;</td>
										<td class="value" onmousedown="href('{if $smarty.request.id ne $data.content_id}index?{get_params parent=$data.parent_id go=edit id=$data.content_id}{else}{if $handler->template}{$handler->template}?{get_params go=edit}{elseif $handler->has_children}{if $handler->is_child}page?{get_params parent=$data.parent_id go=edit id=$data.content_id}{else}index?{get_params parent=$data.content_id id=false}{/if}{else}index?{get_params}{/if}{/if}')">{$data.title}</td>
										<td class="sort">{if $smarty.request.id eq $data.content_id}
											<div>
												<img class="icon sort_up" onmousedown="href('index?{get_params id=$data.content_id action=sort sort=content content=up}');" src="{$relative_path}/images/icon_spacer.png" width="12" height="16" border="0" alt="Move Up" /><img class="icon sort_down" onmousedown="href('index?{get_params id=$data.content_id action=sort sort=content content=down}');" src="{$relative_path}/images/icon_spacer.png" width="12" height="16" border="0" alt="Move Down" />{/if}
											</div>
										</td>
										<td class="actions">
											<div>{if $smarty.request.id eq $data.content_id and $data.enabled eq 1}
												<img class="icon enabled" onmousedown="href('index?{get_params id=$data.content_id action=status status=content content=disable}');" src="{$relative_path}/images/icon_spacer.png" width="16" height="16" border="0" alt="Enabled" />{elseif $data.enabled ne 1}<img class="icon disabled" onmousedown="href('index?{get_params id=$data.content_id action=status status=content content=enable}');" src="{$relative_path}/images/icon_spacer.png" width="16" height="16" border="0" alt="Disabled" />{/if}
											</div>
										</td>
										<td class="date" title="{$data.date_modified|date_format}">{$data.date_modified|date_format:false} by Admin</td>
									</tr>
									{/if}{/db_fetch}
								</table>
							</div>
						</div>
					</div>
					<br />
					<div class="buttons">{if $smarty.request.id}{if $action ne "copy"}
						<div class="button right default"><a href="index?{get_params go=copy}">Copy</a></div>{/if}
						<div class="button right"><a href="index?{get_params action=delete delete=content content=$smarty.request.id}">Delete</a></div>{/if}{if $action ne "insert"}
						<div class="button right{if !$smarty.request.id} default{/if}"><a href="index?{get_params go=new id=false}">New</a></div>{/if}{if $smarty.request.parent}
						<div class="button"><a href="index?{get_params parent=$parent.parent_id id=$smarty.request.parent go=edit}">Back</a></div>{/if}
					</div>
					<div class="clearer"></div>
				</div>
			</div>
			{include file="tabs.tpl"}
		</div>
		{include file="navigation.tpl"}
	</div>
	<script type="text/javascript">/* No Page Flash */</script>
</body>