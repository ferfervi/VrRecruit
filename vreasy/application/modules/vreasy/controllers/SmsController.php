<?php

use Vreasy\Models\Task;
use Vreasy\Services\TwilioService;

class Vreasy_SmsController extends Vreasy_Rest_Controller
{
    protected $task, $tasks;
    

    public function preDispatch()
    {
        parent::preDispatch();

 $req = $this->getRequest();
$action = $req->getActionName();

 if( !in_array($action, [ 'update' ]) && !$this->tasks && !$this->task->id) {
 throw new Zend_Controller_Action_Exception('Resource not found', 404);
 }

}
        
    

   
   
   public function updateAction()
    {
    	    $twilioService= new TwilioService();

	 $body = $this->getRequest()->getRawBody();
         $data = Zend_Json::decode($body);
         //debug..see what we receive from Xulio
         //$id=print_r($data,true);
         
         /* IMP- assumption: https://www.twilio.com/docs/api/twiml/sms/twilio_request
         *
         Here we take the assumption that can send with the sms an ID to the provider and we will get
         back this ID with a reply from a user for the sms that we sent. We assume that this ID is sent
         in the filed: "messageSid" and we store here the task ID, so we can find easily to which tasks
         is linked the sms 
         */
             
         $taskId=$data["MessageSid"];
         $userReply=$data["Body"];
         
         $processedResponse= $twilioService->processReply($userReply);
        
	 
	 
	  $task= Task::findById($taskId);
	 
	 if(isset($task))
	 {
	 	 if($processedResponse == TwilioService::ACCEPTED_JOB)
         	 {
         	    $task->status= "accepted";
         	 }
         	 else if($processedResponse == TwilioService::REJECTED_JOB)
         	 {
         	    $task->status= "rejected";
         	 }
         	 else
         	 {
         	 	 $task->status= "unknown";
         	 }
	 	
	 	 $task->save();
	 	 $this->view->response = $task;
	 	 
	 
	 }
	else
	$this->view->response = ['error' => true, 'message' => "No task was found with the id:$taskId "];
    }

   
}
