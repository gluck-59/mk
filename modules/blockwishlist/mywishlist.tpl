<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

	{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account' mod='blockwishlist'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='My wishlists' mod='blockwishlist'}{/capture}
	{include file=$tpl_dir./breadcrumb.tpl}
<div id="mywishlist">
	<h2 id="cabinet">{l s='My wishlists - list' mod='blockwishlist'}</h2>

	{include file=$tpl_dir./errors.tpl}

	{if $id_customer|intval neq 0}
		{if $wishlists}
<div class="wishlist_column">
				{section name=i loop=$wishlists}
				
{if $smarty.const.site_version == "full"}				
					<div class="wishlist_list" id="wishlist_{$wishlists[i].id_wishlist|intval}">
						<h4>{$wishlists[i].name|escape:'htmlall':'UTF-8'}</h4>

						<p>
							{assign var=n value=0}
							{foreach from=$nbProducts item=nb name=i}
								{if $nb.id_wishlist eq $wishlists[i].id_wishlist}
									{assign var=n value=$nb.nbProducts|intval}
								{/if}
							{/foreach}
						<br>
						{if $n}
							<p class="price">{$n|intval}{declension nb="$n" expressions="ништяк,ништяка,ништяков"}</p>
						{else}
							<p class="price">В этом списке пусто</p>
						{/if}
						<br>
						</p>

						<!-- span class="align_center"><p style="padding-left: 0;">{$wishlists[i].counter|intval}</p></span -->
						{if $n}
    						<p id="wishlist-buttons">						
                                <a class="ebutton {if $n}blue{else}inactive{/if}" href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">Показать</a>
    						</p>
                        {/if}
                        <p id="wishlist-buttons">
						    <a class="ebutton red" href="javascript:;"onclick="return (WishlistDelete('wishlist_{$wishlists[i].id_wishlist|intval}', '{$wishlists[i].id_wishlist|intval}', '{l s='Do you really want to delete this wishlist ?' mod='blockwishlist'}'));">Удалить</a>
						</p>
					</div>
{/if}					
{if $smarty.const.site_version == "mobile"}
					{assign var=n value=0}
					{foreach from=$nbProducts item=nb name=i}
						{if $nb.id_wishlist eq $wishlists[i].id_wishlist}
							{assign var=n value=$nb.nbProducts|intval}
						{/if}
					{/foreach}
<a class="ebutton blue" href="javascript:;" onclick="javascript:WishlistManage('block-order-detail', '{$wishlists[i].id_wishlist|intval}');">{$wishlists[i].name|truncate:30:'...'|escape:'htmlall':'UTF-8'} ({$n|intval})</a>
{/if}					

				{/section}
</div>				
		<div id="block-order-detail">&nbsp;</div>
		
		{else}
			<div id="mywishlist" class="rte">
				<p>С помощью списка хотелок ты можешь создавать перечни товаров, которые ты хотел бы купить, но не сейчас. Так сказать, держать нужные товары в поле зрения "на будущее".<br><br>
				Кроме того ты можешь дать ссылку друзьям на твой список хотелок и они будут знать, что именно можно подарить тебе на день рождения ;)<br /><br />
				</p>
			</div>
		{/if}
		
		
		<form action="{$base_dir_ssl}modules/blockwishlist/mywishlist.php" method="post" class="std">
				<br><br><h2>{l s='Новый список' mod='blockwishlist'}</h2>
				<fieldset>
				<input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
<!--[if IE]><label class="align_right" for="name">{l s='Name' mod='blockwishlist'}</label><![endif]-->
				<input required style="width: 178px;" placeholder="Для моей Ямашки" type="text" id="name" name="name" value="{if isset($smarty.post.name) and $errors|@count > 0}{$smarty.post.name|escape:'htmlall':'UTF-8'}{/if}" />
				<input style="font-size: 10pt; height: 27px; width: 167px; " type="submit" name="submitWishlist" id="submitWishlist" value="{l s='Save' mod='blockwishlist'}" class="ebutton orange" />
			</fieldset>
		</form>
	{/if}
	
	<br><br><h2>Списки хотелок других байкеров</h2>
	<p align="center"><a style="width: 137px;" class="ebutton blue small" href="/wishlists.php">Посмотреть</a></p><br><br>
		

	
<table width="100%" border="0" style="margin-top: 30px;">
  <tr>
    <td width="50%"><div align="center"><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.png" alt="" class="icon" /></a></div></td>
    <td width="50%"><div align="center"><a href="{$base_dir}"><img src="{$img_dir}icon/home.png" alt="" class="icon" /></a></div></td>
  </tr>
  <tr>
    <td><div align="center"><a href="{$base_dir_ssl}my-account.php">В Кабинет</a></div></td>
    <td><div align="center"><a href="{$base_dir}">На главную</a></div></td>
  </tr>
</table>
</div>

{literal}<!-- Yandex.Metrika mywishlist --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459773 = new Ya.Metrika({id:24459773, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459773" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika mywishlist -->{/literal}