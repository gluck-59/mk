<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}<a href="{$base_dir_ssl}my-account.php">{l s='My account'}</a><span class="navigation-pipe">&nbsp;{$navigationPipe}&nbsp;</span>{l s='Your personal information'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2 id="cabinet">{l s='Your personal information'}</h2>

{include file=$tpl_dir./errors.tpl}

{if $confirmation}
	<p class="warning">
		{l s='Your personal information has been successfully updated.'}
		{if $pwd_changed}<br />{l s='Your password has been sent to your e-mail:'} {$email|escape:'htmlall':'UTF-8'}{/if}
	</p>
{else}
	<!-- p>{l s='Do not hesitate to update your personal information if it has changed.'}</p -->
	<form action="{$base_dir_ssl}identity.php" method="post" class="add_address">
		<div class="add_address">

			<p class="radio">
				<input type="radio" name="id_gender" id="id_gender1" value="1" {if $smarty.post.id_gender == 1 OR !$smarty.post.id_gender}checked="checked"{/if} />
				<label for="id_gender1">{l s='Mr.'}</label>
				<input type="radio" name="id_gender" id="id_gender2" value="2" {if $smarty.post.id_gender == 2}checked="checked"{/if} />
				<label for="id_gender2">{l s='Ms.'}</label>
			</p>


			<p class="required" style="text-indent: 6px;" ><sup>&bull;</sup> {l s='Непременно!'}</p>
			<br>


			<fieldset class="add_address">
				<legend for="firstname"><sup>&bull;</sup>&nbsp;&nbsp;{l s='First name'}</legend>
				<input type="text" id="firstname" name="firstname" value="{$smarty.post.firstname}" />
			</fieldset>

			<fieldset class="add_address">
				<legend for="lastname"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Last name'}</legend>
				<input type="text" name="lastname" id="lastname" value="{$smarty.post.lastname}" />
			</fieldset>

			<fieldset class="add_address">
				<legend id="email_field" for="email" {if $smarty.post.email|strpos:"mail.ru"} style="">Письма на MAIL.RU доходят не всегда :({else}><sup>&bull;</sup>&nbsp;&nbsp;{l s='E-mail'}{/if}</legend>
				<input required onkeyup="mailru_checker();" type="email" name="email" id="email" value="{$smarty.post.email}" />
			</fieldset>
			
			<fieldset class="add_address">
				<legend for="old_passwd"><sup>&bull;</sup>&nbsp;&nbsp;{l s='Current password'}</legend>
				<input required type="password" name="old_passwd" id="old_passwd" />
			</fieldset>

			<p class="clear">&nbsp;</p>
			<p class="clear">&nbsp;</p>
			
			<fieldset class="add_address">
				<legend for="passwd">Если хочешь — смени пароль</legend>
				<input placeholder="мин. 5 букв/цифр" type="password" name="passwd" id="passwd" />
			</fieldset>

			<fieldset class="add_address">
				<legend for="confirmation">...и еще раз</legend>
				<input type="password" name="confirmation" id="confirmation" />
			</fieldset>

			<p>&nbsp;</p>

			<p>
				<span id="days">{l s='Birthday'}</span>
 				<select name="days" id="days">
					<option value="">-</option>
					{foreach from=$days item=v}
						<option value="{$v|escape:'htmlall':'UTF-8'}" {if ($sl_day == $v)}selected="selected"{/if}>{$v|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
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
				<select id="months" name="months">
					<option value="">-</option>
					{foreach from=$months key=k item=v}
						<option value="{$k|escape:'htmlall':'UTF-8'}" {if ($sl_month == $k)}selected="selected"{/if}>{l s="$v"}&nbsp;</option>
					{/foreach}
				</select>
				<select id="years" name="years">
					<option value="">-</option>
					{foreach from=$years item=v}
						<option value="{$v|escape:'htmlall':'UTF-8'}" {if ($sl_year == $v)}selected="selected"{/if}>{$v|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;</option>
					{/foreach}
				</select>
							</p>
			<p>&nbsp;</p><p>&nbsp;</p>
			<p class="checkbox">
				<input type="checkbox" id="newsletter" name="newsletter" value="1" {if $smarty.post.newsletter == 1} checked="checked"{/if} />
				<label for="newsletter">&nbsp;{l s='Sign up for our newsletter'}</label>
			</p>
			<p>&nbsp;</p>
			<p class="checkbox">
				<input type="checkbox" name="optin" id="optin" value="1" {if $smarty.post.optin == 1} checked="checked"{/if} />
				<label for="optin">&nbsp;{l s='Receive special offers from our partners'}</label>
			</p>
			<p>&nbsp;</p>			<p>&nbsp;</p>
			<p align="center" class="submit">
				<input type="submit" class="ebutton large orange" name="submitIdentity" value="{l s='Save'}" />
			</p>

		</div>
	</form>
{/if}

<table width="100%" border="0" style="margin-top: 30px;">
  <tr>
    <td width="50%"><div align="center"><a href="{$base_dir_ssl}my-account.php"><img src="{$img_dir}icon/my-account.png" alt="" class="icon" /></a></div></td>
    <td width="50%"><div align="center"><a href="{$base_dir}"><img src="{$img_dir}icon/home.png" alt="" class="icon" /></a></div></td>
  </tr>
  <tr>
    <td><div align="center"><a href="{$base_dir_ssl}my-account.php">{l s='Back to Your Account'}</a></div></td>
    <td><div align="center"><a href="{$base_dir}">{l s='Home'}</a></div></td>
  </tr>
</table>






<script type="text/javascript">
function mailru_checker()
{literal}{{/literal}

	var	input = document.getElementById('email').value;
	
	if (input.indexOf('mail.ru') + 1)
	{literal}{{/literal}
		document.getElementById('email_field').innerHTML = 'Письма на MAIL.RU доходят не всегда :(';
/*		document.getElementById('email_field').style.color = '#f00';
		document.getElementById('email_field').style.fontWeight = 'bold';
		document.getElementById('email_field').style.fontSize = '9pt';
*/	{literal}}{/literal}
	
	else
	{literal}{{/literal}
		document.getElementById('email_field').innerHTML = 'E-MAIL';
/*		document.getElementById('email_field').style.color = '';
		document.getElementById('email_field').style.fontWeight = 'normal';
		document.getElementById('email_field').style.fontSize = '';		
*/	{literal}}{/literal}

{literal}}{/literal}
</script>







