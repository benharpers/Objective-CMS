<h1>Make Copy</h1>
<form id="sidebar_edit" name="sidebar_edit" action="index?{get_params}" method="post">
	<fieldset>
		<div class="field">
			<div class="title"><label for="parent_id">New Location</label></div>
			<div class="content">
				<select name="parent_id">
				{if $smarty.request.parent != 0}
					<option value="0">Top</option>
				{/if}
				{include file="content/content/tree.tpl" space=" &nbsp; " pid="0"}
				</select>
			</div>
		</div>
		<div class="field">
			<div class="title"><label for="action">Method</label></div>
			<div class="content">
				<div class="option"><input type="radio" name="action" id="cp1" value="copy_content" checked="checked"><span class="checkbox_title" onclick="clickSwitch(this,'cp1');">Make a Copy</span></div>
				<div class="option"><input type="radio" name="action" id="cp2" value="move_content"><span class="checkbox_title" onclick="clickSwitch(this,'cp2');">Move to Location</span></div>
			</div>
		</div>
	</fieldset>
</form>
<div class="buttons right">
	<div class="button right default"><a href="javascript:document.forms.sidebar_edit.submit();">Ok</a></div>
	<div class="button right"><a href="index?{get_params id=false}">Cancel</a></div>
</div>
