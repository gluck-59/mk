<?php

/* SSL Management */
$useSSL = true;

include(dirname(__FILE__).'/config/config.inc.php');
include(dirname(__FILE__).'/init.php');
include_once(dirname(__FILE__).'/SxGeo/SxGeo.php'); 

if (!$cookie->isLogged())
    Tools::redirect('authentication.php');

//CSS ans JS file calls
$js_files = array(
	_THEME_JS_DIR_.'tools/statesManagement.js',
	'https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js',
	'https://dadata.ru/static/js/lib/jquery.suggestions-4.10.min.js'
);

print_r($css_files);

if ($back = Tools::getValue('back'))
	$smarty->assign('back', Tools::safeOutput($back));
	
$errors = array();
	
if ($id_address = intval(Tools::getValue('id_address')))
{
    $address = new Address(intval($id_address));
    if (Validate::isLoadedObject($address) AND Customer::customerHasAddress(intval($cookie->id_customer), intval($id_address)))
    {
		if (isset($_GET['delete']))
		{
			if ($cart->id_address_invoice == $address->id)
				unset($cart->id_address_invoice);
			if ($cart->id_address_delivery == $address->id)
				unset($cart->id_address_delivery);
			if ($address->delete())
				Tools::redirect('addresses.php');
			$errors[] = Tools::displayError('this address cannot be deleted');
		}
		$smarty->assign(array(
			'address' => $address,
			'id_address' => intval($id_address)
		));
	}
	else
		Tools::redirect('addresses.php');
}

if (Tools::isSubmit('submitAddress'))
{
	$address = new Address();
	$address->id_customer = intval($cookie->id_customer);
	$errors = $address->validateControler();
	
	if (Configuration::get('PS_TOKEN_ENABLE') == 1 &&
		strcmp(Tools::getToken(false), Tools::getValue('token')) &&
		$cookie->isLogged() === true)
		$errors[] = Tools::displayError('invalid token');

	if (!$country = new Country($address->id_country) OR !Validate::isLoadedObject($country))
			die(Tools::displayError());
		if (intval($country->contains_states) AND !intval($address->id_state))
			$errors[] = Tools::displayError('this country require a state selection');

	if (!sizeof($errors))
    {
		if (isset($id_address))
		{
			$country = new Country(intval($address->id_country));
			if (Validate::isLoadedObject($country) AND !$country->contains_states)
				$address->id_state = false;
			$address_old = new Address(intval($id_address));
			if (Validate::isLoadedObject($address_old) AND Customer::customerHasAddress(intval($cookie->id_customer), intval($address_old->id)))
			{
				if ($cart->id_address_invoice == $address_old->id)
					unset($cart->id_address_invoice);
				if ($cart->id_address_delivery == $address_old->id)
					unset($cart->id_address_delivery);

				if ($address_old->isUsed())
					$address_old->delete();
				else
				{
					$address->id = intval($address_old->id);
					$address->date_add = $address_old->date_add;
				}
			}
		}
		
		if ($result = $address->save())
		{
			if ((bool)(Tools::getValue('select_address', false)) == true)
			{
				/* This new adress is for invoice_adress, select it */
				$cart->id_address_invoice = intval($address->id);
				$cart->update();
			}
			Tools::redirect($back ? $back : 'addresses.php');
		}
		$errors[] = Tools::displayError('an error occurred while updating your address');
    }
}
elseif (!$id_address)
{
	$customer = new Customer(intval($cookie->id_customer));
	if (Validate::isLoadedObject($customer))
	{
		$_POST['firstname'] = Tools::translitIt($customer->firstname); // было без транслита
		$_POST['lastname'] =  Tools::translitIt($customer->lastname); // было без транслита
	}
}

if (isset($_POST['id_country']) AND !empty($_POST['id_country']) AND is_numeric($_POST['id_country']))
	$selectedCountry = intval($_POST['id_country']);
elseif (isset($address) AND isset($address->id_country) AND !empty($address->id_country) AND is_numeric($address->id_country))
	$selectedCountry = intval($address->id_country);
elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
{
	$array = preg_split('/,|-/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
	if (!Validate::isLanguageIsoCode($array[0]) OR !($selectedCountry = Country::getByIso($array[0])))
		$selectedCountry = intval(Configuration::get('PS_COUNTRY_DEFAULT'));
}
else
	$selectedCountry = intval(Configuration::get('PS_COUNTRY_DEFAULT'));

$countries = Country::getCountries(intval($cookie->id_lang), true);
$countriesList = '';
foreach ($countries AS $country)
    $countriesList .= '<option value="'.intval($country['id_country']).'" '.($country['id_country'] == $selectedCountry ? 'selected="selected"' : '').'>'.htmlentities($country['name'], ENT_COMPAT, 'UTF-8').'</option>';

// определим город 
$SxGeo = new SxGeo('SxGeo/SxGeoCity.dat');
$ip = $_SERVER['REMOTE_ADDR'];
if ($ip) 
{
	$geo = $SxGeo->getCityFull($ip);
		if ($geo['city']['name_ru'])
	    $city = $geo['city']['name_ru'];
	  
	elseif ($geo['region']['name_ru'])
		$city = $geo['region']['name_ru'];
	else 
		$city = $geo['country']['name_ru'];	
}


include(dirname(__FILE__).'/header.php');
$smarty->assign('countries_list', $countriesList);
$smarty->assign('countries', $countries);
$smarty->assign('errors', $errors);
$smarty->assign('city', $city);
$smarty->assign('token', Tools::getToken(false));
$smarty->assign('select_address', intval(Tools::getValue('select_address')));
Tools::safePostVars();
$smarty->display(_PS_THEME_DIR_.'address.tpl');
include(dirname(__FILE__).'/footer.php');
?>