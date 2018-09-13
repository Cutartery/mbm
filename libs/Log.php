<?php 
namespace libs;

class Log
{
    private $fp;

    public function __construct($fileName)
    {
        $this->fp = fopen(ROOT.'logs/'.$fileName.'.log','a');

    }
    public function log($content)
    {
        $date = date('Y-m-d H:i:s');

        $c = $date."\r\n";
        $c .= str_repeat('=',120)."\r\n";
        $c .= $content . "\r\n\r\n";
        fwrite($this->fp,$c);
    }
}