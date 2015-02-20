<?php

class AddStatusFieldTasksTable extends Ruckusing_Migration_Base
{
    public function up()
    {
    	    $this->add_column('tasks', 'status', 'string', ['default' => 'pending']);
    }//up()

    public function down()
    {
    	    $this->remove_column("tasks", 'status');
    }//down()
}
