<?php

namespace App\Traits;

trait TIterator
{
    protected $data = [];
    
    //возвращает текущий элемент
    public function current()
    {
        return current($this->data);
    }

    //перемещает указатель на следующий элемент
    public function next()
    {
        next($this->data);
    }

    //возвращает индекс текущего элемента
    public function key()
    {
        return key($this->data);
    }

    //проверяет, существует ли текущий элемент или нет
    public function valid()
    {
        return isset($this->data[$this->key()]);
    }

    //переводит указатель текущего элемента на первый
    public function rewind()
    {
        reset($this->data);
    }
}