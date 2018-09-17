<?php
// ini_set("session.save_handler", "redis"); 
// ini_set("session.save_path", "tcp://127.0.0.1:6379?database=3"); 
session_start();
// ini_set('session.gc_maxlifetime', 600);   // 设置 SESSION 10分钟过期
// session_start();


// if($_SERVER['REQUEST_METHOD'] == 'POST')
// {
//     if(!isset($_POST['_token']))
//         die('违规操作');
//     if($_POST['_token']!=$_SESSION['token'])
//         die('违规操作');
// }



define('ROOT',dirname(__FILE__).'/../');

require(ROOT.'vendor/autoload.php');
function autoLoadClass($class)
{
    require ROOT . str_replace('\\','/',$class).'.php';
}
spl_autoload_register('autoLoadClass');

function view($file,$data=[])
{
    if($data)
    extract($data);
    require ROOT.'views/'.str_replace('.','/',$file).".html";
}



if(php_sapi_name()==='cli')
{

$controller = ucfirst($argv[1]).'Controller';
$action = $argv[2];
}else{


    if(isset($_SERVER['PATH_INFO']))
    {
        $pathinfo = $_SERVER['PATH_INFO'];
        $pathinfo = explode('/',$pathinfo);
        $controller = ucfirst($pathinfo[1]).'Controller';
        $action = $pathinfo[2];
    }
    else
    {
        
        $controller = "IndexController";
        $action = "index";
    }
}


function redirect($url)
{
    header('Location:' . $url);
    exit;
}

$fullController = 'controllers\\'.$controller;

$_C = new $fullController;
$_C->$action();

function getUrlParams($except = [])
{
    foreach($except as $v){
        
        unset($_GET[$v]);

    }

    $ret = '';
    $num = 0;
    foreach($_GET as $k => $v)
    {   
        $num++;
        if(!in_array($k, $except)){
            // if($num==1){
            //     $ret .= "?$k=$v";
            // }else {
                $ret .= "&$k=$v";
            // }
        }
    }


    // echo $ret;
    return $ret;
}
// 
// getUrlParams(['ja','qw']);
function config ($name){

    static $config = null;
    if($config === null)
    {
        // 引入配置文件 
        $config = require(ROOT.'config.php');
    }
    return $config[$name];
}

function message($message,$type,$url,$seconds = 5)
{
    if($type=0)
    {
        echo "<script>alert('{$message}');location.href='{$url}';</script>";
        exit;
    }
    else if($type=1)
    {
        view('common.success',[
            'message'=>$message,
            'url'=>$url,
            'seconds'=>$seconds
        ]);
    }
    else if($type=2)
    {
        $_SESSION['_MISS_'] = $message;
        redirect($url);
    }
}
function e($content)
{
    return htmlspecialchars($content);
}


function hpe($content)
{
    static $purifier = null;
    if($purifier === null)
    {
        // 1. 生成配置对象
        $config = \HTMLPurifier_Config::createDefault();

        // 2. 配置
        // 设置编码
        $config->set('Core.Encoding', 'utf-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        // 设置缓存目录
        $config->set('Cache.SerializerPath', ROOT.'cache');
        // 设置允许的 HTML 标签
        $config->set('HTML.Allowed', 'div,b,strong,i,em,a[href|title],ul,ol,ol[start],li,p[style],br,span[style],img[width|height|alt|src],*[style|class],pre,hr,code,h2,h3,h4,h5,h6,blockquote,del,table,thead,tbody,tr,th,td');
        // 设置允许的 CSS
        $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,margin,width,height,font-family,text-decoration,padding-left,color,background-color,text-align');
        // 设置是否自动添加 P 标签
        $config->set('AutoFormat.AutoParagraph', TRUE);
        // 设置是否删除空标签
        $config->set('AutoFormat.RemoveEmpty', true);

        // 3. 过滤
        // 创建对象
        $purifier = new \HTMLPurifier($config);
        // 过滤
        $clean_html = $purifier->purify($content);
    }
    return $purifier->purify($content);
}
function csrf()
{
    if(!isset($_SESSION['token']))
    {
        $token = md5(rand(1,99999).microtime());
        $_SESSION['token'] = $token;
    }
    return $token;
}
