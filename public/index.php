<?php
// ini_set("session.save_handler", "redis"); 
// ini_set("session.save_path", "tcp://127.0.0.1:6379?database=3"); 
// session_start();
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
    }
    else
    {
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


function route()
{
    $url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
    $defaultController = "IndexController";
    $defaultAction = "index";
    if($url == '/')
    {
        return[
            $defaultController,
            $defaultAction
        ];
    }
    else if(strpos($url,'/',1)!==FALSE)
    {
        $url = ltrim($url,'/');
        $route = explode('/',$url);
        $route[0] = ucfirst($route[0]).'Controller';
        return $route;   
    }
    else
    {
        die('请求的  URL  格式不正确！');
    }
}


$route = route();

$controller = "controllers\\{$route[0]}";

$action = $route[1];

// var_dump($route);
$_C = new $controller;

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