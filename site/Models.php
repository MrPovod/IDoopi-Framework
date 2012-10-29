<?php

require_once '/../iDoopi.php';


class MenuItems extends Model
{
    protected static $tableName = "menuItems";
    public $name;
    public $url;
    public $lang;
    
    function __construct() {
        parent::__construct();
        
        $this->name = new StringField("name", 50, TRUE, "", FALSE, TRUE);
        $this->url = new StringField("url", 50, TRUE);
        $this->lang = new BooleanField("lang");
    }
}

?>
