<?php


namespace App\Traits;


trait TSetGetIsset
{
    protected $data = [];

    function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    function __get($name)
    {
        return $this->data[$name];
    }

    function __isset($name)
    {
        return isset($this->data[$name]);
    }
}


