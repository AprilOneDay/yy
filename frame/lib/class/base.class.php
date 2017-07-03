<?php
class base
{

    public $do;
    public $id;
    public $page;
    public $assign = array();

    public function __construct()
    {
        $this->id   = get('id', 'intval');
        $this->page = get('page', 'intval');
    }

    /**
     * 赋值
     * @date   2017-05-14T21:30:23+0800
     * @author ChenMingjiang
     * @param  [type]                   $field [description]
     * @param  [type]                   $value [description]
     * @return [type]                          [description]
     */
    public function assign($name, $value = '')
    {
        if (is_array($name)) {
            $this->assign = array_merge($this->assign, $name);
        } else {
            $this->assign[$name] = $value;
        }
    }

    /**
     * 视图显示
     * @date   2017-05-07T21:08:44+0800
     * @author ChenMingjiang
     * @param  string                   $viewPath [description]
     * @param  boolean                  $peg      [true 自定义路径]
     * @return [type]                             [description]
     */
    public function show($viewPath = '', $peg = false)
    {

        if ($this->assign) {
            // 模板阵列变量分解成为独立变量
            extract($this->assign, EXTR_OVERWRITE);
        }

        extract(get('all'), EXTR_OVERWRITE);

        if (!$peg) {
            if (!$viewPath) {
                $path = VIEW_PATH . CONTROLLER . '/index.html';
            }
            //绝对路径
            elseif (stripos($viewPath, '/') === 0) {
                $path = VIEW_PATH . substr($viewPath, 1) . '.html';
            }
            //相对路径
            else {
                $path = VIEW_PATH . CONTROLLER . '/' . $viewPath . '.html';
            }
        } else {
            $path = $viewPath;
        }

        if (!is_file($path)) {
            die('视图地址' . $path . '不存在');
        }

        $cachePath = DATA_PATH . md5($path) . '.php';
        if (is_file($cachePath) && filemtime($path) == filemtime($cachePath)) {
            include $cachePath;
        } else {
            //处理视图模板
            $template = new template($path);
            $template->getContent();
            include $template->loadPath;
        }
    }

    /**
     * ajax返回
     * @date   2017-06-13T22:48:29+0800
     * @author ChenMingjiang
     * @param  [type]                   $value [description]
     * @return [type]                          [description]
     */
    public function ajaxReturn($value)
    {
        die(json_encode($value));
    }
}
