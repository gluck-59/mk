<!------------------------------------------
----------------------------------------
blockAdvanceSearchV3 - Couvert Jean-Sébastien - Presta-Module - http://www.presta-module.com/
----------------------------------------
------------------------------------------->
{if $advancedsearch_value || $full_ajax}
<style type="text/css" media="all">
  .advcSelected {ldelim}background:transparent url({$img_dir}icon/delete.gif) 5px 0 no-repeat!important;{rdelim}
  {if $smarty.const._PS_VERSION_ >= 1.3}
	#header {ldelim}position:relative!important;zoom:1;height:180px;z-index:100;{rdelim}
	{else}
#header {ldelim}height:180px;z-index:100;{rdelim}
#page {ldelim}position:relative!important;zoom:1;{rdelim}
	{/if}
{include file=$formtpl|dirname|cat:"/css/styles.tpl"}
</style>
<script type="text/javascript" src="{$this_path}js/advancesearch.js"></script>
{if $full_ajax}
<script type="text/javascript" src="{$this_path}js/jqueryForm.js"></script>
<script type="text/javascript">
	$(document).ready(function() {ldelim}
	blockAdvanceSearch_{$duliq_id}.advcAjaxMode({if $full_ajax == 2}true{/if});
	{rdelim});
	blockAdvanceSearch_{$duliq_id}.submitMode = {$submitMode|intval};
	blockAdvanceSearch_{$duliq_id}.submitOptions = {ldelim}
        target:        '#center_column',   // target element(s) to be updated with server response
        beforeSubmit:  blockAdvanceSearch_{$duliq_id}.showRequestAdvcSearchSubmit,  // pre-submit callback
        success:       blockAdvanceSearch_{$duliq_id}.showResponseAdvcSearchSubmit,  // post-submit callback
 		type:      'get',
 		url:       '{$this_path}advancesearch.php?advc_ajax_mode=1&dupliq_id={$duliq_id}'
    {rdelim};
    blockAdvanceSearch_{$duliq_id}.submitOptionsBloc = {ldelim}
    	target:        '#AdvancedSearchBloc_{$duliq_id} .block_content',   // target element(s) to be updated with server response
    	beforeSubmit:  blockAdvanceSearch_{$duliq_id}.showRequestAdvcBloc,  // pre-submit callback
   		success:       blockAdvanceSearch_{$duliq_id}.showResponseAdvcBloc,  // post-submit callback
		type:      'get',
		url:       '{$this_path}advancesearch.php?advc_ajax_mode=1&advc_get_block=1&dupliq_id={$duliq_id}'
	{rdelim};
	var ADVC_WaitMsg_{$duliq_id} = "{l s='Veuillez patienter' mod='blockadvancesearch_3' js=1}";
</script>
{/if}
<!-- Block advance search module -->
<div id="AdvancedSearchBloc_{$duliq_id}">
	<div class="advc_header_title">{l s='Recherche avancée' mod='blockadvancesearch_3'}</div>
	<div class="block_content">
		{if $full_ajax != 2}
			{include file=$formtpl|dirname|cat:"/blockadvancesearchmenu-horizontal.tpl"}
		{/if}
	</div>
</div>
<!-- /Block advance search module -->
{/if}