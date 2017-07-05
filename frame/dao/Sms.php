<?php
class Sms
{
    public function send($moblie = '', $flag = '', $data = '')
    {
        if (!$moblie || !$flag) {
            return array('status' => false, 'msg' => '参数错误');
        }

        $config = vars('sms', 0);

        $content = $this->smsTemplate($flag, $data);
        if (!$content) {
            return array('status' => false, 'msg' => 'flag无法识别');
        }

        $url  = $config['url'];
        $data = array(
            'CorpID'  => $config['username'],
            'Pwd'     => 'password',
            'Content' => $content,
            'Mobile'  => $moblie,
        );
    }

    private function smsTemplate($flag = '', $data = '')
    {
        $content = '';
        switch ($flag) {
            case 'code':
                $content = '验证码：' . $data . '您正在使用医院预约系统';
                break;
            default:
                # code...
                break;
        }

        return $content;
    }
}
