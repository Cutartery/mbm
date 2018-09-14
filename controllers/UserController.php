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

        $code = md5(rand(1,99999));

        $redis = \libs\Redis::getInstance();

        $value = json_encode([
            'email'=>$email,
            'password'=>$password
        ]);

        $key = "temp_user:{$code}";
        $redis->setex($key,300,$value);
        
        $name = explode('@',$email);
        $from = [$email,$name[0]];

        $message = [
            'title'=>'智聊系統-账号激活',
            'content'=>"点击以下链接进行激活：<br>点击激活：<a href='http://localhost:8888/user/active_user?code={$code}'>
            http://localhost:8888/user/active_user?code={$code}</a><p>
            如果按钮不能点击，请复制上面链接地址，在浏览器中访问来激活账号！</p>。",
            'from' => $from,
        ];
        $message = json_encode($message);

        $redis = \libs\Redis::getInstance();

        $redis->lpush('email',$message);
        echo 'OjbK';
    }


    public function login()
    {
        view('user.login');
    }

    public function logout()
    {
        $_SESSION = [];
        die('退出成功！');
    }



    public function dologin()
    {
        $email = $_POST['email'];
        $password = md5($_POST['password']);

        $user = new User;

        if($user->login($email,$password))
        {
            message('登陆成功！',2,'/blog/index');
        }
        else
        {
            message('账号或密码错误！',1,'/user/login');
        }
    }


    public function active_user()
    {
        $code = $_GET['code'];

        $redis = \libs\Redis::getInstance();

        $key = 'temp_user:'.$code;

        $data = $redis->get($key);

        if($data)
        {
            $redis->del($key);

            $data = json_decode($data,true);

            $user = new \models\User;

            $user->add($data['email'],$data['password']);

            header('Location:/user/login');
        }
        else
        {
            die('激活码无效！');
        }
    }
    public function delete()
    {
        $id = $GET['id'];
        $blog = new Blog;
        $blog->delete($id);
        message('删除成功',2,'/blog/index');
    }
}