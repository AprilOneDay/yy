<?php
/**
 *  extention.func.php 用户自定义函数库
 *
 * @copyright            (C) 2005-2010 PHPCMS
 * @license                http://www.phpcms.cn/license/
 * @lastmodify            2010-10-27
 */
function ystime($value)
{
    include_once $_SERVER['DOCUMENT_ROOT'] . '/frame/lib/base/function.php';
    $timeArray = array(
        '星期一上午' => '11',
        '星期一下午' => '12',
        '星期一晚上' => '13',
        '星期二上午' => '21',
        '星期二下午' => '22',
        '星期二晚上' => '23',
        '星期三上午' => '31',
        '星期三下午' => '32',
        '星期三晚上' => '33',
        '星期四上午' => '41',
        '星期四下午' => '42',
        '星期四晚上' => '43',
        '星期五上午' => '51',
        '星期五下午' => '52',
        '星期五晚上' => '53',
        '星期六上午' => '61',
        '星期六下午' => '62',
        '星期六晚上' => '63',
        '星期日上午' => '71',
        '星期日下午' => '72',
        '星期日晚上' => '73',
    );

    $ys = table('ys_time')->where(array('ys_id' => $value))->find('array');
    if ($ys) {
        foreach ($ys as $key => $value) {
            $check[]                         = $value['time'];
            $ysTime[$value['time']]['stock'] = $value['stock'];
            $ysTime[$value['time']]['id']    = $value['id'];
        }
    }

    foreach ($timeArray as $key => $value) {
        if (in_array($value, $check)) {
            $str .= '<input type="checkbox" value="' . $value . '" name="time[]" checked="checked">' . $key . ' <input type="text" placeholder="可预约位数" name="stock[' . $value . ']" value="' . $ysTime[$value]['stock'] . '" style="width:80px;"><input type="hidden" name="ysTime[' . $value . ']" value="' . $ysTime[$value]['id'] . '"><br/>';
        } else {
            $str .= '<input type="checkbox" value="' . $value . '" name="time[]">' . $key . ' <input type="text" placeholder="可预约位数" name="stock[' . $value . ']" style="width:80px;"><br/>';
        }
    }

    return $str;
}

function hospital($value)
{
    include_once $_SERVER['DOCUMENT_ROOT'] . '/frame/lib/base/function.php';
    $list = table('category')->where(array('parentid' => 6))->field('catname,catid')->find('array');

    $srt = "<select name=\"info[hospital]\" id=\"hospital\" >";
    foreach ($list as $v) {
        if ($value == $v['catid']) {
            $srt .= "<option value='{$v['catid']}' selected>{$v['catname']}</option>";
        } else {
            $srt .= "<option value='{$v['catid']}'>{$v['catname']}</option>";
        }
    }
    $srt .= "</select>";
    return $srt;
}

function keshi($value)
{
    include $_SERVER['DOCUMENT_ROOT'] . '/public/config/index.php';
    //$db = pc_base::load_model('content_model');
    //$db->table_name = 'dsyy_keshi';
    //$res = $db->select();
    $arrchildid = $do->table('category')->where("catid='26'")->field('arrchildid')->find('one');
    $list       = $do->table('news')->where("catid in($arrchildid)")->field('title')->find('array');
    $srt        = "<select name=\"info[keshi]\" id=\"keshi\" >";
    foreach ($list as $v) {
        if ($value == $v['title']) {
            $srt .= "<option value='{$v['title']}' selected>{$v['title']}</option>";
        } else {
            $srt .= "<option value='{$v['title']}'>{$v['title']}</option>";
        }
    }
    $srt .= "</select>";
    return $srt;
}
