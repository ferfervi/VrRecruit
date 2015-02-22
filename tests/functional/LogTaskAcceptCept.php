<?php

$I = new TestGuy($scenario);
$I->wantTo('Test that when the user accepts a task we get the status event(log) "accepted" into the Logs table.');

/* Test when the user ACCEPTS a task we get the status event "accepted" into the Logs table 
*
*/
 
$providerPhone='+34 111-222-333';
$task = $I->haveTask(['assigned_name' => 'Jonas Magnusson', 'assigned_phone' => $providerPhone ,'status' => 'pending']);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST('/sms/update?format=json', ['From' => $providerPhone, 'Body' => "yes", 'MessageSid' => $task->id]);

//check we got the event in logs table
$I->seeInDatabase('logs', ['task_id' => $task->id ,'action_name' => 'accepted']);



?>
