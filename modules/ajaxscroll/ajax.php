<?php
include(dirname(__FILE__).'/../../config/config.inc.php');
require_once(dirname(__FILE__).'/../../init.php');
require_once(dirname(__FILE__).'/ajaxscroll.php');

$ajaxscroll=new ajaxscroll();
echo $ajaxscroll->ajax();

//echo '<pre>jopa';
//print_r($smarty);
//echo '</pre-->';