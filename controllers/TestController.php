<?php
namespace controllers;

use models\User;

class TestController
{
    //插入数据
    public function insert()
    {
        //定义死数据
       
        $user = new User;
        // echo 123;

        $user->insert([
            'name'=>'张三',
            'age'=>10
        ]);
        $user->insert([
            'name'=>'王五',
            'age'=>10
        ]);

    }
    public function update()
    {
        $user = new User;
        $user->update([
            'name'=>'李四'
        ],"age=20");
        // echo 123;
    }
    public function get()
    {
        // echo 13;
         $user = new User;
         $a = $user->get('select * from test');
         echo "<pre>";
         var_dump($a);
         echo "<hr>";
         
         $a = $user->getRow('select * from test where name="王五"');
         echo "<pre>";
         var_dump($a);
         echo "<hr>";
         
         $a = $user->count(3);
         echo "<pre>";
         var_dump($a);
         echo "<hr>";
         
         $a = $user->find(4);
         echo "<pre>";
         var_dump($a);
         echo "<hr>";
    }
    public function delete()
    {
        $user = new User;
        $a = $user->delete("name='王五'");
    }
}