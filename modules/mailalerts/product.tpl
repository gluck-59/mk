<!-- MODULE MailAlerts -->
<span id="mailalert_span">Этого ништяка сейчас нет в наличии. <br>
Если хочешь, мы напишем тебе письмо, когда он снова появится.</span>

<span hidden id="mailalert_sucess">Порядок. Как только ништяк появится на складе, тебе придет уведомление.<br>
Спасибо!</span><br>

{if $email}
	<br><span id="mailalert_need">Нужно только ввести email:</span></br>
	<input type="email" id="oos_customer_email" name="customer_email" size="20" placeholder="твой e-mail" class="mailalerts_oos_email" onclick="clearText();" /><br />
{/if}


<br />
<span><a href="javascript:;" onclick="addNotification();" class="ebutton blue" id="mailalert_link">{l s='Напишите!' mod='mailalerts'}</a></span>


<script type="text/javascript">
oosHookJsCodeFunctions.push('oosHookJsCodeMailAlert');

function clearText()
{ldelim}
	if ($('#oos_customer_email').val() == 'your@email.com')
		$('#oos_customer_email').val('');
{rdelim}

// гость
function oosHookJsCodeMailAlert()
{ldelim}
	$.ajax({ldelim}
		type: 'POST',
		url: '{$base_dir}modules/mailalerts/mailalerts-ajax_check.php',
		data: 'id_product={$id_product}&id_product_attribute='+$('#idCombination').val(),
		success: function (msg)
		{ldelim}
			if (msg == '0')
			{ldelim}
				$('#mailalert_link').show().attr('href', 'modules/mailalerts/mailalerts-add.php?id_product={$id_product}&id_product_attribute='+$('#idCombination').val());
				$('#oos_customer_email').show();
			{rdelim}
			else
			{ldelim}
				$('#mailalert_link').hide();
				$('#oos_customer_email').hide();
				$('#mailalert_need').hide();				
				$('#mailalert_sucess').show();				
				$('#mailalert_span').hide();
		
			{rdelim}
		{rdelim}
	{rdelim});
{rdelim}

// юзер
function  addNotification()
{ldelim}

	if ($('#oos_customer_email').val() == '')
	alert('Введи свой email. На него придет уведомление, когда этот ништяк снова появится на складе. ')
	if ($('#oos_customer_email').val() == '')
	return

	$.ajax({ldelim}
		type: 'POST',
		url: '{$base_dir}modules/mailalerts/mailalerts-ajax_add.php',
		data: 'id_product={$id_product}&id_product_attribute='+$('#idCombination').val()+'&customer_email='+$('#oos_customer_email').val(),
		success: function (msg)
		{ldelim}
			if (msg == '1')
			{ldelim}
				$('#mailalert_link').hide();
				$('#mailalert_span').hide();
				$('#oos_customer_email').hide();
				$('#mailalert_need').hide();								
				$('#mailalert_sucess').show();								
								
			{rdelim}
		{rdelim}
	{rdelim});
	return false;
{rdelim}
</script>

<!-- END : MODULE MailAlerts -->