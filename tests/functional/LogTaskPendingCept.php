<?php

$I = new TestGuy($scenario);
$I->wantTo('Test task event(log) created when task created/pending.');

/* Here we test that when a task is created an event with the action_name(event name) "pending" is created into the Logs table.
*
* To do this:
* -Create a task for user "Jordi Catala"
* -Then check if an event has been created on the Logs table for this task with the pending status
*/
 
$providerPhone='+34 111-222-333';
$task = $I->haveTask(['assigned_name' => 'Jordi Catala', 'assigned_phone' => $providerPhone ,'status' => 'pending']);


$I->haveHttpHeader('Content-Type','application/json');
$I->seeInDatabase('logs', ['task_id' => $task->id, 'action_name' => 'pending' ]);



?>
