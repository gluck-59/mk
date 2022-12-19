{if $smarty.const.site_version == "full"}
<!-- Block Newsletter module-->
<div id="newsletter_block_left" class="block">
	<h4>{l s='Newsletter' mod='blocknewsletter'}</h4>
	<div class="block_content">
	{if $msg}
		<p class="{if $nw_error}warning_inline{else}success_inline{/if}">{$msg}</p>
	{/if}
		<form id="blocknews" action="{$base_dir}" method="post">
			<p><input required type="email" name="email" placeholder="e-mail"  onfocus="javascript:if(this.value=='{l s='your e-mail' mod='blocknewsletter'}')this.value='';" onblur="javascript:if(this.value=='')this.value='{l s='your e-mail' mod='blocknewsletter'}';" /></p>
			<p><span>
				<select name="action">
					<option value="0"{if $action == 0} selected="selected"{/if}>{l s='Subscribe' mod='blocknewsletter'}</option>
					<option value="1"{if $action == 1} selected="selected"{/if}>{l s='Unsubscribe' mod='blocknewsletter'}</option>
				</select>
				 <input  style="border:none; padding-left: 5px" type="submit" value="Ok" class="ebutton blue mini" name="submitNewsletter" /></span>
			</p>
		</form>
	</div>
</div>
{/if}