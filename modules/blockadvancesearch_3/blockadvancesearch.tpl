{if $advancedsearch_value || $full_ajax}
<style type="text/css" media="all">
  .advcSelected {ldelim}background:transparent url({$img_dir}icon/selected.png) 3px 0 no-repeat;padding-bootm:1px;{rdelim}
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
<div id="AdvancedSearchBloc_{$duliq_id}" class="block" {if $smarty.const.site_version == "mobile"}style="left:-415px;"{/if} >
{*

 	<h4>{l s='Recherche avancée' mod='blockadvancesearch_3'}&nbsp;&nbsp;
	<noindex>
	<abbr title="В тестовом режиме могут найтись не все товары">
	<span style="font-size:7pt; cursor:help; border-bottom: 1px dotted white;">(тестовый режим)</span>
	</noindex>
	</abbr>

	</h4>
*}

	<div class="block_content">
		{if $smarty.const.site_version == "mobile"}
            <a id="open_close_a" href="javascript:" onclick="showmenu();">
            <div id="open_close">
                <img style="height: 20px" src="/img/menu.png">
            </div>
            </a>
    		
    		{literal}
    		<script>


// автомат выпадание меню фильтра на главной
//                if ($('body').attr('id') == 'index') 
//                {
//                    showmenu();
//                }

    		
        		function showmenu()
        		{
        			if (document.getElementById('AdvancedSearchBloc_3').style.left != '0px')
        			{
        				document.getElementById('AdvancedSearchBloc_3').style.left = '0';
        				document.getElementById('AdvancedSearchBloc_3').style.height = '100vh';
        				document.getElementById('AdvancedSearchBloc_3').style.top = '1vh';
                        $('#AdvancedSearchBloc_3').css("overflow", "scroll");
                        $('body').css("overflow", "hidden");
//        				document.getElementById('open_close_a').innerHTML = '<<';
        				//$('body').css('overflow','hidden');
        			}
        
        			else
        			{
        				document.getElementById('AdvancedSearchBloc_3').style.left = '-417px';
//        				document.getElementById('AdvancedSearchBloc_3').style.height = '8vh';
        				document.getElementById('AdvancedSearchBloc_3').style.top = '0px';        				
                        $('#AdvancedSearchBloc_3').css("overflow", "hidden");        				
                        $('body').css("overflow", "scroll");        				
//        				document.getElementById('open_close_a').innerHTML = '>>';
//        				document.getElementById('open_close').style.left = '-415px';        				        				
        			}
        
        		}


                scrolled1 = 0;
                window.onscroll = function() 
                {
                    scrolled = window.pageYOffset || document.documentElement.scrollTop;
                    if (scrolled > scrolled1+10) document.getElementById('open_close').style.top = '-51px';
                    if (scrolled < scrolled1-10) document.getElementById('open_close').style.top = '10px';
                    scrolled1 = scrolled;
                }

    		</script>{/literal}
		{/if}

		{if $full_ajax != 2}
			{include file=$formtpl|dirname|cat:"/blockadvancesearchmenu.tpl"}
		{/if}
	</div>
</div>
<!-- /Block advance search module -->
{/if}