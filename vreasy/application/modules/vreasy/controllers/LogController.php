<?php

use Vreasy\Models\Log;

class Vreasy_LogController extends Vreasy_Rest_Controller
{
    protected $logs;

    public function preDispatch()
    {
        parent::preDispatch();
        $req = $this->getRequest();
        $action = $req->getActionName();
       
       
       $this->logs = Log::findById($req->getParam('task_id'));
       //degug: echo "param: ".$req->getParam('task_id');

     if (!in_array($action, ['index']) && !$this->logs) {
     throw new Zend_Controller_Action_Exception('Resource not found', 404);
       }

    }

    // return to the view the list of event(logs) linked to the task_id requested as a parameter
    public function indexAction()
    {
        $this->view->logs = $this->logs;
        $this->_helper->conditionalGet()->sendFreshWhen(['etag' => $this->logs]);
    }

}
