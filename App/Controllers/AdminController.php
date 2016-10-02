<?php

namespace App\Controllers;

use App\Classes\Controller;
use App\Classes\Logs;
use App\Classes\View;
use App\Exceptions\MultiException;
use App\Models\News;

class AdminController extends Controller
{

    function actionAddNews()
    {

        if ($_SERVER['REQUEST_METHOD'] == "POST" && !empty($_POST['title']) && !empty($_POST['text'])) {
            $news = new News();
            $news->title = $_POST['title'];
            $news->text = $_POST['text'];
            $news->save();
        }

        $this->view->display('admin/addNews.php');
    }

    function actionCreate()
    {
        try {
            $article = new News();
            $article->fill($_POST);
            $article->save();
        } catch (MultiException $e) {
            $this->view->errors = $e;
        }
        $this->view->display('admin/create.php');
    }

    function actionShowLog()
    {
        $log = new Logs();
        $this->view->log = $log->read();
        $this->view->display('admin/logs.php');
    }
}






