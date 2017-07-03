<?php
//学分处理API
class Credit extends base
{

    public function lists()
    {
        $page = new page();

        $isApply  = get('is_apply', 'text');
        $type     = get('type', 'intval', 0);
        $yeard    = get('yeard', 'intval', 0);
        $field    = get('field', 'text', '');
        $keyword  = get('keyword', 'text', '');
        $pageNo   = get('pageNo', 'intval', 1);
        $pageSize = get('pageSize', 'intval', 100);

        $offer = ($pageNo - 1) * $pageSize;
        $param = array();

        $map['yeard'] = date('Y');
        if ($type) {
            $map['type']   = $type;
            $param['type'] = $type;
        }

        if ($isApply != '') {
            $map['is_apply']   = $isApply;
            $param['is_apply'] = $isApply;
        }

        if ($yeard) {
            $map['yeard']   = $yeard;
            $param['yeard'] = $yeard;
        }

        if ($field && $keyword) {
            $param['field']   = $field;
            $param['keyword'] = $keyword;
        }

        $yearCopy = table('cqks_credit', false)->group('yeard')->field('yeard')->find('one', true);

        $total = table('cqks_credit', false)->where($map)->count();
        $page->pages($total, $pageNo, $pageSize, url('lists', $param), 5);
        $loadPage = $page->loadConsole();

        $list = table('cqks_credit', false)->where($map)->order('is_apply asc')->limit($offer, $pageSize)->find('array');
        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]['user'] = table('cqks_yh', false)->where(array('bh' => $value['uid']))->field('yy,xm')->find();
            }
        }

        $statusCopy = array('1' => '通过', '0' => '未通过');
        $typeCopy   = array('1' => 'I类', '2' => 'II类');
        $applyCopy  = array('1' => '已申请', '0' => '未申请');

        $this->assign('statusCopy', $statusCopy);
        $this->assign('typeCopy', $typeCopy);
        $this->assign('applyCopy', $applyCopy);
        $this->assign('yearCopy', $yearCopy);

        $this->assign('isApply', $isApply);
        $this->assign('field', $field);
        $this->assign('keyword', $keyword);
        $this->assign('yeard', $yeard);
        $this->assign('type', $type);
        $this->assign('list', $list);
        $this->assign('pages', $loadPage);
        $this->show('/console/credit/list');
    }

    /**
     * 申请学分
     * @date   2017-06-14T16:55:30+0800
     * @author ChenMingjiang
     * @return [type]                   [description]
     */
    public function applyCredit()
    {
        $id = get('id', 'intval', 0);

        $map['id'] = $id;

        $credit = table('cqks_credit', false)->where($map)->find();
        if (!$credit) {
            $this->ajaxReturn(array('status' => false, 'msg' => '信息不存在'));
        }

        //I类学分申请
        if ($credit['type'] == 1) {
            $user = table('cqks_yh', false)->where(array('bh' => $credit['uid']))->field('sfz,xm')->find();
            if (!$user) {
                $this->ajaxReturn(array('status' => false, 'msg' => '用户信息不存在'));
            }

            $data['ID']    = $user['sfz'];
            $data['Name']  = $user['xm'];
            $data['Code']  = $credit['code'];
            $data['Timer'] = time();

            $encrypt = new encrypt();
            $json    = $encrypt->base64Encrypt($data, 'PKCS7');

            $resultContent = file_get_contents('http://cqjlp.91huayi.com/cme/applycertificate.aspx?params=' . $json);
            $result        = json_decode($resultContent, true);

            //更新学分
            if ($result['Status']) {
                table('cqks_credit', false)->where($map)->save(array('is_apply' => 1));
                $this->ajaxReturn(array('status' => true, 'msg' => $result['Msg']));
            }

            $this->ajaxReturn(array('status' => false, 'msg' => $result['Msg']));
        }
        //II类学分
        else {
            if ($credit['status'] == 0) {
                $this->ajaxReturn(array('status' => false, 'msg' => '该学生考试未通过无法审核'));
            }

            $user = table('cqks_yh', false)->where(array('bh' => $credit['uid']))->field('sfz,xm')->find();
            if (!$user) {
                $this->ajaxReturn(array('status' => false, 'msg' => '用户信息不存在'));
            }

            //更新学分
            $result = table('cqks_credit', false)->where($map)->save(array('is_apply' => 1));
            if ($result) {
                $this->ajaxReturn(array('status' => true, 'msg' => '更新成功'));
            }

            $this->ajaxReturn(array('status' => false, 'msg' => '更新失败'));
        }

    }

    public function batchApplyCredit()
    {
        $idArr = post('id', 'json', '');
        if (!$idArr) {
            $this->ajaxReturn(array('status' => false, 'msg' => '请选择需要审核的信息'));
        }

        //更新II类学分
        $listTwo = table('cqks_credit', false)->where(array('type' => 2, 'status' => 1, 'id' => array('in', implode(',', $idArr))))->field('id')->find('one', true);

        if ($listTwo) {
            $reslut = table('cqks_credit', false)->where(array('type' => 2, 'status' => 1, 'id' => array('in', implode(',', $idArr))))->save(array('is_apply' => 1));

            //剔除已更新学分
            foreach ($idArr as $key => $value) {
                if (in_array($value, $listTwo)) {
                    unset($idArr[$key]);
                }
            }
        }

        //更新I类学分
        $listOne = table('cqks_credit', false)->where(array('type' => 1, 'id' => array('in', implode(',', $idArr))))->field('id,code,uid')->find('array');

        if ($listOne) {
            $encrypt = new encrypt();
            foreach ($listOne as $key => $value) {
                $user = table('cqks_yh', false)->where(array('bh' => $value['uid']))->field('sfz,xm')->find();
                if ($user) {
                    $data['ID']    = $user['sfz'];
                    $data['Name']  = $user['xm'];
                    $data['Code']  = $value['code'];
                    $data['Timer'] = time();

                    $json          = $encrypt->base64Encrypt($data, 'PKCS7');
                    $resultContent = file_get_contents('http://cqjlp.91huayi.com/cme/applycertificate.aspx?params=' . $json);
                    $result        = json_decode($resultContent, true);

                    //更新学分
                    if ($result['Status']) {
                        table('cqks_credit', false)->where(array('id' => $value['id']))->save(array('is_apply' => 1));
                    }
                }
            }
        }

        $this->ajaxReturn(array('status' => true, 'msg' => '批量更新完成'));

    }

    public function updateCredit()
    {
        $pageNo   = get('pageNo', 'intval', 1);
        $pageSize = get('pageSize', 'intval', 100);

        $offer = ($pageNo - 1) * $pageSize;

        $list = table('cqks_fs', false)->limit($offer, $pageSize)->find('array');
        $i    = 0;
        if ($list) {
            foreach ($list as $key => $value) {

                $data['uid']     = $value['yhbh'];
                $data['status']  = $value['state'] == 'true' ? 1 : 0;
                $data['code']    = $value['kjbh'];
                $data['created'] = strtotime($value['sj']);
                $data['yeard']   = substr($value['sj'], 0, 4);
                $data['credit']  = table('cqks_kj')->where(array('bh' => $value['yhbh']))->field('xf')->find('one');

                if ($data['status'] == 1) {
                    $data['post_time'] = $data['created'];
                }

                $is = table('cqks_credit', false)->where(array('uid' => $data['uid'], 'code' => $data['code'], 'yeard' => $data['yeard'], 'status' => $data['status']))->field('id')->find('one');

                if (!$is) {
                    table('cqks_credit', false)->add($data);
                    $i++;
                }

            }
        }

        header("refresh:5;url=/frame/index.php?m=console&c=credit&a=update_credit&pageNo=" . ($pageNo + 1));
        print('正在加载，请稍等...<br>5秒后自动跳转到第' . ($pageNo + 1) . '页,共执行' . $i . '条记录<br/>');

    }
}
