<body id="main">
	<script src="{$relative_path}/scripts/scripts.js" type="text/javascript"></script>
	<div class="body">
		<div class="sidebar">{if $smarty.request.id or $smarty.request.go}
			<div class="container">
				<div class="contents side_navigation">
					<div class="page">
						{if $smarty.request.go eq convert}
							{include file="models/sidebar_convert.tpl"}
						{elseif $smarty.request.go eq insert}
							{include file="models/sidebar_new.tpl"}
						{elseif $smarty.request.id}
							{include file="models/sidebar_edit.tpl"}
						{/if}
						<div class="clearer"></div>
					</div>
				</div>
			</div>{/if}
		</div>
		<div class="medium main container tabbed">
			<div class="contents">
				<div class="page">
					{db_query assign=db_query table="model" parent_id=$smarty.request.parent order="sort_order"}
					{db_query assign=parent_query table="model" model_id=$smarty.request.parent}
					{db_fetch_one query=$parent_query assign=parent}
					<div class="listing">
						<div class="contents">
							<div class="table">
								<table cellpadding="0" cellspacing="0" class="listing_content" summary="List of models.">
									<tr class="header">
										<th colspan="4">Model</th>
										<th class="type">Type</th>
										<th class="date">Last Modified</th>
									</tr>{db_fetch query=$db_query assign=data}{if $data}{get_handler handler=$data.handler assign=handler}
									<tr class="{cycle values="even,odd"}{if $data.enabled ne 1} disabled{/if}{if $smarty.request.id eq $data.model_id} selected{/if}">
										<td class="icon {$handler->icon_class}">&nbsp;</td>
										<td class="title" onmousedown="href('index?{if ($handler->has_children or !$handler->is_child) and $smarty.request.id eq $data.model_id}{get_params parent=$data.model_id id=false}{else}{get_params parent=$data.parent_id id=$data.model_id}{/if}')">{$data.title}</td>
										<td class="sort">{if $smarty.request.id eq $data.model_id}{assign var=selected value=$handler}
											<div>
												<img class="icon sort_up" onmousedown="href('index?{get_params id=$data.model_id action=sort sort=model model=up}');" src="{$relative_path}/images/icon_spacer.png" width="12" height="16" border="0" alt="Move Up" /><img class="icon sort_down" onmousedown="href('index?{get_params id=$data.model_id action=sort sort=model model=down}');" src="{$relative_path}/images/icon_spacer.png" width="12" height="16" border="0" alt="Move Down" />{/if}
											</div>
										</td>
										<td class="actions">
											<div>{if $smarty.request.id eq $data.model_id and $data.enabled eq 1}
												<img class="icon enabled" onmousedown="href('index?{get_params id=$data.model_id action=status status=model model=disable}');" src="{$relative_path}/images/icon_spacer.png" width="14" height="16" border="0" alt="Enabled" />{elseif $data.enabled ne 1}
												<img class="icon disabled" onmousedown="href('index?{get_params id=$data.model_id action=status status=model model=enable}');" src="{$relative_path}/images/icon_spacer.png" width="14" height="16" border="0" alt="Disabled" />{/if}
											</div>
										</td>
										<td>{if $data.handler}{$handler->title}{else}Group{/if}</td>
										<td class="date" title="{$data.date_modified|date_format}">{$data.date_modified|date_format:false} by Admin</td>
									</tr>{/if}{/db_fetch}
								</table>
							</div>
						</div>
					</div>
					<br />
					<div class="buttons">{if $smarty.request.id}{if $selected->is_convertable eq true and $smarty.request.go ne convert}
						<div class="button right"><a href="index?{get_params go=convert}">Convert</a></div>{/if}
						<div class="button right"><a href="index?{get_params action=delete delete=model model=$smarty.request.id}">Delete</a></div>{/if}{if $smarty.request.go ne insert}
						<div class="button right{if !$action} default{/if}"><a href="index?{get_params id=false go=insert}">New</a></div>{/if}{if $smarty.request.parent}
						<div class="button"><a href="index?{get_params parent=$parent.parent_id id=$smarty.request.parent page=false}">Back</a></div>{/if}
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