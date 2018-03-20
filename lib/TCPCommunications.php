<?php

define('SUCCESS', 1);
define('TIMEOUT', 2);
define('OTHERERR', 3);

class TCPCommunications
{

	private $address;
	private $local_port;
	private $remote_port;
	private $timeout;
	private $fp;
	
	function __construct($address, $remote_port, $timeout)
	{
		$this->address = $address;
		$this->port = $remote_port;
		$this->timeout = $timeout;
		$this->fp = stream_socket_client("tcp://".$address.":".$remote_port, $errno, $errstr, 5);
		stream_set_timeout ($this->fp, 5);
	}
	public function Send($msg)
	{
		if (!$this->fp)
		{
			echo "Bad";
			echo $errstr ($errno);
			fclose($this->fp);
			return TIMEOUT;//OTHERERR;		
		}
		else
		{
			echo "Good\n";
			fwrite($this->fp, $msg);
			$meta = stream_get_meta_data($this->fp);
			if ($meta['timed_out'] == TRUE)
				throw new Exception('TimeOut', TIMEOUT);	
		}
		return SUCCESS;
	}
	
	public function Recive()
	{
		if (!$this->fp)
		{
			fclose($this->fp);
			return false;
		}
		else
		{
			while (!feof($this->fp))
			{
				return bin2hex(fread($this->fp, 1024));
			}
			fclose($this->fp);
		}
	}
	

}

?>
