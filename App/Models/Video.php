<?php


namespace App\Models;


use App\Classes\DB;
use App\Classes\Model;

class Video extends Model
{

    static $table = 'video';

    public function fillOneField(array $data)
    {
        $field = $data['field'];
        $value = $data['value'];
        $this->$field = $value;
    }


    public static function ajaxSubtitlesLoad($id)
    {
        DB::setClassName(get_called_class());
        $sql = "SELECT * FROM " . static::$table . " WHERE `id` = :id";
        $result = DB::query($sql, [':id' => $id]);

        if (!empty($result)) {
            return ['video_name' => $result[0]->video_name,
                'path_to_file' => $result[0]->path_to_file,
                'ready_eng_sub' => $result[0]->ready_eng_sub,
                'bookmark' => $result[0]->bookmark];
        } else {
            echo false;
        }
    }
}