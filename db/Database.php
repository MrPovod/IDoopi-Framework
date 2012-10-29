<?php

require_once 'BaseConnection.php';
require_once 'Combine.php';
require_once 'Connection.php';
require_once 'Scheme.php';
require_once 'models/init.php';
require_once 'Compilers/init.php';
require_once '/../iDoopiSettings.php';
require_once 'Querys/Query.php';

/**
 * Description of Database
 *
 * @author Борис
 */
class Database {
     static public function initCombaine()
    {
        return new Combine();
    }
    
    static public function query($query)
    {
        $combaine = self::initCombaine();
        $r = $combaine->buildQuery($query);
        self::deleteCombaine($combaine);
        
        return $r;
    }
    
    static public function queryWithoutModel($query)
    {
        $combaine = self::initCombaine();
        $r = $combaine->buildQueryWithoutSceme($query);
        self::deleteCombaine($r);
        return $r;
    }
    
    static public function getLatestBy($className, $modelName, $field)
    {
        $combaine = self::initCombaine();
        $r = $combaine->getByLatest($className, $modelName, $field);
        self::deleteCombaine($combaine);
        return $r;
    }
    
    static public function values($query)
    {
        $combaine = self::initCombaine();
        $r = $combaine->getValues($query);
        self::deleteCombaine($combaine);
        return $r;
    }
    
    static public function getObject($className, $modelName, $args)
    {
        $combaine = self::initCombaine();
        $r = $combaine->getModel($className, $modelName, $args);
        self::deleteCombaine($combaine);
        return $r;
    }
    
    static public function saveTable($model)
    {
        $combaine = self::initCombaine();
        $r = $combaine->saveTable($model);
        self::deleteCombaine($combaine);
    }
    
    static public function deleteModel($model)
    {
        $combaine = self::initCombaine();
        $r = $combaine->deleteModel($model);
        self::deleteCombaine($combaine);
        return $r;
    }
    
    static public function createModel($model)
    {   
        $combaine = self::initCombaine();
        $r = $combaine->createModel($model);
        self::deleteCombaine($combaine);
        
        return $r;
    }
    
    static public function saveModel($model)
    {
        $combaine = self::initCombaine();
        $r = $combaine->saveModel($model);
        self::deleteCombaine($combaine);
        
        return $r;
    }
    
    static public function registerModel($model)
    {
        $combaine = self::initCombaine();
        $r = $combaine->createTable($model);
        self::deleteCombaine($combaine);
        
        return $r;
    }
    
    static public function unregisterModel($model)
    {
        $combaine = self::initCombaine();
        $r = $combaine->deleteTable($model->getName());
        self::deleteCombaine($combaine);
        
        return $r;
    }
    
    static public function deleteCombaine($combaine)
    {
        unset($combaine);
    }
}

?>
