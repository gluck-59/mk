{if $smarty.const.site_version == "full"}
<!-- Block permanent links module HEADER -->
<ul id="header_links">
	<li id="header_link_contact"><a href="{$base_dir_ssl}contact-form.php" title="{l s='contact' mod='blockpermanentlinks'}">{l s='contact' mod='blockpermanentlinks'}</a></li>
	{*<li id="header_link_sitemap"><a href="{$base_dir}sitemap.php" title="{l s='sitemap' mod='blockpermanentlinks'}">{l s='sitemap' mod='blockpermanentlinks'}</a></li>*}
	<li id="header_link_sitemap"><a href="{$base_dir}cms.php?id_cms=1" title="{l s='доставка' mod='blockpermanentlinks'}">{l s='доставка' mod='blockpermanentlinks'}</a></li>	
	<li id="header_link_sitemap"><a href="{$base_dir}cms.php?id_cms=5" title="{l s='оплата' mod='blockpermanentlinks'}">{l s='оплата' mod='blockpermanentlinks'}</a></li>		
{*	<li id="header_link_bookmark">
<!--		<script type="text/javascript">writeBookmarkLink('{$come_from}', '{$shop_name|addslashes|addslashes}', '{l s='bookmark' mod='blockpermanentlinks'}');</script> -->
 
	<a href="#" onclick="return add_favorite(this);">в закладки&nbsp;</a>
	</li>*}
	
	<li id="header_links_podbor">	
	<a href="http://motokofr.com/cms.php?id_cms=6">&nbsp;подбор кофров</a>
	</li>
</ul>

{literal}
<script defer>
function add_favorite(a) 
{

alert('Нажмите Ctrl-D чтобы добавить страницу в закладки');

}
</script>
{/literal}


{/if}

{* в mobile версии этот блок отдается в footer.tpl *}