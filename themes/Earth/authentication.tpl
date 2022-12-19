{capture name=path}{l s='Login'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<!--h2>{if !isset($email_create)}{l s='Log in'}{else}{l s='Create your account'}{/if}</h2-->
<p>&nbsp;</p>

{assign var='current_step' value='login'}
{if $smarty.const.site_version == "full"}
{*include file=$tpl_dir./order-steps.tpl*}
{/if}

{include file=$tpl_dir./errors.tpl}

{if isset($confirmation)}
	<div class="confirmation">
		<p class="warning">{l s='Your account has been successfully created.'}{if isset($smarty.post.customer_firstname)} {$smarty.post.customer_firstname}{/if}!</p><br><br>
		<p align="center">
			<a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.png" alt="{l s='Your account'}" title="{l s='Your account'}" class="icon" /></a><a href="{$base_dir_ssl}my-account.php">{l s='Access your account'}</a>
		</p>
	</div>
{else}
	{if !isset($email_create)}
		<form action="{$base_dir_ssl}authentication.php" method="post" id="create-account_form" class="std {if isset($smarty.post.email) OR isset($smarty.cookies.email)}hidden{/if}" />
			<fieldset class="authentication">
				<h3>{l s='Create your account'}</h3>

				<!--p class="text">
			<a href="http://market.yandex.ru/addresses.xml?callback=http%3A%2F%2Fmotokofr.com%2Fauthentication.php">
			<img src="http://img.motokofr.com/yandex_fast_order.png" border="0" alt="Адрес из профиля в Яндексе" title="Адрес из профиля в Яндексе" /></a>
			</p-->
				<p class="text">
					<span>
					<!--[if IE]><label style="margin:-10px 0 -5px 0" for="email_create">e-mail</label><![endif]-->
					<input required  autocomplete="on" title="e-mail" type="email" placeholder="e-mail" id="email_create" name="email_create" value="{if isset($smarty.post.email_create)}{$smarty.post.email_create|escape:'htmlall'|stripslashes}{/if}" class="account_input" /></span>
				</p>
				<p class="text">
				{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
					<input type="submit" id="SubmitCreate" name="SubmitCreate" class="ebutton blue" value="{l s='Create your account - button'}" />
					<input type="hidden" class="hidden" name="SubmitCreate" value="{l s='Create your account'}" />
				</p>
                <p class="create-account_form_legend">
    				Сообщаем о движении посылок. Никаких хитростей с рассылками. Никому не раздаем ваши адреса.
				</p>
			</fieldset>
		</form>

		<form action="{$base_dir_ssl}authentication.php" method="post" id="login_form" class="std"  onsubmit="getpp('Привет!');">
			<fieldset class="authentication">
				<h3>{if isset($smarty.cookies.firstname)}Привет, байкер {$smarty.cookies.firstname}
				&nbsp;<a href="{$base_dir}index.php?my_logout"><span style="font-size:smaller; color: lightgray; text-decoration:underline;vertical-align: top;">Я не {$smarty.cookies.firstname}!</span></a>

				 {else}{l s='Already registered ?'}{/if}</h3>
				<p class="text">
					<span><!--[if IE]><label style="margin:-10px 0 -5px 0" for="email">{l s='E-mail'}</label><![endif]-->
					<input autocomplete="on" title="e-mail" required type="email" placeholder="e-mail" id="email" name="email" 
						value="{if isset($smarty.post.email)}{$smarty.post.email|escape:'htmlall'|stripslashes}{elseif isset($smarty.cookies.email)}{$smarty.cookies.email|escape:'htmlall'|stripslashes}{/if}" class="account_input" /></span>
				</p>
				
				<p class="text">
					<span>
					<!--[if IE]><label style="margin:-10px 0 -5px 0" for="passwd">{l s='Password'}</label><![endif]-->
					<input required title="пароль" type="password" placeholder="пароль" id="passwd" name="passwd" value="{if isset($smarty.post.passwd)}{$smarty.post.passwd|escape:'htmlall'|stripslashes}{/if}" class="account_input" /></span>
				</p>
				<p class="text">
					{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
					<input type="submit" id="SubmitLogin" name="SubmitLogin" class="ebutton blue" value="{l s='Log in'}" />

{if $smarty.const.site_version == "full"}
				</p>
				<p class="lost_password">
				<a href="{$base_dir}password.php">{l s='Forgot your password?'}</a>
				</p>
{/if}				

{if $smarty.const.site_version == "mobile"}				
</p>
<a href="{$base_dir}password.php">{l s='Forgot your password?'}</a>
				</p>
{/if}
			</fieldset>
		</form>
	{else}
	<form action="{$base_dir_ssl}authentication.php" method="post" id="account-creation_form" class="add_address" onsubmit="getpp('Спасибо!');">
		<div class="add_address">

		<fieldset class="account_creation"> 
			<h3>{l s='Your personal information'}</h3>
			<p class="radio">
				<input type="radio" name="id_gender" id="id_gender1" value="1" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == 1}checked="checked"{/if} checked />
				<label for="id_gender1" class="top">{l s='Mr.'}</label>
				<input type="radio" name="id_gender" id="id_gender2" value="2" {if isset($smarty.post.id_gender) && $smarty.post.id_gender == 2}checked="checked"{/if} />
				<label for="id_gender2" class="top">{l s='Ms.'}</label>
			</p>

<p class="required" style="text-indent: 6px;" ><sup>&bull;</sup> {l s='Непременно!'}</p>
			<br>
			
						
			<fieldset class="add_address">
				<legend for="firstname"><sup>&bull;</sup>&nbsp;&nbsp;{l s='First name'}</legend>
				<input autofocus required type="text" class="text" id="customer_firstname" name="customer_firstname" value="{if isset($smarty.post.customer_firstname)}{$smarty.post.customer_firstname}{/if}" tabindex="2" placeholder="для переписки о заказе"/>
				<!-- onkeyup="$('#firstname').val(this.value);" -->
			</fieldset>
			
			<fieldset class="add_address">
				<legend for="lastname"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Last name'}</legend>
				<input required type="text" class="text" id="customer_lastname" name="customer_lastname" value="{if isset($smarty.post.customer_lastname)}{$smarty.post.customer_lastname}{/if}" tabindex="3" />
				<!-- onkeyup="$('#lastname').val(this.value);"  -->
			</fieldset>
			
			<fieldset class="add_address">
				<legend id="email_field" for="email" <sup>&bull;</sup> &nbsp;&nbsp;{l s='E-mail'}</legend>
				<input onkeyup="mailru_checker();" tabindex="4" required type="email" class="text" id="email" name="email" value="{if isset($smarty.post.email)}{$smarty.post.email}{/if}" />
				{*if $smarty.post.email|strpos:"mail.ru"}<span class="form_info" style="color:red; font-weight:bold "> есть другой?</span>{/if*}
			</fieldset>
			
			<fieldset class="add_address">
				<legend for="passwd"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Password'}</legend>
				<input  type="password" class="text" name="passwd" id="passwd" tabindex="5" placeholder="мин. 5 букв/цифр" />
				{*<span class="form_info">{l s='(5 characters min.)'}</span>*}
			</fieldset>
			
			<p class="select" style=" width: 118%; ">
				<span>{l s='Birthday'}</span>
				<select id="days" name="days" tabindex="6">
					<option value="">-</option>
					{foreach from=$days item=day}
						<option value="{$day|escape:'htmlall':'UTF-8'}" {if ($sl_day == $day)} selected="selected"{/if}>{$day|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
				{*
					{l s='January'}
					{l s='February'}
					{l s='March'}
					{l s='April'}
					{l s='May'}
					{l s='June'}
					{l s='July'}
					{l s='August'}
					{l s='September'}
					{l s='October'}
					{l s='November'}
					{l s='December'}
				*}
				<select id="months" name="months" tabindex="7" >
					<option value="">-</option>
					{foreach from=$months key=k item=month}
						<option value="{$k|escape:'htmlall':'UTF-8'}" {if ($sl_month == $k)} selected="selected"{/if}>{l s="$month"}&nbsp;</option>
					{/foreach}
				</select>
				<select id="years" name="years" tabindex="8">
					<option value="">-</option>
					{foreach from=$years item=year}
						<option value="{$year|escape:'htmlall':'UTF-8'}" {if ($sl_year == $year)} selected="selected"{/if}>{$year|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
			</p>
			<div class="rte">
			{*<p>&nbsp;</p>
			<p class="checkbox" >
				<input type="checkbox" name="newsletter" id="newsletter" tabindex="9" />
				<label for="newsletter">&nbsp;{l s='Sign up for our newsletter'}</label>
			</p>
			<p class="checkbox" >
				<input type="checkbox"name="optin" id="optin" tabindex="10" />
				<label for="optin">&nbsp;{l s='Receive special offers from our partners'}</label>
			</p>*}
			<p>&nbsp;</p>
			<p class="text">
    			<a href="http://motokofr.com/cms.php?id_cms=8" target="_blank">Политика использования Cookies (в новом окне)</a>
			</p>
            <p class="text">
    			<a href="http://motokofr.com/cms.php?id_cms=3" target="_blank">Политика использования персональных данных (в новом окне)</a>
			</p>
			</div>
		</fieldset>
		
		
		
		
		
		
		<fieldset hidden class="account_creation">
			<h3>{l s='Your address'}</h3>
			<p class="text">{l s='Your address1'}</p>

			<p class="required text">
				<label for="firstname">{l s='First name latin'}</label>
				<input type="text" class="text" id="firstname" name="firstname" value="{if isset($smarty.post.firstname)}{$smarty.post.firstname}{/if}" tabindex="11"/>
		
				<sup>&bull;</sup>
			</p>
			<p class="required text">
				<label for="lastname">{l s='Last name latin'}</label>
				<input type="text" class="text" id="lastname" name="lastname" value="{if isset($smarty.post.lastname)}{$smarty.post.lastname}{/if}" tabindex="12" />
				<sup>&bull;</sup>
			</p>
	<br>
		<h3 style="cursor: pointer;" id='bl1' onclick="showhideBlock('bl1','textbl1');"  onmouseover="style.color='orange'" onmouseout="style.color='#555'">&bull; Отправлять посылки EMS и СПСР?</h3><br>
		<div id='textbl1' style="-webkit-transition: all 0.2s ease; -moz-transition: all 0.2s ease; -o-transition: all 0.2s ease; display: none;">		
		<p class="text" style="color: gray; {if $smarty.const.site_version == "mobile"}font-size: medium!important{/if}">
		Если ты планируешь отправлять свои посылки через СПСР, заполни все эти поля. 
		</p>
		<p class="text">
			<label for="address2">{l s='Address (2)'}</label>
			<input type="text" id="address2" name="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{else}{$address->address2|escape:'htmlall':'UTF-8'}{/if}" />
		</p>
		<p class="text" style="color: gray; {if $smarty.const.site_version == "mobile"}font-size: medium!important{/if}">Для EMS будет достаточно только телефона.
		</p>		
		<p class="text">
			<label for="phone_mobile">{l s='Mobile phone'}</label>
			<input type="text" id="phone_mobile" name="phone_mobile" value="{if isset($smarty.post.phone_mobile)}{$smarty.post.phone_mobile}{else}{$address->phone_mobile|escape:'htmlall':'UTF-8'}{/if}" />
		</p>

		<p class="text">
			<input type="hidden" name="token" value="{$token}" />
			<label for="company">{l s='Company'}</label>
			<input type="text" id="company" name="company" value="{if isset($smarty.post.company)}{$smarty.post.company}{else}{$address->company|escape:'htmlall':'UTF-8'}{/if}" />
		</p>
		<p class="text" style="color: gray; {if $smarty.const.site_version == "mobile"}font-size: medium!important{/if}">Пример: 1234, №123456, выд. {$smarty.now|date_format:"%e-%m-%Y "} Бюрократическим ОВД г. Бабруйска
		</p>
		</div>
			<p class="required text">
				<label for="address1">{l s='Address'}</label>
				<input type="text" class="text" name="address1" id="address1" value="{if isset($smarty.post.address1)}{$smarty.post.address1}{/if}" tabindex="13" />
				<sup>&bull;</sup>
			</p>
			<p class="required text">
				<label for="city">{l s='City'}</label>
				<input type="text" class="text" name="city" id="city" value="{if isset($smarty.post.city)}{$smarty.post.city}{/if}" tabindex="14" />
				<sup>&bull;</sup>
			</p>
			<p class="required select">
				<label for="id_country">{l s='Country'}</label>
				<select name="id_country" id="id_country" tabindex="15" >
					<option value="">-</option>
					{foreach from=$countries item=v}
					<option value="{$v.id_country}" {if ($sl_country == $v.id_country)} selected="selected"{/if}>{$v.name|escape:'htmlall':'UTF-8'}</option>
					{/foreach}
				</select>
				<sup>&bull;</sup>
			</p>
			<p class="id_state required select">
				<label for="id_state">{l s='State'}</label>
				<select name="id_state" id="id_state" tabindex="16" >
					<option value="">-</option>
				</select>
				<sup>&bull;</sup>				
			</p>
			<p class="hidden">
				<label for="address2">{l s='Address (2)'}</label>
				<input type="text" class="text" name="address2" id="address2" value="{if isset($smarty.post.address2)}{$smarty.post.address2}{/if}" />
			</p>
			<p class="required text">
				<label for="postcode">{l s='Postal code / Zip code'}</label>
				<input style="widht:10px" type="text" class="text" name="postcode" id="postcode" value="{if isset($smarty.post.postcode)}{$smarty.post.postcode}{/if}" tabindex="17" />
				<sup>&bull;</sup>
			</p>
			<p class="text">
				<label for="other">{l s='Additional information'}</label>
				<textarea name="other" id="other" cols="27" rows="8" tabindex="18">{if isset($smarty.post.other)}{$smarty.post.other}{/if}</textarea>
			</p>
			<p class="hidden">
				<label for="phone">{l s='Home phone'}</label>
				<input type="text" class="text" name="phone" id="phone" value="{if isset($smarty.post.phone)}{$smarty.post.phone}{/if}" />
			</p>
			<p class="required text" id="address_alias">
				<label for="alias">{l s='Assign an address title for future reference'}</label>
				<input type="text" class="text" name="alias" id="alias" value="{if isset($smarty.post.alias)}{$smarty.post.alias}{else}{l s='My address'}{/if}" tabindex="19" />
				<sup>&bull;</sup>
				<br><br><span><sup>&bull;</sup> {l s='Required field'}</span>
			</p>
		</fieldset>
		
		
		{* отключить при включении филдсета обратно 
		<fieldset hidden class="account_creation">
		<input type="hidden" name="token" value="{$token}" />
		</fieldset>*}
		
		
		{$HOOK_CREATE_ACCOUNT_FORM}
		<p class="submit2" style="text-align:center;">
			<input type="hidden" name="email_create" value="1" />
			{if isset($back)}<input type="hidden" class="hidden" name="back" value="{$back|escape:'htmlall':'UTF-8'}" />{/if}
			<input type="submit" name="submitAccount" id="submitAccount" value="{l s='Register'}" class="ebutton large green"  tabindex="20" 	
			/>
		</p>

	</div>
	</form>
	{/if}
{/if}


	<div id="getpp" style="height:0px; top: 100%;">
		<p id="welcome" class="warning"></p>
		<br>
		<img src="{$base_dir}img/loader.gif">
		<p>Сейчас я все вспомню...</p>
	</div>			


{literal}
<script type="text/javascript">
// <![CDATA[
idSelectedCountry = {if isset($smarty.post.id_state)}{$smarty.post.id_state|intval}{else}false{/if};
countries = new Array();
{foreach from=$countries item='country'}
	{if isset($country.states)}
		countries[{$country.id_country|intval}] = new Array();
		{foreach from=$country.states item='state' name='states'}
			countries[{$country.id_country|intval}]['{$state.id_state|intval}'] = '{$state.name|escape:'htmlall':'UTF-8'}';
		{/foreach}
	{/if}
{/foreach}


//]]>
</script>
{/literal}


<script type="text/javascript">
    function mailru_checker()
{literal}{{/literal}

	var input = document.getElementById('email').value;
	
	if (input.indexOf('mail.ru') + 1)
	{literal}{{/literal}
		document.getElementById('email_field').innerHTML = '<sup>&bull;</sup>&nbsp;&nbsp;Письма на MAIL.RU часто не доходят';
		document.getElementById('email_field').style.color = '#f00';
		document.getElementById('email_field').style.fontWeight = 'bold';
	{literal}}{/literal}
	
	else
	{literal}{{/literal}
		document.getElementById('email_field').innerHTML = '<sup>&bull;</sup>&nbsp;&nbsp;E-mail';
		document.getElementById('email_field').style.color = '';
		document.getElementById('email_field').style.fontWeight = 'normal';
	{literal}}{/literal}

{literal}}{/literal}
</script>

{literal}
<script>

function getpp(text)
{
	document.getElementById('welcome').innerHTML = text;
	document.getElementById('getpp').style.height = '100%';
    document.getElementById('getpp').style.top = '0px';
	
}
</script>

<script>
    $(window).load(function(){
    mailru_checker()
      })
        
</script>    

{/literal}