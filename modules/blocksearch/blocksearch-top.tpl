{if $smarty.const.site_version == "full"}
    <!-- Block search module TOP -->
    {* строка поиска в режиме mobile выводится в header.tpl *}
    
    
{* рабочий *}
    <div id="search_block_top" />
    	<form method="get" action="{$base_dir}search.php" id="searchbox"  style="display: inline-flex;">
            <input required type="text" id="search_query" name="search_query" value="{if $smarty.get.search_query}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{/if}" />
    		<input type="hidden" name="orderby" value="position" />
    		<input type="hidden" name="orderway" value="desc" />
            &nbsp;&nbsp;
            <input id="submit" type="submit" name="Submit" value=""/>
    	</form>
    </div>


{* экспериментальный 
<div id="search_block_top" itemscope itemtype="http://schema.org/WebSite" />
  <meta itemprop="url" content="http://motokofr.com/"/>

  <form method="get" action="{$base_dir}search.php" id="searchbox" itemprop="potentialAction" itemscope itemtype="http://schema.org/SearchAction">
        <meta  itemprop = "target"  content = "http://motokofr.com/search.php?search_query={literal}{search_query}{/literal}" /> 

        <input required type="text" id="search_query" name="search_query" 
 value="{if $smarty.get.search_query}{$smarty.get.search_query|htmlentities:$ENT_QUOTES:'utf-8'|stripslashes}{/if}"
 itemprop="query-input" />

    <input type="hidden" name="orderby" value="position" />
    <input type="hidden" name="orderway" value="desc" />

<input id="submit" type="submit" name="Submit" value=""/>
  </form>
  </div>

    
    {literal}<script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "url": "https://www.motokofr.com/",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "http://motokofr.com/search.php?search_query={search_term_string}",
        "query-input": "required name=search_term_string"
      }
    }
    </script>{/literal*}
    
    
    {if $ajaxsearch}
    	<script type="text/javascript">
    		{literal}
    		
    		function formatSearch(row) {
    			return row[2] + ' > ' + row[1];
    		}
    
    		function redirectSearch(event, data, formatted) {
    			$('#search_query').val(data[1]);
    			document.location.href = data[3];
    		}
    		
    		$('document').ready( function() {
    			$("#search_query").autocomplete(
    				'{/literal}{$base_dir}{literal}search.php', {
    				minChars: 1,
    				max:100,
    				width:600,
    				scroll: false,
    				formatItem:formatSearch,
    				extraParams:{ajaxSearch:1,id_lang:3}
    			}).result(redirectSearch)
    		});
    		{/literal}
    	</script>
    {/if}
    <!-- /Block search module TOP -->	
{/if}
