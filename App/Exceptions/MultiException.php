<?php


namespace App\Exceptions;


use App\Traits\TArrayAccess;
use App\Traits\TCountable;
use App\Traits\TIterator;

class MultiException extends \Exception
    implements \ArrayAccess, \Iterator, \Countable
{
    use TArrayAccess;
    use TIterator;
    use TCountable;
    
}