<div class="ADVCTagsClouds">
{counter start=1 skip=1 print=false assign=curVisible}
{assign var='curMaxVisible' value=$maxVisible[$k]}
{if $k eq 'category' && isset($smarty.get.id_category) && $filterCat}
	{assign var='curKey' value="id_category"}
{elseif  $k eq 'manufacturer' && isset($smarty.get.id_manufacturer) && $filterCat}
	{assign var='curKey' value="id_manufacturer"}
{else}
	{assign var='curKey' value=$k}
{/if}
{foreach from=$advancedsearch_value.$k item=item name=advancesearchvalue}
	{assign var='isSelect' value=$oAdvaceSearch->isSelected($smarty.get.$curKey, $item.0)}
	<span style="font-size:{$oAdvaceSearch->getSizeTagCloud($advancedsearch_total[$k],$item.2)}px" class="{if $item.2 <= 0 && !$isSelect}advcCritNotResult{/if}">{if $item.2 > 0 || $isSelect}<a href="{$oAdvaceSearch->getUrlWithMultipleSelect($k, $item.0, $SelectMulti[$k])}">{/if}{if $isSelect}<img src="{$img_dir}icon/delete.gif" alt="{l s='Delete' mod='blockadvancesearch_3'}" class="icon" />{/if}{$item.1}{if $displayNbProduct} ({$item.2}){/if}{if $item.2 > 0 || $isSelect}</a>{/if}</span>
	{if $submitMode == 2 && $isSelect}<input type="hidden" name="{$k}{if $SelectMulti[$k]}[]{/if}" value="{$item.0}" />{/if}
	{if !$smarty.foreach.advancesearchvalue.last}
	{counter}
	{/if}
{foreachelse}
{if $stepMode}
{if !isset($smarty.get.$prevK) || empty($smarty.get.$prevK)}
	<span class="advcStepPrevChoose">{l s='Veuillez choisir' mod='blockadvancesearch_3'} {$advancedsearch_label.$prevK.name}</span>
{else}
	<span class="advcNoChoice">{l s='Aucune proposition' mod='blockadvancesearch_3'}</span>
{/if}
{else}
	<span class="advcNoChoice">{l s='Aucune proposition' mod='blockadvancesearch_3'}</span>
{/if}
{/foreach}
</div>