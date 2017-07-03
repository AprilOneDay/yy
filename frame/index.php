<?php
defined('PATH') or define('PATH', $_SERVER['DOCUMENT_ROOT']); // 系统更目录
defined('FRAME_PATH') or define('FRAME_PATH', PATH . '/frame/'); // 框架根目录
defined('FRAME_LIB_PATH') or define('FRAME_LIB_PATH', FRAME_PATH . '/lib/'); // 框架视核心目录
defined('FRAME_LIB_CONFIG_PATH') or define('FRAME_LIB_CONFIG_PATH', FRAME_LIB_PATH . 'config/'); // 框架视核配置心目录

include FRAME_LIB_CONFIG_PATH . 'path.php'; //调用路径配置文档
include FRAME_LIB_BASE_PATH . 'function.php'; //调用公共方法

//调用页面方法
$model      = lcfirst(get('m', 'text', '')); //項目文件
$controller = ucfirst(get('c', 'text', 'index')); //调用控制器
$action     = convertUnderline(lcfirst(get('a', 'text', 'index'))); //调用方法名
$isExc      = get('isExc', 'bool', true); //是否执行

$frameUrl = array('model' => $model, 'controller' => $controller, 'action' => $action);

defined('MODEL') or define('MODEL', $model); // 当前文件
defined('CONTROLLER') or define('CONTROLLER', $controller); // 控制器
defined('ACTION') or define('ACTION', $action); // 方法名
defined('LOCATION') or define('LOCATION', '/frame/index.php?m=' . $frameUrl['model'] . '&c=' . $frameUrl['controller'] . '&a=' . $frameUrl['action']);

if ($isExc) {
    if (class_exists($frameUrl['controller'])) {
        $model = new $frameUrl['controller']();
    } else {
        die('控制器：' . $frameUrl['controller'] . '不存在');
    }

    if (!method_exists($model, $frameUrl['action'])) {
        die($frameUrl['controller'] . '控制器中' . $frameUrl['action'] . '方法不存在');
    }

    $action = $model->$action();
}
