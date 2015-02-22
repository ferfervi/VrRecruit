<?php
namespace Vreasy\Services;
/* In this class we can implmeent all methods related with TwilioAPI 
   such as: process received sms, send a sms to a provider ...
*/

class TwilioService
{
	const ACCEPTED_JOB= 1;
	const REJECTED_JOB= 2;
	const UNKNOWN_STATUS =-1;
	
	//process reply and return if accepted or not / unknown if not possible to determine
	public function processReply($reply)
	{       $toLower=strtolower($reply);

		if((strpos($toLower,"y")!== FALSE || strpos($toLower,"s")!== FALSE)&& strpos($toLower,"n")!== FALSE) $toReutrn = self::UNKNOWN_STATUS;
		else if(strpos($toLower,"y")!== FALSE || strpos($toLower,"s")!== FALSE) $toReutrn = self::ACCEPTED_JOB;
		else if (strpos($toLower,"n")!== FALSE) $toReutrn = self::REJECTED_JOB;
		else $toReutrn = self::UNKNOWN_STATUS;
	  
		return $toReutrn;
	}
	
	//TODO: implement these functions to send acks to the user after receiving his/her sms
	//IMPROVEMENT: these functions could be implemented in only one function with the message to ack as parameter
	public function ackRejected($phone)
	{
	}
	public function ackAccepted($phone)
	{
	}
	public function informUserWrongFormat($phone)
	{
	}
	

}
?>
