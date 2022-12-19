{capture name=path}{l s='Forgot your password'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h1>{l s='Forgot your password'}</h1>

{include file=$tpl_dir./errors.tpl}
<div class="rte">    
    {if isset($confirmation)}
        <p class="warning">{l s='Your password has been successfully reset and has been sent to your e-mail address:'} {$email|escape:'htmlall':'UTF-8'}</p>
    
    {else}
    <p>{l s='Please enter your e-mail address used to register. We will e-mail you your new password.'}</p>
    <p>&nbsp;</p>
    <form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="std">
    	<fieldset>
    		<p align="center" class="text">
                <!--[if IE]><label for="email">{l s='Type your e-mail address:'}</label><![endif]-->
    			<input required type="email" id="email" name="email" placeholder="Твой email" value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'htmlall'|stripslashes}{/if}" />
    		</p>
    		<br>
    		<p align="center" class="submit">
    			<input type="submit" class="ebutton blue" value="{l s='Retrieve'}" />
    		</p>
    	</fieldset>
    </form>
    {/if}
</div>
<br>
<p align="center">
	<a href="{$base_dir_ssl}authentication.php" title="{l s='Back to Login'}"><img src="{$img_dir}icon/my-account.png" alt="{l s='Back to Login'}" class="icon" /></a><br><a href="{$base_dir}authentication.php" title="{l s='Back to Login'}">{l s='Back to Login'}</a>
</p>

{literal}
<!-- Yandex.Metrika counter lostpassword --><script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter32884220 = new Ya.Metrika({ id:32884220, clickmap:true, trackLinks:true, accurateTrackBounce:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/32884220" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->
{/literal}