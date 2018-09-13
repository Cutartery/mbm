<?php
namespace controllers;

use models\Blog;

class BlogController
{

    public function index()
    {
        $blog = new Blog;
        $data = $blog->search();
        // var_dump($data['btns']);
        // echo "<pre>";
        // var_dump($data);die;
        view('blogs.index',$data);
    } 


    public function content_to_html()
    {
        $blog = new Blog;
        $blog->content_to_html();
    }


    public function index2html()
    {
        $blog = new Blog;
        $blog->index2html();
    }



    public function display()
    {
        $id = (int)$_GET['id'];
        $blog = new Blog;
        $display =  $blog->getDisplay($id);
        echo json_encode([
            'display' => $display,
            'email' => isset($_SESSION['email']) ? $_SESSION['email']: ''
        ]);
    }


    public function displayToDb()
    {
        $blog = new Blog;
        $blog->displayToDb();
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
    public function create()
    {
        view('blogs.create');
    }
}