<?php

//短信接口
class Sms extends base
{
    public function send()
    {
        $moblie = post('moblie', 'trim');
        $flag   = post('flag', 'trim');

        $reslut = dao('Sms')->send($moblie, $flag);
        if ($reslut['status']) {
            $this->ajaxReturn(array('status' => true, 'msg' => '发送成功'));
        }

        $this->ajaxReturn(array('status' => false, 'msg' => $reslut['msg']));

    }
}
