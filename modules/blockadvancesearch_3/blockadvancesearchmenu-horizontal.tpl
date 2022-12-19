{if $submitMode == 2}
<form action="{$this_path}advancesearch.php" method="get" id="AdvancedSearchForm_{$duliq_id}" {if $full_ajax}onsubmit="return false;"{/if}>
{/if}
{foreach from=$advancedsearch_label item=label key=k name=advancesearch}
	{assign var='curMaxVisible' value=$maxVisible[$k]}
		<div class="advc_crits" {if $widthHookTop[$k]}style="width:{$widthHookTop[$k]|intval}px;"{/if}>
		<strong class="advcTitleCrit">{$advancedsearch_label.$k.name}</strong>
		{assign var='curSearchType' value=$searchTypes[$k]|default:1}
		{include file=$formtpl|dirname|cat:"/tplSearch_$curSearchType.tpl"}
			</div>
		{assign var='prevK' value=$k}
	{/foreach}
	{if $submitMode == 2}
		<input {if $full_ajax}type="button" onclick="$('#AdvancedSearchForm_{$duliq_id}').ajaxSubmit(blockAdvanceSearch_{$duliq_id}.submitOptions);return false;"{else} type="submit"{/if} value="{l s='Rechercher' mod='blockadvancesearch_3'}" class="button" name="submitAdvancedSearch" id="submitAdvancedSearch_{$duliq_id}" />
		<br class="clear" />
		</form>
	{/if}
	{if $showCritMethode == 1}
	<script type="text/javascript">
	blockAdvanceSearch_{$duliq_id}.advcViewMoreToogleMouseOut();
	</script>
	{/if}
	{if $smarty.server.SCRIPT_NAME == $this_path|cat:'advancesearch.php' && count($smarty.get)}
	<a href="{$this_path}advancesearch.php" id="advc_reinitsearch_{$duliq_id}">{l s='Réinitialiser ma recherche' mod='blockadvancesearch_3'}</a>
	{/if}
{if $showSelection}
<script type="text/javascript">
$(document).ready(function() {ldelim}
$('#advcCurSelection').html('');
	$('#advcCurSelection').append('<span><b>{l s='Ma sélection' mod='blockadvancesearch_3' js=1}</b></span>');
	{foreach from=$selection item=item name=advancesearchselection}
		{assign var='curUrl' value=$oAdvaceSearch->getUrlWithMultipleSelect($item.3,$item.0,$SelectMulti[$item.3])}
		$('#advcCurSelection').append('<span><a href="{$curUrl}"><img src="{$img_dir}icon/delete.gif" alt="{l s='Delete' mod='blockadvancesearch_3' js=1}" class="icon" /></a>{if '#^attr_#'|preg_match:$item.3}{$item.2 js=1} : {/if}{$item.1 js=1}</span>');
	{/foreach}
	{if $full_ajax}
	blockAdvanceSearch_{$duliq_id}.selectionAjaxMode();
	{/if}
{rdelim});
</script>
{/if}