{capture name=path}Списки хотелок других байкеров{/capture}
{include file=$tpl_dir./breadcrumb.tpl}


{if $products}
	{include file=$tpl_dir./wishlists-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}

	{else}
	<p class="warning">Похоже, все уже купили что хотели...</p>
{/if}


{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
//	jQuery(document).ready(function()
//			{   
			$('body,html').animate({
				scrollTop: 340
			}, 40);
//		});
	</script>
	{/literal}
{/if}
