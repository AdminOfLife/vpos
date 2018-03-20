<?php
//session_start();
require 'lib/controller.php';
echo "<head>";
echo "<style>
        body{
                background-color: #3366CC; /* Цвет фона веб-страницы */
        }
        h2 {
                background-color: RGB(249, 201, 16); /* Цвет фона под заголовком */
        }
        pre {
                background-color: white; /* Цвет фона под текстом параграфа */
                color: green; /* Цвет текста */
        }
        </style>";
echo "<body>";

if (isset($_REQUEST['card_num']))
{
	echo "$_REQUEST is set\n";
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

echo "</body>";
echo "</head>";
?>

