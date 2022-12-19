<?php

/**
 * Envio de Newsletter pelo Admin Prestashop 1.2
 * @category admin
 *
 * @author Odlanier de Souza <master_odlanier@hotmail.com>
 * @copyright prestashopbr.com
 * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
 * @version 1.2
 *
 */

session_start();

function recursive_in_array($needle, $haystack) {
    foreach ($haystack as $stalk) {
        if ($needle === $stalk || (is_array($stalk) && recursive_in_array($needle, $stalk))) {
            return true;
        }
    }
    return false;
}

include_once (PS_ADMIN_DIR . '/../classes/AdminTab.php');

class AdminNewsletter extends AdminTab
{

    public function display()
    {
        global $currentIndex, $cookie;

        if (Tools::getValue('send') != 1)
        {
            $_POST = isset($_SESSION['newsletter']['POST']) ? $_SESSION['newsletter']['POST'] : null;

            $iso = Language::getIsoById(intval($cookie->id_lang));

            echo $this->html = '
		<h2>' . $this->l('Рассылка спама') 	. '</h2><p>'  . 
				$this->l('Переменные:') 	. '<br>   '	.
				$this->l('%FIRSTNAME%') . ' <br> ' . 
				$this->l('%LASTNAME%') 	. ' <br> ' . 
				$this->l('%MAIL%') 		. ' <br>' .
				$this->l('Шаблон: /mails/ru/newsletter.html') .'</p> 
		
		<form action="' . $currentIndex . '&token=' . $this->token .
                '&send=1" method="post" >
		<fieldset>
		<legend>'.$this->l('Тема письма').':</legend>
		<input type="text" name="subject_email" style="width: 100%" value="'.Tools::getValue('subject_email').'" />
		</fieldset>
		
		<br/>
		
		<fieldset>
		<legend>'.$this->l('Текст письма').':</legend>
		
					
		<script type="text/javascript" src="../js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script> 
                <script type="text/javascript">
            	tinyMCE.init({
            		// General options
            		mode : "textareas",
            		theme : "advanced",
                    languages : "ru",
					plugins : "safari,pagebreak,style,layer,table,advimage,advlink,inlinepopups,media,searchreplace,contextmenu,paste,directionality,fullscreen",            
            		// Theme options
            		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            		theme_advanced_toolbar_location : "top",
            		theme_advanced_toolbar_align : "left",
            		theme_advanced_statusbar_location : "bottom",
            		theme_advanced_resizing : true,
            
            		// Example content CSS (should be your site CSS)
					content_css : "'.__PS_BASE_URI__.'themes/'._THEME_NAME_.'/css/global.css",
            
                   save_enablewhendirty : true,
	               save_onsavecallback : "mysave",
            
            		// Drop lists for link/image/media/template dialogs
            		template_external_list_url : "../js/tinymce/jscripts/tiny_mce/lists/template_list.js",
            		external_link_list_url : "lists/link_list.js",
            		external_image_list_url : "lists/image_list.js",
            		media_external_list_url : "lists/media_list.js",
					elements : "nourlconvert",
					convert_urls : false,

            
            		
            	});
                </script>
		
		<textarea name="body_email" style="width:100%; height: auto;">
                '.Tools::getValue('body_email').'
		</textarea>

        <br />
            <div align="left">
            Писем в минуту:
            <input type="text" name="wait_time" size="2" value="3" /></label> <br /><br />
		
            <div align="left">
            <input type="checkbox" name="sTeste" value="1" /> Тестеру:
            <input type="email" value="japancars@perm.ru" name="sMailTest" /></label> <br />';
        
//            <input type="checkbox" name="sSubscribers" value="1" /> Подписчикам Newsletter Block (';
//$rq = Db::getInstance()->ExecuteS('SELECT * FROM `'._DB_PREFIX_.'newsletter`; ');
//echo count($rq);
//echo ' юзерей). Можно указать %LASTNAME%: <input type="text" name="aSubscribers" size="10" /> <br />
        
echo '<input type="checkbox" name="sNewsletter" value="1"  /> Подписчикам на новости (' ;
	echo count(Customer::getNewsletteremails());
	echo ' юзерей) <br />
<input type="checkbox" name="sOptin" value="1"  /> Подписчикам на скидки (';
	echo count(Customer::getOptinemails());
	echo ' юзерей)<br />



<input type="checkbox" name="sWishlist" value="1"  /> Вишлистам с товаром: ';
/* вишлисты
ввод: товар из select
вывод: список юзерей для $finalList
*/

$sql = Db::getInstance()->ExecuteS('
SELECT count(id_product) as qty, `id_product`
FROM `presta_wishlist_product`
GROUP BY `id_product`
ORDER BY  `qty` DESC
');

echo '<select name="wishlist" id="wishlist">';
foreach ($sql as $products)
{
$qty = $products['qty'];
	
	$product = Db::getInstance()->ExecuteS('
	SELECT name
	FROM `presta_product_lang`
	WHERE `id_product` = '.intval($products['id_product']).'
	');

		if (count($product)==false) continue;
		foreach ($product as $names)
		{
		$id_product = $products['id_product'];
		$name = ($names['name']);
		echo '<option value="'.$id_product.'">'.$name.' - ('.$qty.' юзерей)';
		echo('</option>');
		}
}
echo '</select>';

echo '<br><input type="checkbox" name="sNopurchase" value="1"  /> Не купившим ничего (-РАБОТАЕТ, НО ПЕРЕСТАВИТЬ СЧЕТЧИК-';
echo count($nopurchase);
echo ' юзерей)';


echo '<br><input type="checkbox" name="sCustomers" value="1"  /> Всем клиентам (';
	echo count(Customer::getCustomers());
	echo ' юзерей)
        </div>
        
        <br />        
                
        <div align="right">                
		<input type="submit" onclick="return confirm(\''.$this->l('Запустить спам?').'\');" name="send_newsletter" value="'.$this->l('Send').'" class="button" />
		</div>
                
		</fieldset>
		</form>
		';
		
            unset( $_SESSION['newsletter'] );
            $_SESSION['newsletter']['POST']           =   $_POST;
        }
    }

    public function postProcess()
    {
        global $currentIndex, $cookie;

        if (Tools::getValue('send') == 1)
        {
            if( !isset($_SESSION['newsletter']) OR empty($_SESSION['newsletter']['finalList']) )
            {
            
            
            $id_product = $_POST['wishlist'];
			$users = Db::getInstance()->ExecuteS('
			SELECT c.`firstname`, c.`lastname`, c.`email`
			FROM `presta_wishlist_product` p
			INNER JOIN `presta_wishlist` w
			INNER JOIN `presta_customer` c
			WHERE p.`id_wishlist` = w.`id_wishlist`
			AND w.`id_customer` = c.`id_customer`
			AND p.`id_product` = "'.$id_product.'"			

			');
				for($i = 0; $i < count($users); ++$i) 
				{
					$wishlist[] = array('email'=>$users[$i][email], 'firstname'=>$users[$i][firstname], 'lastname'=>$users[$i][lastname]);
				}			


			/* юзера, которые никогда ничего не покупали
			вывод: список юзерей для $finalList
			*/
			
			$nopurchase = Db::getInstance()->ExecuteS('
			SELECT DISTINCT c.`id_customer`, `firstname`, `lastname`, `email`
			FROM `presta_customer` c
			LEFT OUTER JOIN `presta_orders` o ON (o.`id_customer` = c.`id_customer`)
			WHERE o.`id_order` IS null
			and c.`deleted` = 0 
			and c.`active` = 1
			');

                $sCustomers 			= Tools::getValue('sCustomers');
                $sSubscribers           = Tools::getValue('sNewsletter');
                $sNewsletter 			= Tools::getValue('sSubscribers');
                $sTeste                 = Tools::getValue('sTeste');
                $sOptin                 = Tools::getValue('sOptin');
                $sWishlist				= Tools::getValue('sWishlist');
                $sNopurchase			= Tools::getValue('sNopurchase');                
               

                $sendList               = Array();
                if( $sTeste AND Validate::isEmail(Tools::getValue('sMailTest')) )
                {
                    $Array[]            =
                    array( 'email' => Tools::getValue('sMailTest'), 'firstname' => 'Fulano', 'lastname' => 'de Tal' );
                    $sendList = array_merge($sendList,  $Array );
                }

                if ($sCustomers )
                {
                    $customers 		= Customer::getCustomersNoBanned();
                    $sendList 		= array_merge($sendList, $customers);
               	}

                if ($sNewsletter )
                {
                    $rq                 = Db::getInstance()->ExecuteS('
                            SELECT *
                            FROM `'._DB_PREFIX_.'newsletter`; ');
                            $bNewsletters = (is_array($rq) ? $rq : 0);
                    $sendList           = array_merge($sendList,$bNewsletters);
                }

                if ($sSubscribers )
                {
                    $newsletter         = Customer::getNewsletteremails();
                    $sendList           = array_merge($sendList,$newsletter);
                }

                if ($sOptin )
                {
                    $optin				= Customer::getOptinemails();
                    $sendList           = array_merge($sendList, $optin);
                }

                if ($sWishlist )
                {
                    $wishlist			= $wishlist;
                    $sendList           = array_merge($sendList, $wishlist);
                }

                if ($sNopurchase )
                {
                    $nopurchase			= $nopurchase;
                    $sendList           = array_merge($sendList, $nopurchase);
                }

                $finalList = Array();
                foreach ($sendList as $item){
                if (!recursive_in_array($item['email'],$finalList)){$finalList[] = $item;}
                }
                
                        
//echo('<pre>');
//print_r($sendList);
//echo('</pre>');


/*                
                if ($sCustomers )
                {
                $spam = 'Спамим всех: ';
                $q = count(Customer::getCustomers());
               	}
                                
                if ($sSubscribers)
                {
	            $spam = 'Спамим подписчиков Newsletter Block: ';
				$q = count(Customer::getNewsletteremails());
                }
				
				if ($sOptin)
				{
				$spam = 'Спамим подписчиков на скидки: ';
				$q = count(Customer::getOptinemails());
				}

				if ($sNewsletter )
                {
                $spam = 'Спамим подписчиков на новости: ';
                $q = count($rq);
                }

				if ($sWishlist )
                {
                $spam = 'Спамим вишлист: ';
                $q = $qty;
                }
				
				echo $spam.' ('.$q.' юзерей) ';
				echo 'с интервалом ';
				echo (round($check_division));
				echo ' сек.<br>';
//				echo 'Осталось '.$q++;

*/





                $Result['total']            =   0;
                $Result['failed']           =   0;
                $Result['sucess']           =   0;
                $ArrayFailed                =   array();
                $key                        =   0;
                $output                     =   '';
                $check_division             =   60 / ( intval($_POST['wait_time']) > 0 ? intval($_POST['wait_time']) : 2 ) ;
                $wait                       =   round(intval( $check_division ) > 0 ? $check_division : 30);

                $_SESSION['newsletter']['check']          =   TRUE;
                $_SESSION['newsletter']['finalList']      =   $finalList;
                $_SESSION['newsletter']['total']          =   $Result['total'];
                $_SESSION['newsletter']['failed']         =   $Result['failed'];
                $_SESSION['newsletter']['sucess']         =   $Result['sucess'];
                $_SESSION['newsletter']['ArrayFailed']    =   $ArrayFailed;
                $_SESSION['newsletter']['POST']           =   $_POST;
                $_SESSION['newsletter']['GET']            =   $_GET;
                $_SESSION['newsletter']['key']            =   $key;
                $_SESSION['newsletter']['output']         =   $output;
            }else{
                $finalList                  =   $_SESSION['newsletter']['finalList'];
                $Result['total']            =   $_SESSION['newsletter']['total'];
                $Result['failed']           =   $_SESSION['newsletter']['failed'];
                $Result['sucess']           =   $_SESSION['newsletter']['sucess'];
                $ArrayFailed                =   $_SESSION['newsletter']['ArrayFailed'];
                $_POST                      =   $_SESSION['newsletter']['POST'];
                $_GET                       =   $_SESSION['newsletter']['GET'];
                $key                        =   $_SESSION['newsletter']['key']+1;
                $output                     =   $_SESSION['newsletter']['output'];
                
                $check_division             =   60 / ( intval($_POST['wait_time']) > 0 ? intval($_POST['wait_time']) : 2 ) ;
                $wait                       =   round(intval( $check_division ) > 0 ? $check_division : 30);
            }
            

//echo('<pre>');
//print_r($_POST['nopurchase']);
//echo('</pre>');
            

            /** GLOBAL **/
            $email_from                     = Configuration::get('PS_SHOP_EMAIL');
            $name                           = Configuration::get('PS_SHOP_NAME');
            /** GLOBAL **/

            $value      = $finalList[$key];

            //%EMAIL% - %NOME% - %SOBRENOME%
            $html 	= Tools::getValue('body_email');
            $assunto 	= Tools::getValue('subject_email');

            $firstname 	= isset($value['firstname']) ? $value['firstname'] : Tools::getValue('aSubscribers');
            $lastname 	= isset($value['lastname']) ? $value['lastname'] : null;

//тестовый емайл
            $email 	= $value['email'];
//            $email 	= 'japancars@perm.ru';

            $html 	= str_replace($this->l('%FIRSTNAME%'), $firstname, $html);
            $html 	= str_replace($this->l('%LASTNAME%'),  $lastname, $html);
            $html 	= str_replace($this->l('%MAIL%'),      $email, $html);

            $assunto 	= str_replace($this->l('%FIRSTNAME%'), $firstname, $assunto);
            $assunto 	= str_replace($this->l('%LASTNAME%'),  $lastname, $assunto);
            $assunto 	= str_replace($this->l('%MAIL%'),      $email, $assunto);

            unset($headers);
            $headers        = "MIME-Version: 1.0\r\n";
            $headers 	   .= "Content-type: text/html; charset=iso-8859-1\r\n";
            $headers 	   .= "X-Priority: 1\r\n";
            $headers 	   .= "X-MSMail-Priority: High\r\n";
            $headers 	   .= "Disposition-Notification-To: $email_from\r\n" . $headers .=
                              "Reply-To: $email_from\r\n";
            $headers 	   .= "From: $name <$email_from>\r\n";
            $headers 	   .= "Organization: $name\r\n";

            $mensagem 		= nl2br($html);

            $Mail_Send          = new Mail();
            $send               = false;

//отключать рассылку здесь
            $send 		= Mail::Send(intval($cookie->id_lang), 'newsletter', $assunto, array('{email}' => $email_from, '{firstname}' => $firstname, '{message}' => stripslashes($mensagem)), $email,NULL,NULL,NULL,NULL,NULL,_PS_MAIL_DIR_, 0);

 		     $output             .=
            '<tr>
            <td>&nbsp;' . $firstname . '</td>
            <td>&nbsp;' . $lastname . '</td>
            <td>&nbsp;' . $email . '</td>
            <td align="center">';

            if ($send)
            {
                $Result['sucess']++;
                $output .= "<font color=\"GREEN\"> ".$this->l('SUCESS!')." </font> <br>";
            } else
            {
                $ArrayFailed[]  =   $value;
                $Result['failed']++;
                $output .=  "<font color=\"RED\"> ".$this->l('FAILED')." </font> <br>";
            }
            $output .=  ' </td></tr>';

            $Result['total']++;

            $_SESSION['newsletter']['key']            =   $key;
            $_SESSION['newsletter']['output']         =   $output;
            $_SESSION['newsletter']['total']          =   $Result['total'];
            $_SESSION['newsletter']['failed']         =   $Result['failed'];
            $_SESSION['newsletter']['sucess']         =   $Result['sucess'];

            $ouput_foot     =    '<tr><td colspan="4">&nbsp;</td></tr>';
            
            $ouput_foot     .=    '<tr><td colspan="2"  ></td><td><b>&nbsp; '.$this->l('Total sent').'</b></td><td align="center">'
            .$Result['total'].'</td><tr>';
            
            $ouput_foot     .=    '<tr><td colspan="2"  ></td><td><b>&nbsp; '.$this->l('Total successfully').'</b></td><td align="center">
            <font color="GREEN">'.$Result['sucess'].'</font></td><tr>';
            
            $ouput_foot     .=    '<tr><td colspan="2"  ></td><td><b>&nbsp; '.$this->l('Total failure').'</b></td><td align="center">
            <font color="RED">'.$Result['failed'].'</font></td><tr>';

            if( isset($finalList[$key+1]) ){
                echo "<meta http-equiv=\"refresh\" content=\"$wait\" />";
                echo '<div class="alert">'.$this->l('Не перезагружайте страницу до появления надписи "Готово!"').'</div>';
                
                $button      =   '  ';
            }
            else{
                session_destroy();
                echo '<div class="conf">'.$this->l('Готово!').'</div>';
                $button      =   ' <input type="submit" name="voltar" value="'.$this->l('Новый спам').'" class="button" /> ';
            }


            $ouput_header                   = '<fieldset>
            <legend>Процесс: '.$spam.' ('.$q.' юзерей)</legend><br>
            <table border="1" width="100%" style="border-collapse: collapse;"
            cellpadding="2" bordercolor="#e0d0b1" >
            <tr><td></td></tr>
            <tr>
                    <td align="center"><b>'.$this->l('FIRSTNAME').'</b></td>
                    <td align="center"><b>'.$this->l('LASTNAME').'</b></td>
                    <td align="center"><b>'.$this->l('E-MAIL').'</b></td>
                    <td align="center"><b>'.$this->l('STATUS').'</b></td>
            </tr>
            ';

            /** BOTÃO VOLTAR **/
            $ouput_foot     .=    '</table>
            </fieldset>
            <form action="' . $currentIndex . '&token=' . $this->token .
            '&send=0" method="post" >
            <div align="right"><br />
                '.$button.'
		</div>
		</form>';

            echo $ouput_header;
            echo $output;
            echo $ouput_foot;

        }
    }
}

?>