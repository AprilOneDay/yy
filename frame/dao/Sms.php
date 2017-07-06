<?php
namespace Dao;

class Sms
{

    public function send($moblie = '', $flag = '', $data = '')
    {
        if (!$moblie || !$flag) {
            return array('status' => false, 'msg' => '参数错误');
        }

        $config = vars('sms', 0);

        $content = $this->smsTemplate($flag, $data, $moblie);
        if (!$content) {
            return array('status' => false, 'msg' => 'flag无法识别');
        }

        $url  = $config['url'];
        $data = array(
            'CorpID'   => $config['username'],
            'Pwd'      => $config['password'],
            'Content'  => $content,
            'Mobile'   => $moblie,
            'Cell'     => '',
            'SendTime' => '',
        );

        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $config['url']);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 1);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        //执行命令
        $result = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        if ($result) {
            return array('status' => true, 'msg' => '发送成功');
        }

        return array('status' => false, 'msg' => $data, 'result' => $result);
    }

    private function smsTemplate($flag = '', $data = '', $moblie = '')
    {
        $content = '';
        switch ($flag) {
            case 'verification':
                $verification = rand('11111', '99999');
                session('verificationMoblie', $moblie); //保存手机号
                session('verification', $verification); //保存验证码
                $content = '验证码：' . $verification . '您正在使用医院预约系统';
                break;
            default:
                # code...
                break;
        }

        return $content;
    }

}
