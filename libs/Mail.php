<?php
namespace libs;

class Mail
{
    public $mailer;
    public function __construct()
    {
        $config = config('email');
        $transport = (new \Swift_SmtpTransport($config['host'],$config['port']))
        ->setUsername($config['name'])
        ->setPassword($config['pass']);
        $this->mailer = new \Swift_Mailer($transport);
    }
    public function send($title,$content,$to)
    {
        // echo "X";
        $config = config('email');
        $message = new \Swift_Message();
        $message->setSubject($title)
                ->setFrom([$config['from_email'] => $config['from_name']])
                ->setTo([
                    $to[0],
                    $to[0]=>$to[1]
                ])
                ->setBody($content,'text/html');
        if($config['mode']=='debug')
        {
            $mess = $message->toString();

            $log = new Log('email');
            $log->log($mess);
        }
        else
        {
            $this->mailer->send($message);    
        }
    }
}