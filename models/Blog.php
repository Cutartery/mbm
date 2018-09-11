<?php
namespace models;

use PDO;

class Blog 
{
    public $tableName = 'blogs';

    public $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=basic_module','root','123456');
        $this->pdo->exec('SET NAMES utf8');
    }


    public function search()
    {

        $pdo = new PDO('mysql:host=127.0.0.1;dbname=basic_module','root','123456');

        $pdo->exec('SET NAMES utf8');

        $where = 1;

        $value = [];
 
        if(isset($_GET['keywords']) && $_GET['keywords'])
        {
            $where .= " AND (title LIKE ? OR content LIKE ?)";
            $value[] = '%'.$_GET['keywords'].'%';
            $value[] = '%'.$_GET['keywords'].'%';
        }
    
        // 发表日期搜索
        if(isset($_GET['start_date']) && $_GET['start_date'])
        {
            $where .= " AND created_at >= ?";
            $value[] = $_GET['keywords'];
        }
        if(isset($_GET['end_date']) && $_GET['end_date'])
        {
            $where .= " AND created_at <= ?";
            $value[] = $_GET['end_date'];
        }
    
        // is_show 
        if(isset($_GET['is_show']) && $_GET['is_show'] != '')
        {
            $where .= " AND is_show = ?";
            $value[] = $_GET['is_show'];
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
    
        // echo "SELECT * FROM blogs WHERE $where ORDER BY $orderBy $orderyWay";

        // 分页


        $perpage = 20;

        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;

        $offset = ($page-1)*$perpage;

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM blogs WHERE $where");

        $stmt->execute($value);

        $count = $stmt->fetch(PDO::FETCH_COLUMN);

        $pageCount = ceil($count/$perpage);

        $btns = '';

        for($i=1;$i<$pageCount;$i++)
        {
            $urlParams = getUrlParams(['page']);

            $class = $page==$i ? 'active' : '';

            $btns .= "<a class='$class' href='?page=$i{$urlParams}'>$i</a>";
        }

        $stmt = $this->pdo->prepare("SELECT * FROM blogs WHERE $where ORDER BY $orderBy $orderyWay LIMIT $offset,$perpage");

        $stmt->execute($value);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        return [
            '$data' => $data,
            'btns'=>$btns
        ];
    }

    public function content_to_html()
    {
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=basic_module','root','123456');
        $pdo->exec('SET NAMES utf8');

        $stmt = $this->pdo->query('SELECT * FROM blogs');

        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ob_start();

        foreach($blogs as $v)
        {
            view('blogs.content',[
                'blog'=> $v,
            ]);
            $str = ob_get_contents();

            file_put_contents(ROOT.'public/contents/'.$v['id'].'.html',$str);

            ob_clean();
        }
    }
    public function index2html()
    {
        $stmt = $this->pdo->query("SELECT * FROM blogs WHERE is_show=1 ORDER BY id DESC LIMIT 20");
        $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        ob_start();
        view("index.index",[
            'blogs'=>$blogs
        ]);
        $str = ob_get_contents();

        file_get_contents(ROOT.'public/index.html',$str);
    }
    public function getDisplay($id)
    {
        $key = "blog-{$id}";
        $redis = new \Predis\Client([
            'scheme' =>'tcp',
            'host'=>'127.0.0.1',
            'port'=>6379,
        ]);
        if($redis->hexists('blog_displays',$key))
        {
            $newNum = $redis->hincrby('blog_displays',$key,1);
            return $newNum;
        }
        else
        {
            $stmt = $this->pdo->prepare("SELECT display FROM blogs WHERE id=?");
            $stmt->execute([$id]);
            $display = $stmt->fetch(PDO::FETCH_COLUMN);
            $display++;
            $redis->hset('blog_displays',$key,$display);
            return $display;
        }
    }

    public function displayToDb()
    {
        
        $redis = new \Predis\Client([
            'scheme' =>'tcp',
            'host'=>'127.0.0.1',
            'port'=>6379,
        ]);
        $data = $redis->hgetall('blog_displays');
        foreach($data as $k=>$v)
        {
            $id = str_replace('blog-','',$k);
            $sql = "UPDATE blogs SET display={$v} WHERE id={$id}";
            $this->pdo->exec($sql);
        }

    }
}