<?php

require_once "/../../Database.php";

define("UNKNOWN_FIELD", "");
define("INTEGER_FIELD", "int");
define("FLOAT_FIELD", "float");
define("BOOLEAN_FIELD", "tinyint");
define("STRING_FIELD", "varchar");
define("TEXT_FIELD", "text");

class Field
{   
    protected $name;
    protected $value;
    protected $autoIncrement;
    protected $primaryKey;
    protected $unique;
    protected $notNull;
    protected $type;
    protected $length;
    protected $defaultValue;
    protected $unsigned;
    protected $serial;
    
    function __construct($name, $length = NULL, $notNull = TRUE, $defaultValue = NULL, $unsigned = FALSE, $primaryKey = FALSE, $unique = FALSE, $autoIncrement = FALSE) {
        $this->name = $name;
        $this->unsigned = $unsigned;
        $this->defaultValue = $defaultValue;
        $this->autoIncrement = $autoIncrement;
        $this->primaryKey = $primaryKey;
        $this->unique = $unique;
        $this->notNull = $notNull;
        $this->length = $length;
        $this->type = UNKNOWN_FIELD;
    }
    
    public function isSerial() { return $this->serial; }
    public function isUnsigned() { return $this->unsigned; }
    public function getValue() { return $this->value; }
    public function setValue($value) 
    { 
        if (self::checkValue($this->type, $value))
                  $this->value = $value;   
        else
            throw new FieldFormatException("Вводимое значение должно принодлежать к тип $type!");
    }
    
    public function getDefaultValue() { return $this->defaultValue; }
    public function getType() { return $this->type; }
    public function getLength() { return $this->length; }
    public function getName() { return $this->name; }
    
    public function isAutoIncrement() { return $this->autoIncrement; }
    public function isPrimaryKey() { return $this->primaryKey; }
    public function isUnique() { return $this->unique; }
    public function isNotNull() { return $this->notNull; }
    public function isField() { return true; }
    
    public static function checkValue($type, $value)
    {
        return TRUE;
    }
}

class IntegerField extends Field
{   
    function __construct($name, $length = NULL, $notNull = FALSE, $defaultValue = NULL,  $unsigned = FALSE, $primaryKey = FALSE, $unique = FALSE, $autoIncrement = FALSE) {
        parent::__construct($name, $length, $notNull, $defaultValue, $unsigned, $primaryKey, $unique, $autoIncrement);
        
        $this->type = INTEGER_FIELD;
    }
    
    public function getValue()
    {
        if ($this->value)
            return intval($this->value);
        else
            return NULL;
    }
    
    public function setValue($value)
    {
        $this->value = intval($value);
    }
}

class SerialField extends IntegerField
{
    function __construct($name) {
        parent::__construct($name, 0, TRUE, 0, TRUE, TRUE, FALSE, TRUE);
        $this->serial = TRUE;
    }
    
    function setValue($value) {
        $this->value = $value;
    }
}

class TextField extends Field
{
    function __construct($name, $notNull = TRUE, $defaultValue = NULL, $unsigned = FALSE, $primaryKey = FALSE, $unique = FALSE, $autoIncrement = FALSE) {
        parent::__construct($name, NULL, $notNull, $defaultValue, $unsigned, $primaryKey, $unique, $autoIncrement);
        $this->type = TEXT_FIELD;
    }
    
}

class BooleanField extends Field
{
    function __construct($name, $length = NULL, $notNull = TRUE, $defaultValue = NULL, $unsigned = FALSE, $primaryKey = FALSE, $unique = FALSE, $autoIncrement = FALSE) {
        parent::__construct($name, $length, $notNull, $defaultValue, $unsigned, $primaryKey, $unique, $autoIncrement);
        $this->length = 1;
        $this->type = BOOLEAN_FIELD;
    }
    
    function getValue()
    {
        if ($this->value)
            return (bool)$this->value;
        else
            return NULL;
    }
    
    function setValue($value) {
        $this->value = (bool)$value;
    }
}

class FloatField extends Field
{
    function __construct($name, $length = NULL, $notNull = FALSE, $defaultValue = NULL,  $unsigned = FALSE, $primaryKey = FALSE, $unique = FALSE, $autoIncrement = FALSE) {
        parent::__construct($name, $length, $notNull, $unsigned, $defaultValue, $primaryKey, $unique, $autoIncrement);
        
        $this->type = FLOAT_FIELD;
    }
    
    function getValue()
    {
        if ($this->value)
            return floatval($this->value);
        else
            return NULL;
    }
   
    public function setValue($value)
    {
        $this->value = floatval($value);
    }
}

class StringField extends Field
{
    function __construct($name, $length = NULL, $notNull = FALSE, $defaultValue = NULL, $primaryKey = FALSE, $unique = FALSE, $autoIncrement = FALSE) {
        parent::__construct($name, $length, $notNull, $defaultValue, FALSE, $primaryKey, $unique, $autoIncrement);
        
        $this->type = STRING_FIELD;
    }
    
    function getValue()
    {
        
        if ($this->value)
            return strval ($this->value);
        else
            return NULL;
    }
    
    public function setValue($value)
    {
        $this->value = strval($value);
    }
}

class BigIntegerField { }
class CharField { }
class CommaSeparatedIntegerField { }
class DateField { }
class DateTimeField { }
class DecimalField { }
class EmailField { }
class FileField { }
class FilePathField { }
class ImageField { }
class IPAddressField { }
class GenericIPAddressField { }
class NullBooleanField { }
class PositiveSmallIntegerField { }
class SlugField { }
class SmallIntegerField { }
class URLField { }

class ForeignKey {}
class ManyToManyField {}
class OneToOneField { }

class BaseFieldException extends Exception { }
class FieldFormatException extends Exception { }
class SerialFieldException extends Exception { }
?>
