<?php
$useSSL = true;
include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/header.php');
$errors = array();
$smarty->assign('contacts', Contact::getContacts(intval($cookie->id_lang)));

        if (Tools::isSubmit('submitMessage'))
        {
            // если скрытое поле firstname заполнено - это спамер
            if(strlen($_POST['firstname']) > 0) {
                die;
            }

            $recaptcha = $_POST['g-recaptcha-response'];
            if(!empty($recaptcha)) {
                //Получаем HTTP от recaptcha
                $recaptcha = $_REQUEST['g-recaptcha-response'];
                $secret = '6LcQNQ8TAAAAAMwN_F-pQRF0V6v5HZjElRszv3fe';
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=".$secret ."&response=".$recaptcha."&remoteip=".$_SERVER['REMOTE_ADDR'];
             
                //Инициализация и настройка запроса
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_TIMEOUT, 10);
                curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
                //Выполняем запрос и получается ответ от сервера гугл
                $curlData = curl_exec($curl);

                $curlData1 = print_r($curlData, true); // дебаг для отправки админу

                curl_close($curl);  
                $curlData = json_decode($curlData, true);

                //Смотрим на результат 
                if($curlData['success']) {
                    //Сюда попадем если капча пройдена, дальше выполняем обычные 
                    //действия(добавляем коммент или отправляем письмо) с формой
                    
                    	if (!($from = Tools::getValue('from')) OR !Validate::isEmail($from))
                            $errors[] = Tools::displayError('invalid e-mail address');
                        elseif (!($message = nl2br2(Tools::getValue('message'))))
                            $errors[] = Tools::displayError('message cannot be blank');

                        elseif (!Validate::isMessage($message))
                            $errors[] = Tools::displayError('invalid message');
                        elseif (!($id_contact = intval(Tools::getValue('id_contact'))) OR !(Validate::isLoadedObject($contact = new Contact(intval($id_contact), intval($cookie->id_lang)))))
                        	$errors[] = Tools::displayError('please select a contact in the list');
                        else
                        {
                    		if (intval($cookie->id_customer))
                    			$customer = new Customer(intval($cookie->id_customer));
                    		if (Mail::Send(intval($cookie->id_lang), 'contact', 'Message from contact form', array('{email}' => $_POST['from'], '{message}' => stripslashes($message)), $contact->email, $contact->name, $from, (intval($cookie->id_customer) ? $customer->firstname.' '.$customer->lastname : $from)))
                    			$smarty->assign('confirmation', 1);
                    		else
                    			$errors[] = Tools::displayError('an error occurred while sending message');
                        }
                    $email = Tools::safeOutput(Tools::getValue('from', ((isset($cookie) AND isset($cookie->email) AND Validate::isEmail($cookie->email)) ? $cookie->email : '')));
                } else {
                            Mail::Send('3', 'contact', 'Ошибка формы обратной связи', array('{email}' => 'support@motokofr.com', '{message}' => 'Не выполнилось условие if($curlData[\'success\'] <br>Содержимое $curlData: <br>'.$curlData1), 'support@motokofr.com');

                    $errors[] = Tools::displayError('Внутренняя ошибка '.__LINE__);
                }
            }
            else {
                $errors[] = Tools::displayError('Пожалуйста подтверди капчу');
            }
}

$smarty->assign(array(
    'errors' => $errors,
    'email' => $email
));
$smarty->display(_PS_THEME_DIR_.'contact-form.tpl');
include(dirname(__FILE__).'/footer.php');

?>
