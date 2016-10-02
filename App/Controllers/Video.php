<?php


namespace App\Controllers;


use App\Classes\Controller;
use App\Exceptions\E404Ecxeption;
use App\Models\Dictionary;

//use App\Models\Video;

class Video extends Controller
{

    static $table = 'video';

    public function actionAll()
    {
        $videos = \App\Models\Video::findAll();
        $this->view->videos = $videos;
        
        $dictionary_info = Dictionary::countWord();
        $this->view->dictionary_info = $dictionary_info;
        
        $this->view->display('video/all.php');
    }

    public function actionlearning()
    {
        if (!empty($_GET['id'])) {

            $video = \App\Models\Video::findById($_GET['id']);
            if ($video === false) {
                throw new E404Ecxeption('Такой страницы нету');
            }
            $this->view->display('video/learning.php');
        }
    }

    public function actionLoadSubtitles(){
        if (!empty($_GET['id'])) {
           $video = \App\Models\Video::ajaxSubtitlesLoad($_GET['id']);
            echo json_encode($video);
        }
    }

    /**
     * Либо открывает на редактирование уже существующий фильм, либо добавляет новый.
     */
    public function actionAdd()
    {
        if (!empty($_GET['id'])) {
            $video = \App\Models\Video::findById($_GET['id']);
            if ($video === false) {
                throw new E404Ecxeption('Такой страницы нету');
            }
        } else {
            $video = new \App\Models\Video();
            $video->save();
            header('Location: ' . $_SERVER['REQUEST_URI'] . '?id=' . $video->id);
        }
        $this->view->display('video/add.php');
    }

    public function actionEdit(){
        if (!empty($_GET['id'])) {
            $video = \App\Models\Video::findById($_GET['id']);
            if ($video === false) {
                throw new E404Ecxeption('Такой страницы нету');
            }
            $this->view->video = $video;
            $this->view->display('video/edit.php');
        }
    }

    /**
     * Этот метод вызывается AJAX запросом из FrontEnd части
     * Задача этого метода, добавлять новое видео, если оно уже существует то обновить все параметры старое.
     */
    public function actionSave()
    {
        if (!empty($_GET['id'])) {
            $video = \App\Models\Video::findById($_GET['id']);
            $video->fillOneField($_POST);
            $video->save();
        }
    }

    public function actionAddBookmark()
    {
        if (!empty($_GET['id'])) {
            $video = \App\Models\Video::findById($_GET['id']);
            $video->bookmark = $_POST['bookmark'];
            $video->save();
        }
    }
    
    public function actionDelete(){
        if (!empty($_GET['id'])) {
            echo $_GET['id'];
            $video = \App\Models\Video::findById($_GET['id']);
            $video->delete();
        }
    }

}