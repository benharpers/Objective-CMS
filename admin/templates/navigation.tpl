{db_query assign=db_query table="content" parent_id="0" order="sort_order"}
<div class="navigation container">
	<div class="contents">
		<div class="page">
			<ul id="nav">
				<li class="top parent">
					<a name="menu1">Develop</a>
					<ul>{db_fetch query=$db_query assign=data}{if $data}
						<li><a href="{$relative_path}/edit/index?parent={$data.content_id}">{$data.title}</a></li>{/if}{/db_fetch}{if $data}
						<li class="separator">&nbsp;</li>{/if}
						<li><a href="{$relative_path}/edit/">Content</a></li>
						<li><a href="{$relative_path}/models/">Models</a></li>
					</ul>
				</li>
				<li class="top parent">
					<a name="menu2">Manage</a>
					<ul>
						<li><a>Security</a></li>
						<li class="separator">&nbsp;</li>
						<li><a>Database</a></li>
						<li><a>Files &amp; Images</a></li>
						<li><a>Lists</a></li>
					</ul>
				</li>
				<li class="top parent">
					<a name="menu3">Interact</a>
					<ul>
						<li><a>Message Center</a></li>
						<li class="separator">&nbsp;</li>
						<li><a>User List</a></li>
						<li><a>Vendor List</a></li>
					</ul>
				</li>
				<li class="top parent">
					<a name="menu4">Analyze</a>
					<ul>
						<li><a>Statistics</a></li>
						<li class="separator">&nbsp;</li>
						<li><a>Activity Logs</a></li>
					</ul>
				</li>
				<li class="top right link" title="View Website"><a href="{$top_path}/" target="_blank">&nbsp;</a></li>
				<li class="top right logout" title="Logout"><a href="javascript:document.forms.logout.submit();">&nbsp;</a></li>
			</ul>
			<form id="logout" name="logout" action="{$relative_path}/index" method="post">
				<fieldset>
					<input type="hidden" name="action" value="admin_logout" />
				</fieldset>
			</form>
		</div>
	</div>
</div>
<a id="logo" href="{$relative_path}/"><img src="{$relative_path}/images/logo_icon.png" width="40" height="40" border="0" alt="CMS" title="ObjectModels :: CMS" /></a>