<?php

namespace App\Classes;
use App\Traits\TSetGetIsset;


abstract class Model
{
    use TSetGetIsset;

    static protected $table;

    public static function findAll()
    {
        DB::setClassName(get_called_class());
        $sql = "SELECT * FROM " . static::$table;
        return DB::query($sql);
    }

    public static function findAllyield(){
        DB::setClassName(get_called_class());
        $sql = "SELECT * FROM " . static::$table;
        return DB::queryEach($sql);
    }


    public static function findById($id)
    {
        DB::setClassName(get_called_class());
        $sql = "SELECT * FROM " . static::$table . " WHERE `id` = :id";
        $result = DB::query($sql, [':id' => $id]);
        if (!empty($result)) {
            return $result[0];
        } else {
            return false;
        }
    }
    
    
    public static function findByColumn($column, $value)
    {
        DB::setClassName(get_called_class());
        $sql = "SELECT * FROM " . static::$table . " WHERE $column = :value";
        return DB::query($sql, [':value' => $value]);
    }

    public function save()
    {
        if (isset($this->id)) {
            $this->update();
        } else {
            $this->insert();
        }

    }

    public static function findLastOnes(int $quantity)
    {
        DB::setClassName(get_called_class());
        DB::setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $sql = 'SELECT * FROM ' . static::TABLE . ' ORDER BY id DESC LIMIT :quantity';
        $result = DB::query($sql, ['quantity' => $quantity]);
        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }


    private function insert()
    {

        DB::getInstanceDBH()->beginTransaction();

        $params = [];
        foreach ($this->data as $name => $value) {
            $params[':' . $name] = $value;
        }

        $sql = 'INSERT INTO ' . static::$table . '
                    (' . implode(',', array_keys($this->data)) . ') VALUES
                    (' . implode(',', array_keys($params)) . ')';
        if (DB::exec($sql, $params)) {

            $this->data['id'] = DB::getInstanceDBH()->lastInsertId();

        }

        DB::getInstanceDBH()->commit();
    }

    private function update()
    {

        $set = [];
        $params = [];
        foreach ($this->data as $column => $value) {
            $params[':' . $column] = $value;

            if ('id' == $column) {
                continue;
            }
            $set[] = $column . '=:' . $column;

        }

        //SET column1=:value1,column2=:value2,...
        $sql = "UPDATE " . static::$table . ' SET ' . implode(' , ', $set) . ' WHERE id = :id';
        DB::exec($sql, $params);
    }

    public function delete()
    {
        $sql = "DELETE FROM " . static::$table . " WHERE id = :id";
        DB::exec($sql, [':id' => $this->data['id']]);
    }
}












