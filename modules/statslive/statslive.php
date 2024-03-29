<?php

/**
  * Statistics
  * @category stats
  *
  * @author Damien Metzger / Epitech
  * @copyright Epitech / PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  */
  
class StatsLive extends Module
{
    function __construct()
    {
        $this->name = 'statslive';
        $this->tab = 'Stats';
        $this->version = 1.0;
		
        parent::__construct();
		
        $this->displayName = $this->l('Visitors online');
        $this->description = $this->l('Display the list of customers and visitors currently online');
    }
	
	public function install()
	{
		return (parent::install() AND $this->registerHook('AdminStatsModules'));
	}
	
	private function getCustomersOnline()
	{
		return Db::getInstance()->ExecuteS('
		SELECT u.id_customer, u.firstname, u.lastname, pt.name as page
		FROM `'._DB_PREFIX_.'connections` c
		LEFT JOIN `'._DB_PREFIX_.'connections_page` cp ON c.id_connections = cp.id_connections
		LEFT JOIN `'._DB_PREFIX_.'page` p ON p.id_page = cp.id_page
		LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON p.id_page_type = pt.id_page_type
		INNER JOIN `'._DB_PREFIX_.'guest` g ON c.id_guest = g.id_guest
		INNER JOIN `'._DB_PREFIX_.'customer` u ON u.id_customer = g.id_customer
		WHERE cp.`time_end` IS NULL
		AND TIME_TO_SEC(TIMEDIFF(NOW(), cp.`time_start`)) < 900
		GROUP BY c.id_connections
		ORDER BY u.firstname, u.lastname');
	}
	
	private function getVisitorsOnline()
	{
		return Db::getInstance()->ExecuteS('
		SELECT c.id_guest, c.ip_address, c.date_add, c.http_referer, pt.name as page, cs.keywords as keywords
		FROM `'._DB_PREFIX_.'connections` c
		LEFT JOIN `'._DB_PREFIX_.'connections_page` cp ON c.id_connections = cp.id_connections
		LEFT JOIN `'._DB_PREFIX_.'page` p ON p.id_page = cp.id_page
		LEFT JOIN `'._DB_PREFIX_.'page_type` pt ON p.id_page_type = pt.id_page_type
		LEFT JOIN `'._DB_PREFIX_.'connections_source` cs ON c.id_connections = cs.id_connections
		INNER JOIN `'._DB_PREFIX_.'guest` g ON c.id_guest = g.id_guest
		WHERE (g.id_customer IS NULL OR g.id_customer = 0)
		AND cp.`time_end` IS NULL
		AND TIME_TO_SEC(TIMEDIFF(NOW(), cp.`time_start`)) < 900
		GROUP BY c.id_connections
		ORDER BY c.http_referer DESC');
	}
	
	
	function WhoisQuery($server="whois.ripe.net", $query) {
$data="";
$query = "212.33.224.131";

/* Создаем сокет с 43-м портом указанного пользователем узла */
$sp=fsockopen($server, 43);
if(!$sp) {
echo "Не удалось подключиться к сервису Whois!:(<br>\n"; return 0;
}

/* Отправляем запрос */
fputs($sp, $query."\r\n");
while(!feof($sp)) {

/* Читаем в переменную $data данные цугами по 1 Кб */
$data .= fread($sp, 1024); }
fclose($sp);
return $data;

}
	
	public function hookAdminStatsModules($params)
	{
		global $cookie;
		
		$customers = $this->getCustomersOnline();
		$totalCustomers = Db::getInstance()->NumRows();
		$visitors = $this->getVisitorsOnline();
		$totalVisitors = Db::getInstance()->NumRows();
		$whois = $this->WhoisQuery($server="whois.ripe.net", $query);
		$irow = 0;
		
		echo '<script type="text/javascript" language="javascript">openCloseLayer(\'calendar\');</script>
		<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Customers online').'</legend>';
		if ($totalCustomers)
		{
			echo $this->l('Total:').' '.intval($totalCustomers).'
			<table cellpadding="0" cellspacing="0" class="table space">
				<tr><th>'.$this->l('ID').'</th><th>'.$this->l('Name').'</th><th>'.$this->l('Current Page').'</th><th>'.$this->l('View').'</th></tr>';
			foreach ($customers as $customer)
				echo '
				<tr'.($irow++ % 2 ? ' class="alt_row"' : '').'>
					<td>'.$customer['id_customer'].'</td>
					<td style="width: 200px;">'.$customer['firstname'].' '.$customer['lastname'].'</td>
					<td style="width: 200px;">'.substr($customer['page'], 0, 25).'</td>
					<td style="text-align: right; width: 25px;">
						<a href="index.php?tab=AdminCustomers&id_customer='.$customer['id_customer'].'&viewcustomer&token='.Tools::getAdminToken('AdminCustomers'.intval(Tab::getIdFromClassName('AdminCustomers')).intval($cookie->id_employee)).'" target="_blank">
							<img src="../modules/'.$this->name.'/logo.gif" />
						</a>
					</td>
				</tr>';
			echo '</table>';
		}
		else
			echo $this->l('There is no customer online now.');
		echo '</fieldset>
		<fieldset class="width3 space"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Visitors online').'</legend>';
		if ($totalVisitors)
		{
			echo $this->l('Total:').' '.intval($totalVisitors).'
			<div style="height: 100%;">
			<table cellpadding="0" cellspacing="0" class="table space">
				<tr><th>'.$this->l('IP').'</th><th>'.$this->l('Current page').'</th><th>'.$this->l('Referrer').'</th><th>'.$this->l('Keywords').'</th></tr>';
			foreach ($visitors as $visitor)
				echo '
					<tr'.($irow++ % 2 ? ' class="alt_row"' : '').'>
						<td style="width: 80px;">'.long2ip($visitor['ip_address']).'</td> 
																			
						
						<td style="width: 200px;">'.mb_substr($visitor['page'], 0, 15).'</td>
						<td style="width: 200px;"><a target="_blank" href="'.$visitor['http_referer'].'">'.urldecode(mb_substr($visitor['http_referer'], 7, 20)).'</a></td>
						<td style="width: 100px;">'.$visitor['keywords'].'</td>
					</tr>';
			echo '</table>
			</div>';
		}
		else
			echo $this->l('There is no visitor online now.');
		echo '</fieldset>';
		echo '<pre>';
//		echo $whois($query = long2ip($visitor['ip_address'])).'</pre>';
				
	}

}

?>
