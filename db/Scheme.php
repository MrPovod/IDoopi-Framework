<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'Database.php';

/**
 * Description of Schema
 *
 * @author Борис
 */
class Scheme {
    protected $name;
    protected $fields;
    protected $primaryKeys;
    protected $uniqueKeys;
    
    protected $havePrimaryKeys;
    protected $haveUniqueKeys;
    protected $serial;
    
    function __construct($name, $fields = NULL, $primaryKeys = NULL, $uniqueKeys = NULL) {
        $this->name = $name;
        $this->fields = $fields;
        
        $this->primaryKeys = $primaryKeys or array();
        $this->uniqueKeys = $uniqueKeys or array();
    }
    
    public function isHavePrimaryKeys() { if (count($this->primaryKeys) == 0) return false; else return true; }
    public function isHaveUniqueKeys()  { if (count($this->uniqueKeys) == 0) return false; else return true; }
    
    public function getName() { return $this->name; }
    public function getFields() { return $this->fields; }
    
    public function getFieldsWithoutSerial()
    {
        $name = $this->serial->getName();
        $fields = $this->fields;
        $fieldsWithourSerial = $fields;
        unset($fieldsWithourSerial[$name]);
        return $fieldsWithourSerial;
    }
    
    public function getSerial() { return $this->serial; }
    
    public function getPrimaryKeys()
    {
        return $this->primaryKeys;
    }
    
    public function getUniqueKeys()
    {
        return $this->uniqueKeys;
    }
    
    public function getUniqueKeysSQLType()
    {
        $sql = "";
        
        if (count($this->uniqueKeys) == 0)
            return $sql;
        
        
        $c = "UNIQUE(";
        
        foreach ($this->uniqueKeys as $name)
        {
            $sql .= "$c$name";
            $c = ",";
        }
        
        $sql .= ")";
        return $sql;
    }
    
    public function getPrimaryKeysSQLType()
    {
        $sql = "";
        
        if (count($this->primaryKeys) == 0)
            return $sql;
        
        $c = "PRIMARY KEY(";
        foreach ($this->primaryKeys as $name)
        {
            $sql .= "$c$name";
            $c = ",";
        }
        
        $sql .= ")";
        return $sql;
    }
    
    public function getNameFieldsSQL($withoutSerial = FALSE)
    {
        $sql = "";
        $c = "";
        
        foreach ($this->fields as $field)
        {
            if ($withoutSerial)
                if ($field->isSerial())
                    continue;
            
            $name = $field->getName();
            $sql .= "$c:$name";
            $c = ",";
        }
        
        return $sql;
    }
    
    public function getNoNameFieldsSQL($withoutSerial = FALSE)
    {
        $sql = "";
        $c = "";
        
        foreach ($this->fields as $field)
        {
            if ($withoutSerial)
                if ($field->isSerial())
                    continue;
                
            $sql .= "$c?";
            $c = ",";
        }
        
        return $sql;
    }
    
    public function getAllFieldsSQL($withoutSerial = FALSE)
    {
        $sql = "";
        $c = "";
        foreach ($this->fields as $field)
        {
            if ($withoutSerial)
                if ($field->isSerial())
                    continue;
                
            $name = $field->getName();
            $sql .= "$c $name";
            $c = ",";
        }
        
        return $sql;
    }
    
    public function getAllFieldsSQLType($withoutSerial = FALSE)
    {
        $sql = "";
        $c = "";
        
        foreach ($this->fields as $field)
        {
            if ($withoutSerial)
                if ($field->isSerial())
                    continue;
            
            $fieldSql = $this->getFieldSQLType($field);
            $sql .= "$c$fieldSql";
            $c = ",";
        }
        
        return $sql;
    }
    
    public function getFieldSQLType($field)
    {
        $name = $field->getName();
        $type = $field->getType();
        $length = $field->getLength();
        $needLength = TRUE;
        
       if ($type == INTEGER_FIELD || $type == FLOAT_FIELD && empty($length))
           $length = 11;
      
        $unsigned = $field->isUnsigned() ? " UNSIGNED " : "";
        $notNull = $field->isNotNull() ? " NOT NULL " : "";
        $defaultValue = $field->getDefaultValue();
        $default = $defaultValue ? " DEFAULT '$defaultValue' " : "";
        $autoIncrement = $field->isAutoIncrement() ? " AUTO_INCREMENT " : "";
        
        $sql = "$name $type";
        
        if (!empty($length))
            $sql .= "($length)";
        
        $sql .= "$unsigned$notNull$default$autoIncrement";
        return $sql;
    }
    
    static public function makeSchemaFromModel($model)
    {
        
        $reflect = new ReflectionClass($model);
        $public  = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        
        $fields = array();
        $primaryKeys = array();
        $uniqueKeys = array();
        
        $serial = NULL;
        foreach ($public as $o)
        {
            $name = $o->name;
            
            if (empty($model->$name))
                continue;
            
            if ($model->$name->isField())
            {
                $fields[$name] = $model->$name;
                
                if ($model->$name->isPrimaryKey())
                    $primaryKeys[$name] = $model->$name->getName();
                
                if ($model->$name->isUnique())
                    $uniqueKeys[$name] = $model->$name->getName();

                if ($model->$name->isSerial())
                {
                    if ($serial != NULL)
                    {
                        //
                        // Возвращаем NULL и бросаем исключение.
                        //
                        return NULL;
                    }
                    $serial = $model->$name;
                }
            }
        }
        
        $scheme = new Scheme($model->getName(), $fields, $primaryKeys, $uniqueKeys);
        $scheme->serial = $serial;
        return $scheme;
    }
    
    public static function makeModelFromObjects($className, $objects)
    {
        
        $returnObjects = array();
        
        if ($objects == NULL)
            return NUlL;
        
        foreach ($objects as $obj)
        {
            $model = new $className;
            
            foreach ($obj as $key => $value)
            {
                if (is_numeric($key))
                    continue;
                $model->$key->setValue($value);
            }
            
            $returnObjects[] = $model;
        }
        
        return $returnObjects;
    }
}

?>
