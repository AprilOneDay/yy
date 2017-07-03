<?php
//学分处理API
class Credit extends base
{
    public function saveCredit()
    {

        /* $encrypt       = new encrypt();
        $data['ID']    = '510213198203181219';
        $data['Name']  = '杨利平';
        $data['Code']  = '0965D915-9F53-4627-9A3E-A42F00B0ACC3';
        $data['Timer'] = time();

        $a      = $encrypt->base64Encrypt($data, 'PKCS7');
        $a      = 'yIi0OLBd0uBbuX0O%2BqdhDKGJ68Z7UE%2Fi0s8XeD8DQUF8G3VuiT6BbI7x%2FiCtYBOAG5dUZ4GGrXOhYQFANifkqHvI0LKXyhNv9HzpycFi2m%2F8fAxnfKQfTZTZOUXLuPcAuvtxBE0AE1VkYIKYOrlX6rbw2im88dPSU6nW9cYbSVI%3D';
        $params = $encrypt->base64Decrypt(urldecode($a), 'PKCS7');

        print_r($data);
        var_dump($a);
        var_dump($params);
        exit();*/
        $encrypt = new encrypt();
        $params  = get('params', 'trim');
        $params  = json_decode($encrypt->base64Decrypt($params, 'PKCS7'), true);
        if (!is_array($params)) {
            die('参数错误');
        }

        if (!$params['ID'] || !$params['Name']) {
            die('请传学员信息');
        }

        $uid = table('cqks_yh', false)->where(array('sfz' => $params['ID'], 'xm' => $params['Name']))->field('bh')->find('one');
        if (!$uid) {
            die('未查询到相关学员信息');
        }

        $kc = table('kj')->where(array('kc_sn' => $params['Code']))->field('id,credit,title')->find();
        if (!$kc) {
            die('未查询到相关课程信息');
        }

        //保存学分
        $data['uid']      = $uid;
        $data['type']     = 1;
        $data['code']     = $params['Code'];
        $data['credit']   = $kc['credit'];
        $data['title']    = $kc['title'];
        $data['status']   = 1; //已通过
        $data['is_apply'] = 0; //未申请
        $data['created']  = isset($params['PassTime']) ? $params['PassTime'] : time();
        $data['yeard']    = date('Y');

        $result = table('cqks_credit', false)->add($data);
        if ($result) {
            //删除学习记录
            table('cqks_course', false)->where(array('uid' => $uid, 'code' => $params['code']))->save(array('status' => 3));
            die('操作成功');
        }

        die('操作失败');
    }
}
