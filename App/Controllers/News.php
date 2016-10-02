<?php

namespace App\Controllers;
use App\Classes\Controller;
use App\Classes\View;
use App\Exceptions\E404Ecxeption;
use App\Models\News as NewsModel;

class News extends Controller
{

    public function actionAll()
    {
        $news = NewsModel::findAll();
        $this->view->items = $news;
        $this->view->display('news/all.php');
    }

    public function actionOne()
    {
        $news = NewsModel::findById((int)$_GET['id']);
        if (empty($news)) {
            throw new E404Ecxeption('Новость с id ' . $_GET['id'] . ' не найдена');
        }

        $this->view->news = $news;
        $this->view->display('news/one.php');
    }

    public function actionAllGenerator(){
        $news = NewsModel::findAllyield();
        $this->view->items = $news;
        $this->view->display('news/allgenerator.php');
    }
}