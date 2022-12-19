{if $submitMode == 2}
<form action="{$this_path}advancesearch.php" method="get" id="AdvancedSearchForm_{$duliq_id}" {if $full_ajax}onsubmit="return false;"{/if}>
{/if}
{foreach from=$advancedsearch_label item=label key=k name=advancesearch}
	{assign var='curMaxVisible' value=$maxVisible[$k]}
		<div style=" margin-top: -7px; ">
		<noindex><h4 class="advcTitleCrit">{$advancedsearch_label.$k.name}</h4></noindex>
		{assign var='curSearchType' value=$searchTypes[$k]|default:1}
		{include file=$formtpl|dirname|cat:"/tplSearch_$curSearchType.tpl"}
			</div>
			<div class="clear">&nbsp;</div>
			{assign var='prevK' value=$k}
	{/foreach}
	<div class="clear"></div>
	{if $submitMode == 2}
		<input {if $full_ajax}type="button" onclick="$('#AdvancedSearchForm_{$duliq_id}').ajaxSubmit(blockAdvanceSearch_{$duliq_id}.submitOptions);return false;"{else} type="submit"{/if} value="{l s='Rechercher' mod='blockadvancesearch_3'}" class="button" name="submitAdvancedSearch" id="submitAdvancedSearch"_{$duliq_id} />
		<br class="clear" />
		</form>
	{/if}

	{if $showCritMethode == 1}
	<script type="text/javascript">
	blockAdvanceSearch_{$duliq_id}.advcViewMoreToogleMouseOut();
	</script>
	{/if}
	{if $smarty.server.SCRIPT_NAME == $this_path|cat:'advancesearch.php' && count($smarty.get)}
	<center><a class="ebutton blue" style="text-decoration:none; margin-bottom: 30px;" href="{$this_path}advancesearch.php">{l s='Réinitialiser ma recherche' mod='blockadvancesearch_3'}</a></center>
	{/if}
{if $showSelection}
<script type="text/javascript">
$(document).ready(function() {ldelim}
$('#advcCurSelection_{$duliq_id}').html('');
$('#advcCurSelection_{$duliq_id}').append('<span style="display:none" class="selected"><b>{l s='Ma sélection' mod='blockadvancesearch_3' js=1}</b></span>');
	{foreach from=$selection item=item name=advancesearchselection}
	{assign var='curUrl' value=$oAdvaceSearch->getUrlWithMultipleSelect($item.3,$item.0,$SelectMulti[$item.3])}
		$('#advcCurSelection_{$duliq_id}').append('<span class="userselected">{if '#^attr_#'|preg_match:$item.3}{$item.2 js=1} : {/if}{$item.1 js=1}<a href="{$curUrl}">&nbsp;</a></span>');
	{/foreach}
		{if $full_ajax}
		blockAdvanceSearch_{$duliq_id}.selectionAjaxMode();
		{/if}
{rdelim});
</script>
{/if}


