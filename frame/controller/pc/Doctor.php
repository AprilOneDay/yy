<?php
class Doctor extends Init
{
    public function index()
    {
        $catid = get('catid', 'intval', 0);
        if (!$catid) {
            die('参数错误');
        }
        $topCatid = table('category')->where(array('catid' => $catid))->field('parentid')->find('one');

        $templateList = $this->checkedCatid();
        $ys           = table('ys')->tableName();
        $yd           = table('ys_data')->tableName();
        $field        = "$ys.id,$ys.title,$ys.thumb,$ys.zc,$yd.keshi,$yd.like";
        $doctorList   = table('ys')->join($yd, "$yd.id = $ys.id", 'left')->where(array($yd . '.hospital' => $topCatid))->field($field)->find('array');

        $keshi = table('ys_data')->where(array('hospital' => $topCatid))->group('keshi')->field('keshi')->find('array');

        foreach ($doctorList as $key => $value) {
            $doctor[$value['id']] = $value;
        }

        $week = date('w');
        for ($i = 0; $i < 7; $i++) {
            $time = $week == 6 ? 7 : $week + 1;

            $weekArray[$i]['zh']   = $this->week($week);
            $weekArray[$i]['data'] = date('m-d', strtotime("+$i day"));
            $weekArray[$i]['time'] = array($time . '1', $time . '2', $time . '3');

            $week == 6 ? $week = 0 : $week++;
        }

        $this->assign('catid', $catid);
        $this->assign('keshi', $keshi);
        $this->assign('doctor', $doctor);
        $this->assign('week', $weekArray);
        switch ($templateList) {
            case 'default':
                $this->show('/pc/doctor/index');
                break;
            default:
                break;
        }
    }

    public function week($week)
    {
        switch ($week) {
            case 1:
                return '星期一';
                break;
            case 2:
                return '星期二';
                break;
            case 3:
                return '星期三';
                break;
            case 4:
                return '星期四';
                break;
            case 5:
                return '星期五';
                break;
            case 6:
                return '星期六';
                break;
            case 0:
                return '星期日';
                break;
            default:;
        };
    }
}
