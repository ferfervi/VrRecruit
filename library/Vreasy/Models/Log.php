<?php

namespace Vreasy\Models;

use Vreasy\Query\Builder;

class Log extends Base
{
    // Protected attributes should match table columns
    protected $id;
    
    protected $task_id;
    protected $action_name;
    protected $updated_at;

    
    public function __construct()
    {
        // Validation is done run by Valitron library
        $this->validates(
            'required',
            ['task_id', 'action_name']
        );
        $this->validates(
            'date',
            ['updated_at']
        );
        $this->validates(
            'integer',
            ['task_id','id']
        );
    }

    public function save()
    {
        // Base class forward all static:: method calls directly to Zend_Db
        if ($this->isValid()) {
            $this->updated_at = gmdate(DATE_FORMAT);
          //  if ($this->isNew()) {
          //IMP: we insert a new entry always for every event on a task	
          
                static::insert('logs', $this->attributesForDb());
                $this->id = static::lastInsertId();
           // } 
           /* else {
                static::update(
                    'logs',
                    $this->attributesForDb(),
                    ['id = ?' => $this->id]
                );
            }*/
            return $this->id;
        }
    }

    public static function findOrInit($task_id)
    {
        $log = new Log();
        if ($logsFound = static::where(['task_id' => (int)$task_id])) {
            $log = array_pop($logsFound);
        }
        return $log;
    }
    
    //There may be multiple events on a task_id: return all of them
  public static function findById($task_id)
    {
        $log = null;
        if ($logsFound = static::where(['task_id' => (int)$task_id])) {
              while(!empty($logsFound))
        	$log[] = array_pop($logsFound);
        }

        return $log;
    }


    public static function where($params, $opts = [])
    {
        // Default options' values
        $limit = 0;
        $start = 0;
        $orderBy = ['updated_at'];
        $orderDirection = ['asc'];
        extract($opts, EXTR_IF_EXISTS);
        $orderBy = array_flatten([$orderBy]);
        $orderDirection = array_flatten([$orderDirection]);

        // Return value
        $collection = [];
        // Build the query
        list($where, $values) = Builder::expandWhere(
            $params,
            ['wildcard' => true, 'prefix' => 'l.']);

        // Select header
        $select = "SELECT l.* FROM logs AS l";

        // Build order by
        foreach ($orderBy as $i => $value) {
            $dir = isset($orderDirection[$i]) ? $orderDirection[$i] : 'ASC';
            $orderBy[$i] = "`$value` $dir";
        }
        $orderBy = implode(', ', $orderBy);

        $limitClause = '';
        if ($limit) {
            $limitClause = "LIMIT $start, $limit";
        }

        $orderByClause = '';
        if ($orderBy) {
            $orderByClause = "ORDER BY $orderBy";
        }
        if ($where) {
            $where = "WHERE $where";
        }

        $sql = "$select $where $orderByClause $limitClause";
        if ($res = static::fetchAll($sql, $values)) {
            foreach ($res as $row) {
                $collection[] = static::instanceWith($row);
            }
        }
        return $collection;
    }
}
