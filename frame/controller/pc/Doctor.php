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

        $keshi = table('ys_data')->where(array('hospital' => $topCatid))->group('keshi')->field('keshi')->find('one', true);

        foreach ($keshi as $key => $value) {
            foreach ($doctorList as $k => $v) {
                if (in_array($value, $v)) {
                    $doctor[$value][$v['id']] = $v;
                }
            }
        }

        $week = date('w');
        for ($i = 0; $i < 7; $i++) {
            $time = $week == 6 ? 7 : $week + 1;

            $weekArray[$i]['zh']   = $this->week($week);
            $weekArray[$i]['data'] = date('m-d', strtotime("+$i day"));
            $weekArray[$i]['unix'] = strtotime(date('Y-m-d', strtotime("+$i day")));
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

    public function detail()
    {
        $id    = get('id', 'intval', 0);
        $catid = get('catid', 'intval', 0);
        if (!$catid || !$id) {
            die('参数错误');
        }
        $topCatid     = table('category')->where(array('catid' => $catid))->field('parentid')->find('one');
        $templateList = $this->checkedCatid();

        $doctor = table('ys')->where(array('id' => $id))->field('id,title,thumb,zc')->find();
        $ysTime = table('ys_time')->where(array('ys_id' => $id))->find('array');
        if ($ysTime) {
            foreach ($ysTime as $key => $value) {
                $doctorTime[$value['time']] = $value;
            }
        }
        $doctor['time'] = (array) $doctorTime;

        $week = date('w');
        for ($i = 0; $i < 7; $i++) {
            $time = $week == 6 ? 7 : $week + 1;

            $weekArray[$i]['zh']   = $this->week($week);
            $weekArray[$i]['data'] = date('m-d', strtotime("+$i day"));
            $weekArray[$i]['unix'] = strtotime(date('Y-m-d', strtotime("+$i day")));
            $weekArray[$i]['time'] = array($time . '1', $time . '2', $time . '3');

            $week == 6 ? $week = 0 : $week++;
        }

        $this->assign('catid', $catid);
        $this->assign('doctor', $doctor);
        $this->assign('week', $weekArray);
        switch ($templateList) {
            case 'default':
                $this->show('/pc/doctor/detail');
                break;
            default:
                break;
        }
    }

    public function orderIndex()
    {
        $catid = get('catid', 'intval', 0);
        $id    = get('id', 'intval', 0);
        $time  = get('time', 'trim');
        if (!$id || !$time) {
            die('参数错误');
        }

        $weekCopy = array(
            '11' => '星期一 上午', '12' => '星期一 下午', '13' => '星期一 晚上',
            '21' => '星期二 上午', '22' => '星期二 下午', '23' => '星期二 晚上',
            '31' => '星期三 上午', '32' => '星期三 下午', '33' => '星期三 晚上',
            '41' => '星期四 上午', '42' => '星期四 下午', '43' => '星期四 晚上',
            '51' => '星期五 上午', '52' => '星期五 下午', '53' => '星期五 晚上',
            '61' => '星期六 上午', '62' => '星期六 下午', '63' => '星期六 晚上',
            '71' => '星期日 上午', '72' => '星期日 下午', '73' => '星期日 晚上',
        );

        $data                    = table('ys_time')->where(array('id' => $id))->field('ys_id,time,stock')->find();
        $data['time']            = date('Y-m-d', $time) . ' ' . $weekCopy[$data['time']];
        $data['unix']            = $time;
        $data['doctor']          = table('ys')->where(array('id' => $data['ys_id']))->field('title')->find();
        $data['doctor']['keshi'] = table('ys_data')->where(array('id' => $data['ys_id']))->field('keshi')->find('one');

        print_r($data);

        //获取所选模板
        $templateList = $this->checkedCatid();
        $this->assign('catid', $catid);
        $this->assign('data', $data);
        switch ($templateList) {
            case 'default':
                $this->show('/pc/doctor/order_index');
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
