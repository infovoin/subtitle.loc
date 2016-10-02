<?php


namespace App\Controllers;


class Dictionary
{
    public function actionAdd()
    {
        $word = new \App\Models\Dictionary();
        $word->fill(array_merge($_GET, $_POST));
            $word->save();
            echo true;

    }

    public function actionlearning()
    {
        $word = \App\Models\Dictionary::findById(15);
        var_dump($word);
    }


    public function actionLoadWords()
    {
        if (!empty($_GET['id'])) {
            $words = \App\Models\Dictionary::getWordsToRepeat($_GET['id']);
            echo json_encode($words);
        }
    }

    public function actionUpdateNextTime()
    {
        if (!empty($_GET['id'])) {
            $word = \App\Models\Dictionary::findById($_GET['id']);
            //Устанавливаем вреям на указанное количество дней.
            $word->when_repeat = date("Y-m-d H:i:s", time() + (3600 * 24 * $_POST['next_time']));
            $word->save();
        }
    }

    public function actionDelete()
    {
        if (!empty($_GET['id'])) {
            $word = \App\Models\Dictionary::findById($_GET['id']);
            $word->delete();
        }
    }


}