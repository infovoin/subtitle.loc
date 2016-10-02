<?php


namespace App\Traits;


trait TArrayAccess
{

    protected $data = [];
    
    //проверяем есть ли в моём объекте эллемент с заданным ключом
    public function offsetExists($offset)
    {
        return array_key_exists($this->data[$offset]);
    }

    //получение значения по ключу
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}