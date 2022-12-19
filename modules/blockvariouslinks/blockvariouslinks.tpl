<!-- MODULE Block various links -->
<div id="footer-links" align="center">
<ul class="block_various_links" id="block_various_links_footer">
 	<li class="first_item">&nbsp;<a href="{$base_dir_ssl}contact-form.php" title="">{l s='Contact us' mod='blockvariouslinks'}</a>&nbsp;</li>
 		{foreach from=$cmslinks item=cmslink}
		<li class="item"><a href="{$cmslink.link|addslashes}" title="{$cmslink.meta_title|escape:'htmlall':'UTF-8'}">{$cmslink.meta_title|escape:'htmlall':'UTF-8'}</a>&nbsp;</li>
	{/foreach}
	<li class="item">&nbsp;<a href="{$base_dir}prices-drop.php" title="">{l s='Specials' mod='blockvariouslinks'}</a>&nbsp;</li>
	<li class="item">&nbsp;<a href="{$base_dir}new-products.php" title="">{l s='Свежачок' mod='blockvariouslinks'}</a>&nbsp;</li>
	<li class="item">&nbsp;<a href="{$base_dir}best-sales.php" title="">{l s='ТОП-50' mod='blockvariouslinks'}</a>&nbsp;</li> 
 	<li class="item">&nbsp;<a href="{$base_dir_ssl}sitemap.php" title="Карта сайта">{l s='Карта сайта' mod='blockvariouslinks'}</a>&nbsp;</li>
</ul>
</div>