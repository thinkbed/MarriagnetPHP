<?php

class View
{
    protected $variables = array();
    protected $_controller;
    protected $_action;

    function __construct($controller, $action)
    {
        $this->_controller = strtolower($controller);
        $this->_action = strtolower($action);
    }

    public function assign($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function render()
    {
        extract($this->variables);
        $controllerLayout = APP_PATH . 'application/views/' . $this->_controller . '/' . $this->_action . '.php';

        if (file_exists($controllerLayout)) {
            include ($controllerLayout);
        } else {
            echo "<h1>无法找到视图文件</h1>";
        }

    }
}
