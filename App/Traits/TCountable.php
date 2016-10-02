<?php


namespace App\Traits;


trait TCountable
{
    protected $data = [];

    public function count()
    {
        return count($this->data);
    }
}