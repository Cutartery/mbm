<?php
namespace controllers;

use models\Blog;

class BlogController
{

    public function index()
    {
        $where = 1;
 
        if(isset($_GET['keywords']) && $_GET['keywords'])
        {
            $where .= " AND (title like '%{$_GET['keywords']}%' OR content like '%{$_GET['keywords']}%')";
        }
    
        // 发表日期搜索
        if(isset($_GET['start_date']) && $_GET['start_date'])
        {
            $where .= " AND created_at >= '{$_GET['start_date']}'";
        }
        if(isset($_GET['end_date']) && $_GET['end_date'])
        {
            $where .= " AND created_at <= '{$_GET['end_date']}'";
        }
    
        // is_show 
        if(isset($_GET['is_show']) && $_GET['is_show'] != '')
        {
            $where .= " AND is_show={$_GET['is_show']}";
        }
    
        // 默认的排序条件
        $orderBy = 'created_at';
        $orderyWay = 'desc';
    
        // 设置排序字段
        if(isset($_GET['order_by']) && $_GET['order_by'] == 'display')
        {
            $orderBy = 'display';
        }
        // 设置排序方式
        if(isset($_GET['order_way']) && $_GET['order_way'] == 'asc')
        {
            $orderyWay = 'asc';
        }
    
        echo "SELECT * FROM blogs WHERE $where ORDER BY $orderBy $orderyWay";
        $blog = new Blog;
        $blogs = $blog->get("SELECT * FROM blogs WHERE $where ORDER BY $orderBy $orderyWay");
    
        view('blogs.index', [
            'blogs' => $blogs
        ]);
    }












    public function mock()
    {
        $user = new Blog;
        
        for($i=1;$i<100;$i++)
        {
            $user->insert([
                'title'=>$this->getChar(30),
                'content'=>$this->getChar(200),
                'short_content'=>$this->getChar(200),
                'display'=>rand(5,1000),
                'is_show'=>rand(0,1),
                'created_at'=>date('Y-m-d H:i:s',rand(1000000000,1536585479)),
            ]);
        }
        echo "OjbK";
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