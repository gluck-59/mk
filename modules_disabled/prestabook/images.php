
<?php 

include('../../config/config.inc.php'); 
include('../../init.php');
require('prestabook.php');
global $cookie;
$skipcategory = Configuration::get('PRESTABOOK_SKIP_CAT');
$category = new Category($skipcategory);
$nb = intval(Configuration::get('PRESTABOOK_NBR'));
$langs = 1;
$color = Configuration::get('PRESTABOOK_COLOR');
$number = Configuration::get('PRESTABOOK_NUMBER');
$width = Configuration::get('PRESTABOOK_WIDTH');
$height = Configuration::get('PRESTABOOK_HEIGHT');
$align = Configuration::get('PRESTABOOK_ALIGN');
$sort = intval(Configuration::get('PRESTABOOK_SORT'));
		  switch ($sort) {
		        case '0':
			$products = PrestaBook::getProductscath($skipcategory,intval($cookie->id_lang), 1, ($nb ? $nb : 10));
			break;
		    case '1':
			$products = PrestaBook::getProductscath($skipcategory,intval($cookie->id_lang), 1, 1000);
			shuffle($products);
			array_splice($products, ($nb ? $nb : 10));
			break;
		    case '2':
			$products = PrestaBook::getProductscath($skipcategory,intval($cookie->id_lang), 1, ($nb ? $nb : 10), 'price', 'ASC');  
			break;
		    case '3':
			$products = PrestaBook::getProductscath($skipcategory,intval($cookie->id_lang), 1, ($nb ? $nb : 10), 'price', 'DESC');  
			break;
		    case '4':
			$products = PrestaBook::getProductscath($skipcategory,intval($cookie->id_lang), 1, ($nb ? $nb : 10), 'date_upd', 'DESC');  
			break;
		    case '5':
			$products = PrestaBook::getProductscath($skipcategory,intval($cookie->id_lang), 1, ($nb ? $nb : 10), 'date_add', 'DESC');  
			break;
		    case '6':
			$products = PrestaBook::getProductscath($skipcategory,intval($cookie->id_lang), 1, ($nb ? $nb : 10), 'name', 'ASC');  
			break;
		    default:
			$products = PrestaBook::getProductscath(intval($skipcategory,intval($cookie->id_lang), 1, ($nb ? $nb : 10)));
			break;
		}	

$currency = Currency::getCurrency($cookie->id_currency);
$sign = html_entity_decode($currency[sign],ENT_NOQUOTES,"UTF-8");
$rate = $currency[conversion_rate];

//crear XML//
$xml = fopen ("xml/config.xml", "w");
 
      fwrite ($xml, '<?xml version="1.0"  encoding="utf-8"' . '?' .'>
<site title="'.$align.'" description="'.$color.'" img="../../img/logo.jpg">
	<about title="'.$number.'">
		<section title="'.$align.'" img="about.jpg" description="">
			
			<h1>'.$align.'</h1>
			<br/>
				<p>'.$height.'</p>
		<br/>
		</section>
	</about>
	<clients title="'.$width.'">');
	  
	
	   
	
	   $align2 = explode(",",$skipcategory); 
for($a=0;$a<count($align2);$a++){
	
	 $sorgu=mysql_query("SELECT * FROM "._DB_PREFIX_."category WHERE id_category = '$align2[$a]' ");
	  $veri= mysql_fetch_assoc($sorgu);
	  $catid=$veri['id_category'];
	  
	  
	  $sorgu2=mysql_query("SELECT * FROM "._DB_PREFIX_."category_lang WHERE id_category = '$align2[$a]' and id_lang = '1'");
	  $veri2= mysql_fetch_assoc($sorgu2);
	  $catname=$veri2['name'];
	   if (file_exists('../../img/c/'.$align2[$a].'-bookcategory.jpg')){
		   $contenidoxml = $contenidoxml .'<section title="'.$catname.'" img="../../img/c/'.$align2[$a].'-bookcategory.jpg" description="">';
	   }
	   else
	   {
		  $contenidoxml = $contenidoxml .'<section title="'.$catname.'" img="noimage.jpg" description="">';
		   }
   
	
 $sorgu3=mysql_query("SELECT * FROM "._DB_PREFIX_."product WHERE id_category_default = '$align2[$a]' AND active = '1'");
	  $veri3= mysql_fetch_assoc($sorgu3);
	 
	

	  //start//
	
do{
	
        $no=$veri3['id_product'];	
		
	  	 $sorgu4=mysql_query("SELECT * FROM "._DB_PREFIX_."product_lang WHERE id_product = '$no' ");
	  $veri4= mysql_fetch_assoc($sorgu4);
	   $sorgu5=mysql_query("SELECT * FROM "._DB_PREFIX_."image WHERE id_product = '$no'");
$veri5= mysql_fetch_assoc($sorgu5);
echo "SELECT * FROM "._DB_PREFIX_."product_lang WHERE id_product = '$no' AND active = '1'";

$link=$products[$i][link];
$names=$veri4['name'];
$price=number_format($veri3['price']*$rate,2,',','.');
$ds = $veri4['description_short'];
$name = (strlen($names) > 17) ? substr($names, 0, 17) . "..." : $names;
$description_short = html_entity_decode($ds,ENT_NOQUOTES,"UTF-8");
$description_short2 = utf8_decode(strip_tags($description_short));
$description_short3 = (strlen($description_short2 ) > 90) ? substr($description_short2 , 0, 90) . "..." : $description_short2 ;
$id_image=$veri5['id_image'];

 if (file_exists(__PS_BASE_URI__.'../../img/p/'.$no.'-'.$id_image.'-prestabook.jpg')){
	 

 $contenidoxml = $contenidoxml .'<client img="product.jpg" title="'.$name.' '.$sign.$price.'" description="'.$description_short3.'" link="'.__PS_BASE_URI__.'product.php?id_product='.$no.'"/>';
 }
 else
 {$contenidoxml = $contenidoxml .'<client img="../../img/p/'.$no.'-'.$id_image.'-prestabook.jpg" title="'.$name.'" description="'.$description_short3.'" link="'.__PS_BASE_URI__.'product.php?id_product='.$no.'"/>';
	
	 }

} 

	  while($veri3= mysql_fetch_assoc($sorgu3));
	 $contenidoxml = $contenidoxml .'  	</section>';
  }
   
   //end//
      fwrite ($xml, $contenidoxml);
      
	  
	  $shop = Configuration::get('PS_SHOP_NAME');
	  $email = Configuration::get('PS_SHOP_EMAIL');
	  $addr1 = Configuration::get('PS_SHOP_ADDR1');
	  	$addr2 = Configuration::get('PS_SHOP_ADDR2');
	  $postal = Configuration::get('PS_SHOP_CODE');
		$city = Configuration::get('PS_SHOP_CITY');
		 $state = Configuration::get('PS_SHOP_STATE');
		 $country = Configuration::get('PS_SHOP_COUNTRY');
		 $phone = Configuration::get('PS_SHOP_PHONE');
		$fax = Configuration::get('PS_SHOP_FAX');
fwrite ($xml, '</clients>		
	<contact title="CONTACT US" img="contact.jpg" description="">
		<p align="center">
		<h1>'.$shop.'</h1>
			<h2>'.$addr1.' '.$addr2.' '.$postal.'</h2>
			<h2>'.$city.' '.$state.'</h2>
			<h2>'.$country.'</h2>
			<h2>Tel.:'.$phone.'</h2>
		
			<a href="mailto:'.$email.'">'.$email.'</a>
		</p>
	</contact>
	<backgrounds>
		<background img="bg01.jpg" bgImg="a2.jpg" bgType="pattern"/>
		<background img="bg02.jpg" bgImg="bg02.gif" bgType="pattern"/>
		<background img="bg03.jpg" bgImg="bg03.gif" bgType="pattern"/>


	</backgrounds>
	<music title="Music">
		<track title="01 Track 001" link="tracks/track01.mp3"/>

	</music>
</site>');

if (fclose ($xml))
{
   echo "";
    } 
	else 
	{
    exit ("Error escribiendo el XML");
}
	   



?>
