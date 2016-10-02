<?php

require __DIR__ . '/../../autoload.php';


//тестирую написанное получение "связанной модели" связь между таблицами articles и authors

/** @var $news News */$news = App\Models\News::findById(3);
echo $news->author->name;


/*$a = new \App\Classes\Collection();

$a[] = 1;
$a[11] = 1;
$a[8] = 4;

var_dump($a);

foreach ($a as $k => $v){
    echo $k.' '.$v;
}*/