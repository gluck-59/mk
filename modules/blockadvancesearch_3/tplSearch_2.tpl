<select name="{$k}" class="{if $advancedsearch_label.$k.is_color_group}color_picker_advc{else}bullet{/if}" style="margin:1%;width:98%;" {if !$full_ajax && $submitMode == 1}onchange="if($(this).children('option:selected').val())location.href = $(this).children('option:selected').val();"{/if}>
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
{if $smarty.foreach.advancesearchvalue.first}
<option value="{if $submitMode == 1}{$oAdvaceSearch->getUrlWithMultipleSelect($k, $smarty.get.$k, $SelectMulti[$k])}{else}0{/if}" style="font-weight:bold;">{if $stepMode}{l s='Veuillez faire un choix' mod='blockadvancesearch_3'}{else}{l s='Indifférent' mod='blockadvancesearch_3'}{/if}</option>
{/if}
	{assign var='isSelect' value=$oAdvaceSearch->isSelected($smarty.get.$curKey, $item.0)}

	{if !$curMaxVisible || $curVisible <= $curMaxVisible}
		<option value="{if $submitMode == 2}{$item.0}{else}{if $item.2 > 0}{$oAdvaceSearch->getUrlWithMultipleSelect($k, $item.0, false)}{/if}{/if}" {if $isSelect}selected="selected"{/if}>{$item.1}{if $displayNbProduct} ({$item.2}){/if}</option>
	{/if}
	{counter}
{foreachelse}
{if $stepMode}
		{if !isset($smarty.get.$prevK) || empty($smarty.get.$prevK)}
			<option value="">{l s='Veuillez choisir' mod='blockadvancesearch_3'} {$advancedsearch_label.$prevK.name}</option>
		{else}
			<option value="">{l s='Aucune proposition' mod='blockadvancesearch_3'}</option>
		{/if}

{else}
			<option value="">{l s='Aucune proposition' mod='blockadvancesearch_3'}</option>
{/if}
{/foreach}
</select>