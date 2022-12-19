<ul class="{if $advancedsearch_label.$k.is_color_group && count($advancedsearch_value.$k)}color_picker_advc{else}bullet{/if} advcSearchList">
{counter start=1 skip=1 print=false assign=curVisible}
{assign var='curMaxVisible' value=$maxVisible[$k]}
{if $k eq 'category' && isset($smarty.get.id_category) && $filterCat}
	{assign var='curKey' value="id_category"}
{elseif  $k eq 'manufacturer' && isset($smarty.get.id_manufacturer) && $filterCat}
	{assign var='curKey' value="id_manufacturer"}
{elseif  $k eq 'supplier' && isset($smarty.get.id_supplier) && $filterCat}
	{assign var='curKey' value="id_supplier"}
{else}
	{assign var='curKey' value=$k}
{/if}
{foreach from=$advancedsearch_value.$k item=item name=advancesearchvalue}
	{assign var='isSelect' value=$oAdvaceSearch->isSelected($smarty.get.$curKey, $item.0)}
	<li ident="{if !$advancedsearch_label.$k.is_color_group}{$item.0}{/if}" title="{if !$advancedsearch_label.$k.is_color_group}{$item.1}{/if}" style="{if $advancedsearch_label.$k.is_color_group}background:{$item.color}{if file_exists($col_img_dir|cat:$item.0|cat:'.jpg')} url({$img_col_dir}{$item.0}.jpg){/if};{/if}" class="{if $curMaxVisible && $curVisible > $curMaxVisible}advcHideCrit{/if}{if $item.2 <= 0 && !$isSelect} advcCritNotResult{/if}{if $isSelect && !$advancedsearch_label.$k.is_color_group} advcSelected{/if}{if $isSelect} advcDelete{/if}">
	{if $item.2 > 0  || $isSelect}
	<a href="{$oAdvaceSearch->getUrlWithMultipleSelect($k, $item.0, $SelectMulti[$k])}">{/if}{if !$advancedsearch_label.$k.is_color_group}{$item.1}
	
	{if $displayNbProduct} <span class="advancedsearch">{$item.2}</span>{/if}
	
	{else}{if $isSelect}<img src="{$img_dir}icon/delete.gif" alt="" />{/if}{/if}{if $item.2 > 0  || $isSelect}</a>{/if}
	{if $submitMode == 2 && $isSelect}<input type="hidden" name="{$k}{if $SelectMulti[$k]}[]{/if}" value="{$item.0}" />{/if}
	
	</li>
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

{if $curMaxVisible && $curVisible > $curMaxVisible}
{* не работающий раскрыватель списка
<span class="advcViewMoreToogle"><a href="javascript:void(0)" onclick="blockAdvanceSearch_{$duliq_id}.advcViewMoreToogle(this,'{l s='+ de critères' mod='blockadvancesearch_3'}','{l s='- de critères' mod='blockadvancesearch_3'}')">{l s='+ de critères' mod='blockadvancesearch_3'}</a></span>
*}
<noindex>
<span class="advcViewMoreToogle" id="tablet">Длинный тап раскрывает список</span>
<span class="advcViewMoreToogle" id="desktop">Наведи мышь на список</span>
</noindex>

{/if}
<div class="clear"></div>

