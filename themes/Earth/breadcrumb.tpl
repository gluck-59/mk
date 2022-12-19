{if $smarty.const.site_version == "full"}
{if $page_name != "order" && $page_name != "validation" && $page_name != "submit"} {* отключим header на страницах оформления заказа *}
	<!-- Breadcrumb -->
	{if isset($smarty.capture.path)}{assign var='path' value=$smarty.capture.path}{/if}
	<div class="breadcrumb">
    		<a href="{$base_dir}">{l s='Home'}</a>
            {if $path}&nbsp;
                {$navigationPipe}&nbsp;
                {$path}&nbsp;
    		{/if}
	</div>
{/if}
{/if}