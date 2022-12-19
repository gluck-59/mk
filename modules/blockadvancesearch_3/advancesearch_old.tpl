{capture name=path}{l s='Votre Recherche' mod='blockadvancesearch_3'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<noindex>
<h2>{l s='Votre Recherche' mod='blockadvancesearch_3'}
<span>{$nbProducts|intval}&nbsp;{declension nb="$nbProducts|intval" expressions="ништяк,ништяка,ништяков"}</span>
</h2>
</noindex>

{*<form method="get" action="{$smarty.server.REQUEST_URI}">*}
<span id="advcCurSelection_{$duliq_id}">&nbsp;</span>&nbsp;
	{if $smarty.server.SCRIPT_NAME == $this_path|cat:'advancesearch.php' && count($smarty.get)}
	<a class="ebutton {if $smarty.const.site_version == "full"}mini {/if}blue" style="text-decoration:none" href="{$this_path}advancesearch.php">Сброс</a>
	{/if}
	

{*<input autofocus class="filter" type="text" name="filter" id="filter"  placeholder="Что искать будем
	 {if isset($smarty.cookies.firstname)}, {$smarty.cookies.firstname}{/if}?"  >
*}
	 
<input required autofocus class="filter" type="text" name="filter" id="filter"  placeholder="{if !empty($placeholder)}{$placeholder}{else}Что искать будем
	 {if isset($smarty.cookies.firstname)}, {$smarty.cookies.firstname}{/if}?{/if}">

{* выведем свои подсказки для каждого userselect *}
{*$tips|print_r*}
{if $tips}
	<p class="tips">
		{foreach from=$tips item=tip name=tips}
			&nbsp;<a class="tips" rel="{$tip|trim}" onclick="setAttr('filter',getAttr('filter')+'{$tip|trim}')">{$tip|trim}</a>&nbsp;
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
	
	{*include file=$tpl_dir./product-sort.tpl*} 
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
				scrollTop: 285
			}, 400);
		
			setTimeout("document.getElementById('product_raise').style.backgroundColor = '#fff'", 500);
		});
		
	</script>
	{/literal}
{/if}



{literal}
<script language="JavaScript">
	jQuery(document).ready(function()
	{   
		$("#filter").keypress(function(e)
		{
			if(e.keyCode==13)
			{
				setAttr('filter',getAttr('filter')+this.value);
				
			}
		});
	});

function getAttr(prmName) 
{ 
   //var $_GET = {}; 
   var d = window.location.search.substring(1).split("&"); 
   for(var i=0; i< d.length; i++) { 
      var getVar = d[i].split("="); 
      if (getVar[0]==prmName)
      {
        //return typeof(getVar[1])=="undefined" ? "" : getVar[1]
        return getVar[1]+'|';
      }
      //$_GET[getVar[0]] = typeof(getVar[1])=="undefined" ? "" : getVar[1]; 
   } 
   return '';
   //return $_GET; 
} 

function setAttr(prmName,val)
{
    var res = '';
	var d = location.href.split("#")[0].split("?");  
	var base = d[0];
	var query = d[1];
	if(query) {
		var params = query.split("&");  
		for(var i = 0; i < params.length; i++) {  
			var keyval = params[i].split("=");  
			if(keyval[0] != prmName) {  
				res += params[i] + '&';
			}
		}
	}
	
	if (val != '') 	
	{	
		var val = val.replace(/ /g, '|')
		res += prmName + '=' + val;
		window.location.href = base + '?' + res;
		return false;
	}
}
		
	
</script>
{/literal}
