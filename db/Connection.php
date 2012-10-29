<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once  'Database.php';

/**
 * Description of Connection
 *
 * @author Борис
 */
class Connection extends BaseConnection {
    protected $connection;
    protected $connected;
    
    function __construct($name = "default") {
        $this->connection = $this->initialConnection($name);
        
        if ($this->connection)
            $this->connected = TRUE;
    }
    
    protected function initialConnection($name)
    {
        global $databases;
        if (!isset($databases[$name]))
        {
            //
            // Шлем исключение.
            //
        }
        else
        {
            $db = NULL;
            $dbInfo = $databases[$name];
            $type = $dbInfo['type'];
            $host = $dbInfo['host'];
            $port = $dbInfo['port'];
            $user = $dbInfo['user'];
            $password = $dbInfo['password'];
            $name = $dbInfo['dbname'];
            $encoding = $dbInfo['encoding'];
            
            if (isset($port))
                $host .= ":" . $port;
            
            try
            {
                if ($type != "sqllite")
                    $db = new PDO("$type:host=$host;dbname=$name", $user, $password);
                else
                {
                    $path = $databases['path'];
                    $db = new PDO("$type:$path");
                }
                
                if ($encoding)
                {
                    $db->exec("SET NAMES '$encoding'");
                }
            }
            catch (PDOException $e)
            {
                // 
                // Обработка ошибок.
                //
                echo $e->getMessage();
            }
            
            return $db;
        }
    }
    
    public function closeConnection() {
        $this->connection = NULL;
        $this->connected = FALSE;
    }
    
    public function getConnection() {
        parent::getConnection();
    }
    
    public function queryWithField($sql, $field, $returnID = FALSE)
    {
        if (!$this->connected)
            return false;
        
        $pr = $this->connection->prepare($sql);
        $name = $field->getName();
        $value = $field->getValue();
        
        if ($field->isNotNull() && !$value)
        {
            $value = $field->getDefaultValue();

            if ($value)
            {
                //
                // Шлем исключение.
                //

                return false;
            }
        }
        
        $pr->bindParam(":$name", $value);
       
        try
        {
        $r = $pr->execute();
        
        if ($returnID)
            return $this->connection->lastInsertId();
        else
            return $r;
        }
        catch (PDOException $e) 
        {
            return false;
        }
    }
    
    public function queryObjects($sql, $fields = NULL)
    {
        if (!$this->connected)
            return false;
       
        $pr = NULL;
        if ($fields == NULL)
        {
            $pr = $this->connection->prepare($sql);
        }
        else
        {
            $pr = $this->connection->prepare($sql);
            
            foreach ($fields as $key => $value)
            {
                $pr->bindValue(":$key", $value);
            }
        }
        
        if ($pr->execute() == FALSE)
            return NULL;
        else 
            return $pr->fetchAll();
    }
    
    public function queryWithFields($sql, $fields, $returnID = FALSE)
    {
        if (!$this->connected)
            return false;
        
        $pr = $this->connection->prepare($sql);
        
        foreach ($fields as $f)
        {
            $name = $f->getName();
            $value = $f->getValue();
            if ($f->isNotNull() && !$value)
            {
                $value = $f->getDefaultValue();
                
                if ($value)
                {
                    //
                    // Шлем исключение.
                    //
                    
                    return false;
                }
            }
          
            $pr->bindValue(":$name", $value);
        }

        $r = $pr->execute();
 
        if ($returnID)
            return $this->connection->lastInsertId();
        else
            return $r;
       
    }
    
    public function query($sql) {
        if (!$this->connected)
            return false;

        $pr = $this->connection->prepare($sql);
       
        if ($pr->execute())
            return true;
        else
            return false;
    }
}

?>
