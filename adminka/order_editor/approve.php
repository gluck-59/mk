<?php
require 'approve.php';
require("../../config/settings.inc.php");
if ($_GET['id_order']) $_POST['id_order'] =$_GET['id_order'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Add Product Order n.<?php echo $_POST['id_order'] ?></title>
</head>

<body>


<form name="search_form" method="post" action="add_product.php">
  <label>Поиск
  <input name="search_txt" type="text" value="<?php echo $_POST['search_txt'] ?>" size="60"  />
  </label>

  <p>
    <label>Фильтр языка
    <select name="language">
      
      <?php 
	  if ($_POST['language']==0 or is_null($_POST['language'])) $selected=' selected="selected" ';
	  echo '<option selected="selected" value="">Все</option>';
		$query=" select * from ". _DB_PREFIX_."lang ";
		$res=dbquery($query);
		while ($language=mysql_fetch_array($res)) {
		$selected='';
	  if ($language['id_lang']==$_POST['language']) $selected=' selected="selected" ';
	  
      echo '<option  value="'.$language['id_lang'].'" '.$selected.'>'.$language['name'].'</option>';
	  }
	?>
        </select>
    </label>
</p>
  <p>
    <input type="submit" name="search" value="Искать" />
    <input name="id_order" type="hidden" value="<?php echo $_POST['id_order'] ?>" />

    <br />
    </p>
</form>
<br />
<br />

<table width="100%" border="1">
  <tr>
    <td width="5%">ID</td>
    <td width="10%">Код</td>
    <td width="5%">Язык</td>
    <td width="50%">Товар</td>
    <td width="10%">Цена</td>
    <td width="5%">Налог</td>
    <td width="10%">Действие</td>
  </tr>
<?php
echo $_POST['language'];

if ($_POST['search']) {
if ($_POST['language']!=0)  $id_lang=' and id_lang='.$_POST['language'];
$query=" select p.*,pl.name,pl.id_lang,l.iso_code,t.* from ". _DB_PREFIX_."product p left join ". _DB_PREFIX_."product_lang pl on p.id_product=pl.id_product";
$query.=" left join ". _DB_PREFIX_."lang l on pl.id_lang=l.id_lang ";
$query.=" left join ". _DB_PREFIX_."tax t on t.id_tax=p.id_tax";
$query.=" left join ". _DB_PREFIX_."tax_lang tl on t.id_tax=tl.id_tax";
$query.=" where  (p.reference like '%".$_POST['search_txt']."%' or p.supplier_reference like '%".$_POST['search_txt']."%' or pl.name like '%".$_POST['search_txt']."%') ".$id_lang." limit 100";
// echo $query;
$res=dbquery($query);
	if (mysql_num_rows($res)>0) {
		while ($products=mysql_fetch_array($res)) {
?>
  <tr>
    <td width="5%"><?php echo $products['id_product'] ?></td>
    <td width="10%"><?php echo $products['reference'] ?></td>
    <td width="10%"><?php echo $products['iso_code'] ?></td>
    <td width="50%"><?php echo $products['name'] ?></td>
    <td width="10%"><?php echo round($products['price'],3) ?></td>
    <td width="5%"><?php echo $products['rate'] ?></td>
    <td width="10%"><div align="center"><a href="order_edit.php?action=add_product&id_lang=<?php echo $products['id_lang'] ?>&id_order=<?php echo $_POST['id_order'] ?>&id_product=<?php echo $products['id_product'] ?>">Добавить</a></div></td>
  </tr>

    <?php
	}
	} else {
	echo "<strong>products not found</strong>";
	}

}
?>
</table>






</body>

</html>



<?php
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

?>
