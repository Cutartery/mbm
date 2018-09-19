<?php

namespace controllers;

use Yansongda\Pay\Pay; 

class AlipayController 
{

    public $config = [
        'app_id' => '2016092200567821',
        // 通知地址
        'notify_url' => 'http://aea571b0.ngrok.io/alipay/notify',
        // 跳回地址
        'return_url' => 'http://localhost:9999/alipay/return',
        // 支付宝公钥
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0biB/54hW3ZI460IunN06uRvFPPdZzkhXCDYyVbgFhf5RUp1kzHnClPcy8MBvzb5VKK2MRi2JOTr73y1ijOtTGrf/EOeVP6VbBbzpQmNB2WAUD9G+RPWbSOGaEarLKQKP2Knyc0Db13QUTAa1JrDDXVno1OtgO3APKJrPA+qHTb9yRbCveK4jGF7cbl+kNurXCi3+y21qHv2s361yFRDWCLAq1lMWRjQ96KvEWPmdKP7dHiouHLr9b8/EanLvJ1yVtWYq0oDFrzOjy7dMePjpZszxXmMZkK1GubnH8vwML5Bg06W9kcIsQ1GJfkRM3+Ugvwzs48+MDjZhwNJNwBJHwIDAQAB',
        // 商户应用密钥
        'private_key' => 'MIIEowIBAAKCAQEA1kF3sr7OOTIgwcc8osa/KaFqAT9r6U9HXFjphE2ap+VuZoWAuVSrLWkKe/8/T5rwQG53jdyTXYmTP8D5O0jKwa3IdXKRSUBAuXSr5QtD2GAgy+4YCcr2fYRq2dpO6gcC/rjQaZk40rNdJ4xVBnz5/llg97jUSlP7hOcbt9xTipWcVKsMUm2dwtkN3p+bwTPyoh9ZxV6fm0Ql4b2W9lw2bKaMZuM7dK9zR+pyn4AhBooP90GuwLeGw8zu4W49rKPp5iqDKpgW8xVYUavWl9Q0khYK5B0eTvd81g+LF3N6hzOW0dlw2+akAfnFKWc7p0QVEMiCK2H6rJMRAQbJZn0tZQIDAQABAoIBAHFY+ykt9k2d7mR1sedve4xbn4f/dGhej6MUSp26rHnsT8afFCV33INOkcRdmbHUhwufJVKAgdm3QZY10eSCMvnZpTY1IRV+j39NcO7gUckzucYJNOBhZpuqpRiRfLQkd3oobeGftMcLKtoUTJr9HQhpYnpLZsvXuDVsSmavgebLHL47dUnEV5McV4MVRkIdXVRsjWhhG65hfeCV2MWLbJdHS8JhG0vhRkP13IQmkHdrSOzF/fWRJ/OknHxGi4zVpiP/Qo3FwSEXCsDTHVHoCyFZAA1PaX1lbmOROYZ9nactyYuS0oD4wWc0RYWKMSliqW+UhMkDTf5ElpJnOZZDtwECgYEA/+TfmwGj2u5fNUzchmx5am3Rma/eu0bDIi/jCZw5A3X0mlQH6LV+bmigSASJW98AJwnH4LDVSPvpX3jo9iv/B3lQkdUoDVmj1BqFRZcwzu9DMV7GUirTYCjQbjaNUQc+YDMAKl7jMVPtsYxZpdeUw/RwfMlpLtKuKtsAEnF87EECgYEA1lguHzFG+oOy8IERsAYaPHbFx+dgkeFTEFXGExUoWDHF2gytdUfM40Cmb9l5Q9RGT0PjRKegCu5Jjry5WbNU541WLTj+TqU74epceUOGQrzhNUDpM+fWyqTYxEqDrWdUetQRUwrJLFBG1Kd1dCyeBoqAYKnSaI7z52OZr+jNCCUCgYEAzW9rbMAGUg8o9Ft+d5TCbQU5To7425TVt66GMQLwaqzZ8MQhjVuX4v+wHeE2fUEsmCqsAuE0eeFz3CfvryJnHhLw8gMUopMiiXe5IsNcdeV0JkCWKwUiqYJawwCctz5/fy+ypFGFR+a/Xtj0Z8YJMsAdRrFT9jlBhjpHdocR6YECgYBBLR8ZLQrAMju/FgAi3jgsGpGZb6nVsgZn+Sz5sH1MFBZtb11vRB3xHD9/00kkbu/SOnpo7e+kY0DKvmg99mHi5v9FUQdDMss6ruDfnsC8mmKApvRCbETL6cRgK6hOmhT+JG4AKk9xwhdHooP8h3Sl2Ieg3QK1+IIB4i9hZhCyYQKBgES4M/dNG1D7w3150AJMeMp08bq/By8fbvdS3yohy5JBmBKnn3cc0eHjuO/2ncUwhRgIsai+9eY3KBD/K8Khmz/QTvibrEsPPkd7NMePLcdJui7kbXsdygwYQXn0HrgE/nT/xgHVmgMNSWycVUopHVD5+eZ2QznpR2XMOVcPmtVs',
        // 沙箱模式（可选）
        'mode' => 'dev',
    ];
    // 发起支付
    public function pay()
    {
        $order = [
            'out_trade_no' => time(),    // 本地订单ID
            'total_amount' => '0.01',    // 支付金额
            'subject' => 'test subject', // 支付标题
        ];

        $alipay = Pay::alipay($this->config)->web($order);

        $alipay->send();
    }
    // 支付完成跳回
    public function return()
    {
        $data = Pay::alipay($this->config)->verify(); // 是的，验签就这么简单！
        echo '<h1>支付成功！</h1> <hr>';
        var_dump( $data->all() );
    }
    // 接收支付完成的通知
    public function notify()
    {
        $alipay = Pay::alipay($this->config);
        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！
            // 这里需要对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            echo '订单ID：'.$data->out_trade_no ."\r\n";
            echo '支付总金额：'.$data->total_amount ."\r\n";
            echo '支付状态：'.$data->trade_status ."\r\n";
            echo '商户ID：'.$data->seller_id ."\r\n";
            echo 'app_id：'.$data->app_id ."\r\n";
        } catch (\Exception $e) {
            echo '失败：';
            var_dump($e->getMessage()) ;
        }
        // 返回响应
        $alipay->success()->send();
    }

    //退款
    public function refund()
    {
        //生成唯一的订单号
        $refundNo = md5(rand(1,99999).microtime());
        try{
            //退款
            $ret = Pay::alipay($this->config)->refund([
                'out_trade_no'=>'15893074791', //之前的流水订单号
                'refund_amount'=> 0.01,    //退款金额，单位元
                'out_request_no'=> $refundNo    //退款订单号
            ]);
            if($ret->code == 10000)
            {
                echo '退款成功！';
            }
            else
            {
                echo '退款失败，错误信息'.$ret->sub_msg;
                echo '错误编号'.$ret->sub_code;
            }
        }
        catch(\Exception $e)
        {
            var_dump($e->getMessage());
        }

    }
}
