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


    function cs (){
        $flake = new \libs\Snowflake;
        echo $flake->nextId();
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
        $id =  $blog->add($title,$content,$is_show);

        if($is_show == 1)
        {
            $blog->singlePage($id);
        }

        message('发表成功！',2,'/blog/index');
    }

    //显示私有日志
    public function content()
    {
        //接收id并显示日志
        $id = $_GET['id'];
        $model = new Blog;
        $blog = $model->find($id);
        //判断这个日志是否是我的
        if($_SESSION['id']!=$blog['user_id'])
        {
            die('访问无效');
        }
        //加载视图
        view('blogs.content',[
            'blog'=>$blog
        ]);

    }

    //登录状态

    public function display()
    {
        //接收id
        $id = (int)$_GET['id'];
        $blog = new Blog;
        //让浏览能量加一
        $display = $blog->getDisplay($id);
        //返回多个数据必须要用JSON

        echo json_encode([
            'display'=>$display,
            'email'=>isset($_SESSION['email']) ? $_SESSION['email'] : ''
        ]);
    }



    //删除日志
    public function delete()
    {
        $id = $_POST['id'];
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
    //修改日志
    function edit()
    {
        $id = $_GET['id'];
        $blog = new Blog;
        $data = $blog->find($id);
        view('blogs.edit',[
            'data'=>$data
        ]);
    }
    public function update()
    {
    
        $title = $_POST['title'];
        $content = $_POST['content'];
        $is_show = $_POST['is_show'];
        $id = $_POST['id'];

        $blog = new Blog;
        $blog->update($title,$content,$is_show,$id);

        if($is_show == 1){
            $blog->singlePage($id);
        }else {
            $blog->deleteHtml($id);
        }

        
        message('修改成功',0,'/blog/index');
    }
}