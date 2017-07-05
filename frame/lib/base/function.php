<?php
defined('PATH') or define('PATH', $_SERVER['DOCUMENT_ROOT']); // 系统更目录
defined('FRAME_PATH') or define('FRAME_PATH', PATH . '/frame/'); // 框架根目录
defined('FRAME_LIB_PATH') or define('FRAME_LIB_PATH', FRAME_PATH . '/lib/'); // 框架视核心目录
defined('FRAME_LIB_CONFIG_PATH') or define('FRAME_LIB_CONFIG_PATH', FRAME_LIB_PATH . 'config/'); // 框架视核配置心目录

include_once FRAME_LIB_CONFIG_PATH . 'path.php'; //调用路径配置文档
include_once FRAME_LIB_CLASS_PATH . 'autoClass.class.php'; //调用自动加载类
spl_autoload_register('LOAD::loadClass');
spl_autoload_register('LOAD::loadDao');
spl_autoload_register('LOAD::loadController');

//弹出消息返回地址
function message($info, $url)
{
    if ($url) {
        echo '<script language="javascript">alert("' . $info . '");window.location.href="' . $url . '";</script>';
    } else {
        echo '<script language="javascript">alert("' . $info . '");</script>';
    }
}

//POST过滤
function post($name, $type = '', $default = '')
{
    if ($name == "all") {
        foreach ($_POST as $key => $val) {
            $val        = trim($val);
            $data[$key] = !get_magic_quotes_gpc() ? htmlspecialchars(addslashes($val), ENT_QUOTES, 'UTF-8') : htmlspecialchars($val, ENT_QUOTES, 'UTF-8');

        }

    } else {
        $data = isset($_POST[$name]) ? $_POST[$name] : '';
    }

    if ($name != 'all' && !is_array($data)) {
        switch ($type) {
            case 'intval':
                $data = $data === '' ? intval($default) : intval($data);
                break;
            case 'float':
                $data = $data === '' ? float($default) : float($data);
                break;
            case 'text':
                $data = $data === '' ? strval($default) : strval($data);
                break;
            case 'trim':
                $data = $data === '' ? trim($default) : trim($data);
                break;
            case 'bool':
                $data = $data === '' ? (bool) $default : (bool) $data;
                break;
            case 'json':
                $data = $data === '' ? $default : json_decode($data, true);
                break;
            default:
                # code...
                break;
        }
    }
    return $data;
}

//GET过滤
function get($name, $type = '', $default = '')
{
    if ($name == "all") {

        foreach ($_GET as $key => $val) {
            $val        = trim($val);
            $data[$key] = !get_magic_quotes_gpc() ? htmlspecialchars(addslashes($val), ENT_QUOTES, 'UTF-8') : htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
        }

    } else {
        $data = isset($_GET[$name]) ? $_GET[$name] : '';
    }

    if ($name != 'all' && !is_array($data)) {
        switch ($type) {
            case 'intval':
                $data = $data === '' ? intval($default) : intval($data);
                break;
            case 'float':
                $data = $data === '' ? float($default) : float($data);
                break;
            case 'text':
                $data = $data === '' ? strval($default) : strval($data);
                break;
            case 'trim':
                $data = $data === '' ? trim($default) : trim($data);
                break;
            case 'bool':
                $data = $data === '' ? (bool) $default : (bool) $data;
                break;
            case 'json':
                $data = $data === '' ? $default : json_decode($data, true);
                break;
            default:
                # code...
                break;
        }
    }
    return $data;
}

//判断文件是否存在
function existsUrl($url)
{

    if ($url == '') {return false;}
    if (stripos($url, 'http') === false) {
        $http = $_SERVER['SERVER_NAME'];
        $url  = 'http://' . $http . '/' . $url;
    }
    $opts = array(
        'http' => array(
            'timeout' => 30,
        ),
    );

    $context = stream_context_create($opts);
    $rest    = @file_data_contents($url, false, $context);

    if ($rest) {
        return true;
    } else {
        return false;
    }
}

function table($name, $isTablepre = true)
{
    $do = dbMysqli::getInstance(); //单例实例化
    if ($name) {
        return $do->table($name, $isTablepre);
    }

    return $do;
}

function dao($name)
{
    if (class_exists($name)) {
        $dao = new $name();
        return $dao;
    }

    die('Dao方法：' . $name . '不存在');
}

//包含文件
function comprise($path)
{
    include VIEW_PATH . $path . '.html';
}

//获取config下配置文档
function vars($path = 'urls', $name = '')
{
    $data = include FRAME_LIB_CONFIG_PATH . $path . '.php';
    if ($name === '') {
        return $data;
    }

    return @$data[$name];
}

//创建getUrl
function url($location = '', $params = array())
{
    $locationUrl = $location;
    if (stripos($location, '/') === false && $location != '') {
        $locationUrl = '/frame/index.php?m=' . MODEL . '&c=' . CONTROLLER . '&a=' . $location;
    }
    $param = '';
    if (!empty($params)) {
        foreach ($params as $key => $value) {
            if ($key == 0 && stripos($locationUrl, '?') === false) {
                $param = '?' . $key . '=' . $value;
            } else {
                $param .= '&' . $key . '=' . $value;
            }
        }
    }

    return $locationUrl . $param;
}

//保存Cookie
function cookie($name = '', $value = '', $expire = '86400')
{
    if (!$name) {
        return false;
    }
    setcookie($name, $value, time() + $expire, '/');
}

//保存Session
function session($name = '', $value = '')
{
    !isset($_SESSION) ? session_start() : '';
    // 数组
    if (is_array($name)) {
        foreach ($name as $k => $v) {
            $_SESSION[$k] = $v;
        }
    }
    //二维数组
    elseif (is_array($value)) {
        foreach ($value as $k => $v) {
            $_SESSION[$name][$k] = $v;
        }
    } else {
        $_SESSION[$name] = $value;
    }

}

//读取Session
function getSession($name)
{
    !isset($_SESSION) ? session_start() : '';
    $data = isset($_SESSION[$name]) ? $_SESSION[$name] : '';
    if (is_object($data)) {
        $data = (array) $data;
    }
    return $data;
}

//下划线转驼峰
function convertUnderline($string)
{
    $string = preg_replace_callback('/([-_]+([a-z]{1}))/i', function ($matches) {
        return strtoupper($matches[2]);
    }, $string);
    return $string;
}

//驼峰转下划线
function humpToLine($string)
{
    $string = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
        return '_' . strtolower($matches[0]);
    }, $string);
    return $string;
}

//生成密文参数
function showValue($code)
{
    $user = getSession('yh');
    if (!$user || !$code) {
        return '';
    }

    $data['ID']    = $user['sfz'];
    $data['Name']  = $user['xm'];
    $data['Code']  = $code;
    $data['Timer'] = time();

    $encrypt = new encrypt();
    $json    = $encrypt->base64Encrypt($data, 'PKCS7');

    return $json;
}

//保存课程记录
function saveCourseLog($code, $type)
{
    $user = getSession('yh');
    if (!$user || !$code || !$type) {
        return '参数错误';
    }

    $is = table('cqks_course', false)->where(array('uid' => $user['bh'], 'code' => $code, 'status' => 1, 'yeard' => date('Y'), 'type' => $type))->find();
    if ($is) {
        return false;
    }

    if ($type == 1) {
        $title = table('kj')->where(array('kc_sn' => $code))->field('title')->find('one');
    } else {
        $title = table('kj')->where(array('id' => $code))->field('title')->find('one');
    }

    $data['title']   = $title;
    $data['uid']     = $user['bh'];
    $data['type']    = $type;
    $data['code']    = $code;
    $data['status']  = 1;
    $data['time']    = time();
    $data['created'] = time();
    $data['yeard']   = date('Y');

    $reslut = table('cqks_course', false)->add($data);
    return true;
}
