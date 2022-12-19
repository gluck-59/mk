<!-- contact-form -->
{capture name=path}{l s='Contact'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2>{l s='Contact us'}</h2>

{if isset($confirmation)}
<script>
toastr.success('{l s='Your message has been successfully sent to our team.'}');
//setTimeout(window.location.href = "http://motokofr.com", 2000);
</script>

<p class="warning">{l s='Your message has been successfully sent to our team.'}</p>


<p align="center" style="margin:20px 0 50px">
<a href="{$base_dir}"><img class="icon" alt="{l s='Home'}" src="{$img_dir}icon/home.png"/></a><br><a href="{$base_dir}">{l s='Home'}</a>
</p>

{else}
	{include file=$tpl_dir./errors.tpl}
	<form name="mail" action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" class="std">
		<fieldset>
			<h3>{l s='Отдел исполнения желаний'}</h3>
			
			{if $contacts|@count > 1}
    			<p class="select" align="center">
    				<!--[if IE]><label for="id_contact">{l s='Subject'}</label><![endif]-->
    				<select id="id_contact" name="id_contact" onchange="showElemFromSelect('id_contact', 'desc_contact')">
    					<option value="0">{l s='-- Choose --'}</option>
    				{foreach from=$contacts item=contact}
    					<option value="{$contact.id_contact|intval}" {if isset($smarty.post.id_contact) && $smarty.post.id_contact == $contact.id_contact}selected="selected"{/if}>{$contact.name|escape:'htmlall':'UTF-8'}</option>
    				{/foreach}
    				</select>
    			</p>
    
    			<p id="desc_contact0" class="desc_contact">&nbsp;</p>
    			<br>
                {foreach from=$contacts item=contact}
        			<p align="center" id="desc_contact{$contact.id_contact|intval}" class="desc_contact" style="display:none;">
        			{$contact.description|escape:'htmlall':'UTF-8'}</p>
       			{/foreach}
    			<br>

            {else}
                <p hidden class="select" align="center">
    				<!--[if IE]><label for="id_contact">{l s='Subject'}</label><![endif]-->
    				<select autofocus id="id_contact" name="id_contact" onchange="showElemFromSelect('id_contact', 'desc_contact')">
    				{foreach from=$contacts item=contact}
    					<option value="{$contact.id_contact|intval}" {if isset($smarty.post.id_contact) && $smarty.post.id_contact == $contact.id_contact}selected="selected"{/if}>{$contact.name|escape:'htmlall':'UTF-8'}</option>
    				{/foreach}
    				</select>
    			</p>
    
    			<p hidden id="desc_contact0" class="desc_contact">&nbsp;</p>
                {foreach from=$contacts item=contact}
        			<p hidden align="center" id="desc_contact{$contact.id_contact|intval}" class="desc_contact" style="display:none;">
        			{$contact.description|escape:'htmlall':'UTF-8'}</p>
    			{/foreach}

            {/if}

		<p class="textarea" align="center">
			<!--[if IE]><label for="message">{l s='Message'}</label><![endif]-->
			 <textarea required placeholder="текст сообщения..." style="height: 90pt;" id="message" name="message" rows="7" cols="35">{if isset($smarty.post.message)}{$smarty.post.message|escape:'htmlall':'UTF-8'|stripslashes}{/if}</textarea>
		</p>
		
		<br>
		<p class="text" align="center">
			<!--[if IE]><label for="email">{l s='E-mail'}</label><![endif]-->
			<input required type="email" id="email" name="from" placeholder="...и e-mail для ответа" value="{if isset($smarty.cookies.email)}{$smarty.cookies.email}{/if}"/>
			<input hidden type="text" name="firstname" value="" placeholder="Введите имя">
		</p>
		<br>
		<p class="text" align="center">Письма на mail.ru НЕ ДОХОДЯТ. Пожалуйста используйте качественные почтовые сервисы.</p>

		<p class="submit"><center>
   			<div class="g-recaptcha" data-sitekey="6LcQNQ8TAAAAAIx9X8LF5blhDemfqG3RnKq3F9n4"></div>
   			<p>&nbsp;</p>
			<button type="submit" name="submitMessage" id="submitMessage" value="{l s='Send'}" class="ebutton large blue" />{l s='Send'}</button>
			</center>
		</p>
	</fieldset>
</form>

<!--div class="rte">

    <h2>Бесплатный звонок</h2>
    <p align="center">Введите свой телефон с кодом страны в любом формате. <br>Свободный оператор тут же перезвонит. Бесплатно.</p>
    {*<p align="center"><script src="http://www.comtube.com/button_template.php?hash=DlyZnG9b1t0lsT@KpIwYHg" charset="utf-8" type="text/javascript"></script></p>*}
    <script src="http://comtube.com/get_js.php?option=callme_api&lang=ru" charset="utf-8" type="text/javascript"></script>
    <p align="center">
    	<span style="font-size:12pt">+</span>
        <input placeholder="79876543210 " id="phonenum" type="text" onkeydown="callmeCheckEnter(event);" autocomplete="on"><br><br>
        <input class="ebutton large blue" type="button" onclick="callmeCall()" value="Позвонить">
    </p>    
        <p>&nbsp;</p>

    
    
    <h2>Чат</h2>
    <p align="center">Кнопка чата — в правом нижнем углу на всех страницах.
        <br /> Зайдите на страницу с интересующим ништяком и нажмите ее.</p>
    <p>&nbsp;</p>

</div-->

    <h2>Online-мессенджер</h2>
    
    <center>
        <p>Telegram:</p><br /> 
        <a href="https://telegram.me/motokofr" style=" border:0;">
            <img style="width:60px;" src="/img/telegram_logo.png" id="telegram" />
            </a>
        

{*
            <br /><br /><br /><p>Skype:</p>
            <script type="text/javascript" src="http://www.skypeassets.com/i/scom/js/skype-uri.js"></script>
            <div id="SkypeButton_Call_g.luck_1" style="margin: -2em">
              <script type="text/javascript">
                {literal}      
                Skype.ui({
                  "name": "chat",
                  "element": "SkypeButton_Call_g.luck_1",
                  "participants": ["g.luck"],
                  "imageSize": 32
                });
                {/literal}      
              </script>
            </div>
*}
    
            <!--br /><p>ICQ: 473726424</p><br />
            <img src="http://status.icq.com/online.gif?icq=473726424&amp;img=21" id="icq" /-->
    </center>
    
{/if}



{literal}
<script type="text/javascript">

function callmeInit() {
    clmAPI.setHash('DlyZnG9b1t0lsT@KpIwYHg');


    var failCallback = function(errObj) { 
        var str = errObj.err_desc;
        toastr.error(str);
    };
    
    var successCallback = function() { 
        var str = 'Минуточку, соединяемся...';
        toastr.success(str);
    };
    
    clmAPI.setCallSuccessCallback(successCallback)
    clmAPI.setCallFailCallback(failCallback)
}


function callmeCall()
{
    callmeInit();
    var phonenum = document.getElementById('phonenum').value;
//    var phonenum = parseInt(phonenum.replace(/\D+/g,""));    
    var phonenum = phonenum.replace(/\D+/g,"");    
    if (phonenum.length == 11)
    {
        clmAPI.call(parseInt(phonenum));
    }
    else
    {
        toastr.error('11 цифр с кодом страны', 'Ошибка в номере');
    }
}


function callmeCheckEnter(event)
{
    if (checkHitEnter(event)) 
    { 
        callmeCall(); 
    }
}

function checkHitEnter(evt) 
{ 
    evt = evt || window.event; 
    var key = evt.keyCode || evt.charCode || evt.which; 
    return (key == 13) 
}
</script>

<!-- Yandex.Metrika contact-form --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459746 = new Ya.Metrika({id:24459746, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/24459746" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika contact-form -->
{/literal}

<script src='https://www.google.com/recaptcha/api.js'></script>
{*
<script type="text/javascript" src="js/ajaxupload.js"></script>
    
<input value="Загрузка" class="ebutton blue" id="uploadButton" name="uploadButton" >
<font>Загрузка пока не работает</font>



<ol id="files"></ol>
        
        
<script>

      var button = $('#uploadButton'), interval;

      $.ajax_upload(button, {
            action : 'upload.php',
            name : 'myfile',
            onSubmit : function(file, ext) {
              // показываем картинку загрузки файла
              $("img#load").attr("src", "/img/loader.gif");
              $("#uploadButton font").text('Загрузка');

              /*
               * Выключаем кнопку на время загрузки файла
               */
              this.disable();

            },
            onComplete : function(file, response) {
              // убираем картинку загрузки файла
              $("img#load").attr("src", "loadstop.gif");
              $("#uploadButton font").text('Загрузить');

              // снова включаем кнопку
              this.enable();

              // показываем что файл загружен
              $("<li>" + file + "</li>").appendTo("#files");

            }
          });

    </script>

*}