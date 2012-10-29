<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once 'Database.php';

/**
 * Description of BaseConnection
 *
 * @author Борис
 */
abstract class BaseConnection {
    abstract protected function initialConnection($name);
    abstract public function query($sql);
    abstract public function closeConnection();
    
    public function getConnection() { return $this->connection; }
}

?>
