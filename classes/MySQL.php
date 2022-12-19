<?php

/**
  * MySQL class, MySQL.php
  * MySQLs management
  * @category classes
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

define('_PS_DEBUG_', true);

class MySQL extends Db
{
	public function	connect()
	{
		if ($this->_link = @mysqli_connect($this->_server, $this->_user, $this->_password, $this->_database))
		{
//			if(!$this->set_db($this->_database))
//				die(Tools::displayError('The database selection cannot be made.'));
		}
		else
			die(Tools::displayError('Link to database cannot be established.'));
		/* UTF-8 support */
//		if (!mysqli_query('SET NAMES \'utf8\'', $this->_link))
//			die(Tools::displayError('PrestaShop Fatal error: no utf-8 support. Please check your server configuration.'));
		/* Disable some MySQL limitations */
		mysqli_query($this->_link, 'SET GLOBAL SQL_MODE=\'\'');
		return $this->_link;
	}
	
	/* do not remove, useful for some modules */
	public function set_db($db_name) {
		return mysqli_select_db($db_name, $this->_link);
	}
	
	public function	disconnect()
	{
		if ($this->_link)
			@mysqli_close($this->_link);
		$this->_link = false;
	}
	
	public function	getRow($query)
	{
		$this->_result = false;
		if ($this->_link)
			if ($this->_result = mysqli_query($this->_link, $query.' LIMIT 1'))
			{
				$this->displayMySQLError($query);
				return mysqli_fetch_assoc($this->_result);
			}
		$this->displayMySQLError($query);
		return false;
	}

	public function	getValue($query)
	{
		$this->_result = false;
		if ($this->_link AND $this->_result = mysqli_query($this->_link, $query.' LIMIT 1') AND is_array($tmpArray = mysqli_fetch_assoc($this->_result)))
			return array_shift($tmpArray);
		return false;
	}
	
	public function	Execute($query)
	{
		$this->_result = false;
		if ($this->_link)
		{
			$this->_result = mysqli_query($this->_link, $query);
			$this->displayMySQLError($query);
			return $this->_result;
		}
		$this->displayMySQLError($query);
		return false;
	}
	
	public function	ExecuteS($query, $array = true)
	{
		$this->_result = false;
		if ($this->_link && $this->_result = mysqli_query($this->_link, $query))
		{
			$this->displayMySQLError($query);
			if (!$array)
				return $this->_result;
			$resultArray = array();
			while ($row = mysqli_fetch_assoc($this->_result))
				$resultArray[] = $row;
			return $resultArray;
		}
		$this->displayMySQLError($query);
		return false;
	}

	public function nextRow($result = false)
	{
		return mysqli_fetch_assoc($result ? $result : $this->_result);
	}
	
	public function	delete($table, $where = false, $limit = false)
	{
		$this->_result = false;
		if ($this->_link)
			return mysqli_query($this->_link, 'DELETE FROM `'.pSQL($table).'`'.($where ? ' WHERE '.$where : '').($limit ? ' LIMIT '.intval($limit) : ''));
		return false;
	}
	
	public function	NumRows()
	{
		if ($this->_link AND $this->_result)
			return mysqli_num_rows($this->_result);
	}
	
	public function	Insert_ID()
	{
		if ($this->_link)
			return mysqli_insert_id($this->_link);
		return false;
	}
	
	public function	Affected_Rows()
	{
		if ($this->_link)
			return mysqli_affected_rows($this->_link);
		return false;
	}

	protected function q($query)
	{
		$this->_result = false;
		if ($this->_link)
			return mysqli_query($this->_link, $query);
		return false;
	}
	
	/**
	 * Returns the text of the error message from previous MySQL operation
	 *
	 * @acces public
	 * @return string error
	 */
	public function getMsgError($query = false)
	{
		return mysqli_error($this->_link);
	}

	public function getNumberError()
	{
		return mysqli_errno();
	}

	public function displayMySQLError($query = false)
	{
		if (_PS_DEBUG_ AND mysqli_errno($this->_link))
		{
			if ($query)
				die(Tools::displayError(mysqli_error().'<br /><br /><pre>'.$query.'</pre>'));
			die(Tools::displayError((mysqli_error())));
		}
	}

	static public function tryToConnect($server, $user, $pwd, $db)
	{
		if (!$link = @mysqli_connect($server, $user, $pwd))
			return 1;
		if (!@mysqli_select_db($db, $link))
			return 2;
		@mysqli_close($link);
		return 0;
	}

	static public function tryUTF8($server, $user, $pwd)
	{
		$link = @mysqli_connect($server, $user, $pwd);
		if (!mysqli_query($link, 'SET NAMES \'utf8\''))
			$ret = false;
		else
			$ret = true;
		@mysqli_close($link);
		return $ret;
	}
}
