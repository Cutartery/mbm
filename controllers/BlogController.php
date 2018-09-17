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
    public function store()
    {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];
        $blog = new Blog;
        $blog->add($title,$content,$is_show);
        message('发表成功！',2,'/blog/index');
    }
    public function content()
    {
        $id = $_GET['id'];
        $model = new Blog;
        $blog = $model->find($id);
        if($_SESSION['id'] != $blog['user_id'])
        {
            die('无权访问！');
        }
        view('blogs.content',[
            'blog'=>$blog
        ]);
    }
    public function delete()
    {
        $id = $_GET['id'];
        $blog = new Blog;
        $blog->delete($id);
        $blog->deleteHtml($id);
        message('删除成功！',2,'/blog/index');
    }

    // 单页面静态化
    function singlePage (){
        // 接收传来的参数
        $id = $_GET['p'];
        $blog = new Blog;
        $blog->singlePage($id);
    }
}