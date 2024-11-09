{capture name=path}{l s='Votre Recherche' mod='blockadvancesearch_3'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<noindex>
<h2>{l s='Votre Recherche' mod='blockadvancesearch_3'}
<span>{$nbProducts|intval}&nbsp;{declension nb="$nbProducts|intval" expressions="ништяк,ништяка,ништяков"}</span>
</h2>
</noindex>

{*<form method="get" action="{$smarty.server.REQUEST_URI}">*}
<span id="advcCurSelection_{$duliq_id}">&nbsp;</span>&nbsp;
	{if $smarty.server.SCRIPT_NAME == $this_path|cat:'advancesearch.php' && ($smarty.get.supplier!='' or $smarty.get.category!='' or $smarty.get.filter!='' or $smarty.get.manufacturer!='')}
	<a class="ebutton {if $smarty.const.site_version == "full"}mini {/if}blue" {if $smarty.const.site_version == "mobile"}style="font-size: 1em; text-align: center; padding: 12px 0;"{/if} href="{$this_path}advancesearch.php">Сброс</a><br>
	{/if}
	

{*<input autofocus class="filter" type="text" name="filter" id="filter"  placeholder="Что искать будем
	 {if isset($smarty.cookies.firstname)}, {$smarty.cookies.firstname}{/if}?"  >
*}
	 
<input required class="filter" type="text" name="filter" id="filter"  placeholder="{if !empty($placeholder)}{$placeholder} — фильтр по словам{else}Что искать будем
	 {if isset($smarty.cookies.firstname)}, {$smarty.cookies.firstname}{/if}?{/if}">

{* выведем свои подсказки для каждого userselect *}
{if $tips}
	<p class="tips">
		{foreach from=$tips item=tip name=tips}
			{if $full_ajax}
			&nbsp;<a class="tips" rel="{$tip|trim}" onclick="javascript:blockAdvanceSearch_{$duliq_id}.advcLoadUrl('{$oAdvaceSearch->getUrlWithMultipleSelect('filter',$tip , $SelectMulti[$tip])}')">{$tip|trim}</a>&nbsp;
			{else}
			&nbsp;<a class="tips" rel="{$tip|trim}" onclick="blockAdvanceSearch_3.setAttr('filter',blockAdvanceSearch_3.getAttr('filter')+'{$tip|trim}')">{$tip|trim}</a>&nbsp;
			{/if}
			
		{/foreach }
	</p>
{/if}

{*</form>*}

<script type="text/javascript">
blockAdvanceSearch_{$duliq_id}.isSearchPage = true;
</script>
{include file=$tpl_dir./errors.tpl}
{if $tabSearch}{$tabSearch}{/if}
{if !$nbProducts}
	<p class="warning">
			{l s='Aucun résultat' mod='blockadvancesearch_3'} {*&nbsp;"{$query|escape:'htmlall':'UTF-8'}"*}
	</p>
{else}
	
	{include file=$tpl_dir./product-sort.tpl} 
	{include file=$tpl_dir./product-list.tpl products=$products}
	{include file=$tpl_dir./pagination.tpl}
{/if}

{if $smarty.const.site_version == "full"}
	{* отскроллимся чуть ниже после загрузки страницы *}					
	{literal}
	<script defer language="JavaScript">
	jQuery(document).ready(function()
			{   
			$('body,html').animate({
				scrollTop: 320
			}, 10);
		});
		
	</script>
	{/literal}
{/if}




{literal}
<script>
	jQuery(document).ready(function() {

	});
</script>
{/literal}
