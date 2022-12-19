<?php

/**
  * Statistics
  * @category stats
  *
  * @author gluck
  * @version 1.0
  */
  
class StatsBestPayWay extends ModuleGrid
{
	private $_html = null;
	private $_query =  null;
	private $_columns = null;
	private $_defaultSortColumn = null;
	private $_emptyMessage = null;
	private $_pagingMessage = null;
	
	function __construct()
	{
		$this->name = 'statsbestpayway';
		$this->tab = 'Stats';
		$this->version = 1.0;
		
		$this->_defaultSortColumn = 'paid';
		$this->_emptyMessage = $this->l('Empty recordset returned');
		$this->_pagingMessage = $this->l('Displaying').' {0} - {1} '.$this->l('of').' {2}';
		
		$this->_columns = array(
			array(
				'id' => 'payment',
				'header' => $this->l('payment'),
				'dataIndex' => 'payment',
				'width' => 160
			),
			array(
				'id' => 'count',
				'header' => $this->l('count'),
				'dataIndex' => 'count',
				'width' => 30
			),
			
			array(
				'id' => 'paid',
				'header' => $this->l('paid'),
				'dataIndex' => 'paid',
				'width' => 70,
				'align' => 'right'				
			),
			
		);
		
		parent::__construct();
		
		$this->displayName = $this->l('Best Pay Way');
		$this->description = $this->l('A list of the best ways of payments');
	}
	
	public function install()
	{
		return (parent::install() AND $this->registerHook('AdminStatsModules'));
	}
	
	public function hookAdminStatsModules($params)
	{
		$engineParams = array(
			'id' => 'id_customer',
			'title' => $this->displayName,
			'columns' => $this->_columns,
			'defaultSortColumn' => $this->_defaultSortColumn,
			'emptyMessage' => $this->_emptyMessage,
			'pagingMessage' => $this->_pagingMessage
		);
	
		$this->_html = '
		<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->displayName.'</legend>
			'.ModuleGrid::engine($engineParams).'
		</fieldset><br />
<!--		<fieldset class="width3"><legend><img src="../img/admin/comment.gif" /> '.$this->l('Guide').'</legend>
			<h2 >'.$this->l('Develop clients\' loyalty').'</h2>
			<p class="space">
				'.$this->l('Keeping a client is more profitable than capturing a new one. Thus, it is necessary to develop its loyalty, in other words to make him come back in your webshop.').' <br />
				'.$this->l('Word of mouth is also a means to get new satisfied clients; a dissatisfied one won\'t attract new clients.').'<br />
				'.$this->l('In order to achieve this goal you can organize: ').'
				<ul>
					<li>'.$this->l('Punctual operations: commercial rewards (personalized special offers, product or service offered), non commercial rewards (priority handling of an order or a product), pecuniary rewards (bonds, discount coupons, payback...).').'</li>
					<li>'.$this->l('Sustainable operations: loyalty or points cards, which not only justify communication between merchant and client, but also offer advantages to clients (private offers, discounts).').'</li>
				</ul>
				'.$this->l('These operations encourage clients to buy and also to come back in your webshop regularly.').' <br />
			</p><br />
		</fieldset>
-->'; 
		return $this->_html;
	}
	
	public function getTotalCount()
	{
		$result = Db::getInstance()->getRow('
SELECT COUNT(DISTINCT `payment`) totalCount 
FROM `presta_orders`
		');
		return $result['totalCount'];
	}

	
	public function setOption($option)
	{
	}
	
	public function getData()
	{
		$this->_totalCount = $this->getTotalCount();		

		$this->_query = '
SELECT `payment`, count(`payment`) as count, SUM(`total_paid_real`) as paid, sum(`total_products`) as products, SUM(`total_paid_real`) - sum(`total_products`) + sum(`total_shipping`) as zarplata
FROM `presta_orders`
where `valid` = 1
and `date_add` BETWEEN '.$this->getDate().'
group by `payment`		
		';
		if (Validate::IsName($this->_sort))
		{
			if ($this->_sort == 'zarplata')
				$this->_sort = 'paid';
			$this->_query .= ' ORDER BY `'.$this->_sort.'`';
			if (isset($this->_direction) AND Validate::IsSortDirection($this->_direction))
				$this->_query .= ' '.$this->_direction;
		}
		if (($this->_start === 0 OR Validate::IsUnsignedInt($this->_start)) AND Validate::IsUnsignedInt($this->_limit))
			$this->_query .= ' LIMIT '.$this->_start.', '.($this->_limit);
		$this->_values = Db::getInstance()->ExecuteS($this->_query);
		}
	
}
