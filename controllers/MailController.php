<?php
namespace controllers;

use libs\Mail;

class MailController
{
    public function send()
    {
        $redis = new \Predis\Client([
            'scheme'=>'tcp',
            'host'=>'127.0.0.1',
            'port'=>6379
        ]);

        $mailer = new Mail;

        ini_set('default_socket_timeout',-1);
        echo "发邮件启动完成..\r\n";
        while(true)
        {
            $data = $redis->brpop('email',0);
            $message = json_decode($data[1],TRUE);
            $mailer->send($message['title'],$message['content'],$message['from']);
            echo "发邮件成功，请等待下一个。\r\n";
        }
    }
}