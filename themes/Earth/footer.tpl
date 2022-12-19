{if !$content_only}
    </div> {* div#center_column *}

    {* левая колонка для full отдается в header.tpl *}
    {if $smarty.const.site_version == "mobile"}
    <!-- Left -->
    	<div id="left_column" class="column">
    		{$HOOK_LEFT_COLUMN}
    	</div>
    {/if}			
    
    <!-- Right -->
    	<div id="right_column" class="column">
    		{$HOOK_RIGHT_COLUMN}
    	</div>


    {if $smarty.const.site_version == "full"}
    	<script type="text/javascript">
        {literal}    	

    	window.onload = function() 
    	{
            // vk comments
            if ($('body').attr('id') == 'product')
            {
            	VK.Widgets.Comments('vk_comments', {limit: {/literal}{$vklimit|intval}{literal}}, {/literal}{$vkid|intval}{literal});	
            }
        }
            
        {/literal}
        </script>
       
    {/if}

    
    {if $smarty.const.site_version == "mobile"}
    <!-- Block permanent links module HEADER -->
    	<div class="link">
    		<a href="{$base_dir}" title="{$shop_name|escape:'htmlall':'UTF-8'}"><p id="home">Главная</p></a>
    		<a href="{$base_dir}my-account.php"><p id="account">Кабинет</p></a>
    		<a href="{$base_dir}order.php"><p id="cart">Корзина</p></a>
    		<a href="{$base_dir}new-products.php"><p id="new">Свежачок</p></a>	
    		<a href="{$base_dir}best-sales.php"><p id="best-sales">Популярные ништяки</p></a>	
    		<a href="{$base_dir_ssl}contact-form.php" title="{l s='contact' mod='blockpermanentlinks'}"><p id="contact">Контакты</p></a>
    		<a href="{$base_dir}sitemap.php" title="{l s='sitemap' mod='blockpermanentlinks'}"><p id="sitemap">{l s='sitemap' mod='blockpermanentlinks'}</p></a>
    	</div>
        
        <noindex>    	
    	<script type="text/javascript">
        {literal}    	

    	window.onload = function() 
    	{
            // vk comments
            if ($('body').attr('id') == 'product')
            {
            	VK.Widgets.Comments('vk_comments', {limit: {/literal}{$vklimit|intval}{literal}}, {/literal}{$vkid|intval}{literal});	
            }
            
            // swiper фото товара
            if (typeof count =="undefined") count = 1;
            if (count > 1) // если фото товара больше 1 - запускаем
            {
                var swiper = new Swiper('.swiper-container', {
                    effect: 'coverflow',        
                    pagination: '.swiper-pagination',
                    nextButton: '.swiper-button-next',
                    prevButton: '.swiper-button-prev',
                    slidesPerView: count, // передается из product.tpl
                    centeredSlides: true,
                    paginationClickable: true,
                    spaceBetween: 60,
                    loop: true
                });
            }

        }

        window.onresize = function()
        {
            loadSwiper();
        }
        
            
        function loadSwiper()
        {
            // swiper blockviewed и wishlist
            delete swiper1;
            var swiper1 = new Swiper('.swiper-container1', {
                slidesPerView: Math.round(screen.height / 190),
                paginationClickable: true,
                spaceBetween: 10,
                loop: false
            });
        }
        
        </script>

    
    	
        <!-- Yandex.Metrika общий mobile --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24648656 = new Ya.Metrika({id:24648656, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24648656" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
        <!-- /Yandex.Metrika общий mobile -->
    	
        {/literal}
    {/if}
    
    
    
    <!-- SEO schema -->
    <footer hidden itemscope itemtype="//schema.org/LocalBusiness">
        <img itemprop="image" src="//motokofr.com/img/logo/logo.png">
        <span itemprop="url">https://motokofr.com</span>.
        <elem itemprop="priceRange">RUB</elem>
        ЕООД «<span itemprop="name">Мотокофр</span>»
        <div itemprop="address" itemscope itemtype="//schema.org/PostalAddress">
            <span itemprop="addressLocality">Москва</span>,
            <span itemprop="addressRegion">Москва</span>
            <meta itemprop="addressCountry" content="RU"/>
        </div>
        Время работы менеджера-консультанта:
        <time itemprop="openingHours" datetime="Mo, Tu, We, Th, Fr, Sa, Su 10:00-0:00">10:00 - 20:00 Ежедневно</time>
        <span itemprop="telephone">+359892740300</span>.
        <span itemprop="email">support(at)motokofr.com</span>.
        <meta itemprop="logo" content="//motokofr.com/img/logo/logo_paypal.png"/>
    </footer>	
    		
    {if $smarty.const.site_version == "full"}
        	<div id="footer" align="center"></div>
        	
            <!-- Footer -->
        	<div id="footer-content">
        		{$HOOK_FOOTER}
            </div>
       	</div> {* div#page *}
    	{/if}


    	{* gdeposylka 
   		<script type="text/javascript">
   		<!-- gp_wdg_title    = "Motokofr.com";
   			document.write('<sc'+'ript type="text/javascript" src="/modules/blocklink/blocklink.js"></sc'+'ript>'); //-->
   		</script>
    	{* http://cdn.gdeposylka.ru/assets/js/widgets/packed.widget.v2.js *}	
    	
    	
    	{literal}
    	<!-- Yandex.Metrika общий full --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter924826 = new Ya.Metrika({id:924826, webvisor:true, clickmap:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/924826" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    	<!-- /Yandex.Metrika общий full  -->
    	{/literal}
    	

    	<div class="nonprintable" id="scroller"></div>
    {/if}
    
    
    
    {*literal}
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
      "@type": "WebSite",
      "name": "motokofr.com",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "http://motokofr.com/search.php?orderby=position&orderway=desc&search_query={q}&Submit=",
        "query-input": "required name=q"
      }
    }
    </script>
    {/literal*}
    
    {if $smarty.const.site_version == "full"}
    {literal}
    <script type="text/javascript">
    // грузим reformal        
        var reformalOptions = {
            project_id: 18022,
            project_host: "motokofr.reformal.ru",
            tab_orientation: "top-right",
            tab_indent: "10px",
            tab_bg_color: "#ff7733",
            tab_border_color: "#FFFFFF",
    //        tab_image_url: "http://tab.reformal.ru/T9GC0LfRi9Cy0Ysg0Lgg0L%252FRgNC10LTQu9C%252B0LbQtdC90LjRjw==/FFFFFF/9c87995e4a804015c7fcd66342939545/top-right/0/tab.png",
            tab_border_width: 0
        };
        
        (function() {
            var script = document.createElement('script');
            script.type = 'text/javascript'; script.async = true;
            script.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'motokofr.com/js/reformal.js'; 
            document.getElementsByTagName('head')[0].appendChild(script);
        })();
    </script><noindex><noscript><a href="https://reformal.ru">Отзывы и предложения</a><a href="//motokofr.reformal.ru">Oтзывы и предложения</a></noscript></noindex>
    
    
    <script>
    // грузим header background
    if (document.getElementById('header'))
    {
       season=new XMLHttpRequest();
       season.onreadystatechange=function()
       {
        if (season.readyState==4 && season.status==200)
        {
            document.getElementById('header').style.background = 'url(/themes/Earth/img/back/'+season.responseText+')';
        }
       }
       season.open('GET','/themes/Earth/img/back/season.php', true);
       season.send(season.responseText);
    }
    </script>
    {/literal}
    {/if}




</noindex>
</body>
</html>