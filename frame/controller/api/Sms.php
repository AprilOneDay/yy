<?php
namespace Controller;

//短信接口
class Sms extends base
{
    public function sendInfo()
    {
        var_dump('111111');
        var_dump($flag);exit();
        $moblie = post('moblie', 'trim');
        $flag   = post('flag', 'trim');

        var_dump($moblie);
        var_dump($flag);exit();

        /*$reslut = dao('Sms')->send($moblie, $flag);
    if ($reslut['status']) {
    $this->ajaxReturn(array('status' => true, 'msg' => '发送成功'));
    }

    $this->ajaxReturn(array('status' => false, 'msg' => $reslut['msg']));*/

    }
}
