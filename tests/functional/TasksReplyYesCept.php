<?php

$I = new TestGuy($scenario);
$I->wantTo('Test that simulates Twilio reply (user replies YES).');

/* As explained in SmsController.php , we assume that we have sent to Twilio with the request to the provider the tasksID and we get it back with the reply
 in the field MessageSid */
 
$providerPhone='+34 111-222-333';
 
$task = $I->haveTask(['assigned_name' => 'Jordi Catala', 'assigned_phone' => $providerPhone ,'status' => 'pending']);



$I->haveHttpHeader('Content-Type','application/json');
$I->sendPOST('/sms/update?format=json', ['From' => $providerPhone, 'Body' => "yes", 'MessageSid' => $task->id]);
$I->seeInDatabase('tasks', ['id' => $task->id ,'status' => 'accepted']);
$I->seeResponseContains('"status":"accepted"');


?>
