{if $cms}
{capture name=path}{$cms->meta_title}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}
	{if $content_only}
	<div style="text-align:left; padding:10px;" class="rte">
		{$cms->content}
	</div>
	{else}
	<div class="rte">
		{$cms->content}
	</div>
	{/if}
{else}
	{l s='This page does not exist.'}
{/if}
<br />
{if !$content_only}
<br /><br /><br />
<p align="center"><a href="{$base_dir}" title="{l s='Home'}"><img src="{$img_dir}icon/home.png" alt="{l s='Home'}" class="icon" /></a><br><a href="{$base_dir}" title="{l s='Home'}">{l s='Home'}</a></p>
{/if}

