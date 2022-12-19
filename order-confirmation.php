<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/header.php');

$id_cart = intval(Tools::getValue('id_cart', 0));
$id_module = intval(Tools::getValue('id_module', 0));
$id_order = Order::getOrderByCartId(intval($id_cart));
$secure_key = isset($_GET['key']) ? $_GET['key'] : false;
if (!$id_order OR !$id_module OR !$secure_key OR empty($secure_key))
	Tools::redirect('history.php');
$order = new Order(intval($id_order));


//////////// перемещено из конца скрипта
//////////// перенести обратно если заглючит
// узнаем цену закупа всех товаров в заказе
$my_wholesale_price = Db::getInstance()->ExecuteS('
	SELECT sum(pp.wholesale_price * pcp.quantity) as my_wholesale_price 
	FROM presta_cart_product as pcp 
	left join presta_product pp on pp.id_product = pcp.id_product 
	WHERE pcp.id_cart = '.intval($id_cart)
	);

// пишем в базу цену закупа всех товаров в заказе
// для вычисления $zp
Db::getInstance()->Execute('
	UPDATE presta_orders 
	SET my_wholesale_price = '.$my_wholesale_price[0][my_wholesale_price].' 
	WHERE id_order = '.intval($id_order)
	);
/////////////////////////


if (!Validate::isLoadedObject($order) OR $order->id_customer != $cookie->id_customer OR $secure_key != $order->secure_key)
	Tools::redirect('history.php');
$module = Module::getInstanceById(intval($id_module));
if ($order->payment != $module->displayName)
	Tools::redirect('history.php');
$smarty->assign(array(
	'HOOK_ORDER_CONFIRMATION' => Hook::orderConfirmation(intval($id_order)),
	'id_order' => intval($id_order),
	'HOOK_PAYMENT_RETURN' => Hook::paymentReturn(intval($id_order), intval($id_module))));

$smarty->display(_PS_THEME_DIR_.'order-confirmation.tpl');


include(dirname(__FILE__).'/footer.php');

/*
echo "
<script>
$(document).ready(function() 
{
  convead('event', 'purchase', {
    order_id: '".$id_order."',
    revenue: ".$order->total_paid.",
    items: [
      {product_id: 0000, qnt: 1, price: ".$order->total_paid."},
    ]
  });
});
</script>
";
*/