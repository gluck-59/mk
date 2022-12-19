<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='My account'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

<h2 id="cabinet">{l s='My account'}</h2>

<p class="" id="hello" >Привет, байкер {$customer_firstname}!</p>

<div class="rte cabinet">
    <div align="center">
    <a href="{$base_dir_ssl}history.php?{$smarty.now}" title="{l s='History and details of your orders'}">
    		<img src="{$img_dir}icon/order.png" alt="{l s='History and details of your orders'}" class="icon" /></a> 
    <br>
    	<a href="{$base_dir_ssl}history.php?{$smarty.now}" title="{l s='History and details of your orders'}">&nbsp;{l s='History and details of your orders'}</a>
    </div>
    
        <div align="center">
		<a href="{$base_dir_ssl}addresses.php" title="{l s='Addresses'}"><img src="{$img_dir}icon/addrbook.png" alt="{l s='Addresses'}" class="icon" /></a> 
		<br>
		<a href="{$base_dir_ssl}addresses.php" title="{l s='Addresses'}">&nbsp;{l s='Your addresses'}</a>
    </div>

	{$HOOK_CUSTOMER_ACCOUNT}

    {if $returnAllowed}
        <div align="center">
            <a href="{$base_dir_ssl}order-follow.php" title="{l s='Merchandise returns'}"><img src="{$img_dir}icon/return.png" alt="{l s='Merchandise returns'}" class="icon" /></a> 
            <br>
            <a href="{$base_dir_ssl}order-follow.php" title="{l s='Merchandise returns'}">&nbsp;{l s='Merchandise returns'}</a>
        </div>
	{/if}

	{*<div align="center">
		<a href="{$base_dir_ssl}order-slip.php" title="{l s='Orders'}"><img src="{$img_dir}icon/slip.png" alt="{l s='Orders'}" class="icon" /></a> 
		<br>
		<a href="{$base_dir_ssl}order-slip.php" title="{l s='Credit slips'}">&nbsp;{l s='Credit slips'}</a>
    </div>*}

    <div align="center"> 
		<a href="{$base_dir_ssl}identity.php" title="{l s='Your personal information'}"><img src="{$img_dir}icon/userinfo.png" alt="{l s='Your personal information'}" class="icon" /></a> 
		<br>
		<a href="{$base_dir_ssl}identity.php" title="{l s='Your personal information'}">&nbsp;{l s='Your personal information'}</a>
    </div>
    
    {if $voucherAllowed}
        <div align="center">    
            <a href="{$base_dir_ssl}discount.php" title="{l s='Скидки'}"><img src="{$img_dir}icon/voucher.png" alt="{l s='Скидки'}" class="icon" /></a> 
            <br>
            <a href="{$base_dir_ssl}discount.php" title="{l s='Скидки'}">&nbsp;{l s='Скидки'}</a>
        </div>
   {/if}

    <center><div align="center">
        <a href="{$base_dir}" title="{l s='Home'}"><img src="{$img_dir}icon/home.png" alt="{l s='Home'}" class="icon" /></a>
        <br>
        <a href="{$base_dir}" title="{l s='Home'}">&nbsp;{l s='Home'}</a>
    </div></center>
    
</div>

{literal}
<script type="text/javascript">
var re = document.referrer;
if (re == 'http://motokofr.com/authentication.php?back=my-account.php')
{
	setTimeout("document.getElementById('hello').style.opacity = '1'", 200);
	setTimeout("document.getElementById('hello').style.opacity = '0'", 3000);
}
</script>
{/literal}
