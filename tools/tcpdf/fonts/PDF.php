<?php

/**
  * PDF class, PDF.php
  * PDF invoices and document management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.0
  *
  */

include_once(_PS_FPDF_PATH_.'fpdf.php');

class PDF extends FPDF
{
	private static $order = NULL;
	private static $orderReturn = NULL;
	private static $orderSlip = NULL;

	/** @var object Order currency object */
	private static $currency = NULL;

	private static $_iso;

	/** @var array Special PDF params such encoding and font */

	private static $_pdfparams = array();

	/**
	* Constructor
	*/
	
function PDF($orientation='P', $unit='mm', $format='A4')
    {
        global $cookie;
	    
        if (!isset($cookie) OR !is_object($cookie))
        $cookie->id_lang = intval(Configuration::get('PS_LANG_DEFAULT'));
        $lang = strtoupper(Language::getIsoById($cookie->id_lang));
        $conf = Configuration::getMultiple(array('PS_PDF_ENCODING_'.$lang, 'PS_PDF_FONT_'.$lang));
        $conf['PS_PDF_ENCODING'] = (isset($conf['PS_PDF_ENCODING_'.$lang]) AND $conf['PS_PDF_ENCODING_'.$lang] == true) ? $conf['PS_PDF_ENCODING_'.$lang] : 'iso-8859-1';
        $conf['PS_PDF_FONT'] = (isset($conf['PS_PDF_FONT_'.$lang]) AND $conf['PS_PDF_FONT_'.$lang] == true) ? $conf['PS_PDF_FONT_'.$lang] : 'Arial';
        self::$_pdfparams[$lang] = array('encoding' => $conf['PS_PDF_ENCODING'],
        'font' => $conf['PS_PDF_FONT']);
        FPDF::FPDF($orientation, $unit, $format);
        $font = self::embedfont();
            if($font) {
                    $this->AddFont($font);
                    $this->AddFont($font, 'B');
                    }        
    } 	
	
/*	function PDF($orientation='P', $unit='mm', $format='A4')
	{
		global $cookie;

		if (!isset($cookie) OR !is_object($cookie))
			$cookie->id_lang = intval(Configuration::get('PS_LANG_DEFAULT'));
		$lang = strtoupper(Language::getIsoById($cookie->id_lang));
		$conf = Configuration::getMultiple(array('PS_PDF_ENCODING_'.$lang, 'PS_PDF_FONT_'.$lang));
		$conf['PS_PDF_ENCODING'] = (isset($conf['PS_PDF_ENCODING_'.$lang]) AND $conf['PS_PDF_ENCODING_'.$lang] == true) ? $conf['PS_PDF_ENCODING_'.$lang] : 'iso-8859-1';
		$conf['PS_PDF_FONT'] = (isset($conf['PS_PDF_FONT_'.$lang]) AND $conf['PS_PDF_FONT_'.$lang] == true) ? $conf['PS_PDF_FONT_'.$lang] : 'Arial';
		self::$_pdfparams[$lang] = array('encoding' => $conf['PS_PDF_ENCODING'],
										'font' => $conf['PS_PDF_FONT']);
		FPDF::FPDF($orientation, $unit, $format);
	}
*/
	/**
	* Invoice header
	*/
	function Header()
	{
		$conf = Configuration::getMultiple(array('PS_SHOP_NAME', 'PS_SHOP_ADDR1', 'PS_SHOP_CODE', 'PS_SHOP_CITY', 'PS_SHOP_COUNTRY'));
		$conf['PS_SHOP_NAME'] = isset($conf['PS_SHOP_NAME']) ? Tools::iconv('utf-8', self::encoding(), $conf['PS_SHOP_NAME']) : 'Your company';
		$conf['PS_SHOP_ADDR1'] = isset($conf['PS_SHOP_ADDR1']) ? Tools::iconv('utf-8', self::encoding(), $conf['PS_SHOP_ADDR1']) : 'Your company';
		$conf['PS_SHOP_CODE'] = isset($conf['PS_SHOP_CODE']) ? Tools::iconv('utf-8', self::encoding(), $conf['PS_SHOP_CODE']) : 'Postcode';
		$conf['PS_SHOP_CITY'] = isset($conf['PS_SHOP_CITY']) ? Tools::iconv('utf-8', self::encoding(), $conf['PS_SHOP_CITY']) : 'City';
		$conf['PS_SHOP_COUNTRY'] = isset($conf['PS_SHOP_COUNTRY']) ? Tools::iconv('utf-8', self::encoding(), $conf['PS_SHOP_COUNTRY']) : 'Country';

		if (file_exists(_PS_IMG_DIR_.'/logo.jpg'))
			$this->Image(_PS_IMG_DIR_.'/logo.jpg', 10, 8, 0, 15);
		$this->SetFont(self::fontname(), 'B', 15);
		$this->Cell(115);
		
		if (self::$orderReturn)
			$this->Cell(80, 10, self::l('RETURN #').sprintf('%06d', self::$orderReturn->id), 0, 0, 'C');
		elseif (self::$orderSlip)
			$this->Cell(80, 10, self::l('SLIP #').sprintf('%06d', self::$orderSlip->id), 0, 0, 'C');
		else
			$this->Cell(80, 10, self::l('INVOICE #').sprintf('%06d', self::$order->id), 0, 0, 'C');
	}

	/**
	* Invoice footer
	*/
	function Footer()
	{
		$this->SetY(-26);

		/*
		 * Display a message for customer
		 */
		$this->SetFont(self::fontname(), '', 8);
		$this->Cell(0, 10, self::l('An electronic version of this invoice is kept in your account. To access it, log in to the'), 0, 0, 'C', 0, (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].__PS_BASE_URI__.'history.php');
		$this->Ln(4);
		$this->Cell(0, 10, Tools::iconv('utf-8', self::encoding(), Configuration::get('PS_SHOP_NAME')).' '.self::l('website using your e-mail address and password (which you created while placing your first order).'), 0, 0, 'C', 0, (Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].__PS_BASE_URI__.'history.php');
		$this->Ln(9);

		$arrayConf = array('PS_SHOP_NAME', 'PS_SHOP_ADDR1', 'PS_SHOP_CODE', 'PS_SHOP_CITY', 'PS_SHOP_COUNTRY', 'PS_SHOP_DETAILS', 'PS_SHOP_PHONE');
		$conf = Configuration::getMultiple($arrayConf);
		foreach($conf as $key => $value)
			$conf[$key] = Tools::iconv('utf-8', self::encoding(), $value);
		foreach ($arrayConf as $key)
			if (!isset($conf[$key]))
				$conf[$key] = '';
		$this->SetFillColor(240, 240, 240);
		$this->SetTextColor(0, 0, 0);
		$this->SetFont(self::fontname(), '', 8);
		$this->Cell(0, 5, Tools::strtoupper($conf['PS_SHOP_NAME']).
		(!empty($conf['PS_SHOP_ADDR1']) ? ' - '.self::l('Headquarters:').' '.$conf['PS_SHOP_ADDR1'].(!empty($conf['PS_SHOP_ADDR2']) ? ' '.$conf['PS_SHOP_ADDR2'] : '').' '.$conf['PS_SHOP_CODE'].' '.$conf['PS_SHOP_CITY'].' '.$conf['PS_SHOP_COUNTRY'] : ''), 0, 1, 'C', 1);
		$this->Cell(0, 5,
		(!empty($conf['PS_SHOP_DETAILS']) ? self::l('Details:').' '.$conf['PS_SHOP_DETAILS'].' - ' : '').
		(!empty($conf['PS_SHOP_PHONE']) ? self::l('PHONE:').' '.$conf['PS_SHOP_PHONE'] : ''), 0, 1, 'C', 1);
	}

	public static function multipleInvoices($orders)
	{
		$pdf = new PDF('P', 'mm', 'A4');
		foreach ($orders AS $id_order)
		{
			$orderObj = new Order(intval($id_order));
			if (Validate::isLoadedObject($orderObj))
				PDF::invoice($orderObj, 'D', true, $pdf);
		}

		return $pdf->Output('invoices.pdf', 'D');
	}
	
	public static function orderReturn($orderReturn, $mode = 'D', $multiple = false, &$pdf = NULL)
	{
		$pdf = new PDF('P', 'mm', 'A4');
		self::$orderReturn = $orderReturn;
		$order = new Order($orderReturn->id_order);
		self::$order = $order;
		$pdf->AliasNbPages();
		$pdf->AddPage();
		
		/* Display address information */
		$delivery_address = new Address(intval($order->id_address_delivery));
		$deliveryState = $delivery_address->id_state ? new State($delivery_address->id_state) : false;
		$shop_country = Configuration::get('PS_SHOP_COUNTRY');
		$arrayConf = array('PS_SHOP_NAME', 'PS_SHOP_ADDR1', 'PS_SHOP_CODE', 'PS_SHOP_CITY', 'PS_SHOP_COUNTRY', 'PS_SHOP_DETAILS', 'PS_SHOP_PHONE');
		$conf = Configuration::getMultiple($arrayConf);
		foreach ($conf as $key => $value)
			$conf[$key] = Tools::iconv('utf-8', self::encoding(), $value);
		foreach ($arrayConf as $key)
			if (!isset($conf[$key]))
				$conf[$key] = '';
		
		$width = 100;

		$pdf->SetX(10);
		$pdf->SetY(25);
		$pdf->SetFont(self::fontname(), '', 9);

		if (!empty($delivery_address->company))
		{
			$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->company), 0, 'L');
			$pdf->Ln(5);
		}
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->firstname).' '.Tools::iconv('utf-8', self::encoding(), $delivery_address->lastname), 0, 'L');
		$pdf->Ln(5);
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->address1), 0, 'L');
		$pdf->Ln(5);
		if (!empty($delivery_address->address2))
		{
			$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->address2), 0, 'L');
			$pdf->Ln(5);
		}
		$pdf->Cell($width, 10, $delivery_address->postcode.' '.Tools::iconv('utf-8', self::encoding(), $delivery_address->city), 0, 'L');
		$pdf->Ln(5);
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->country.($deliveryState ? ' - '.$deliveryState->name : '')), 0, 'L');
		
		/*
		 * display order information
		 */
		$pdf->Ln(12);
		$pdf->SetFillColor(240, 240, 240);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont(self::fontname(), '', 9);
		$pdf->Cell(0, 6, self::l('RETURN #').sprintf('%06d', self::$orderReturn->id).' '.self::l('from') . ' ' .Tools::displayDate(self::$orderReturn->date_upd, self::$order->id_lang), 1, 2, 'L');
		$pdf->Cell(0, 6, self::l('We have logged your return request.'), 'TRL', 2, 'L');
		$pdf->Cell(0, 6, self::l('We remind you that your package must be returned to us within 7 days of initially receiving your order.'), 'BRL', 2, 'L');
		$pdf->Ln(5);
		$pdf->Cell(0, 6, self::l('List of items marked as returned :'), 0, 2, 'L');
		$pdf->Ln(5);
		$pdf->ProdReturnTab();
		$pdf->Ln(5);
		$pdf->SetFont(self::fontname(), 'B', 10);
		$pdf->Cell(0, 6, self::l('Return reference:').' '.self::l('RET').sprintf('%06d', self::$order->id), 0, 2, 'C');
		$pdf->Cell(0, 6, self::l('Thank you for including this number on your return package.'), 0, 2, 'C');
		$pdf->Ln(5);
		$pdf->SetFont(self::fontname(), 'B', 9);
		$pdf->Cell(0, 6, self::l('REMINDER:'), 0, 2, 'L');
		$pdf->SetFont(self::fontname(), '', 9);
		$pdf->Cell(0, 6, self::l('- All products must be returned in their original packaging without damage or wear.'), 0, 2, 'L');
		$pdf->Cell(0, 6, self::l('- Please print out this document and slip it into your package.'), 0, 2, 'L');
		$pdf->Cell(0, 6, self::l('- The package should be sent to the following address:'), 0, 2, 'L');
		$pdf->Ln(5);
		$pdf->SetFont(self::fontname(), 'B', 10);
		$pdf->Cell(0, 5, Tools::strtoupper($conf['PS_SHOP_NAME']), 0, 1, 'C', 1);
		$pdf->Cell(0, 5, (!empty($conf['PS_SHOP_ADDR1']) ? self::l('Headquarters:').' '.$conf['PS_SHOP_ADDR1'].(!empty($conf['PS_SHOP_ADDR2']) ? ' '.$conf['PS_SHOP_ADDR2'] : '').' '.$conf['PS_SHOP_CODE'].' '.$conf['PS_SHOP_CITY'].' '.$conf['PS_SHOP_COUNTRY'] : ''), 0, 1, 'C', 1);
		$pdf->Ln(5);
		$pdf->SetFont(self::fontname(), '', 9);
		$pdf->Cell(0, 6, self::l('Upon receiving your package, we will inform you by e-mail and will then begin processing the reimbursement of your order total.'), 0, 2, 'L');
		$pdf->Cell(0, 6, self::l('Let us know if you have any questions.'), 0, 2, 'L');
		$pdf->Ln(5);
		$pdf->SetFont(self::fontname(), 'B', 10);
		$pdf->Cell(0, 6, self::l('If the conditions of return listed above are not respected,'), 'TRL', 2, 'C');
		$pdf->Cell(0, 6, self::l('we reserve the right to refuse your package and/or reimbursement.'), 'BRL', 2, 'C');
		
		return $pdf->Output(sprintf('%06d', self::$order->id).'.pdf', $mode);
	}
	
	/**
	* Product table with price, quantities...
	*/
	function ProdReturnTab()
	{
		global $ecotax;

		$header = array(
			array(self::l('Description'), 'L'),
			array(self::l('Reference'), 'L'),
			array(self::l('Qty'), 'C')
		);
		$w = array(110, 25, 20);
		$this->SetFont(self::fontname(), 'B', 8);
		$this->SetFillColor(240, 240, 240);
		for ($i = 0; $i < sizeof($header); $i++)
			$this->Cell($w[$i], 5, $header[$i][0], 'T', 0, $header[$i][1], 1);
		$this->Ln();
		$this->SetFont(self::fontname(), '', 7);

		$products = OrderReturn::getOrdersReturnProducts(self::$orderReturn->id, self::$order);
		foreach ($products AS $product)
		{
			$before = $this->GetY();
            $this->MultiCell($w[0], 5, Tools::iconv('utf-8', self::encoding(), $product['product_name']), 'B');
			$lineSize = $this->GetY() - $before;
			$this->SetXY($this->GetX() + $w[0], $this->GetY() - $lineSize);
			$this->Cell($w[1], $lineSize, ($product['product_reference'] != '' ? $product['product_reference'] : '---'), 'B');
			$this->Cell($w[2], $lineSize, $product['product_quantity'], 'B', 0, 'C');
			$this->Ln();
		}
	}

	/**
	* Main
	*
	* @param object $order Order
	* @param string $mode Download or display (optional)
	*/
	public static function invoice($order, $mode = 'D', $multiple = false, &$pdf = NULL, $slip = false)
	{
	 	global $cookie, $ecotax;
		
		if (!Validate::isLoadedObject($order) OR (!$cookie->id_employee AND (!OrderState::invoiceAvailable($order->getCurrentState()))))
			die('Invalid order or invalid order state');
		self::$order = $order;
		self::$orderSlip = $slip;
		self::$_iso = strtoupper(Language::getIsoById(intval(self::$order->id_lang)));
		self::$currency = new Currency(intval(self::$order->id_currency));
		self::$currency->sign = Tools::iconv('utf-8', self::encoding(), self::$currency->sign);

		if (!$multiple)
			$pdf = new PDF('P', 'mm', 'A4');
		$pdf->AliasNbPages();
		$pdf->AddPage();

		/* Display address information */
		$invoice_address = new Address(intval($order->id_address_invoice));
		$invoiceState = $invoice_address->id_state ? new State($invoice_address->id_state) : false;
		$delivery_address = new Address(intval($order->id_address_delivery));
		$deliveryState = $delivery_address->id_state ? new State($delivery_address->id_state) : false;
		$shop_country = Configuration::get('PS_SHOP_COUNTRY');

		$width = 100;

		$pdf->SetX(10);
		$pdf->SetY(25);
		$pdf->SetFont(self::fontname(), '', 12);
		$pdf->Cell($width, 10, self::l('Delivery'), 0, 'L');
		$pdf->Cell($width, 10, self::l('Invoicing'), 0, 'L');
		$pdf->Ln(5);
		$pdf->SetFont(self::fontname(), '', 9);

		if (!empty($delivery_address->company) OR !empty($invoice_address->company))
		{
			$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->company), 0, 'L');
			$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $invoice_address->company), 0, 'L');
			$pdf->Ln(5);
		}
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->firstname).' '.Tools::iconv('utf-8', self::encoding(), $delivery_address->lastname), 0, 'L');
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $invoice_address->firstname).' '.Tools::iconv('utf-8', self::encoding(), $invoice_address->lastname), 0, 'L');
		$pdf->Ln(5);
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->address1), 0, 'L');
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $invoice_address->address1), 0, 'L');
		$pdf->Ln(5);
		if (!empty($invoice_address->address2) OR !empty($delivery_address->address2))
		{
			$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->address2), 0, 'L');
			$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $invoice_address->address2), 0, 'L');
			$pdf->Ln(5);
		}
		$pdf->Cell($width, 10, $delivery_address->postcode.' '.Tools::iconv('utf-8', self::encoding(), $delivery_address->city), 0, 'L');
		$pdf->Cell($width, 10, $invoice_address->postcode.' '.Tools::iconv('utf-8', self::encoding(), $invoice_address->city), 0, 'L');
		$pdf->Ln(5);
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $delivery_address->country.($deliveryState ? ' - '.$deliveryState->name : '')), 0, 'L');
		$pdf->Cell($width, 10, Tools::iconv('utf-8', self::encoding(), $invoice_address->country.($invoiceState ? ' - '.$invoiceState->name : '')), 0, 'L');
		$pdf->Ln(5);
		$pdf->Cell($width, 10, $delivery_address->phone, 0, 'L');
		if (!empty($delivery_address->phone_mobile))
		{
			$pdf->Ln(5);
			$pdf->Cell($width, 10, $delivery_address->phone_mobile, 0, 'L');
		}

		/*
		 * display order information
		 */
		$carrier = new Carrier(self::$order->id_carrier);
		if ($carrier->name == '0')
				$carrier->name = Configuration::get('PS_SHOP_NAME');
		$history = self::$order->getHistory(self::$order->id_lang);
		foreach($history as $h)
			if ($h['id_order_state'] == _PS_OS_SHIPPING_)
				$shipping_date = $h['date_add'];
		$pdf->Ln(12);
		$pdf->SetFillColor(240, 240, 240);
		$pdf->SetTextColor(0, 0, 0);
		$pdf->SetFont(self::fontname(), '', 9);
		if (self::$orderSlip)
			$pdf->Cell(0, 6, self::l('SLIP #').sprintf('%06d', self::$orderSlip->id).' '.self::l('from') . ' ' .Tools::displayDate(self::$orderSlip->date_upd, self::$order->id_lang), 1, 2, 'L', 1);
		else
			$pdf->Cell(0, 6, self::l('INVOICE #').sprintf('%06d', self::$order->id).' '.self::l('from') . ' ' .Tools::displayDate(self::$order->date_upd, self::$order->id_lang), 1, 2, 'L', 1);
		$pdf->Cell(75, 6, self::l('Order date:').' '.Tools::displayDate(self::$order->date_add, self::$order->id_lang), 'L', 0);
		$pdf->Cell(50, 6, self::l('Carrier:').' '.Tools::iconv('utf-8', self::encoding(), $carrier->name), 'L');
		$pdf->Cell(0, 6, self::l('Payment method:'), 'LR');
		$pdf->Ln(5);
		$pdf->Cell(75, 6, (isset($shipping_date) ? self::l('Shipping date:').' '.Tools::displayDate($shipping_date, self::$order->id_lang) : ' '), 'LB', 0);
		$pdf->Cell(50, 6, ($shop_country ? self::l('Origin:').' '.Tools::iconv('utf-8', self::encoding(), $shop_country) : ' '), 'LRB');
		$pdf->Cell(0, 6, Tools::iconv('utf-8', self::encoding(), $order->payment), 'LRB');
		$pdf->Ln(15);
		$pdf->ProdTab();
		$pdf->DiscTab();

		/*
		 * Display price summation
		 */
		$pdf->Ln(5);
		$pdf->SetFont(self::fontname(), 'B', 8);
		$width = 165;
		$pdf->Cell($width, 0, self::l('Total products TI').' : ', 0, 0, 'R');
		$totalProductsTi = self::$order->getTotalProductsWithTaxes((self::$orderSlip ? self::$order->products : false));
		$pdf->Cell(0, 0, self::convertSign(Tools::displayPrice($totalProductsTi, self::$currency, true, false)), 0, 0, 'R');
		$pdf->Ln(4);

		if (self::$order->total_discounts != '0.00')
		{
			$pdf->Cell($width, 0, self::l('Total discounts').' : ', 0, 0, 'R');
			$pdf->Cell(0, 0, (!self::$orderSlip ? '-' : '').self::convertSign(Tools::displayPrice(self::$order->total_discounts, self::$currency, true, false)), 0, 0, 'R');
			$pdf->Ln(4);
		}
		
		if(isset(self::$order->total_wrapping) and (floatval(self::$order->total_wrapping) > 0))
		{
            $pdf->Cell($width, 0, self::l('Total wrapping').' : ', 0, 0, 'R');
            $pdf->Cell(0, 0, self::convertSign(Tools::displayPrice(self::$order->total_wrapping, self::$currency, true, false)), 0, 0, 'R');
            $pdf->Ln(4);
        }		
		
		if (self::$order->total_shipping != '0.00' AND !self::$orderSlip)
		{
			$pdf->Cell($width, 0, self::l('Total shipping').' : ', 0, 0, 'R');
			$pdf->Cell(0, 0, self::convertSign(Tools::displayPrice(self::$order->total_shipping, self::$currency, true, false)), 0, 0, 'R');
			$pdf->Ln(4);
		}
		
		if (!self::$orderSlip)
		{
			$pdf->Cell($width, 0, self::l('Total with Tax').' : ', 0, 0, 'R');
			$pdf->Cell(0, 0, self::convertSign(Tools::displayPrice((self::$orderSlip ? ($totalProductsTi + self::$order->total_discounts + (self::$orderSlip ? 0 : self::$order->total_shipping)) : self::$order->total_paid), self::$currency, true, false)), 0, 0, 'R');
			$pdf->Ln(4);
		}
		
		if ($ecotax != '0.00' AND !self::$orderSlip)
		{
			$pdf->Cell($width, 0, self::l('Eco-participation').' : ', 0, 0, 'R');
			$pdf->Cell(0, 0, self::convertSign(Tools::displayPrice($ecotax, self::$currency, true, false)), 0, 0, 'R');
			$pdf->Ln(5);
		}

		$pdf->TaxTab();

		Hook::PDFInvoice($pdf, self::$order->id);

		if (!$multiple)
			return $pdf->Output(sprintf('%06d', self::$order->id).'.pdf', $mode);
	}

	/**
	* Product table with price, quantities...
	*/
	function ProdTab()
	{
		global $ecotax;

		$header = array(
			array(self::l('Description'), 'L'),
			array(self::l('Reference'), 'L'),
			array(self::l('U. price'), 'R'),
			array(self::l('Qty'), 'C'),
			array(self::l('Pre-Tax Total'), 'R'),
			array(self::l('Total'), 'R')
		);
		$w = array(90, 15, 25, 10, 25, 25);
		$this->SetFont(self::fontname(), 'B', 8);
		$this->SetFillColor(240, 240, 240);
		for($i = 0; $i < sizeof($header); $i++)
			$this->Cell($w[$i], 5, $header[$i][0], 'T', 0, $header[$i][1], 1);
		$this->Ln();
		$this->SetFont(self::fontname(), '', 7);

		if (isset(self::$order->products) AND sizeof(self::$order->products))
			$products = self::$order->products;
		else
			$products = self::$order->getProducts();
		$ecotax = 0;
		foreach($products AS $product)
		{
			
			$ecotax += $product['ecotax'] * intval($product['product_quantity']);
			$unit_without_tax = $product['product_price'];
			$total_without_tax = $product['total_price'];
			$total_with_tax = $product['total_wt'];

			$before = $this->GetY();
            $this->MultiCell($w[0], 5, Tools::iconv('utf-8', self::encoding(), $product['product_name']), 'B');
			$lineSize = $this->GetY() - $before;
			$this->SetXY($this->GetX() + $w[0], $this->GetY() - $lineSize);
			$this->Cell($w[1], $lineSize, $product['product_reference'], 'B');
			$this->Cell($w[2], $lineSize, self::convertSign(Tools::displayPrice($unit_without_tax, self::$currency, true, false)), 'B', 0, 'R');
			$this->Cell($w[3], $lineSize, $product['product_quantity'], 'B', 0, 'C');
			$this->Cell($w[4], $lineSize, self::convertSign(Tools::displayPrice($total_without_tax, self::$currency, true, false)), 'B', 0, 'R');
			$this->Cell($w[5], $lineSize, self::convertSign(Tools::displayPrice($total_with_tax, self::$currency, true, false)), 'B', 0, 'R');
			$this->Ln();
		}

		if (!sizeof(self::$order->getDiscounts()))
			$this->Cell(array_sum($w), 0, '');
	}

	/**
	* Discount table with value, quantities...
	*/
	function DiscTab()
	{
		$w = array(90, 25, 15, 10, 25, 25);
		$this->SetFont(self::fontname(), 'B', 7);
		$discounts = self::$order->getDiscounts();

		foreach($discounts AS $discount)
		{
			$this->Cell($w[0], 6, self::l('Discount:').' '.$discount['name'], 'B');
			$this->Cell($w[1], 6, '', 'B');
			$this->Cell($w[2], 6, '', 'B');
			$this->Cell($w[3], 6, '1', 'B', 0, 'C');
			$this->Cell($w[4], 6, '', 'B', 0, 'R');
			$this->Cell($w[5], 6, (!self::$orderSlip ? '-' : '').self::convertSign(Tools::displayPrice($discount['value'], self::$currency, true, false)), 'B', 0, 'R');
			$this->Ln();
		}

		if (sizeof($discounts))
			$this->Cell(array_sum($w), 0, '');
	}

	/**
	* Tax table
	*/
	function TaxTab()
	{
		if (self::$order->total_paid == '0.00' || self::$orderSlip)
			return ;
		
		// Setting products tax
		if (isset(self::$order->products) AND sizeof(self::$order->products))
			$products = self::$order->products;
		else
			$products = self::$order->getProducts();
		$total_with_tax = array();
		$total_without_tax = array();
		$taxes = array();
		foreach ($products AS $product)
		{
			if (!isset($total_with_tax[$product['tax_rate']]))
				$total_with_tax[$product['tax_rate']] = 0;
			if (!isset($total_without_tax[$product['tax_rate']]))
				$total_without_tax[$product['tax_rate']] = 0;
			if (!isset($taxes[$product['tax_rate']]))
				$taxes[$product['tax_rate']] = 0;

			$price_with_tax = number_format($product['product_price'] * (1 + ($product['tax_rate'] / 100)), 2, '.', '');
			$price_without_tax = $product['product_price'];
			$vat = ($price_with_tax - $price_without_tax) * $product['product_quantity'];

			$taxes[$product['tax_rate']] += $vat;
			$total_with_tax[$product['tax_rate']] += $price_with_tax * $product['product_quantity'];
			$total_without_tax[$product['tax_rate']] += $price_without_tax * $product['product_quantity'];
		}
		
		// Displaying header tax
		$header = array(self::l('Tax detail'), self::l('Tax %'), self::l('Pre-Tax Total'), self::l('Total Tax'), self::l('Total with Tax'));
		$w = array(60, 30, 40, 30, 30);
		$this->SetFont(self::fontname(), 'B', 8);
		for($i = 0; $i < sizeof($header); $i++)
			$this->Cell($w[$i], 5, $header[$i], 0, 0, 'R');

		$this->Ln();
		$this->SetFont(self::fontname(), '', 7);
		
		// Display product tax
		if (intval(Configuration::get('PS_TAX')) AND self::$order->total_paid != '0.00')
		{
			foreach ($taxes AS $tax_rate => $vat)
			{
				if ($tax_rate == '0.00' OR $total_with_tax[$tax_rate] == '0.00')
					continue ;
				$before = $this->GetY();
				$lineSize = $this->GetY() - $before;
				$this->SetXY($this->GetX(), $this->GetY() - $lineSize + 3);
				$this->Cell($w[0], $lineSize, self::l('Products'), 0, 0, 'R');
				$this->Cell($w[1], $lineSize, number_format($tax_rate, 2, ',', ' '), 0, 0, 'R');
				$this->Cell($w[2], $lineSize, self::convertSign(Tools::displayPrice($total_without_tax[$tax_rate], self::$currency, true, false)), 0, 0, 'R');
				$this->Cell($w[3], $lineSize, self::convertSign(Tools::displayPrice($vat, self::$currency, true, false)), 0, 0, 'R');
				$this->Cell($w[4], $lineSize, self::convertSign(Tools::displayPrice($total_with_tax[$tax_rate], self::$currency, true, false)), 0, 0, 'R');
				$this->Ln();
			}
		}

		// Display carrier tax
		$carrier = new Carrier(self::$order->id_carrier);
		$carrier_taxe = new Tax($carrier->id_tax);
		if ($carrier_taxe->rate && $carrier_taxe->rate != '0.00')
		{
			$total_shipping_wt = self::$order->total_shipping / (1 + ($carrier_taxe->rate / 100));
			$before = $this->GetY();
			$lineSize = $this->GetY() - $before;
			$this->SetXY($this->GetX(), $this->GetY() - $lineSize + 3);
			$this->Cell($w[0], $lineSize, self::l('Carrier'), 0, 0, 'R');
			$this->Cell($w[1], $lineSize, number_format($carrier_taxe->rate, 2, ',', ' '), 0, 0, 'R');
			$this->Cell($w[2], $lineSize, self::convertSign(Tools::displayPrice($total_shipping_wt, self::$currency, true, false)), 0, 0, 'R');
			$this->Cell($w[3], $lineSize, self::convertSign(Tools::displayPrice(self::$order->total_shipping - $total_shipping_wt, self::$currency, true, false)), 0, 0, 'R');
			$this->Cell($w[4], $lineSize, self::convertSign(Tools::displayPrice(self::$order->total_shipping, self::$currency, true, false)), 0, 0, 'R');
			$this->Ln();
		}
		
		// Display wrapping tax
		if (self::$order->total_wrapping && self::$order->total_wrapping != '0.00')
		{
			$total_wrapping_wt = self::$order->total_wrapping / (1 + ($tax_rate / 100));
	        $before = $this->GetY();
			$lineSize = $this->GetY() - $before;
			$this->SetXY($this->GetX(), $this->GetY() - $lineSize + 3);
			$this->Cell($w[0], $lineSize, self::l('Wrapping'), 0, 0, 'R');
			$this->Cell($w[1], $lineSize, number_format($tax_rate, 2, ',', ' '), 0, 0, 'R');
			$this->Cell($w[2], $lineSize, self::convertSign(Tools::displayPrice($total_wrapping_wt, self::$currency, true, false)), 0, 0, 'R');
			$this->Cell($w[3], $lineSize, self::convertSign(Tools::displayPrice(self::$order->total_wrapping - $total_wrapping_wt, self::$currency, true, false)), 0, 0, 'R');
			$this->Cell($w[4], $lineSize, self::convertSign(Tools::displayPrice(self::$order->total_wrapping, self::$currency, true, false)), 0, 0, 'R');
		}
	}

	static private function convertSign($s)
	{
		return str_replace('&yen;', chr(165), str_replace('&pound;', chr(163), str_replace('&euro;', chr(128), $s)));
	}

	static protected function l($string)
	{
		global $cookie;
		if (@!include(_PS_TRANSLATIONS_DIR_.Language::getIsoById($cookie->id_lang).'/pdf.php'))
			die('Cannot include PDF translation language file : '._PS_TRANSLATIONS_DIR_.Language::getIsoById($cookie->id_lang).'/pdf.php');

		if (!is_array($_LANGPDF))
			return str_replace('"', '&quot;', $string);
		$key = md5(str_replace('\'', '\\\'', $string));
		$str = (key_exists('PDF_invoice'.$key, $_LANGPDF) ? html_entity_decode($_LANGPDF['PDF_invoice'.$key], ENT_COMPAT, 'UTF-8') : $string);

		return (Tools::iconv('utf-8', self::encoding(), $str));
	}

    static private function encoding()
    {
		return (isset(self::$_pdfparams[self::$_iso]) AND is_array(self::$_pdfparams[self::$_iso]) AND self::$_pdfparams[self::$_iso]['encoding']) ? self::$_pdfparams[self::$_iso]['encoding'] : 'iso-8859-1';
	}

    static private function embedfont()
    {
		return (isset(self::$_pdfparams[self::$_iso]) AND is_array(self::$_pdfparams[self::$_iso]) AND self::$_pdfparams[self::$_iso]['font']) ? self::$_pdfparams[self::$_iso]['font'] : false;
	}

    static private function fontname()
    {
		$font = self::embedfont();
		return $font ? $font : 'Arial';
 	}
	
}

?>
