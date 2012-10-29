<?php

require_once '/../iDoopi.php';

/**
 * Description of Route
 *
 * @author Борис
 */
class Route {
    private function __construct() {
        ;
    }
    
    private function __clone() {
        ;
    }
    
    public static function run()
    {   
        $url = $_SERVER['REQUEST_URI'];
      
        global $urlpatterns;
        foreach ($urlpatterns as $key => $value)
        {
            if (preg_match($key, $url))   
            {
                preg_match_all($key, $url, $res);
                
                $obj = new HttpParams();

                foreach ($res as $k => $v)
                {
                    //echo "$k <br />";
                    if (is_numeric($k))
                        continue;
                  
                    $obj->$k = $res[$k][0];
                }
                
                call_user_func($value, $obj);
                break;
            }
        }
    }
}

?>
