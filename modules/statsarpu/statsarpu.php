<?php

/**
  * Statistics
  * @category stats
  *
  * @author Gluck
  * @version 1.0
  * вычисляет средний чек
  */
  
class statsarpu extends Module
{
    function __construct()
    {
        $this->name = 'statsarpu';
        $this->tab = 'Stats';
        $this->version = 1.0;
		
        parent::__construct();
		
        $this->displayName = $this->l('Средний чек');
        $this->description = $this->l('Показывает средний чек покупателя');
    }
	
	public function install()
	{
		return (parent::install() AND $this->registerHook('AdminStatsModules'));
	}
	
	/* колво заказов по году */
	private function getOrders()
	{
		return Db::getInstance()->ExecuteS('
		SELECT year(o.`date_add`) as year, COUNT(o.`date_add`) as orders
		FROM `presta_orders` o
		where `valid` = 1
		group by year(o.`date_add`)
		');
	}

	/* ARPU по годам */
	private function getArpuYear()
	{
		return Db::getInstance()->ExecuteS('
SELECT year(`date_add`) as year, count(`id_customer`) as orders, sum(`total_paid_real`) / count(`id_customer`) as arpu
FROM `presta_orders`
where `valid` = 1
group by year
		');
	}
	
	/* колво клиентов, совершивших покупки */
	private function getCustomers($y)
	{
		return Db::getInstance()->ExecuteS('
SELECT distinct (`id_customer`) as users, year(`date_add`) as years
FROM `presta_orders`
where `valid` = 1
and year(`date_add`) = '.$y.'
		');
	}
	


	/* ARPU по месяцам */
	private function getArpuMonth()
	{
		return Db::getInstance()->ExecuteS('
SELECT sum(`total_paid_real`) / count(`id_customer`) as arpu, month(`date_add`) as month, DATE_FORMAT(`date_add`, "%b") as m
FROM `presta_orders`
where `valid` = 1
group by month
		');
	}
	

	public function hookAdminStatsModules($params)
	{
		global $cookie;
		
//		$total = $this->getOrders();
		$arpuyear = $this->getArpuYear();
		
		echo '<script type="text/javascript" language="javascript">openCloseLayer(\'calendar\');</script>
		<fieldset class="width3" style="height:900px"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->l('Средний чек по годам').'</legend>';
//			echo $this->l('Всего активных клиентов').': '.count($arpuyear).'<br><br>';
			echo '<div style="position: relative; bottom: -400px;">';
			$i=0;
			foreach ($arpuyear as $years)
				{
				$res = $years + array(orders=>$total[$i]["orders"]);

//				echo '<div style="bottom:0; background:#5f9be3; color:#fff; float:left;	margin-right:10px; 	padding: 5px;  text-align: center; height:'.round($years["arpu"] / 30).'px"><p style="font-size: 20pt;"><b>'.$res["year"].'</b></p><p>зак: '.$res["orders"].' </p><p>кли: '.sizeof($this->getCustomers($y=$years[year])).' </p><p>('.round($res["arpu"]).' р.)</p></div>';

				echo '
				<div style="bottom:-470px;position: absolute; left:'.round(($res["year"] - 2010)).'00px; background:#5f9be3; color:#fff;  padding: 15px; text-align: center; width: 65px; height:'.round($years["arpu"] / 28).'px; "><b>'.round($res["arpu"]).' р.</b></div>
			
				<div style="font-size: 10pt; bottom:-470px;position: absolute; left:'.round(($res["year"] - 2010)).'00px; background:#F70; color:#fff;padding: 15px; text-align: center; width: 59px; height:'.(round($res["orders"] + 20) + 7).'px; min-height: 63px;">'.$res["orders"].'<br>заказов</div>
				
				<div style="font-size: 10pt; bottom:-470px;position: absolute; left:'.round(($res["year"] - 2010)).'00px; background:#6B4; color:#fff;padding: 11px; text-align: center; width: 60px; height:'.sizeof($this->getCustomers($y=$years[year])).'px; min-height: 23px;">'.sizeof($this->getCustomers($y=$years[year])).'<br>клиентов</div>

				<div style="font-size: 16pt; bottom:-470px;position: absolute; width: 65px; left:'.round(($res["year"] - 2010)).'00px;color:#fff; padding: 11px; text-align: center"><b>'.$res["year"].'</b></div>
				
				';
			
				$i=$i+1;	
				}
			echo '</div>';


		echo '</fieldset><pre>';
		

//print_r($years);

	
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
				
	
/*		$whenm = $this->getPurchaseMonth();
		$totalMonth = Db::getInstance()->NumRows();
		$whenm_curr = $this->getPurchaseMonthCurr();
		$irow = -1;
		
						
		foreach ($whenm as $month)
				{
				$background = "5f9be3";
				if ($whenm[$irow]["month"] == date(m)-1) $background = ('7fbdf5');				
				echo ($irow++ % 2 ? '' : '').'<div style="background:#'.$background.'; color:#fff;	float:left;	margin-right:10px; 	padding:0 0 0 2px;  min-width: 55px; width:'.round($whenm[$irow]["orders"]/$n[$whenm[$irow]["month"]]).'0px"><span>'.$whenm[$irow]["m"].' ('.round($whenm[$irow]["orders"] / $n[$whenm[$irow]["month"]]).')</span>';

echo '<span style="position: relative; left:'.$whenm_curr[$irow]["orders_curr"].'0px; background:#FB0;margin-left: -80px;">'.$whenm_curr[$irow]["orders_curr"].'</span>';

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

*/



	

/*
$sales = $this->getZarplataCurr();
$new = array();

foreach ($sales as $key=>$value) {
 $new[$value['month']][] = $sales[$key];
}		

foreach ($new as $products)
{
	foreach ($products as $product)
	{
		 	$a = $product[price] - $product[wholesale_price] + $product[shipping];
		 	echo($a);
	}
}
*/		
		
	


/*
	for ($irow = 1; $irow < 13; $irow++)	
		{
			$zp = $this->getZarplata($irow);
//			print_r($zp);
			$background = "5f9be3";
			if ($zp["month"] == date(m)) $background = ('7fbdf5');				
			echo '<div style="background:#'.$background.'; color:#fff;	float:left;	padding:0 0 0 2px;  width:'.round($zp["zp"]/100 /$n[$irow]).'px"><span>'.$zp["m"].' ('.round($zp["zp"]/1000 /$n[$irow]).')</span>';

			
				// за этот год		
				$zp_curr = $this->getZarplataCurr($irow);
							
				if ($zp_curr["zp_curr"] > 0) 
					{
					echo '<span style="color:#fff; position: relative; left:'.round($zp_curr["zp_curr"]/100).'px; background:#FB0; margin-left:-80px">'.round($zp_curr["zp_curr"]/1000,1).'</span></div>';
					}
				else 
					{
					echo '<span></span></div>';
					}
			echo '<div style="margin:4px 0;width:0px;">&nbsp;</div>';			
		}
	echo '</pre></fieldset>';	

*/	

			
	}
}
?>
