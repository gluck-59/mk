<script type="text/javascript">
<!--
	var baseDir = '{$base_dir_ssl}';
-->
</script>

{capture name=path}{l s='Готово!'}{/capture}
{include file=$tpl_dir./breadcrumb.tpl}

{*assign var='current_step' value='payment'}
{include file=$tpl_dir./order-steps.tpl*}

    <p class="warning">
        Заказ №{$id_order} создан
        <br>
    </p>
<p>&nbsp;</p>
<div class="rte">
    

{include file=$tpl_dir./errors.tpl}

{$HOOK_ORDER_CONFIRMATION}
{$HOOK_PAYMENT_RETURN}

</div>

<table width="100%" border="0" style="margin-top: 30px;">
  <tr>
    <td width="50%"><div align="center"><a href="{$base_dir_ssl}history.php"><img src="{$img_dir}icon/order.png" alt="Отследить заказ" class="icon" /></a></div></td>
    <td width="50%"><div align="center"><a href="{$base_dir}"><img src="{$img_dir}icon/home.png" alt="На главную" class="icon" /></a></div></td>
  </tr>
  <tr>
    <td><div align="center"><a href="{$base_dir_ssl}my-account.php">Отследить заказ</a></div></td>
    <td><div align="center"><a href="{$base_dir}">На главную</a></div></td>
  </tr>
</table>

<script type="text/javascript">
    toastr.success('Спасибо за покупку!');
</script>