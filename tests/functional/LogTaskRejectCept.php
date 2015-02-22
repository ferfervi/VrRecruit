<?php

$I = new TestGuy($scenario);
$I->wantTo('Test that when the user rejects a task we get the status event(log) "rejected" into the Logs table.');

/* Test when the user REJECTS a task we get the status event "rejected" into the Logs table 
*
*/
 
$providerPhone='+34 111-222-333';
$task = $I->haveTask(['assigned_name' => 'Jonas Magnusson', 'assigned_phone' => $providerPhone ,'status' => 'pending']);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST('/sms/update?format=json', ['From' => $providerPhone, 'Body' => "no", 'MessageSid' => $task->id]);

//check we got the event in logs table
$I->seeInDatabase('logs', ['task_id' => $task->id ,'action_name' => 'rejected']);



?>
