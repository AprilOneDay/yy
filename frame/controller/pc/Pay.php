<?php
class Pay extends Init
{
    public function index()
    {
        $catid   = get('catid', 'intval', 0);
        $orderSn = get('order_sn', 'text');

        if (!$orderSn || !$catid) {
            die('参数错误');
        }

        $orders = table('orders')->where(array('order_sn' => $orderSn))->field('type,amount')->find();

        //获取所选模板
        $templateList = $this->checkedCatid();
        $this->assign('orders', $orders);
        switch ($templateList) {
            case 'default':
                $this->show('/pc/pay/test');
                break;

            default:
                # code...
                break;
        }
        $this->show();
    }

    public function bulid()
    {

    }
}
