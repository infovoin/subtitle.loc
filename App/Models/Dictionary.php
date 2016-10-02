<?php


namespace App\Models;


use App\Classes\DB;
use App\Classes\Model;

class Dictionary extends Model
{
    static $table = 'my_dictionary';

    /*public $id;
    public $video_id;
    public $subtitle_id;
    public $sentence;
    public $word;
    public $translate;;*/

    public function fill(array $data)
    {
        $this->video_id = $data['id'];
        $this->subtitle_id = $data['subtitle_id'];
        $this->sentence = $data['sentence'];
        $this->word = $data['word'];
        $this->translate = $data['translate'];
    }

    public static function countWord()
    {
        $dictionary_info = new Dictionary();

        //Запрос достающий количество слов, и идеально если будет число вообще слов и текущих на повторение
        DB::setClassName(get_called_class());

        $sql = "SELECT video_id, COUNT(*) AS count FROM " . static::$table . " WHERE `when_repeat` < current_timestamp GROUP BY video_id";
        $result = DB::queryGetArray($sql, []);

        $it_is_time_repeat = [];
        foreach ($result as $value) {
            $it_is_time_repeat[$value['video_id']] = $value['count'];
        }
        $dictionary_info->it_is_time_repeat = $it_is_time_repeat;


        $sql = "SELECT video_id, COUNT(*) AS count FROM " . static::$table . " GROUP BY video_id";
        $result = DB::queryGetArray($sql, []);

        $total_word = [];
        foreach ($result as $value) {
            $total_word[$value['video_id']] = $value['count'];
        }
        $dictionary_info->total_word = $total_word;

        return $dictionary_info;
    }

    public static function getWordsToRepeat($video_id)
    {
        //Запрос достающий количество слов, и идеально если будет число вообще слов и текущих на повторение
        DB::setClassName(get_called_class());

        $sql = "SELECT * FROM " . static::$table . " WHERE video_id = :video_id AND `when_repeat` < current_timestamp ORDER BY subtitle_id";
        $result = DB::query($sql, [':video_id' => $video_id]);
        $arrayWords = [];
        foreach ($result as $word) {
            static $i = 0;
            $arrayWords[$i] = [
                'id' => $word->id,
                'video_id' => $word->video_id,
                'subtitle_id' => $word->subtitle_id,
                'sentence' => $word->sentence,
                'word' => $word->word,
                'translate' => $word->translate,
                'when_repeat' => $word->when_repeat,
            ];
            $i++;
        }
        return $arrayWords;
    }
    
    
    public function updateNextTime(){
        
    }
}