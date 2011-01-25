<body id="main">
	<script src="{$relative_path}/scripts/scripts.js" type="text/javascript"></script>
	<div class="body">
		<div class="sidebar">&nbsp;</div>
		<div class="multicontainer main locked">
			<div class="small container">
				<div class="contents">
					<div class="page splash">
						<img id="splash" src="{$relative_path}/images/logo.png" width="128" height="128" border="0" alt="Objective CMS" title="Objective CMS" />
						<h1 class="splash">Objective CMS</h1>
						<h2 class="splash">Content Management System</h2>
						<div class="clearer"></div>
					</div>
				</div>
			</div>
			<div class="small container">
				<div class="contents">
					<div class="page">
						<div class="listing">
							<div class="contents">
								<div class="table">
									<table cellpadding="0" cellspacing="0" class="listing_content" summary="List of content items.">
										<tr class="header">
											<th colspan="2">Providers</th>
										</tr>{foreach from=$providers item=provider}{if $provider.hidden != true}
										<tr class="{cycle values="even,odd"}">
											<td class="icon {$provider.icon_class}">&nbsp;</td>
											<td title="{$provider.description}">{$provider.title}</td>
										</tr>
										{/if}{/foreach}
									</table>
								</div>
							</div>
						</div>
						<div class="clearer"></div>
					</div>
				</div>
			</div>
			<div class="small container last">
				<div class="contents">
					<div class="page">
						<div class="listing">
							<div class="contents">
								<div class="table">
									<table cellpadding="0" cellspacing="0" class="listing_content" summary="List of content items.">
										<tr class="header">
											<th colspan="2">Handlers</th>
										</tr>{foreach from=$handlers item=handler}
										<tr class="{cycle values="even,odd"}">
											<td class="icon {$handler->icon_class}">&nbsp;</td>
											<td title="{$handler->description}">{$handler->title}</td>
										</tr>
										{/foreach}
									</table>
								</div>
							</div>
						</div>
						<div class="clearer"></div>
					</div>
				</div>
			</div>
		</div>
		{include file="navigation.tpl"}
	</div>
	<script type="text/javascript">/* No Page Flash */</script>
</body>