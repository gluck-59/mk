{counter start=1 skip=1 print=false assign=curVisible}
{assign var='curMaxVisible' value=$maxVisible[$k]}
{if $k eq 'category' && isset($smarty.get.id_category) && $filterCat}
	{assign var='curKey' value="id_category"}
{elseif  $k eq 'manufacturer' && isset($smarty.get.id_manufacturer) && $filterCat}
	{assign var='curKey' value="id_manufacturer"}
{else}
	{assign var='curKey' value=$k}
{/if}
<ul class="advcSearchList">
{foreach from=$advancedsearch_value.$k item=item name=advancesearchvalue}
	{assign var='isSelect' value=$oAdvaceSearch->isSelected($smarty.get.$curKey, $item.0)}
	<li class="{if $curMaxVisible && $curVisible > $curMaxVisible}advcHideCrit{/if}{if $item.2 <= 0 && !$isSelect} advcCritNotResult{/if}"><label><input type="{if $SelectMulti[$k]}checkbox{else}radio{/if}" name="{$k}{if $SelectMulti[$k]}[]{/if}" class="advc_checkboxSearch" {if $full_ajax || $submitMode == 2}value="{if $submitMode == 2}{$item.0}{else}{if $item.2 > 0 || $isSelect}{$oAdvaceSearch->getUrlWithMultipleSelect($k, $item.0, $SelectMulti[$k])}{/if}{/if}"{else}{if $item.2 > 0}onclick="location.href='{$oAdvaceSearch->getUrlWithMultipleSelect($k, $item.0, $SelectMulti[$k])}'"{/if}{/if} {if $isSelect} checked="checked"{/if} /> {$item.1}{if $displayNbProduct} ({$item.2}){/if}</a></label></li>
	{if !$smarty.foreach.advancesearchvalue.last}
	{counter}
	{/if}
{foreachelse}
{if $stepMode}
		{if !isset($smarty.get.$prevK) || empty($smarty.get.$prevK)}
			<li class="advcStepPrevChoose">{l s='Veuillez choisir' mod='blockadvancesearch_3'} {$advancedsearch_label.$prevK.name}</li>
		{else}
			<li class="advcNoChoice">{l s='Aucune proposition' mod='blockadvancesearch_3'}</li>
		{/if}

{else}
			<li class="advcNoChoice">{l s='Aucune proposition' mod='blockadvancesearch_3'}</li>
{/if}
{/foreach}
</ul>
{if $curMaxVisible && $curVisible > $curMaxVisible}<span class="advcViewMoreToogle"><a href="javascript:void(0)" onclick="blockAdvanceSearch_{$duliq_id}.advcViewMoreToogle(this,'{l s='+ de critères' mod='blockadvancesearch_3'}','{l s='- de critères' mod='blockadvancesearch_3'}')">{l s='+ de critères' mod='blockadvancesearch_3'}</a></span>{/if}