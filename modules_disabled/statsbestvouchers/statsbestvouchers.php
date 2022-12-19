<?php
//include(dirname(__FILE__).'/cron.php');


/**
  * Statistics
  * @category stats
  *
  * @author Damien Metzger / Epitech
  * @copyright Epitech / PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  */
  
class StatsBestVouchers extends ModuleGrid
{
	private $_html = null;
	private $_query =  null;
	private $_columns = null;
	private $_defaultSortColumn = null;
	private $_emptyMessage = null;
	private $_pagingMessage = null;
	
	function __construct()
	{
		$this->name = 'statsbestvouchers';
		$this->tab = 'Stats';
		$this->version = 1.0;
		
		$this->_defaultSortColumn = 'value';
		$this->_emptyMessage = $this->l('Empty recordset returned');
		$this->_pagingMessage = $this->l('Displaying').' {0} - {1} '.$this->l('of').' {2}';
		
		$this->_columns = array(
			array(
				'id' => 'firstname',
				'header' => $this->l('Имя'),
				'dataIndex' => 'firstname',
				'align' => 'left',
				'width' => 60
			),
			array(
				'id' => 'lastname',
				'header' => $this->l('Фамилия'),
				'dataIndex' => 'lastname',
				'width' => 60,
				'align' => 'left'
			),
			array(
				'id' => 'email',
				'header' => $this->l('email'),
				'dataIndex' => 'email',
				'width' => 90,
				'align' => 'left'
			),
			array(
				'id' => 'name',
				'header' => $this->l('Купон'),
				'dataIndex' => 'name',
				'width' => 60,
				'align' => 'left'
			),

			array(
				'id' => 'value',
				'header' => $this->l('Скидка'),
				'dataIndex' => 'value',
				'width' => 40,
				'align' => 'right'
			),
			
			array(
				'id' => 'date',
				'header' => $this->l('Протухнет'),
				'dataIndex' => 'date',
				'width' => 40,
				'align' => 'right'
			)
		);
		
		parent::__construct();
		
		$this->displayName = $this->l('Best vouchers');
		$this->description = $this->l('A list of the best vouchers');
	}
	
	public function install()
	{
		return (parent::install() AND $this->registerHook('AdminStatsModules'));
	}
	
	public function hookAdminStatsModules($params)
	{
		$engineParams = array(
			'id' => 'id_product',
			'title' => $this->displayName,
			'columns' => $this->_columns,
			'defaultSortColumn' => $this->_defaultSortColumn,
			'emptyMessage' => $this->_emptyMessage,
			'pagingMessage' => $this->_pagingMessage
		);


	
		$this->_html = '
			<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> Протухающие купоны</legend>
			<p>Через неделю протухнут:
				<br>

			'.print_r($users).'

			<input type="checkbox"> Отправлять письмо с уведомлением о протухании
			
		</fieldset>
	<br>
		<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->displayName.'</legend>
			'.ModuleGrid::engine($engineParams).'
		</fieldset>';
		return $this->_html;
	}
	
	public function getTotalCount()
	{
		$result = Db::getInstance()->GetRow('SELECT COUNT(`id_order_discount`) total FROM `'._DB_PREFIX_.'order_discount`');
		return $result['total'];
	}

	public function getData()
	{	mysql_query("SET lc_time_names = 'ru_RU'");
		$this->_totalCount = $this->getTotalCount();
		$this->_query = '
SELECT c.firstname, c.lastname, c.email, d.name, d.value, DATE_FORMAT(d.`date_to`, "%d %b %y") as date
FROM presta_discount d
LEFT JOIN presta_customer c ON d.id_customer = c.id_customer
WHERE d.active = 1
AND d.quantity > 0
AND d.date_to > now()
';

		if (Validate::IsName($this->_sort))
		{
			$this->_query .= ' ORDER BY `'.$this->_sort.'`';
			if (isset($this->_direction))
				$this->_query .= ' '.$this->_direction;
		}
		$this->_values = Db::getInstance()->ExecuteS($this->_query);
	}


}