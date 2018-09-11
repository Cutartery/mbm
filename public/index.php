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