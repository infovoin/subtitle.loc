<?php

namespace App\Classes;

use App\Traits\TIterator;
use App\Traits\TSetGetIsset;


class View implements \Iterator, \Countable
{
    use TIterator;
    use TSetGetIsset;
    
    const PATH_VIEW = __DIR__ . '/../Views/';
    
    function render($file)
    {
        
        foreach ($this->data as $name => $value) {
            $$name = $value;
        }
        ob_start();
        include self::PATH_VIEW . $file;
        $content = ob_get_clean();
        return $content;
    }

    function display($file)
    {
        echo $this->render($file);
    }

    public function count()
    {
        // TODO: Implement count() method.
        return count($this->data);
    }
}