<?php

function my_autoload($className)
{
    require __DIR__ . DIRECTORY_SEPARATOR .str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
}

spl_autoload_register('my_autoload');


include __DIR__ . "/vendor/autoload.php";