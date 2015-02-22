<?php

use Vreasy\Models\Task;
use Vreasy\Services\TwilioService;
// We need to include the Log model because we are going to store an event on the task after the user reply
use Vreasy\Models\Log;

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
    	 //service for interactin with Twilio API; send ,process & receive sms
    	 $twilioService= new TwilioService();

	 $body = $this->getRequest()->getRawBody();
         $data = Zend_Json::decode($body);
         
         //debug..see what we receive from Xulio
         //$id=print_r($data,true);
         
         /* IMP- assumption: https://www.twilio.com/docs/api/twiml/sms/twilio_request
         *
         * Here we take the assumption we that can send with the sms (task proposal) an ID to the provider 
         * and we will get back this ID with thr user reply. We assume that this ID is sent in the filed:
         * "messageSid" and we store here the task ID, so we can find easily the task linked to the sms
         */
             
         $taskId = $data["MessageSid"];
         $userReply = $data["Body"];
         $phone = $data["From"];
         
         $processedResponse= $twilioService->processReply($userReply);
        
	 
	 
	  $task= Task::findById($taskId);
	 
	 if(isset($task))
	 {
	 	 if($processedResponse == TwilioService::ACCEPTED_JOB)
         	 {
         	    $task->status= Task::TASK_ACCEPTED;
         	    
         	    //TASK 2) Now store in the task log this task event with the time happened
         	    $log = new Log();
         	    $log->task_id = $taskId;
         	    $log->action_name= Task::TASK_ACCEPTED;
         	    $log->save();
         	    
         	    
         	    /* send acknowledgement
         	     *
         	     * i.e: $twilioService->ackAccpeted($phone)
         	     */
         	 }
         	 else if($processedResponse == TwilioService::REJECTED_JOB)
         	 {
         	    $task->status= Task::TASK_REJECTED;
         	    
         	    //TASK 2) Now store in the task log this task event with the time happened
         	    $log = new Log();
         	    $log->task_id = $taskId;
         	    $log->action_name= Task::TASK_REJECTED;
         	    $log->save();
         	    
         	    
         	    /*send acknowledgement
         	      *
         	      * i.e: $twilioService->ackRejected($phone)
         	      */
         	 }
         	 else
         	 {
         	 	 /* $task->status= Task::TASK_UNKNOWN;
         	 	 * Instead of changing the status of the task, if the reply cannot be understood
         	 	 * Reply the user with a sms about wrong format
         	 	 *
         	 	 *i.e: $twilioService->informUserWrongFormat($phone)
         	 	 */
         	 }
	 	
	 	 $task->save();
	 	 $this->view->response = $task;
	 	 
	 
	 }
	else
	{
		$this->view->response = ['error' => true, 'message' => "No task was found with the id:$taskId "];
	}
    }

   
}
