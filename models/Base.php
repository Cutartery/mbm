<?php
namespace models;

use PDO;

class Base{

    public static $pdo = null;

    public function __construct()
    {
        if(self::$pdo === null)
        {
            self::$pdo = new PDO('mysql:host=127.0.0.1;dbname=basic_module','root','123456');
            self::$pdo->exec('SET NAMES utf8');
        }
    }

}