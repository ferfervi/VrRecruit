<?php

$I = new TestGuy($scenario);
$I->wantTo('Test we can fetch the events(logs) on a specific task: 1)pending + 2)rejected.');


$task_id = 1;
//we have a task pending (event 1)
$event1 = $I->haveLog(['task_id' => $task_id, 'action_name' => 'pending']);

//the task is rejected (event 2)
$event2 = $I->haveLog(['task_id' => $task_id, 'action_name' => 'rejected']);

$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET('/log/index', ['task_id' => $task_id,'format' => 'json']);

//First check if we get the event for the selected task as "pending"
$I->seeResponseContains('"action_name":"pending"');

//Check if we get the event "rejected" for the same task
$I->seeResponseContains('"action_name":"rejected"');



?>
