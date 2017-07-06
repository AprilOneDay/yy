<?php

//短信接口
class Sms extends base
{
    public function send()
    {
        $mobile = post('mobile', 'trim');
        $flag   = post('flag', 'trim');

        $reslut = dao('Sms')->send($mobile, $flag);
        if ($reslut['status']) {
            $this->ajaxReturn(array('status' => true, 'msg' => '发送成功'));
        }

        $this->ajaxReturn($reslut);

    }
}
