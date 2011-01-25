{get_content name="document" assign="documents" order="sort_order"}
{get_content name="document" content_id=$smarty.request.document assign="document"}
{get_prev_next prev=prev next=next num=num list=$documents current=$smarty.request.document}
<h1>{$document.title}</h1>
<p><b>{"l, F jS, Y"|date:$document.date}</b></p>
<p><a href="index">Home</a> | {if $prev ne $smarty.request.document}<a href="document?document={$prev}">Previous</a>{else}Previous{/if} | {if $next ne $smarty.request.document}<a href="document?document={$next}">Next</a>{else}Next{/if}</p>
<p>{$document.body}</p>
{get_files group=$document.images assign=images}
{foreach from=$images item=image}
	<img src="index?action=get&get=image&image={$image.file_id}&amp;width=128&amp;height=128" width="128" height="128" alt="" />
{/foreach}
