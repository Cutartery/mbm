<?php
namespace models;

use PDO;

class User 
{  
    public $tableName = "users";

    public $pdo;

    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=127.0.0.1;dbname=basic_module",'root','123456');
        $this->pdo->exec("SET NAMES utf8");
    }


    public function add($email,$password)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users(email,password) VALUES(?,?)");
        return $stmt->execute([
            $email,
            $password,
        ]);
    }

}