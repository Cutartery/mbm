<?php
namespace controllers;

use models\User;

class UserController
{
    public function hello()
    {
        $user = new User;
        // var_dump($user);
        $name = $user->getName();
        return view('user.hello',[
            'name'=>$name
        ]);
    }
    public function register()
    {
        view("user.add");
    }
    public function store()
    {
        $email = $_POST['email'];
        $password = md5($_POST['password']);
        $user = new User;
        $ret = $user->add($email,$password);
        if(!$ret)
        {
            die('注册失败！');
        }
        $mail = \libs\Mail;

    }
}