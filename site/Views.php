<?php

require_once '/../iDoopi.php';

class MainPageView
{
    public static function mainpage()
    {
        $menuItems = MenuItems::query()->all();
        require  'templates/mainpage.template.html';
    }
    
    public static function soon()
    {
        $dir = dirname($_SERVER['SCRIPT_NAME']);
        require 'templates/soon.template.html';
    }
}

?>
