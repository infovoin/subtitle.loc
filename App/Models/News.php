<?php
namespace App\Models;

use App\Classes\Model;
use App\Exceptions\MultiException;
use App\Traits\TIterator;

/**
 * @property $id
 * @property $title
 * @property $text
 * @property $author
 */
class News extends Model implements \Iterator
{
    use TIterator;

    static $table = 'articles';

    function __get($name)
    {
        switch ($name) {
            case 'author';
                if (!empty($this->author_id)) {
                    return Author::findById($this->author_id);
                }
                break;
        }
        return $this->data[$name];
    }

    function __isset($name)
    {
        switch ($name) {
            case 'author';
                return !empty($this->author_id);
                break;
        }
        return isset($this->data[$name]);
    }

    public function fill(array $data)
    {
        $e = new MultiException();

        if (empty($data['title'])) {
            $e[] = new \Exception('заголовок пуст');
        }
        if (empty($data['text'])) {
            $e[] = new \Exception('поле текст пустое');
        }
        throw $e;
    }
}