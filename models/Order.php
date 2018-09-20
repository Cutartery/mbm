<?php

namespace models;

use PDO;

class Order extends Base
{

    //下订单
    public function create($money)
    {
        $flake = new \libs\Snowflake(1023);
        $code_id = $flake->nextId();
        $stmt = self::$pdo->prepare("INSERT INTO orders(user_id,money,sn) VALUES(?,?,?)");
        
        $stmt->execute([
            $_SESSION['id'],
            $money,
            $code_id
        ]);
    }
    //
    public function search()
    {
        //去除当前的订单
        $where = 'user_id='.$_SESSION['id'];
        //排序
        //默认排序
        $odby = 'created_at';
        $odway = 'desc';
        //翻页
        $perpage = 15;
        $page = isset($_GET['page']) ? max(1,(int)$_GET['page']) : 1;
        $offset = ($page-1)*$perpage;
        //制作按钮
        //取出总的记录数
        $stmt = self::$pdo->prepare("SELECT COUNT(*) FROM orders WHERE $where");
        $stmt ->execute();
        $count = $stmt->fetch(PDO::FETCH_COLUMN);
        //计算总的页数
        $pageCount = ceil($count/$perpage);
        $btns = '';
        for($i=1;$i<$pageCount;$i++)
        {
            //获取之前的页数
            $params = getUrlParams(['page']);
            $class = $page==$i ? 'active' :'';
            $btns .= "<a class='$class' href='?{$params}page=$i'>$i</a>";
        }
        //执行SQL语句
        $stmt = self::$pdo->prepare("SELECT * FROM orders WHERE $where ORDER BY $odby $odway LIMIT $offset,$perpage");
        $stmt->execute();
        //取数据
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return [
            'btns'=>$btns,
            'data'=>$data
        ];
    }
    //根据编号在数据库中取出订单信息
    public function findBySn($sn)
    {
        $stmt = self::$pdo->prepare("SELECT * FROM orders WHERE sn=?");
        $stmt->execute([$sn]);
        //以关联数组的结构返回数据
        $cc = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<pre>";
        var_dump($cc);
        return $cc;
    }
    //设置订单为一支付的状态
    public function setPaid($sn)
    {
        $stmt = self::$pdo->prepare("UPDATE orders SET status=1,pay_time=now() WHERE sn=?");
        $cc = $stmt->execute([$sn]);
        return $cc;
    }
        //开启事务
        public function startTrans()
        {
            self::$pdo->exec("start transaction");
        }
        //提交事务
        public function commit()
        {
            self::$pdo->exec("commit");
        }
        //回滚事务
        public function rollback()
        {
            self::$pod->exec("rollback");
        }
}