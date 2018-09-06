<?php
define('ROOT',dirname(__FILE__).'/../');

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
