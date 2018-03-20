<?php
require 'pack.php';
require 'TCPCommunications.php';

class Controller
{
	private $host;
	private $port;
	private $username;
	private $password;
	private $connection_string;
	private $login_file = __DIR__.'/../loginfo.json';
	public function process($card_num, $sum, $pid, $cvv)
	{
		$this->get_login_info($this->login_file);
		$this->port = $this->get_port_by_pid($pid);
		$message = pack_message($card_num, $sum, $pid);
		$packed_message = $message->pack();

		var_dump($message->getbitmap());
		var_dump($message->getmti());
		var_dump($message->getfields());

		$tcp = new TCPCommunications($this->host, $this->port, 5);
		try
		{
			$tcp->Send(hex2bin($packed_message)); 
		}
		catch (Exception $e)
		{
			if ($e->getCode() == TIMEOUT)
			SendReversal($card_num, $sum, $pid);
			return -1;
		}
		$unpack = $message->unpack($tcp->Recive());
		
		var_dump($message->getbitmap());
		var_dump($message->getmti());
		var_dump($message->getfields());
	}

	private function get_port_by_pid($pid)
	{
		$data = file_get_contents('oci.json');
		$oci = json_decode($data, true);

		$conn = oci_connect($this->username, $this->password, $this->get_connection_string());
		if (!$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$stid = oci_parse($conn, 'SELECT LOCAL_PORT FROM TCP_TAB WHERE PID = '.$pid);
		oci_execute($stid);

		while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
			foreach ($row as $item) {
				return $item;
			}
		}
	}

	private function get_connection_string()
	{
		$data = file_get_contents('oci.json');
		$oci = json_decode($data, true);
		return $this->json_to_oracle($oci);
	}

	private function get_login_info($file)
	{
		$data = file_get_contents($file);	
		$log_info = json_decode($data, true);

		$this->username = $log_info['username'];
		$this->password = $log_info['password'];		
		$this->host = $log_info['host'];
	}

	private function json_to_oracle($oci)
	{
		if(is_array($oci))
		{
			foreach($oci as $key=>$value)
			{
				if(is_array($value) || is_object($value))
				{
					print("({$key} = ");
					$this->json_to_oracle($value);
					print(")");
				}
				else
				{
					print("({$key} = {$value})");
				}
			}
		}
	}

}
?>
