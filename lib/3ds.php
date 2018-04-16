<?php
	//require_once 'XML/Serializer.php';

	define('VAReq',0);	
	define('PAReq');

	class ThreeDSecure
	{
		public $version = "1.0.2";	
	}
	/*************************************
	*	CRReq (Card Range Request)	
	**************************************/	
	class CRReq
	{
		function __construct()
		{
			echo "test\n";
		}
	}
	/*************************************
	*	CRRes (Card Range Response)		
	**************************************/		
	class CRRes
	{

	}
	/*************************************
	*	VEReq (Verify Enrollment Request)	
	**************************************/			
	class VEReq extends  ThreeDSecure
	{
		
		public $content; 
		
		public function __construct()
		{
			$this->content = array(
			'Message Version Number' => $this->version/*,
			'Cardholder PAN' => $this->pan,
			'Acquirer BIN' => $this->Merchant_acqBIN */
			); 
			var_dump($this->content);
		}
	}
	/*************************************
	*	VERes (Verify Enrollment Response)
	**************************************/		
	class VERes
	{

	}
	/*************************************
	*	PAReq (Payer Authentication Request)
	**************************************/			
	class PAReq
	{

	}
	/*************************************
	*	PARes (Payer Authentication Response)
	**************************************/		
	class PARes
	{

	}
	
	$verreq = new VEReq();




?>
