<?php
namespace controllers;

use PDO;
// use models\Blog;

class MockController
{

    public function users()
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=basic_module','root','123456');
        $pdo->exec("SET NAMES utf8");
        $pdo->exec("TRUNCATE users");

        for($i=0;$i<20;$i++)
        {
            $email = rand(50000,99999999999).'@126.com';
            $password = md5('123456');
            $pdo->exec("INSERT INTO users (email,password) VALUES ('$email','$password')");
        }
    }
    public function blog()
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=basic_module','root','123456');
        $pdo->exec("SET NAMES utf8");
        $pdo->exec("TRUNCATE blogs");

        for($i=0;$i<300;$i++)
        {
            $title = $this->getChar(rand(20,100));
            $content = $this->getChar(rand(100,600));
            $display = rand(10,500);
            $is_show = rand(0,1);
            $date = rand(1233333399,1462587952);
            $date = date("Y-m-d H:i:s",$date);
            $user_id = rand(1,20);
            // echo "INSERT INTO blogs (title,content,display,is_show,created_at,user_id) VALUES ('$title','$content','$display','$is_show','$date','$user_id')"; 
            // die;
            $pdo->exec("INSERT INTO blogs (title,content,display,is_show,created_at,user_id) VALUES ('$title','$content','$display','$is_show','$date','$user_id')");
            // echo 123;
        }
    }

    public function getChar($num)
    {
        $b='';
        for($i=0;$i<$num;$i++)
        {
            $a = chr(mt_rand(0xB0,0xD0)).chr(mt_rand(0xA1, 0xF0));
            $b .= iconv('GB2312', 'UTF-8', $a);
        }
        return $b;
    }

    
}