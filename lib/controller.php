<?php
require 'pack.php';
require 'TCPCommunications.php';
require 'mpi.php';
//require 'tmp.php';

class Controller
{
	private $host;
	private $port;
	private $username;
	private $password;
	private $connection_string = "";
	private $login_file = __DIR__.'/../loginfo.json';

	public function process($card_num, $sum, $pid, $cvv)
	{
		$mpi = new MPI($card_num);
		if ($mpi->Process() == MPI_STATUS[0])
		{
			return -1;	
		}
		
		$this->get_login_info($this->login_file);
		$this->port = $this->get_port_by_pid($pid);
		$message = pack_message($card_num, $sum, $pid);
		$packed_message = $message->pack();

		try
		{
			$tcp = new TCPCommunications($this->host, $this->port, 5);	
			$tcp->Send(hex2bin($packed_message)); 
		}
		catch (Exception $e)
		{
			if ($e->getCode() == TIMEOUT)
			{
				try
				{
					SendReversal($card_num, $sum, $pid);
					// Return cancaled operation
				}
				catch (Exception $e)
				{
					if ($e->getCode() == TIMEOUT)
					{
						// deferred operation
						return -1;
					}	
				}
				return -2;
			}
		}
		$unpack = $message->unpack($tcp->Recive());
		
		$bitmap = "{\"bitmap\":".json_encode($message->getbitmap())."}\n";
		$mti = "{\"mti\":".json_encode($message->getmti())."}\n";;
		$fields = json_encode($message->getfields());
		echo($mti."\n");
		echo($bitmap."\n");
		echo($fields."\n");
		$fields = $message->getfields();	
		return $fields['39'];
	}

	private function get_port_by_pid($pid)
	{
		$conn = oci_connect($this->username, $this->password, $this->get_connection_string());
		if (!$conn) {
			$e = oci_error();
			trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$stid = oci_parse($conn, 'SELECT LOCAL_PORT FROM TCP_TAB WHERE PID = '.$pid);
		oci_execute($stid);

		while ($row = oci_fetch_assoc($stid)){ 
			foreach ($row as $item) {
				return $item;
			}
		}
	}

	private function get_connection_string()
	{
		$data = file_get_contents('oci.json');
		$oci = json_decode($data, true);
		$this->json_to_oracle($oci);
		return $this->connection_string; 
	}

	private function json_to_oracle($oci)
	{
		if(is_array($oci))
		{
			foreach($oci as $key => $value)
			{
				if(is_array($value) || is_object($value))
				{
					$this->connection_string .= "({$key} = ";
					$this->json_to_oracle($value);
					$this->connection_string .= ")";
				}
				else
				{
					$this->connection_string .= "({$key} = {$value})";
				}
			}
		}
	}

	private function get_login_info($file)
	{
		$data = file_get_contents($file);	
		$log_info = json_decode($data, true);

		$this->username = $log_info['username'];
		$this->password = $log_info['password'];		
		$this->host = $log_info['host'];
	}

}
?>
