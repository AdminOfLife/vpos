<?php
require 'lib/controller.php';

if (isset($_REQUEST['card_num']))
{
	$card_num = htmlspecialchars($_REQUEST['card_num']);
	$sum = htmlspecialchars($_REQUEST['sum']);
	$pid = htmlspecialchars($_REQUEST['pid']);
	$cvv = htmlspecialchars($_REQUEST['cvv']);
}
else
{
	$card_num = "4038403250843830"; 
	$sum = 1000;
	$pid = 9002;
	$cvv = null;
}

$controller = new Controller();
$controller->process($card_num, $sum, $pid, $cvv);
$resp_code = $controller->process($card_num, $sum, $pid, $cvv);

?>

