<?php

include_once  "/../Database.php";
/**
 * Description of Model
 *
 * @author Борис
 */
class Model { 
    protected static $tableName;    // Имя таблицы.
    protected $loaded;  // Проверяем, погружены ли в таблицу данные.
    public $mid;
    protected $serialName;
    protected $getLatestBy;
    protected static $db;
    
    function __construct() {
        $this->mid = new SerialField("mid");
        $this->serialName = "mid";
    }
    
    public function getName() { return static::$tableName; }
    
    public static function setDB($db) { static::$db = $db; }
    public static function getDB() { return static::$db; }
    
    public static function getTableName() { return static::$tableName; }
    protected static function setTableName($name) { static::$tableName = $name; }
    
    public function getMID() { return $this->mid; }
    
    public static function query()
    {
        return new Query(get_called_class(), self::getTableName());
    }
    
    protected function customSerial()
    {
        if (isset($this->mid))
            unset($this->mid);
    }
    
    public function customSerialWithName($name)
    {
        $this->customSerial();
        $this->$name = new SerialField($name);
        $this->serialName = $name;
    }
    
    public static function latest($field = NULL)
    {
        if ($field == NULL)
            if (!empty ($this->getLatestBy))
                $field = $this->getLatestBy;
            else
                throw new ModelInvalidArgumentsException();
        
        $r = Database::getLatestBy(get_called_class(), self::getTableName(), $field);
        
        if ($r == NULL)
            throw new ObjectDoesNotExistException();
        
        return $r;
    }
    
    //
    // Получаем объект по указанным аргументам.
    //
    public static function get($args)
    {
        $r = Database::getObject(get_called_class(), self::getTableName(), $args);
        
        if ($r == NULL)
            throw new ObjectDoesNotExistException();
        
        if (is_array($r))
            throw new MultipleObjectsReturnedException();
        
        return $r;
    }
    
    //
    // Создает новую модель в базе данных.
    //
    public function create()
    {
        $id = Database::createModel($this);
        
        if ($id)
        {
            $serialName = $this->serialName;
            $this->$serialName->setValue(intval($id));
            return true;
        }
        else
            return false;
    }
    
    //
    // Сохраняет данные в уже существующей базе данных.
    //
    public function save()
    {
        if (!$this->mid)
            return false;
        
        $r = Database::saveModel($this);
        
        if ($r)
            return true;
        else
            return false;
    }
    
    //
    // Удаляет уже существующий объект из базы данных.
    //
    public function delete()
    {
        if (!$this->mid)
            return false;
        
        $r = Database::deleteModel($this);
        
        if ($r)
        {
            $serialName = $this->serialName;
            $this->$serialName->setValue(NULL);
        }
    }
}

class BaseModelException extends Exception { }

class ObjectDoesNotExistException extends BaseModelException { }
class  MultipleObjectsReturnedException extends BaseModelException { }
class ModelInvalidArgumentsException extends BaseModelException {}
?>
