<?php

class googleqrcodelite extends Module
{
 	function __construct()
 	{
 	 	$this->name = 'googleqrcodelite';
 	 	$this->version = '1.1';
		$this->tab = version_compare(_PS_VERSION_, '1.4.0.0', '>=')?'front_office_features':'Mediacom87';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Display a QRCode');
		$this->description = $this->l('Display a QRcode on your product page');
		$this->author = 'Mediacom87';
 	}

	function install()
	{
	 	if (!parent::install()
			OR !$this->registerHook('productActions'))
	 		return false;
	 	return true;
	}
	
	function uninstall()
	{
		if (!parent::uninstall())
			return false;
		return true;
	}

	public function getContent()
	{
		$output = '<h2><img src="'.$this->_path.'logo.gif" alt="" /> '.$this->displayName.'</h2>';
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		global $cookie;
		
		$iso = Language::getIsoById((int)$cookie->id_lang);
		return '
		
		<fieldset class="space">
				<legend><img src="http://netdna.prestatoolbox.net/images/google-icon-16x16.png" alt="" height="16" width="16" /> '.$this->l('Ads').'</legend>
				<p style="text-align:center"><a href="http://www.mediacom87.fr/?utm_source=module&utm_medium=cpc&utm_campaign='.$this->name.'" target="_blank" title="Mediacom87 WebAgency">Mediacom87 WebAgency</a></p>
				<p style="text-align:center">
				<script type="text/javascript"><!--
				google_ad_client = "ca-pub-1663608442612102";
				/* Gratuit - GoogleQRcodeLite 728x90 */
				google_ad_slot = "2372267055";
				google_ad_width = 728;
				google_ad_height = 90;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
				</p>
		</fieldset>
		
		<fieldset class="space">
			<legend><a href="https://www.paypal.com/fr/mrb/pal=LG5H4P9K8K6FC" target="_blank"><img src="http://netdna.prestatoolbox.net/images/paypal-icon-16x16.png" alt="" /></a> '.$this->l('Donation').'</legend>
			<p style="font-size: 1em; font-weight: bold; padding-bottom: 0">'.$this->displayName.'</p>
			<p style="clear: both">
			'.$this->l('Thanks for installing this module on your website.').'
			</p>
			<p>
			'.$this->l('This module help you to include a QRcode on your product page').'
			</p>
			<p>
			'.$this->l('Developped by').' <a style="color: #900; text-decoration: underline;" href="http://www.prestatoolbox.'.(($iso != 'fr')?'com':'fr').'/?utm_source=module&utm_medium=cpc&utm_campaign='.$this->name.'">Mediacom87</a>'.$this->l(', which helps you develop your e-commerce site.').'
			</p>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align:center" target="_blank">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="WQN3WEBWP2DML">
			<input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
			<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
			</form>
		</fieldset>
		';
	}

	function hookproductActions($params)
	{
		global $smarty,$link,$cookie;
		$product = new Product((int)Tools::getValue('id_product'),true,(int)$cookie->id_lang);
		$plink = $link->getProductLink((int)$product->id, $product->link_rewrite, $product->category, $product->ean13);
		$smarty->assign(array('plink' => $plink));
		return $this->display(__FILE__, $this->name.'.tpl');
	}

}

?>