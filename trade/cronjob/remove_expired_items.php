<?php
require(dirname(dirname(dirname(__FILE__))) . '/includes/bootstrap.php');

/**
* Remove expired items
* Items will be expired in 7 days, and the 7 will be existed in config file
* 
* 
* TODO: You should call this file once every 30 min or one hour.
*/


$tradeItemIns = new BuckysTradeItem();

$tradeItemIns->removeExpiredItems();



exit;