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

    public function login($email,$password)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM users WHERE email=? AND password=?");
        $stmt->execute([
            $email,
            $password
        ]);
        $user = $stmt->fetch();
        if($user)
        {
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['money'] = $user['money'];
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}