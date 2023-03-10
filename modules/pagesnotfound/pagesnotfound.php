<?php

/**
  * Statistics
  * @category stats
  *
  * @author Damien Metzger / Epitech
  * @copyright Epitech / PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.1
  */
  
class Pagesnotfound extends Module
{
    private $_html = '';

    function __construct()
    {
        $this->name = 'pagesnotfound';
        $this->tab = 'Stats';
        $this->version = 1.0;

        parent::__construct();
		
        $this->displayName = $this->l('Pages not found');
        $this->description = $this->l('Display the pages requested by your visitors but not found');
    }

	function install()
	{
		if (!parent::install() OR !$this->registerHook('top') OR !$this->registerHook('AdminStatsModules'))
			return false;
		return Db::getInstance()->Execute('
		CREATE TABLE `'._DB_PREFIX_.'pagenotfound` (
		  id_pagenotfound INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
		  request_uri VARCHAR(256) NOT NULL,
		  http_referer VARCHAR(256) NOT NULL,
		  date_add DATETIME NOT NULL,
		  PRIMARY KEY(id_pagenotfound),
		  INDEX (`date_add`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
	}
	
    function uninstall()
    {
        if (!parent::uninstall())
			return false;
		return Db::getInstance()->Execute('DROP TABLE `'._DB_PREFIX_.'pagenotfound`');
    }
	
	private function getPages()
	{
		$result = Db::getInstance()->ExecuteS('
		SELECT http_referer, request_uri, COUNT(*) as nb
		FROM `'._DB_PREFIX_.'pagenotfound` p
		WHERE p.date_add BETWEEN '.ModuleGraph::getDateBetween().'
		GROUP BY request_uri
		ORDER BY nb DESC'
		);
	
		
		$pages = array();
		foreach ($result as $row)
		{
			$row['http_referer'] = preg_replace('/^www./', '', parse_url($row['http_referer'], PHP_URL_HOST)).parse_url($row['http_referer'], PHP_URL_PATH);
			if (!isset($row['http_referer']) OR empty($row['http_referer']))
				$row['http_referer'] = '--';
			if (!isset($pages[$row['request_uri']]))
				$pages[$row['request_uri']] = array('nb' => 0);
			$pages[$row['request_uri']][$row['http_referer']] = $row['nb'];
			$pages[$row['request_uri']]['nb'] += $row['nb'];
		}
		uasort($pages, 'pnfSort');
		return $pages;
	}
	
    function hookAdminStatsModules()
    {
        $this->_html .= '<fieldset class="width3"><legend><img src="../modules/'.$this->name.'/logo.gif" /> '.$this->displayName.'</legend>';
		if (!file_exists(dirname(__FILE__).'/../../.htaccess'))
			$this->_html .= '<div class="warning warn">'.$this->l('You <b>must</b> use a .htaccess file to redirect 404 errors to the page "404.php"').'</div>';
		
		$pages = $this->getPages();
		if (sizeof($pages))
		{
			$this->_html .= '
			<table class="table" cellpadding="0" cellspacing="0">
				<tr>
					<th width="150">'.$this->l('Page').'</th>
					<th width="200">'.$this->l('Referrer').'</th>
					<th>'.$this->l('Counter').'</th>
				</tr>';
			foreach ($pages as $ru => $hrs)
				foreach ($hrs as $hr => $counter)
					if ($hr != 'nb')
						$this->_html .= '
						<tr>
							<td><a target="_blank" href="'.$ru.'-admin404">'.wordwrap($ru, 30, '<br />', true).'</a></td>
							<td><a target="_blank" href="http://'.$hr.'">'.wordwrap($hr, 40, '<br />', true).'</a></td>
							<td align="right">'.$counter.'</td>
						</tr>';
			$this->_html .= '
			</table>';
		}
		else
			$this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" /> '.$this->l('No pages registered').'</div>';
			
		$this->_html .= '
			</fieldset><br />
		<fieldset class="width3"><legend><img src="../img/admin/comment.gif" /> '.$this->l('Guide').'</legend>
			<h2>'.$this->l('404 errors').'</h2>
			<p>'.$this->l('A <i>404 error</i> is an HTTP error code which means that the file requested by the user can\'t be found. In your case it means that one of your visitors entered a wrong URL in the address bar or that you or another website has a dead link somewhere. When it is available, the referrer is shown so you can find the page which contains the dead link. If not, it means generally that it is a direct access, so maybe someone has bookmarked a link which doesn\'t exist anymore.').'</p>
			<h3>'.$this->l('How to catch these errors?').'</h3>
			<p>'.$this->l('If your webhost supports the <i>.htaccess</i> file, you can create it in the root directory of PrestaShop and insert the following line inside:').' <i>ErrorDocument 404 '.__PS_BASE_URI__.'404.php</i>. '.$this->l('A user requesting a page which doesn\'t exist will be redirected to the page').' <i>'.__PS_BASE_URI__.'404.php</i>. '.$this->l('This module logs the accesses to this page: the page requested, the referrer and the number of times that it occurred.').'</p><br />
		</fieldset>';

        return $this->_html;
    }
	
	function hookTop($params)
	{
		if (strstr($_SERVER['REQUEST_URI'], '404.php') AND isset($_SERVER['REDIRECT_URL']))
			$_SERVER['REQUEST_URI'] = $_SERVER['REDIRECT_URL'];
		if (!Validate::isUrl($request_uri = $_SERVER['REQUEST_URI']) OR strstr($_SERVER['REQUEST_URI'], '-admin404'))
			return;
		if (strstr($_SERVER['PHP_SELF'], '404.php') AND !strstr($_SERVER['REQUEST_URI'], '404.php'))
		{
			$http_referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
			if (empty($http_referer) OR Validate::isAbsoluteUrl($http_referer))
				Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'pagenotfound` (`request_uri`,`http_referer`,`date_add`) VALUES (\''.pSQL($request_uri).'\',\''.pSQL($http_referer).'\',NOW())');
		}
	}
}

function pnfSort($a, $b) {
    if ($a['nb'] == $b['nb'])
        return 0;
    return ($a['nb'] > $b['nb']) ? -1 : 1;
}

?>
