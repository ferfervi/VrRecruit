<?php

$cliIndex = implode(DIRECTORY_SEPARATOR, ['vreasy', 'application', 'cli', 'cliindex.php']);
require_once($cliIndex);

use Vreasy\Models\Log;
use Vreasy\Models\Task;

class InsertSomeTaskLogs extends Ruckusing_Migration_Base
{
    public function up()
    {
        foreach ([1,2,3] as $i) {
            $l = Log::instanceWith([
                'updated_at' => (new \DateTime(gmdate(DATE_FORMAT)))->format(DATE_FORMAT),
                'action_name' => Task::TASK_PENDING,
                'task_id' => $i,
            ]);
            $l->save();
        }
    }//up()

    public function down()
    {
    }//down()
}
