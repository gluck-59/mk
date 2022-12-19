<?php
//if ( $_SESSION['token'] != 'password')  die('Access Denied');
require 'approve.php';
require("../../config/settings.inc.php");
if ($_GET['id_order']) $_POST['id_order']=$_GET['id_order'];
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Prestashop Order Modify</title>
<style type="text/css">
<!--
* 
{
	font-family: PT Sans Caption, Arial, Helvetica, Sans-Serif;
}
.Stile1 {
	font-size: large;
	font-weight: bold;
}
.Stile2 {font-size: small}
-->
</style>
</head>

<body>


<form name="order" method="post" action="order_edit.php">
  <label>Номер заказа
  <input name="id_order" type="text" value="<?php echo $_POST['id_order'] ?>" size="10" maxlength="10" />
  </label>
  <input name="send" type="submit" value="Открыть" />
  <br />
</form>

<?php
	if ($_POST['id_order']) {
	
		if ($_GET['action']=='add_product') {
		$query=" select p.*,pl.name,t.rate as tax_rate,tl.name as tax_name from ". _DB_PREFIX_."product p join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product ";
		$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=p.id_tax";
		$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax";
		$query.=" where p.id_product=".$_GET['id_product']. " and pl.id_lang=".$_GET['id_lang'];
		// echo $query."<br/>";
		$res=dbquery($query);
		$products=mysql_fetch_array($res);
		
		if (is_null($products['tax_rate'])) $products['tax_rate']=0;
		
		$query="insert into ". _DB_PREFIX_."order_detail (id_order ,product_id ,product_name ,product_quantity ,product_quantity_in_stock ,product_price ,product_ean13 ,product_reference ,product_supplier_reference ,product_weight ,tax_name ,tax_rate ) values  ";
		$query.="(".$_GET['id_order'].",".$products['id_product'].",'".addslashes($products['name'])."',1,1,";
		$query.=$products['price'].",'".$products['ean13']."','".addslashes($products['reference'])."','".addslashes($products['supplier_reference'])."',".$products['weight'].",'".addslashes($products['tax_name'])."',".$products['tax_rate'].")";
		// echo $query;
		dbquery($query);
		update_total($_GET['id_order']);
		}
	
		if ($_POST['order_total']) {
		$total=price($_POST['total_products']*(1+$_POST['tax_rate']/100) )+price($_POST['total_shipping'])+price($_POST['total_wrapping'])-price($_POST['total_discounts']);
		
		$query="update  ". _DB_PREFIX_."orders set ";
		$query.=" total_discounts=".price($_POST['total_discounts']);
		$query.=" ,total_wrapping=".price($_POST['total_wrapping']);
		$query.=" ,total_shipping=".price($_POST['total_shipping']);
		$query.=" ,total_paid_real=".$total;
		$query.=" ,total_paid=".$total;
		$query.=" where id_order=".$_POST['id_order'];
		$query.=" limit 1";
		dbquery($query);
		echo "<br/><b> Order Modified </b><br/>";
		
		}
	
		if ($_POST['Apply']) {
		
		//delete product
		if ($_POST['product_delete']) {
		foreach ($_POST['product_delete'] as $id_order_detail=>$value) {
		dbquery("delete from ". _DB_PREFIX_."order_detail where id_order_detail=".$id_order_detail);
		}
		}
		
		if ($_POST['product_price']) {
		foreach ($_POST['product_price'] as $id_order_detail=>$price_product) {
		$qty_difference=$_POST['product_quantity_old'][$id_order_detail]-$_POST['product_quantity'][$id_order_detail];
		$stock=max(0,$_POST['product_stock'][$id_order_detail]+$qty_difference);
		$name=addslashes($_POST['product_name'][$id_order_detail]);
		
		
		dbquery("update  ". _DB_PREFIX_."order_detail set product_price=".$price_product." where id_order_detail=".$id_order_detail);
		dbquery("update  ". _DB_PREFIX_."order_detail set product_quantity=".$_POST['product_quantity'][$id_order_detail].", product_quantity_in_stock=".$_POST['product_quantity'][$id_order_detail]." where id_order_detail=".$id_order_detail);
		dbquery("update  ". _DB_PREFIX_."order_detail set product_name='".$name."' where id_order_detail=".$id_order_detail);
		
		//servirebbe ad aggiornare lo stock, ma si dovrebbe vincolare ad uno stato. Attualmete lo disabilito
		dbquery("update  ". _DB_PREFIX_."product set quantity=".$stock." where id_product=".$_POST['product_id'][$id_order_detail]);
		
		$total_products+=$_POST['product_quantity'][$id_order_detail]*price($price_product);
		
		}
		
		$total_products=$total_products*(1+$_POST['tax_rate']/100);
		dbquery("update  ". _DB_PREFIX_."orders set total_products=".$total_products." where id_order=".$_POST['id_order']);
		update_total($_POST['id_order']);
		
		}
		
		
		
		
		}
		
		
		
	if ($_POST['id_order']) {
	$query="select distinct o.*,a.*,p.tax_rate from ". _DB_PREFIX_."orders o,". _DB_PREFIX_."address a,". _DB_PREFIX_."order_detail p  where a.id_address=o.id_address_delivery and o.id_order=p.id_order and o.id_order=".$_POST['id_order'];
	
	$res=dbquery($query);
	if (mysql_num_rows($res)>0) {
	$order=mysql_fetch_array($res);
	$id_customer=$order['id_customer'];
	$id_lang=$order['id_lang'];
	$id_cart=$order['id_cart'];
	$payment=$order['payment'];
	$module=$order['module'];
	$tax_rate=$order['tax_rate'];
	$invoice_number=$order['invoice_number'];
	$delivery_number=$order['delivery_number'];
	$total_paid_real=$order['total_paid_real'];
	$total_products=$order['total_products'];
	$total_discounts=$order['total_discounts'];
	$total_shipping=$order['total_shipping'];
	$total_wrapping=$order['total_wrapping'];
	$firstname=$order['firstname'];
	$lastname=$order['lastname'];
	$company=$order['company'];
	
	}
	
	} 
	// echo "alert('Are you sure you want to give us the deed to your house?')"
	?>
	
	
	
	<p> id Клиента: <?php echo $id_customer ?><br />
	  Получатель:  <?php echo $firstname." ".$lastname. " ".$company ?><br /></p>
	
	<table border="1">
	<tr><td>
	<b>Общий редактор </b>
	<form name="order_total" method="post" action="order_edit.php">
	  <label><br />
	  Скидки
	  <input name="total_discounts" type="text"  value="<?php echo $total_discounts ?>" />
	  <br />
	  Упаковка
	  <input name="total_wrapping" type="text" value="<?php echo $total_wrapping ?>" />
	  <br />
	  Доставка
	  <input name="total_shipping" type="text" value="<?php echo $total_shipping ?>" /> 
	  <br />
	  Номер доставки
	  <input name="delivery_number" type="text"  value="<?php echo $delivery_number ?>" />
	  <br />
	  <br />
	  Всего (без нал.): <?php echo $total_products ?>
	  <input name="total_products" type="hidden"  value="<?php echo $total_products ?>" />
	  <input name="tax_rate" type="hidden"  value="<?php echo $tax_rate ?>" />
	  <br />
	  Всего (с нал.): <?php echo $total_paid_real ?>
	  <input name="total" type="hidden"  id="total_paid_real" value="<?php echo $total_paid_real ?>" />
	  <input name="id_order" type="hidden" value="<?php echo $_POST['id_order'] ?>" />
	  <br />
	  <br />
	  </label>
	  <label>
	  <input type="submit" name="order_total"  value="Изменить" />
	  </label>
	</form>
	</td></tr>
	</table>
	
	<p align="center" class="Stile1"><a href="add_product.php?id_order=<?php echo $_POST['id_order'] ?>&id_lang=<?php echo $id_lang ?>" target="_self">Добавить продукт</a></p>
	
	<form name="products" method="post" action="order_edit.php">
	
	<table width="100%" border="1" bgcolor="#FFCCCC">
	  <tr>
	    <td>id продукта</td>
	    <td>Код продукта</td>
	    <td>Имя продукта</td>
	    <td>Цена</td>
	    <td>Налог</td>
	    <td>Цена с нал.</td>
	    <td>Кол-во</td>
	    <td>Всего без нал.</td>
	    <td>Всего с нал.</td>
	    <td>Вес</td>
	    <td>Удалить</td>
	  </tr>
	
	  <?php
	$query="select o.*,p.quantity as stock 
	from ". _DB_PREFIX_."order_detail o 
	left join ". _DB_PREFIX_."product p  on  o.product_id=p.id_product";
	
	$query.=" where id_order=".$_POST['id_order'];
	$query.=" order by id_order_detail asc";
	  $res1=dbquery($query);
	if (mysql_num_rows($res1)>0) {
	
	while ($products=mysql_fetch_array($res1)) {
	$tax_rate=$products['tax_rate'];
	  echo '<tr>';
	  echo '  <td>'.$products['product_id'].'</td>';
	  echo '  <td>'.$products['product_reference'].'</td>';
	  echo '  <td><input name="product_name['.$products['id_order_detail'].']" type="text" value="'.$products['product_name'].'" /></td>';
	  echo '  <td><input name="product_price['.$products['id_order_detail'].']" type="text" value="'.number_format($products['product_price'], 4, ',', '').'" /></td>';
	  echo '  <td>'.$products['tax_rate'].'%</td>';
	  echo '  <td>'.number_format($products['product_price']*(1+$products['tax_rate']/100),3, ',', '').'</td>';  
	  echo '  <td><input name="product_quantity['.$products['id_order_detail'].']" type="text" value="'.$products['product_quantity'].'" /></td>';
	  echo '  <td>'.number_format($products['product_price']*$products['product_quantity'],2, ',', '').'</td>';  
	  echo '  <td>'.number_format($products['product_price']*$products['product_quantity']*(1+$products['tax_rate']/100),2, ',', '').'</td>';  
	  echo '  <td>'.number_format($products['product_weight'],2, ',', '').'</td>';
	  echo '  <td><input name="product_delete['.$products['id_order_detail'].']" type="checkbox" /></td>';
	  echo '</tr> ';
	  echo '  <input name="product_quantity_old['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_quantity'].'" />';
	  echo '  <input name="product_id['.$products['id_order_detail'].']" type="hidden" value="'.$products['product_id'].'" />';
	  echo '  <input name="product_stock['.$products['id_order_detail'].']" type="hidden" value="'.$products['stock'].'" />';
	    ?>
	<?php
	  }
	  }
	  ?>
	</table>
	<p align="center">
	  <input name="Apply" type="submit" value="Изменить" />
	  <input name="id_order" type="hidden" value="<?php echo $_POST['id_order'] ?>" />
	  <input name="tax_rate" type="hidden" value="<?php echo $tax_rate ?>" />
	  <input name="id_lang" type="hidden" value="<?php echo $id_lang ?>" />
	  </p>
	  </form>
	
	<hr/>
	<p align="center" class="Stile2">Script realizzato da Luca Lo Bascio. Visita <a href="http://www.atomicshop.it">www.atomicshop.it</a> per comprare Hardware, Console, CD and DVDs Vergini, Monitor, TV e molto altro.<br />
	  <br/>
	  <strong>Se sei interessato al DROPSHIPPING</strong>, abbiamo realizzato uno script che inserisce ed aggiorna automaticamente categorie, prodotti e marche di www.AtomicShop.it nel tuo e-commerce basato su PrestaShop, e anche per il modulo e-commerce VirtuaMart di Joomla o anche il modulo e-commerce di Wordpress.
	<strong><em>Tu vendi, noi spediamo!</em></strong> Il guadagno e assicurato! Attualmente le spedizioni sono effettuate solo ai clienti Italiani</p>
	<p align="center" class="Stile2">Per informazioni invia una e-mail a <a href="mailto:marketing@atomicshop.it?subject=prestashop_ordermodify">marketing@atomicshop.it</a></p>
	<hr/>
	<p align="center" class="Stile2">перевод на русский sem4444 <a href="http://www.pazlov.net">www.pazlov.net</a> Пазлы для детей и взрослых,головоломки, мозаика, настольные игры. <br />
	  <br/>
	  <strong>If you are interested at DROPSHIPPING</strong>, we created a script that inserts and updates categories, brands and products of www.AtomicShop.it in your PrestaShop e-commerce, Joomla VirtuaMart or Wordpress.
	<strong><em>You sell, we ship!</em></strong> The gain is assured! Acctually only for shipping in Italy</p>
	<p align="center" class="Stile2">For information send email to <a href="mailto:marketing@atomicshop.it?subject=prestashop_ordermodify">marketing@atomicshop.it</a></p>
	</body>
	
	</html>
	
	
	
	<?php
	}
function dbquery($query) {
$conn = mysql_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_) or die ("Connessione non avvenuta");
mysql_select_db(_DB_NAME_, $conn) or die ("Selezione del db non avvenuta");
$res = @mysql_query($query, $conn); //or die ("Query non eseguita");
mysql_close($conn);
return $res;
}

function connessione() {
$conn = mysql_connect(_DB_SERVER_, _DB_USER_, _DB_PASSWD_) or die ("Connessione non avvenuta");
mysql_select_db(_DB_NAME_, $conn) or die ("Selezione del db non avvenuta");
}
function price($price) {
$price=str_replace(",",".",$price);
return $price;
}

function update_total($id_order) {
$query="select sum(product_price*product_quantity*(1+tax_rate/100)) as total_products,sum(product_price*product_quantity) as total_products_notax  from  ". _DB_PREFIX_."order_detail where id_order=".$id_order;
$res2=dbquery($query);
$products=mysql_fetch_array($res2);
$query="select * from  ". _DB_PREFIX_."orders where id_order=".$id_order;
$res3=dbquery($query);
$order=mysql_fetch_array($res3);
$total=price($products['total_products'])+price($order['total_shipping'])+price($order['total_wrapping'])-price($order['total_discounts']);
$query="update  ". _DB_PREFIX_."orders set ";
$query.=" total_discounts=".$order['total_discounts'];
$query.=" ,total_wrapping=".$order['total_wrapping'];
$query.=" ,total_shipping=".$order['total_shipping'];
$query.=" ,total_products=".$products['total_products_notax'];
$query.=" ,total_paid_real=".$total;
$query.=" ,total_paid=".$total;
$query.=" where id_order=".$id_order;
$query.=" limit 1";

dbquery($query);
}

?>
