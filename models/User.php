<?php
namespace models;

use PDO;

class User extends Base
{  
    public $tableName = "users";

    // public static $pdo;

    public function add($email,$password)
    {
        $stmt = self::$pdo->prepare("INSERT INTO users(email,password) VALUES(?,?)");
        return $stmt->execute([
            $email,
            $password,
        ]);
    }

    public function login($email,$password)
    {
        $stmt = self::$pdo->prepare('SELECT * FROM users WHERE email=? AND password=?');
        $stmt->execute([
            $email,
            $password
        ]);
        
        $user = $stmt->fetch();
        if($user)
        {
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            // $_SESSION['money'] = $user['money'];
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

}