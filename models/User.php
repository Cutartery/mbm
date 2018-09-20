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
            $_SESSION['money'] = $user['money'];
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }

    public function addMoney($money,$userId)
    {   

        $stmt = self::$pdo->prepare("UPDATE users SET money=money+? WHERE id=?");
         $cc = $stmt->execute([
            $money,
            $userId
        ]);
        $_SESSION['money'] += $money;
        return $cc;

    }

    //获取余额
    public function getMoney()
    {
        $id = $_SESSION['id'];
        //查询数据库
        $stmt = self::$pdo->prepare("SELECT money FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $money = $stmt->fetch(PDO::FETCH_COLUMN);
        echo $money;
        //更新到session中
        $_SESSION['money'] = $money;
        return $money;
    }

}