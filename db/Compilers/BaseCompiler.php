<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once "/../Database.php";

/**
 * Description of Compiler
 *
 * @author Борис
 */

abstract class BaseCompiler {
    abstract public function createTable($scheme);
    abstract public function deleteTable($name);
}


?>
