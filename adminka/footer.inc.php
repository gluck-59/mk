<?php

/**
  * Admin panel footer, footer.inc.php
  * @category admin
  *
  * @author PrestaShop <support@prestashop.com>
  * @copyright PrestaShop
  * @license http://www.opensource.org/licenses/osl-3.0.php Open-source licence 3.0
  * @version 1.2
  *
  */

if (!defined('_PS_VERSION_'))
	exit();
ob_flush();
?>

				</div>
			</div>
		
			<p id="footer">
				Работает на <a href="http://www.prestashop.com/" target="_blank">PrestaShop&trade;</a><br />
			  Русская версия подготовлена <a href="http://www.prestadev.ru/" target="_blank">PrestaDev.ru</a><br />
			  версия <?php echo _PS_VERSION_; ?> - <?php echo number_format(microtime(true) - $timerStart, 3, '.', ''); ?>s<br>
			  <?php echo date('d-m-Y H:i', $_SERVER['REQUEST_TIME']); ?>
			  </p>
		</div>
	</body>
</html>
<?php
	//ob_end_flush();
?>