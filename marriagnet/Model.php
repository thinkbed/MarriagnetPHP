<?php

class Model extends Sql
{
    protected $_model;
    protected $_table;
    public static $dbConfig = [];

    public function __construct()
    {
        if(!$this->_table)
        {
            $this->_model = get_class($this);
            $this->_model = substr($this->_model, 0, -5);

            $this->_table = strtolower($this->_model);
        }
    }
}
