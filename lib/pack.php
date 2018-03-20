<?php
require 'vendor/autoload.php';

$base = __DIR__;

use ISO8583\Protocol;
use ISO8583\Message;
function pack_message($card_num, $sum, $pid){
	$iso = new Protocol();
	$message = new Message($iso, [
	    'lengthPrefix' => 0
	]);

	// Packing message
	//3,4,7,11,12,22,24,25,35,41,42,49,52
	$message->setMTI('0100');
	$message->setField(2, str_pad($card_num, 12, "0", STR_PAD_LEFT));
	$message->setField(3, "000000");
	$message->setField(4, str_pad($sum, 12, "0", STR_PAD_LEFT));
	$message->setField(7, "0914225918");
	$message->setField(11, "000155");
	$message->setField(12, "235918000000");
	$message->setField(22, "010");
	$message->setField(25, "00");
	$message->setField(41, str_pad($pid, 8, "0", STR_PAD_LEFT));
	$message->setField(42, "000000000020553");
	$message->setField(49, "643");
	
	return $message;
}
