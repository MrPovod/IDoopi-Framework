<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "/../Database.php";

/**
 * Description of Query
 *
 * @author Борис
 */
class Query {
    protected $objects;
    protected $isProcessed;
    
    protected $all;
    protected $filter;
    protected $exclude;
    protected $orderBy;
    protected $limitFrom;
    protected $limitTo;
    protected $desc;
    protected $setLimit;
    protected $groupBy;
    
    protected $className;
    protected $tableName;
    
    protected $select;
    protected $distinct;
    
    protected $onlyValues;
    
    protected $values;
    protected $db;
    
    public function getDistinct() { return NULL; }
    public function getDB() { return $this->db; }
    public function getValues() { return $this->values; }
    public function isOnlyValues() { return $this->onlyValues; }
    public function isSetLimit()  { return $this->setLimit; }
    public function isAll() { return $this->all; }
    public function getFilter() { return $this->filter; }
    public function getExclude() { return $this->exclude; }
    public function getOrderBy() { return $this->orderBy; }
    public function getLimit() { return array($this->limitFrom, $this->limitTo); }
    public function getDesc() { return $this->desc; }
    public function getSelect() { return $this->select; }

    public function getTableName() { return $this->tableName; }
    public function getClassName() { return $this->className; }

    function __construct($className, $tableName, $select = "*", $objects = NULL) {
        $this->objects = $objects;
        $this->filter = array();
        $this->exclude = array();
        $this->className = $className;
        $this->tableName = $tableName;
        $this->limitFrom = NULL;
        $this->limitTo = NULL;
        $this->desc = FALSE;
        $this->orderBy = array();
        $this->setLimit = FALSE;
        $this->isProcessed = FALSE;
        $this->select = $select;
        $this->distinct = NULL;
        $this->groupBy = NULL;
        $this->db = NULL;
    }
   
    public function orderBy($orderBy)
    {
        $this->orderBy = $orderBy;
        return $this;
    }
    
    public function limit($limitFrom, $limitTo)
    {
        $this->setLimit = TRUE;
        $this->limitFrom = $limitFrom;
        $this->limitTo = $limitTo;
        return $this;
    }
    
    public function desc($desc)
    {
        $this->desc = $desc;
        return $this;
    }
    
    public function all()
    {
        $this->all = TRUE;
        return $this;
    }
    
    public function filter($args)
    {
        if ($this->all)
            return NULL; // Или посылаем исключение.
        
        $this->filter = array_merge($this->filter, $args);
        return $this;
    }
    
    public function exclude($args)
    {
        if ($this->all)
            return NULL; // Или посылаем исключение.
        
        $this->exclude = array_merge($this->exclude, $args);
        return $this;
    }
    
    public function count($a = "*")
    {
        if (!strcmp($this->select, "*"))
                $this->select = "COUNT($a)";
        else
            $this->select .= ", COUNT($a)";
        
        return Database::queryWithoutModel($this);
    }
    
    public function values($args)
    {
        $this->onlyValues = TRUE;
        $this->values = $args;
        return $this;
    }

    public function valuesList($args)
    {
        $this->onlyValues = TRUE;
        $this->list = TRUE;
        $this->values = $args;
        return $this;
    }
    
    public function distinct($args)
    {
        $this->distinct = $args;
        return $this;
    }
    
    public function getDisctinct()
    {
        return $this->distinct;
    }
    
    public function groupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }
    
    public function getGroupBy()
    {
        return $this->groupBy;
    }
    
    public function exists()
    {
        if (empty($this->objects))
            return FALSE;
        
        if (count($this->objects) == 0)
            return FALSE;
        
        return TRUE;
    }
    
    public function using($db)
    {
        $this->db = $db;
        return $this;
    }
    
    protected function run()
    {
        if ($this->onlyValues)
            $objects = Database::values ($this);
        else
            $objects = Database::query($this);
        
        $this->objects = $objects;
        $this->isProcessed = TRUE;
    }
    
    public function getObjects()
    {
        if ($this->isProcessed == FALSE)
            $this->run();
        
        return $this->objects;
    }
    
    public function isProcessed() { return $this->isProcessed; }
}

?>
