<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Controller
 *
 * @author Борис
 */
class Controller {
    protected $model;
    protected $view;
    
    function __construct() {
        $this->view = new View();
    }
    
    public function index() { }
}

?>
