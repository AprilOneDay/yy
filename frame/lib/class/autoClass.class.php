<?php
class LOAD
{
    public $model;
    public $controller;
    public $action;

    public function __contruction()
    {
        $this->model      = isset($_GET['m']) ? get('m', 'trim') : ''; //項目文件
        $this->controller = isset($_GET['c']) ? get('c', 'trim') : 'index'; //调用控制器
        $this->action     = isset($_GET['a']) ? get('a', 'trim') : 'index'; //调用方法名
    }

    public static function loadClass($name)
    {
        $filename = FRAME_LIB_CLASS_PATH . $name . ".class.php";
        if (is_file($filename)) {
            return include_once $filename;
        } else {
            //print('Class模块：' . $filename . '不存在');
        }
    }

    public static function loadDao($name)
    {
        $filename = DAO_PATH . $name . ".php";
        if (is_file($filename)) {
            return include_once $filename;
        } else {
            //die('Dao模块：' . $filename . '不存在');
        }
    }

    public static function loadController($name)
    {
        $model    = isset($_GET['m']) ? get('m', 'trim') : ''; //項目文件
        $filename = CONTROLLER_PATH . $name . ".php";
        if ($model) {
            $filename = CONTROLLER_PATH . $model . '/' . $name . ".php";
        }

        if (is_file($filename)) {
            return include_once $filename;
        } else {
            //die('Controller模块：' . $filename . '不存在');
        }
    }

}
