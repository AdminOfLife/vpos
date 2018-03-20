<?php
require 'lib/controller.php';

$test_input_file = 'test_input.json';
$CONF_DIR;

if (isset($_REQUEST['card_num']))
{
	$card_num = htmlspecialchars($_REQUEST['card_num']);
	$sum = htmlspecialchars($_REQUEST['sum']);
	$pid = htmlspecialchars($_REQUEST['pid']);
	$cvv = htmlspecialchars($_REQUEST['cvv']);
}
else
{
	$content = file_get_contents($test_input_file);
	$content = json_decode($content, true);
	$card_num = $content['card_num']; 
	$sum = $content['sum'];
	$pid = $content['pid'];
	$cvv = $content['cvv']; 
}

$controller = new Controller();
$controller->process($card_num, $sum, $pid, $cvv);
$resp_code = $controller->process($card_num, $sum, $pid, $cvv);

print("respcode {$resp_code}\n");

?>

