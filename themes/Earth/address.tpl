<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>
<link href="/themes/Earth/css/suggestions.css" type="text/css" rel="stylesheet" />
<!--[if lt IE 10]>
<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery-ajaxtransport-xdomainrequest/1.0.1/jquery.xdomainrequest.min.js"></script>
<![endif]-->

{capture name=path}{l s='Your addresses'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2 id="cabinet">{l s='Your addresses'}</h2>

<!-- h3>{if isset($id_address)}{l s='Modify the address'} {if isset($smarty.post.alias)}"{$smarty.post.alias}"{elseif $address->alias}"{$address->alias|escape:'htmlall':'UTF-8'}"{/if}{else}{l s='Здесь можно добавить или изменить адрес доставки посылок'}{/if}</h3-->

{include file=$tpl_dir./errors.tpl}

<p class="required" style="text-indent: 6px;" ><sup>&bull;</sup> {l s='Непременно!'}</p>
<br>

<form action="{$base_dir_ssl}address.php" method="post" class="add_address">
	<div class="add_address">
		{*<h3>{if isset($id_address)}{l s='Your address'}{else}{l s='New address'}{/if}</h3><br>*}

		<fieldset class="add_address">
			<legend for="firstname"><sup>&bull;</sup>&nbsp;&nbsp;{l s='First name'}</legend>
			<input required type="text" name="firstname" id="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{else}{$address->firstname|escape:'htmlall':'UTF-8'}{/if}" />
		</fieldset>
	
		<fieldset class="add_address">
			<legend for="lastname"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Last name'}</legend>
			<input required type="text" id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{else}{$address->lastname|escape:'htmlall':'UTF-8'}{/if}" />
		</fieldset>

		<fieldset class="add_address">
			<legend for="phone"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Home phone'}</legend>
			<input type="text" placeholder="12 цифр" id="phone" name="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{else}{$address->phone|escape:'htmlall':'UTF-8'}{/if}" />
		</fieldset>
		<p style="margin-top: -30px;  float: right;"><a class="tips" id="model" onclick="javascript:$('#inn').toggle(100)">зачем это?</a></p>
		<p hidden class="textarea" id="inn" style="overflow: hidden; margin-bottom: 20px;">
		Нам очень жаль, но согласно приказу №1861 ФТС России с 7 декабря 2017 года все граждане Российской Фередации обязаны указывать свой ИНН при покупках через интернет.<br>
		Все посылки без указания ИНН будут отправлены обратно.<br><br>
       		<a style="text-align: center" class="ebutton blue" href="http://customs.ru/images/stories/2017/December/prikaz_fts_1861.pdf" target="_blank">Ссылка на приказ</a>&nbsp;
            <a style="text-align: center" class="ebutton orange" href="https://service.nalog.ru/inn.do" target="_blank">Узнать мой ИНН</a><br>
		</p>		
				
		<fieldset class="add_address">
			<legend for="other">{l s='Additional information'}</legend>			
			<input id="other" name="other" value="{if isset($smarty.post.other)}{$smarty.post.other}{else}{$address->other|escape:'htmlall':'UTF-8'}{/if}" placeholder="чтобы все точно подошло" />
		</fieldset>
		<p style="margin-top: -30px;  float: right;"><a class="tips" id="model" onclick="javascript:$('#model_tip').toggle(100)">а это еще зачем?</a></p>
		<p hidden class="textarea" id="model_tip" style="overflow: hidden; margin-bottom: 20px;">
		Это необязательно. Мы сравним заказанные ништяки с твоим мотоциклом. <br>И если ты ошибешься при выборе ништяка, будем уточнять.
		</p>
		
	</div>		

		<fieldset class="add_address" id="fsugaddress" style="display:none;-webkit-transition: all 0.3s ease; ">
			<legend for="sugaddress" id="lsugaddress">{l s='Начни вводить адрес'}</legend>
			<div class="rte">			
			<input autofocus id="sugaddress" name="sugaddress" type="text" style="width:99%;" placeholder="Забыл свой индекс? Начни вводить адрес, а индекс мы возьмем на себя" onblur="onblurit();" onfocus="onfocusit();" value="{if $city && !$address->address1 && !$address->city && !$address->postcode}{$city}{/if}" />
			</div>
		</fieldset>

	<div class="add_address">

		<fieldset class="add_address" id="paddress1" >
			<legend for="address1"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Address'}</legend>
				<input required type="text" id="address1" name="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{else}{$address->address1|escape:'htmlall':'UTF-8'}{/if}" placeholder ="" />
		</fieldset>
		
		<fieldset class="add_address" id="pcity" style="opacity:1;" >		
			<legend for="city"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Город, область'}</legend>			
			<input required id="city" type="text" class="city" name="city" placeholder="" value="{if isset($smarty.post.city)}{$smarty.post.city}{else}{$address->city|escape:'htmlall':'UTF-8'}{/if}" />
		</fieldset>
		
		<fieldset class="add_address" id="ppostcode" >
			<legend for="postcode"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Postal code / Zip code'}</legend>			
			<input required type="text" class="postcode" id="postcode" name="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{else}{$address->postcode|escape:'htmlall':'UTF-8'}{/if}" placeholder="" />
			<span style="float:none" id="postindex_result"></span>
		</fieldset>
			
		<p class="required select">&nbsp;</p>
		<p class="required select">
			<sup>&bull;</sup>&nbsp;&nbsp;<label for="id_country">{l s='Country'}</label>&nbsp;&nbsp;&nbsp;
			<select id="id_country" name="id_country">{$countries_list}</select>
		</p>
		
		{*<p class="id_state required select">
			<label for="id_state">{l s='State'}</label>
			<select name="id_state" id="id_state">
				<option value="">-</option>
			</select>
			<sup>&bull;</sup>
		</p>*}


<br><br>
	</div>

<div class="add_address">

<div {*if $address->phone_mobile or $address->address2 or $address->company}"javascript:$('#textbl1').show(100)"{/if*} >
<br><br>

	<fieldset class="add_address">		
		<legend for="phone_mobile">{l s='Mobile phone'}</legend>			
		<input type="text" id="phone_mobile" name="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{$address->phone_mobile|escape:'htmlall':'UTF-8'}{/if}" placeholder=""/>
	</fieldset>

	<fieldset class="add_address">		
		<legend for="address2">{l s='Address (2)'}</legend>
		<input type="text" id="address2" placeholder="" name="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{$address->address2|escape:'htmlall':'UTF-8'}{/if}" />
	</fieldset>
		<p style="margin-top: -30px;  float: right;"><a class="tips" id="model"  onclick="javascript:$('#passport').toggle(100)">зачем это?</a></p>

	<fieldset class="add_address">		
		<legend for="company">{l s='Company'}</legend>
		<input type="hidden" name="token" value="{$token}" />
		<input type="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{$address->company|escape:'htmlall':'UTF-8'}{/if}" placeholder="1234, №123456, выд. {$smarty.now|date_format:"%d-%m-%Y"} Бюрократическим ОВД г. Бабруйска" />
	</fieldset>

	<p style="margin-top: -30px;  float: right;"><a class="tips" id="model"  onclick="javascript:$('#passport').toggle(100)">а это еще зачем?</a></p>
	<p  hidden class="textarea" id="passport" style="overflow: hidden;">
		Отчество и паспорт — новое требование таможни, введенное в 2014 году. <br>Только для курьерской доставки: обычных почтовых посылок не касается.
	</p>
</div>


<p class="clear">&nbsp;</p><p class="clear">&nbsp;</p>

	<fieldset class="add_address" id="adress_alias">		
		<legend for="alias"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Assign an address title for future reference'}</legend>
		<input type="text" id="alias" name="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{elseif $address->alias}{$address->alias|escape:'htmlall':'UTF-8'}{elseif isset($select_address)}{l s='My address'}{else}{l s='My address'}{/if}" />
	</fieldset>
</div>

	<input type="checkbox" id="pdn" checked name="pdn">&nbsp;&nbsp;<span>Я даю согласие на обработку моих персональных данных</span>
	<br><br>
		
		<p class="pdn">
		Я даю согласие Motokofr.com на обработку персональных данных в целях выполнения моих заказов, включая осуществление действий (операций) с моими персональными данными, такими как сбор, запись, систематизацию, накопление, хранение, уточнение (обновление, изменение), извлечение, использование, передачу (предоставление), обезличивание, блокирование, удаление в электронной форме.
		</p>
		<p class="pdn">
		Настоящее согласие действует со дня его подписания до момента достижения цели обработки персональных данных или его отзыва. Мне разъяснено, что настоящее согласие может быть отозвано путем удаления данного адреса.
		</p>

<p class="clear">&nbsp;</p>
<p class="clear">&nbsp;</p>

	<p id="submit2" style="text-align:center">
		{if isset($id_address)}<input type="hidden" name="id_address" value="{$id_address|intval}" />{/if}
		{if isset($back)}<input type="hidden" name="back" value="{$back}?step=1" />{/if}
		{if isset($select_address)}<input type="hidden" name="select_address" value="{$select_address|intval}" />{/if}
		<input type="submit" name="submitAddress" id="submitAddress" value="{l s='Save'}" class="ebutton orange large" />
	</p>
</form>

{*<script src='//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js'></script>*}
<script type="text/javascript">
// <![CDATA[
idSelectedCountry = {if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}false{/if};
countries = new Array();
{foreach from=$countries item='country'}
	{if isset($country.states)}
		countries[{$country.id_country|intval}] = new Array();
		{foreach from=$country.states item='state' name='states'}
			countries[{$country.id_country|intval}]['{$state.id_state|intval}'] = '{$state.name}';
		{/foreach}
	{/if}
{/foreach}
$(function(){ldelim}
	$('.id_state option[value={if isset($smarty.post.id_state)}{$smarty.post.id_state}{else}{$address->id_state|escape:'htmlall':'UTF-8'}{/if}]').attr('selected', 'selected');
{rdelim});
//]]>
</script>

{literal}
<script>
// транслитерация
function toTranslit(text) {
  return text.replace(/([а-яё])|([\s_-])|([^a-z\d])/gi,
  function (all, ch, space, words, i) {
    if (space || words) {
      return space ? ' ' : '';
    }
    var code = ch.charCodeAt(0),
      index = code == 1025 || code == 1105 ? 0 :
        code > 1071 ? code - 1071 : code - 1039,
      t = ['yo', 'a', 'b', 'v', 'g', 'd', 'e', 'zh',
        'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
        'r', 's', 't', 'u', 'f', 'h', 'c', 'ch', 'sh',
        'shch', '', 'y', '', 'e', 'yu', 'ya'
      ]; 
    return t[index];
  });
}


// коррекция гуглозаполнятеля
function firstUpper(text)
{
// здесь вставляются пробелы и запятые
s = text.charAt(0).toUpperCase() + text.substr(1)
s = s.replace("Russia,","");
s = s.replace("oblast","obl, ");
s = s.replace("respublika",", ");
//s = s.replace("kray","kray ")
s = s.replace("avtonomnyy okrug","AO, ")
s = s.replace("rayon","r-n, ")
return s;
}
</script>

<script type="text/javascript">

var xhr = new XMLHttpRequest();
xhr.open("GET", 'https://dadata.ru/api/v2/version', false);
xhr.send(null); 
if (xhr.readyState == 4) 
{
	document.getElementById('fsugaddress').style.display = 'block';
	$("#sugaddress").suggestions({
        serviceUrl: "https://dadata.ru/api/v2",
        token: "0a4626882bbbd48b65b3d11beb6e1332aa0a366c",
        type: "ADDRESS",
        count: 10,
        /* Вызывается, когда пользователь выбирает одну из подсказок */
        onSelect: function(suggestion) {

			if(suggestion.data.postal_code != null) 
			{
			document.getElementById('ppostcode').style.display = 'block';
			document.getElementById('postcode').value = suggestion.data.postal_code;
			}
			
			if(suggestion.data.country != null) 
			{
				document.getElementById('city').value = firstUpper(toTranslit(suggestion.data.region_with_type));
				if(suggestion.data.city != null) document.getElementById('city').value += ', ' + firstUpper(toTranslit(suggestion.data.city));
				if(suggestion.data.area_with_type != null) document.getElementById('city').value += ', ' + firstUpper(toTranslit(suggestion.data.area_with_type)) + '. ' + firstUpper(toTranslit(suggestion.data.area_with_type));								
				if(suggestion.data.settlement != null) document.getElementById('city').value += ', ' + toTranslit(suggestion.data.settlement_type) + '. ' + firstUpper(toTranslit(suggestion.data.settlement));

			}
			
			if(suggestion.data.street != null) document.getElementById('address1').value = firstUpper(toTranslit(suggestion.data.street));
			if(suggestion.data.house != null) document.getElementById('address1').value += ', ' + suggestion.data.house;			
			if(suggestion.data.flat != null) document.getElementById('address1').value += '-' + suggestion.data.flat;						

//            console.log(suggestion.data);
        }
    });
}    
    
    function onfocusit()
    {
//	    document.getElementById('sugaddress').style.margin = '0 0 110px 0';
	    document.getElementById('fsugaddress').style.border = '0';	    
    }
    
    function onblurit()
    {
	    document.getElementById('sugaddress').style.margin = '0';
	    document.getElementById('fsugaddress').style.border = 'solid 1px #aaa';	    
	    document.getElementById('fsugaddress').style.border.bottom = '0';	    	    

		if(document.getElementById('postcode').value != '' && document.getElementById('city').value != '' && document.getElementById('address1').value != '')
		{
/*		    document.getElementById('sugaddress').value = ''; */
//			document.getElementById('fsugaddress').style.display = 'none';		    
		}
    }
</script>



{/literal}

{literal}<!-- Yandex.Metrika counter --><script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter24459827 = new Ya.Metrika({ id:24459827, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="https://mc.yandex.ru/watch/24459827" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->{/literal}