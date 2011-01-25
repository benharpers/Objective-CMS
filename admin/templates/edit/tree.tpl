{db_query assign=tree_query table="content" parent_id=$pid order="sort_order"}
{db_fetch_all query=$tree_query assign=tree_array}
{foreach from=$tree_array item=tree}{if $tree.content_id != $smarty.request.parent}
	<option value="{$tree.content_id}">{$space}{$tree.title}</option>
{include file="edit/tree.tpl" space=" &nbsp; `$space`" pid=$tree.content_id}
{/if}{/foreach}