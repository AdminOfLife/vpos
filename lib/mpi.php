<?php
require_once '3ds.php';

define("MPI_STATUS", array(
		"BAD_CA"
		)); 

class MPI
{
	private $pan;

	public function Process()
	{
		$status;	
		$this->Request(VAReq);
		
		/**********************************/	
		/**							STEP 5					 **/ 
		/**********************************/
		if ($this->Response(VARes) == FAIL) // 'PAN Authentication Available' is not equal to “Y”
		{
			/* Process it and go to on step 12 (3DSSpecifications-CoreFunctions.pdf) */
			$status = FAIL;
		}
		
		/**********************************/	
		/**							STEP 6					 **/ 
		/**********************************/
		/* 	
			c) The MPI formats a Payer Authentication Request message (PAReq) including
			the Account Identifier received in VERes. 
		*/	
		// TODO: format PAReq
		
		/*
			d) The MPI deflates and Base64-encodes the PAReq as described in Appendix D
			on page 91. The result is referred to as PaReq (note the case difference).
		*/

		/**********************************/	
		/**							STEP 10					 **/ 
		/**********************************/
		
		/*
			a) The MPI reads the response, which contains the PaRes field. The MPI Base64-
			decodes and inflates the PaRes field reversing the process described in
			response
			Appendix D on page 91 to obtain the PARes (note the case difference) or
			Error.
		*/
		$resp = $this->Response(PARes);

		/* If an Error message is received, continue with Step 12 on page 40. */
		
		// TODO: test on Error message
		
		/*
			c) The MPI validates the syntax of the PARes and should send an Error to the
			ACS (via the ACS URL received in the VERes) if validation fails.
		*/		
		// TODO: validate the syntax
	
		/**********************************/	
		/**							STEP 11					 **/ 
		/**********************************/

		

		/**********************************/	
		/**							STEP 12					 **/ 
		/**********************************/
		 	
		return $status;
	}

	public function __construct($pan)
	{
		$this->pan = $pan;	
	}

	protected function Request($type)
	{
		switch ($type)
		{
			case VAReq:
			{
				echo VAReq . "\n";
				break;
			} 
		}
	}

	protected function Response($type)
	{

	}
}

?>
