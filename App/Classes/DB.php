<?php

namespace App\Classes;

use App\Exceptions\EDb;
use App\Exceptions\E404Ecxeption;
use PDO;
use PDOException;

class DB
{
    static private $DBH = null;
    static protected $className = 'stdClass';

    function __construct()
    {
    }

    function __clone()
    {
    }

    public static function getInstanceDBH()
    {

        if (self::$DBH === null) {
            try {
                $config = Config::getInstance()->data['MySQL'];
                self::$DBH = new PDO("{$config['dsn']}:host={$config['host']};dbname={$config['dbname']}", $config['user'], $config['password']);
            } catch (PDOException $e) {
                throw new E404Ecxeption('Нет подключения к базе данных');
            }
        }

        return self::$DBH;
    }

    public static function setClassName($class)
    {
        self::$className = $class;
    }

    public static function query($sql, $params = [])
    {
        $sth = self::getInstanceDBH()->prepare($sql);
        $res = $sth->execute($params);
        if (false === $res) {
            throw new EDb('Запрос выполнен неверное sql: ' . $sql . 'params: ' . implode(' , ', $params));
        }
        return $sth->fetchAll(PDO::FETCH_CLASS, self::$className);

    }

    public static function queryGetArray($sql, $params = [])
    {
        $sth = self::getInstanceDBH()->prepare($sql);
        $res = $sth->execute($params);
        if (false === $res) {
            throw new EDb('Запрос выполнен неверное sql: ' . $sql . 'params: ' . implode(' , ', $params));
        }
        return $sth->fetchAll(PDO::FETCH_ASSOC);

    }

    public static function queryEach($sql, $params = [])
    {
        $sth = self::getInstanceDBH()->prepare($sql);
        $res = $sth->execute($params);
        if (false === $res) {
            throw new EDb('Запрос выполнен неверное sql: ' . $sql . 'params: ' . implode(' , ', $params));
        }

        return function () use ($sth) {
            $sth->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, self::$className);
            while ($next = $sth->fetch()) {
                yield $next;
            }
        };
    }


    public static function exec($sql, $params = [])
    {
        
        $sth = self::getInstanceDBH()->prepare($sql);
        $res = $sth->execute($params);
        if (false === $res) {
            throw new EDb('Запрос выполнен неверное sql: ' . $sql . 'params: ' . implode(' , ', $params));
        }
        return $res;
    }
}
