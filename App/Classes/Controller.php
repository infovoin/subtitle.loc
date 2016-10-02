<?php


namespace App\Classes;


class Controller
{

    protected $view;

    public function __construct()
    {
        $this->view = new View();
    }
}