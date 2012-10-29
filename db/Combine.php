<?php

include_once 'Database.php';

/**
 * Description of Combine
 *
 * @author Борис
 */
class Combine {
    protected $compiler;


    function __construct() {
        $this->compiler = new Compiler();
    }
    
    
    public function buildQuery($query)
    {
        $sql = $this->compiler->queryBuild($query);
        
        if ($query->getDB() != NULL)
            $connection = new Connection($query->getDB());
        else
            $connection = new Connection();
        
        $fields = array_merge($query->getFilter(), $query->getExclude());
        $objects = $connection->queryObjects($sql, $fields);
        $objects = Scheme::makeModelFromObjects($query->getClassName(), $objects);
        $connection->closeConnection();
        return $objects;
    }
    
    public function buildQueryWithoutSceme($query)
    {
        $sql = $this->compiler->queryBuild($query);
        
        if ($query->getDB() != NULL)
            $connection = new Connection($query->getDB());
        else
            $connection = new Connection();
        
        $fields = array_merge($query->getFilter(), $query->getExclude());
        $result = $connection->queryObjects($sql, $fields);
        $connection->closeConnection();
        $r = $result[0];
        
 
        if (is_array($r))
        {
            if (count($r) / 2 == 1)
                return $r[0];
            else if (count($r)  == 0)
                return NULL;
            else
                return $r;
        }
        else
            return $r;
    }
    
    public function getValues($query)
    {
        $sql = $this->compiler->queryBuild($query);
        
        if ($query->getDB() != NULL)
            $connection = new Connection($query->getDB());
        else
            $connection = new Connection();
        
        $objects = $connection->queryObjects($sql);
        $connection->closeConnection();
        
        return $objects;
    }
    
    public function getByLatest($className, $modelName, $field)
    {
        $sql = $this->compiler->selectByLatest($modelName, $field);
        
        if ($className::getDB() != NULL)
            $connection = new Connection($className::getDB());
        else
            $connection = new Connection();
        
        $objects = $connection->queryObjects($sql);
        $connection->closeConnection();
        $objects = Scheme::makeModelFromObjects($className, $objects);
        
        if (count($objects) == 0)
            return NULL;
        else
            return $objects[0];
    }
    
    public function getModel($className, $modelName, $args)
    {
        $sql = $this->compiler->select($modelName, $args);
        
        if ($className::getDB() != NULL)
            $connection = new Connection($className::getDB());
        else
            $connection = new Connection();
        
        $object = $connection->queryObjects($sql, $args);
        $connection->closeConnection();
       
        $objects = Scheme::makeModelFromObjects($className, $object);
        
        if (count($objects) == 1)
            return $objects[0];
        else if (count($objects) == 0)
            return NULL;
        else
            return $objects;
    }
    
    public function saveModel($model)
    {
        $scheme = Scheme::makeSchemaFromModel($model);
        $sql = $this->compiler->update($scheme);
        
        if ($model->getDB() != NULL)
            $connection = new Connection($model->getDB());
        else
            $conection = new Connection();
        
        $r = $conection->queryWithFields($sql, $scheme->getFields());
        $conection->closeConnection();
        return $r;
    }
    
    public function deleteModel($model)
    {
        $scheme = Scheme::makeSchemaFromModel($model);
        $sql = $this->compiler->delete($scheme);
        
        if ($model->getDB() != NULL)
            $connection = new Connection($model->getDB());
        else
            $conection = new Connection();
        
        $r = $connection->queryWithField($sql, $scheme->getSerial());
        $connection->closeConnection();
        return $r;
    }
    
    public function createModel($model)
    {
        $scheme = Scheme::makeSchemaFromModel($model);
        $sql = $this->compiler->insert($scheme);
      
        if ($model->getDB() != NULL)
            $connection = new Connection($model->getDB());
        else
            $connection = new Connection();
        
        $r = $connection->queryWithFields($sql, $scheme->getFieldsWithoutSerial(), TRUE);
        $connection->closeConnection();
        return $r;
    }
    
    public function createTable($model)
    {
        $sql = $this->compiler->createTable(Scheme::makeSchemaFromModel($model));
        
        if ($model->getDB() != NULL)
            $connection = new Connection($model->getDB());
        else
            $connection = new Connection();
        
        $r = $connection->query($sql);
        $connection->closeConnection();
        return $r;
    }
    
    public function deleteTable($name, $db = NULL)
    {
        $sql = $this->compiler->deleteTable($name);
        
        if ($db != NULL)
            $connection = new Connection($db);
        else
            $connection = new Connection();
        
        $r = $connection->query($sql);
        $connection->closeConnection();
        
        return $r;
    }
}

?>
