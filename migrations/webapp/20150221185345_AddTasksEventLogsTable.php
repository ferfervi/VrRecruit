<?php

class AddTasksEventLogsTable extends Ruckusing_Migration_Base
{
    public function up()
    {
        $tasks = $this->create_table('logs', ['id' => false, 'options' => 'Engine=InnoDB']);
        $tasks->column(
            'id',
            'integer',
            [
                'primary_key' => true,
                'auto_increment' => true,
                'null' => false
            ]
        );
        $tasks->column('task_id','integer');
        $tasks->column('action_name','string');
        $tasks->column('updated_at','datetime');
        $tasks->finish();
    }//up()

    public function down()
    {
        $this->drop_table("logs");
    }//down()
}
