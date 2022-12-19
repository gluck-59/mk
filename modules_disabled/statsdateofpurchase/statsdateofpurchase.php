<?php
// крашится в 7.3

//установка локали
mysql_query("SET lc_time_names = 'ru_RU'");



/**
  * Statistics
  * @category stats
  *
  * @author Gluck
  * @version 1.0
  * установите ваш статус заказа "оплачено" в строку WHERE `id_order_state` = сюда
  * можно установить статус "отправлено", тогда будет показывать date of shipping :)
  */
  
class statsdateofpurchase extends Module
{
    function __construct()
    {
        $this->name = 'statsdateofpurchase';
        $this->tab = 'Stats';
        $this->version = 1.0;
		
        parent::__construct();
		
        $this->displayName = $this->l('Date of Purchase');
        $this->description = $this->l('Groups purchases by date');
    }
	
	public function install()
	{
		return (parent::install() AND $this->registerHook('AdminStatsModules'));
	}
	
	/* платежи по датам - проверено */
	private function getPurchaseDate()
	{
		return Db::getInstance()->ExecuteS('
		SELECT COUNT(oh.`date_add`) as orders, day(oh.`date_add`) AS days
		FROM `presta_order_history` oh 
		join `presta_orders` o on (oh.`id_order` = o.`id_order` and `valid` = 1)
		where oh.`id_order_state` = 2
		GROUP BY days
		');
	}

	/* платежи по дням недели */
	private function getPurchaseDay()
	{
		return Db::getInstance()->ExecuteS('
		SELECT count(o.`date_add`) as orders, WEEKDAY(o.`date_add`) AS days, DATE_FORMAT(o.`date_add`, "%W") as d
		FROM `presta_orders` o
		group by days
		');
	}


	/* платежи по датам этого года этого месяца (не используется) - проверено */
	private function getPurchaseDateCurr()
	{
		return Db::getInstance()->ExecuteS('
		SELECT COUNT(oh.`date_add`) as orders, day(oh.`date_add`) AS days
		FROM `presta_order_history` oh 
		join `presta_orders` o on (oh.`id_order` = o.`id_order` and `valid` = 1)
		where oh.`id_order_state` = 2
		and year(oh.`date_add`) = year(now())
		and month(oh.`date_add`) = month(now())
		GROUP BY days
		');
	}


	/* платежи по месяцам - проверено */
	private function getPurchaseMonth()
	{
	return Db::getInstance()->ExecuteS('
	SELECT DISTINCT COUNT(oh.`date_add`) as orders, MONTH(oh.`date_add`) AS month, DATE_FORMAT(oh.`date_add`, "%b") as m
	FROM `presta_order_history` oh 
	join `presta_orders` o on (oh.`id_order` = o.`id_order` and `valid` = 1)
	where oh.`id_order_state` = 2
	GROUP BY month
	');
	}

	/* платежи по месяцам этого года - проверено */	
	private function getPurchaseMonthCurr()
	{
	return Db::getInstance()->ExecuteS('
	SELECT DISTINCT COUNT(oh.`date_add`) as orders_curr, MONTH(oh.`date_add`) AS month_curr, DATE_FORMAT(oh.`date_add`, "%b") as m_curr
	FROM `presta_order_history` oh 
	join `presta_orders` o on (oh.`id_order` = o.`id_order` and `valid` = 1)
	where oh.`id_order_state` = 2
	and YEAR(oh.`date_add`) = DATE_FORMAT(CURDATE(), "%Y")
	GROUP BY month_curr
	');
	}
	
	
	/* платежи по годам - проверено */
	private function getPurchaseYears()
	{
		return Db::getInstance()->ExecuteS('
		SELECT  COUNT(oh.`date_add`) as orders, 
		YEAR(oh.`date_add`) AS years
		FROM `presta_order_history` oh
		join `presta_orders` o on (oh.`id_order` = o.`id_order` and `valid` = 1)
		WHERE `id_order_state` = 2
		GROUP BY years
		');
	}
	
	/* всего заказов (не сходится на 1) */
	private function getPurchaseDateOrders()
	{
		return Db::getInstance()->ExecuteS('
		SELECT (oh.`id_order`)
		FROM `presta_order_history` oh
		join `presta_orders` o on (oh.`id_order` = o.`id_order` and `valid` = 1)
		WHERE `id_order_state` = 2
		');
	}
	
	
	private function getZarplata1($month)
	{
		$z1 = Db::getInstance()->ExecuteS('
		SELECT DATE_FORMAT(oh.`date_add`, "%b") as m, (od.`product_price` - p.`wholesale_price`)*od.`product_quantity` as z
		FROM `presta_orders` o
		join `presta_order_detail` od ON o.`id_order` = od.`id_order`
		join `presta_product` p on od.`product_id` = p.`id_product`
		join `presta_order_history` oh ON (o.`id_order` = oh.`id_order` and oh.`id_order_state` = 2)
		where o.`valid` = 1
		and month(oh.`date_add`) = '.intval($month).'
		');

		$z2 = Db::getInstance()->ExecuteS('
		SELECT (o.`total_shipping` - o.`total_discounts`) as z 
		FROM `presta_orders` o
		join `presta_order_detail` od ON o.`id_order` = od.`id_order`
		join `presta_product` p on od.`product_id` = p.`id_product`
		join `presta_order_history` oh ON (o.`id_order` = oh.`id_order` and oh.`id_order_state` = 2 and month(oh.`date_add`) =  '.intval($month).')
		where o.`valid` = 1
		group by o.`id_order`
		');

		for ($i=0; $i <= sizeof($z1); $i++) {
			$z3 += $z1[$i][z];
			}

		for ($i=0; $i <= sizeof($z2); $i++) {
			$z4 += $z2[$i][z];
			}
		$z = $z3 + $z4;	
		return array(month=>$month, m=>$z1[0][m], zp=>$z);
	}


/* этот вариант считает вроде правильно, но если товар был удален из магазина (presta_products), заказ с этим товаром не загрузится и посчитается вообще
 	нам нужно джойнить presta_products чтобы узнать с/с товара wholesale_price
 	чтобы обойти эту проблему, нужно чтобы wholesale_price дублировалось из presta_products в presta_order_detail
*/
	
	private function getZarplataCurr1($month)
	{
		$z1 = Db::getInstance()->ExecuteS('
		SELECT DATE_FORMAT(oh.`date_add`, "%b") as m, (od.`product_price` - p.`wholesale_price`)*od.`product_quantity` as z
		FROM `presta_orders` o
		join `presta_order_detail` od ON o.`id_order` = od.`id_order`
		join `presta_product` p on od.`product_id` = p.`id_product`
		join `presta_order_history` oh ON (o.`id_order` = oh.`id_order` and oh.`id_order_state` = 2)
		where o.`valid` = 1
		and year(oh.`date_add`) = year(NOW())
		and month(oh.`date_add`) = '.intval($month).'
		');

		$z2 = Db::getInstance()->ExecuteS('
		SELECT (o.`total_shipping` - o.`total_discounts`) as z 
		FROM `presta_orders` o
		join `presta_order_detail` od ON o.`id_order` = od.`id_order`
		join `presta_product` p on od.`product_id` = p.`id_product`
		join `presta_order_history` oh ON (o.`id_order` = oh.`id_order` and oh.`id_order_state` = 2 and month(oh.`date_add`) =  '.intval($month).')
		where o.`valid` = 1
		and year(oh.`date_add`) = year(NOW())
		group by o.`id_order`
		');

		for ($i=0; $i <= sizeof($z1); $i++) {
			$z3 += $z1[$i][z];
			}

		for ($i=0; $i <= sizeof($z2); $i++) {
			$z4 += $z2[$i][z];
			}
		$z = $z3 + $z4;	
		return array(month=>$month, m=>$z1[0][m], zp_curr=>$z);
		 
	}	


	private function getZarplata()
	{
		return Db::getInstance()->ExecuteS('
SELECT DISTINCT  sum(o.`total_paid_real`), sum(o.`my_wholesale_price`),  sum(o.`total_discounts`), month(o.`date_add`) as month, DATE_FORMAT(o.`date_add`, "%b") as m, 

(sum(o.`total_paid_real`) - sum(o.`my_wholesale_price`) - sum(o.`total_discounts`) - sum(o.`total_shipping`)) as zp

FROM `presta_orders` o
		join `presta_order_detail` od ON o.`id_order` = od.`id_order`
		join `presta_product` p on od.`product_id` = p.`id_product`
		join `presta_order_history` oh ON (o.`id_order` = oh.`id_order` and oh.`id_order_state` = 2)
		where `valid` = 1
		group by month(o.`date_add`)
		');
	}

	private function getZarplataCurr()
	{
		return Db::getInstance()->ExecuteS('
SELECT  DISTINCT month(o.`date_add`) as month, DATE_FORMAT(o.`date_add`, "%b") as m, 

(sum(o.`total_paid_real`) - sum(o.`my_wholesale_price`) - sum(o.`total_discounts`) - sum(o.`total_shipping`)) as zp_curr

FROM `presta_orders` o
		join `presta_order_detail` od ON o.`id_order` = od.`id_order`
		join `presta_product` p on od.`product_id` = p.`id_product`
		join `presta_order_history` oh ON (o.`id_order` = oh.`id_order` and oh.`id_order_state` = 2)
		where `valid` = 1
		and year(o.`date_add`) = year(NOW())
		group by month(o.`date_add`)
		');
	}





	public function hookAdminStatsModules($params)
	{
		global $cookie;
		
		$when = $this->getPurchaseDate();
		$when_curr = $this->getPurchaseDateCurr();
		$totalDates = Db::getInstance()->NumRows();
		$totalOrders = $this->getPurchaseDateOrders();
		$irow = -1;
		
		echo '<script type="text/javascript" language="javascript">openCloseLayer(\'calendar\');</script>
		<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Purchase dates').'</legend>';
//		if ($totalDates)
//		{
			echo $this->l('Total orders:').' '.count($totalOrders).'<br>
				'.$this->l('Date').' ('.$this->l('Purchases').')<br><br><pre>';
				foreach ($when as $days)
				{
				$background = "5f9be3";
				if ($when[$irow]["days"] == date(d)-1) $background = ('7fbdf5');				
				echo ($irow++ % 2 ? '' : '').'<div style="margin-bottom: 0px;"><span style="background:#'.$background.'; color:#fff;	float:left;	margin-right:10px; 	padding:0 0 0 2px;  width:'.$when[$irow]["orders"].'0px">'.$when[$irow]["days"].' ('.$when[$irow]["orders"].')</span>';

				//за этот месяц этого года
//				echo '<span style="color:#fff; position: relative; left:'.round($when_curr[$irow]["orders_curr"]).'0px; background:#FB0; margin-left:-80px">'.round($when_curr[$irow]["orders_curr"],1).'</span></div>';
				
					echo '<div style="	padding:2px 0;">&nbsp;</div>';
				}
//		}
//		else
//			echo $this->l('Error with DB:order_history');
		echo '</fieldset></pre><br><br>';
		
		
				
		$whend = $this->getPurchaseDay();
		$irow = -1;

		echo '<script type="text/javascript" language="javascript">openCloseLayer(\'calendar\');</script>
		<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Заказы (не платежи!) по дням недели').'</legend>';
				foreach ($whend as $days)
				{
				$background = "5f9be3";
				if ($whend[$irow]["days"] == date(w)-1) $background = ('7fbdf5');				
				echo ($irow++ % 2 ? '' : '').'<div style="margin-bottom: 0px;"><span style="background:#'.$background.'; color:#fff;	float:left;	margin-right:10px; 	padding:0 0 0 2px;  width:'.round($whend[$irow]["orders"] * 2,0).'px">'.$whend[$irow]["d"].' ('.$whend[$irow]["orders"].')</span>';
				echo '<div style="	padding:2px 0;">&nbsp;</div>';
				}
		echo '</fieldset></pre><pre>';

	
		
		echo '<br><br><fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Month of Purchase').'</legend><pre>';
					
//определяем сколько каких месяцев было с даты начала работы
$begin = new DateTime( '2010-03-01' );
$end = new DateTime();

$interval = new DateInterval('P1M');
$daterange = new DatePeriod($begin, $interval ,$end);

$m = array();
foreach($daterange as $date)
	{
    array_push($m, $date->format("n"));
	}
$n = array_count_values($m);				
				
	
		$whenm = $this->getPurchaseMonth();
		$totalMonth = Db::getInstance()->NumRows();
		$whenm_curr = $this->getPurchaseMonthCurr();
		$irow = -1;
		
						
		foreach ($whenm as $month)
				{
				$background = "5f9be3";
				if ($whenm[$irow]["month"] == date(m)-1) $background = ('7fbdf5');				
				echo ($irow++ % 2 ? '' : '').'<div style="background:#'.$background.'; color:#fff;	float:left;	margin-right:10px; 	padding:0 0 0 2px;  min-width: 55px; width:'.round($whenm[$irow]["orders"]/$n[$whenm[$irow]["month"]]*15).'px"><span>'.$whenm[$irow]["m"].' ('.round($whenm[$irow]["orders"] / $n[$whenm[$irow]["month"]]).')</span>';

echo '<span style="position: relative; left:'.round($whenm_curr[$irow]["orders_curr"]*15).'px; background:#FB0;margin-left: -80px;">'.$whenm_curr[$irow]["orders_curr"].'</span>';

				echo '</div><div style="padding:2px 0;width:0px;">&nbsp;</div>';
				}				
				
				
	echo '</fieldset></pre>';
	echo '<br><br><fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Purchase Years').'</legend><pre>';

		$wheny = $this->getPurchaseYears();
		$totalMonth = Db::getInstance()->NumRows();
		$irow = -1;
				
		foreach ($wheny as $years)
				{
				echo ($irow++ % 2 ? '' : '').'<div style="background:#5f9be3; color:#fff;	float:left;	margin-right:10px; 	padding:0 0 0 2px;  max-width: 101%; width:'.($wheny[$irow]["orders"]*2).'px">'.$wheny[$irow]["years"].' ('.$wheny[$irow]["orders"].')</div><div style="padding:2px 0;width:0px;">&nbsp;</div>';
				}
	
	echo '</fieldset></pre>';	
	
echo '<br><br><fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Средняя зарплата по месяцам').'</legend><pre>';
// перывый fieldset

	
	for ($irow = 1; $irow < 13; $irow++)	
		{
			$zp1 = $this->getZarplata1($irow);
//			print_r($zp);
			$background = "5f9be3";
			if ($zp1["month"] == date(m)) $background = ('7fbdf5');				
			echo '<div style="background:#'.$background.'; color:#fff;	float:left;	padding:0 0 0 2px;  width:'.round($zp1["zp"]/100 /$n[$irow]).'px"><span>'.$zp1["m"].' ('.round($zp1["zp"]/1000 /$n[$irow]).')</span>';

			
				// за этот год		
				$zp_curr1 = $this->getZarplataCurr1($irow);
							
				if ($zp_curr1["zp_curr"] != 0) 
					{
					echo '<span style="color:#fff; position: relative; left:'.round($zp_curr1["zp_curr"]/100).'px; background:#FB0; margin-left:-80px">'.round($zp_curr1["zp_curr"]/1000,1).'</span></div>';
					}
				else 
					{
					echo '<span></span></div>';
					}
			echo '<div style="margin:4px 0;width:0px;">&nbsp;</div>';			
		}
	echo '</pre></fieldset>';	






echo '<br><br><fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Средняя зарплата по месяцам - эксп').'</legend><pre>';
// второй fieldset

	$zp = $this->getZarplata();	
	$zp_curr = $this->getZarplataCurr();
	$irow = -1;
	foreach ($zp as $zpm)
	{
			$background = "5f9be3";
			if ($zp[$irow]["month"] == date(m)-1) $background = ('7fbdf5');				
			echo ($irow++ % 2 ? '' : '').'<div style="background:#'.$background.'; color:#fff;	float:left;	padding:0 0 0 2px;  width:'.round($zp[$irow]["zp"]/$n[$whenm[$irow]["month"]] / 150).'px"><span>'.$zp[$irow]["m"].' ('.round($zp[$irow]["zp"]/$n[$whenm[$irow]["month"]]/1000).') '.$zp[$irow]["zp"].'/'.$n[$whenm[$irow]["month"]].'</span>';

			
				// за этот год		
										
				if ($zp_curr[$irow]["zp_curr"] != 0) 
					{
					echo '<span style="color:#fff; position: relative; left:'.round($zp_curr[$irow]["zp_curr"]/150).'px; background:#FB0; margin-left:-80px">'.round($zp_curr[$irow]["zp_curr"]/1000,1).'</span></div>';
//					print_r($zp_curr[$irow]);
				
					}
				else 
					{
					echo '<span></span></div>';
					}
			echo '<div style="margin:4px 0;width:0px;">&nbsp;</div>';	
		
		
		}

	echo '</pre></fieldset>';	

echo '<pre>';
print_r($zp_curr);



		
	}
}
?>
