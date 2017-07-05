﻿<?php
class dbMysqli
{

    private static $instance;

    public $dbInfo; //数据库连接信息
    public $db_tablepre; //表前缀

    public $link;
    public $result;
    public $querystring;
    public $isclose;
    public $safecheck;
    public $debug;

    public $table;
    public $join;
    public $field;
    public $where;
    public $order;
    public $limit;
    public $group;
    public $total;
    public $excID; //插入ID
    public $_sql; //最后执行sql

    private function __construct($db_host = 'localhost', $db_user = 'root', $db_pwd = 'root', $db_name = 'yy', $db_tablepre = 'yy_')
    {
        $db = include $_SERVER['DOCUMENT_ROOT'] . '/caches/configs/database.php';

        $this->dbInfo['db_host'] = isset($db['default']['hostname']) ? $db['default']['hostname'] : $db_host;
        $this->dbInfo['db_user'] = isset($db['default']['username']) ? $db['default']['username'] : $db_user;
        $this->dbInfo['db_pwd']  = isset($db['default']['password']) ? $db['default']['password'] : $db_pwd;
        $this->dbInfo['db_name'] = isset($db['default']['database']) ? $db['default']['database'] : $db_name;
        $this->db_tablepre       = isset($db['default']['tablepre']) ? $db['default']['tablepre'] : $db_tablepre;
        $this->debug             = 'true'; //true 开启显示调试信息 force 强制显示
        if ($this->dbInfo['db_host'] == '' || $this->dbInfo['db_user'] == '' || $this->dbInfo['db_name'] == '') {
            die('接数据库信息有误');
        }

        $this->link = $this->openMysql();

        mysqli_query($this->link, 'set names utf8');

        if (!$this->link) {
            print_r($this->dbInfo);
            die('连接数据库失败，可能数据库密码不对或数据库服务器出错！');
        } else {
            //print_r( $this->linkid );
        }

    }

    //单例实例化 避免重复New暂用资源
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new dbMysqli;
        }
        return self::$instance;

    }

    /**
     * 连接数据库
     * @date   2017-03-19T16:18:28+0800
     * @author ChenMingjiang
     */
    public function openMysql()
    {
        $rest = mysqli_connect($this->dbInfo['db_host'], $this->dbInfo['db_user'], $this->dbInfo['db_pwd'], $this->dbInfo['db_name']);
        return $rest;
    }

    public function getSql()
    {
        return $this->_sql;
    }

    /**
     * 数据表
     * @date   2017-03-19T16:18:23+0800
     * @author ChenMingjiang
     * @param  [type]                   $table  [description]
     * @param  string                   $table2 [description]
     * @return [type]                           [description]
     */
    public function table($table, $isTablepre = true)
    {
        $this->table = $table;
        if ($isTablepre) {
            $this->db_tablepre != '' ? $this->table = $this->db_tablepre . $this->table : '';
        }

        $this->where = '';
        $this->field = '';
        $this->limit = '';
        $this->group = '';
        $this->order = '';
        $this->join  = '';
        return $this;
    }

    /**
     * 获取表名称
     * @date   2017-06-10T22:56:01+0800
     * @author ChenMingjiang
     * @param  [type]                   $table [description]
     * @return [type]                          [description]
     */
    public function tableName()
    {
        return $this->table;
    }

    /**
     * 查询条件
     * @date   2017-03-19T16:18:18+0800
     * @author ChenMingjiang
     * @param  [type]                   $where [description]
     * @return [type]                          [description]
     */
    public function where($where)
    {
        if (is_array($where)) {
            $newWhere = '';
            foreach ($where as $k => $v) {
                if (is_array($v)) {
                    if ($v[0] == '>' || $v[0] == '<' || $v[0] == '>=' || $v[0] == '<=' || $v[0] == 'like') {
                        $newWhere .= $k . '  ' . $v[0] . ' \'' . $v[1] . '\' AND ';
                    } elseif ($v[0] == 'in' || $v['0'] == 'not in') {
                        $newWhere .= $k . '  ' . $v[0] . ' (' . $v[1] . ') AND ';
                    }
                } elseif ($k == '_string') {
                    $newWhere .= $v;
                } else {
                    $newWhere .= $k . ' = \'' . $v . '\' AND ';
                }
            }
        } else {
            $newWhere = $where;
        }
        $this->where = ' WHERE ' . substr($newWhere, 0, -4);
        return $this;
    }

    /**
     * 关联查询
     * @date   2017-06-10T22:52:34+0800
     * @author ChenMingjiang
     * @param  [type]                   $table [description]
     * @param  [type]                   $where [description]
     * @param  string                   $float [description]
     * @return [type]                          [description]
     */
    public function join($table, $where, $float = 'left')
    {
        $this->join = ' ' . $float . ' JOIN ' . $table . ' ON ' . $where;
        return $this;
    }

    /**
     * 查询数量
     * @date   2017-03-19T16:18:13+0800
     * @author ChenMingjiang
     * @param  [type]                   $limit [description]
     * @return [type]                          [description]
     */
    public function limit($limit, $pageSize = '')
    {
        $this->limit = ' LIMIT ' . $limit;
        if ($pageSize) {
            $this->limit = ' LIMIT ' . $limit . ',' . $pageSize;
        }

        return $this;
    }

    /**
     * 查询字段
     * @date   2017-03-19T16:18:09+0800
     * @author ChenMingjiang
     * @param  [type]                   $field [description]
     * @return [type]                          [description]
     */
    public function field($field)
    {
        $newField = '';
        if (is_array($field)) {
            $i = 0;
            foreach ($field as $k => $v) {
                if ($i == 0) {
                    $newField .= $v;
                } else {
                    $newField .= "," . $v;
                }

                $i++;
            }
        } else {
            $newField = $field;
        }
        $this->field = $newField;
        return $this;
    }

    public function group($value = '')
    {
        if (is_array($value)) {
            $i = 0;
            foreach ($value as $k => $v) {
                if ($i == 0) {
                    $newGroup .= $v;
                } else {
                    $newGroup .= "," . $v;
                }

                $i++;
            }
        } else {
            $newGroup = $value;
        }
        $this->group = ' GROUP BY ' . $newGroup;
        return $this;
    }

    public function order($value)
    {
        if (is_array($value)) {
            $i = 0;
            foreach ($value as $k => $v) {
                if ($i == 0) {
                    $newValue .= $v;
                } else {
                    $newValue .= "," . $v;
                }

                $i++;
            }
        } else {
            $newValue = $value;
        }
        $this->order = ' ORDER BY ' . $newValue;
        return $this;
    }

    /**
     * 判断是否存在该数据表
     * @date   2017-03-19T16:18:01+0800
     * @author ChenMingjiang
     * @param  string                   $table [description]
     * @return [type]                          [description]
     */
    public function existsTbale($table = '')
    {
        if ($table == '') {$table = $this->table;}
        $sql                      = "SELECT COUNT(*) as total  FROM information_schema.TABLES WHERE TABLE_NAME='$table'";
        $t                        = mysqli_fetch_array(mysqli_query($this->link, $sql));
        if ($t['total'] == 0) {return false;}
        return true;
    }

    /**
     * 查询表字段名
     * @date   2017-03-19T16:14:45+0800
     * @author ChenMingjiang
     * @return [type]                   [description]
     */
    public function getField()
    {
        $this->where = 'table_name = ' . "'" . $this->table . "'";
        $this->field = 'column_name';
        $this->table = 'information_schema.columns';
        $this->limit = '99';

        $sql    = "select " . $this->field . " from " . $this->table;
        $result = $this->query($sql);

        $data = mysqli_fetch_array($result, MYSQL_ASSOC);

        foreach ($data as $key => $value) {
            $varField[$key] = $value;
        }

        return $varField;
    }

    /**
     * 统计总数
     * @date   2017-06-14T11:09:55+0800
     * @author ChenMingjiang
     * @return [type]                   [description]
     */
    public function count($field = 't')
    {

        $this->limit(1);

        $sql = 'SELECT  COUNT(*) AS ' . $field . '  FROM ' . $this->table;

        if ($this->join) {
            $sql .= $this->join;
        }

        if ($this->where != '') {
            $sql .= $this->where;
        }
        if ($this->group != '') {
            $sql .= $this->group;
        }

        $sql .= $this->limit;

        $result = $this->query($sql);
        $data   = mysqli_fetch_array($result, MYSQL_ASSOC);

        return $data[$field];
    }

    /**
     * 查询单条/多条信息
     * @date   2017-03-19T16:18:52+0800
     * @author ChenMingjiang
     * @param  string                   $value [array:查询数据 one:查询单条单个字段内容]
     * @return [type]                          [description]
     */
    public function find($value = '', $isArray = false)
    {
        if ($this->table == '') {die('请选择数据表');}
        if ($this->field == '') {$this->field = '*';}
        if ($this->limit == '' && $value != 'array' && !$isArray) {$this->limit(1);}

        if ($value == 'array' || ($value == 'one' && $isArray)) {
            if ($this->limit == '') {$this->limit(1000);}
        }

        $sql = 'SELECT ' . $this->field . ' FROM ' . $this->table;
        if ($this->join) {
            $sql .= $this->join;
        }

        if ($this->where != '') {
            $sql .= $this->where;
        }
        if ($this->group != '') {
            $sql .= $this->group;
        }
        if ($this->order) {
            $sql .= $this->order;
        }

        if ($this->limit) {
            $sql .= $this->limit;
        }

        $result = $this->query($sql);

        //获取记录条数
        $this->total = mysqli_num_rows($result);

        if ($this->total == 0) {return false;}
        //单个字段模式
        if ($value == 'one' && !$isArray) {
            $row = mysqli_fetch_array($result, MYSQL_NUM);
            if (empty($row)) {
                return false;
            }

            if (count($row) > 1) {
                die('sql模块中one只能查询单个字段内容请设置field函数');
            }

            return $row[0];
        }
        //单字段数组模式
        elseif ($value == 'one' && $isArray) {
            while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                if (count($row) > 1) {
                    die('sql模块中one只能查询单个字段内容请设置field函数');
                }
                $data[] = $row[$this->field];
            }

            if (empty($data)) {
                return false;
            }
            return $data;
        }
        //三维数组模式
        elseif ($this->total > 1 || $value == 'array') {
            while ($row = mysqli_fetch_array($result, MYSQL_ASSOC)) {
                $data[] = $row;
            }
            for ($i = 0, $n = count($data); $i < $n; $i++) {
                if (is_array($data[$i])) {
                    foreach ($data[$i] as $key => $value) {
                        $datas[$i][$key] = $value;
                    }
                }
            }
            return $datas;
        }
        //二维数组模式
        else {
            $row = mysqli_fetch_array($result, MYSQL_ASSOC);

            foreach ($row as $key => $value) {
                $data[$key] = $value;
            }

            return $data;
        }
    }

    /**
     * 添加
     * @date   2017-03-19T16:19:43+0800
     * @author ChenMingjiang
     * @param  string                   $data [description]
     */
    public function add($data = '')
    {
        $newField = '';
        if (is_array($data)) {
            $i = 0;
            foreach ($data as $k => $v) {
                if ($i == 0) {
                    $newField .= '`' . $k . '` = \'' . $v . '\'';
                } else {
                    $newField .= ",`" . $k . '`=\'' . $v . '\'';
                }
                $i++;
            }
        } else {
            $newField = $field;
        }
        $this->field = $newField;

        $sql = 'INSERT INTO `' . $this->table . '` SET ' . $this->field;
        $sql .= $this->where ? $this->where : '';
        $result = $this->query($sql);
        $result = mysqli_insert_id($this->link);
        return $result;
    }

    /**
     * 修改保存
     * @date   2017-03-19T16:20:24+0800
     * @author ChenMingjiang
     * @param  string                   $data [description]
     * @return [type]                         [description]
     */
    public function save($data = '')
    {
        $newField = '';
        if (is_array($data)) {
            $i = 0;
            foreach ($data as $k => $v) {
                if ($i == 0) {
                    $newField .= '`' . $k . '` = \'' . $v . '\'';
                } else {
                    $newField .= ",`" . $k . '`=\'' . $v . '\'';
                }
                $i++;
            }
        } else {
            $newField = $field;
        }
        $this->field = $newField;

        $sql = 'UPDATE ' . $this->table . ' SET ' . $this->field;
        $sql .= $this->where ? $this->where : '';
        $result = $this->query($sql);
        return $result;
    }

    /**
     * 删除数据
     * @date   2017-03-19T16:20:32+0800
     * @author ChenMingjiang
     * @return [type]                   [description]
     */
    public function delete()
    {
        $sql    = 'DELETE FROM ' . $this->table . $this->where;
        $result = $this->query($sql);
        return $result;
    }

    /**
     * 执行
     * @date   2017-03-19T16:20:36+0800
     * @author ChenMingjiang
     * @param  [type]                   $sql [description]
     * @return [type]                        [description]
     */
    public function query($sql)
    {
        $this->_sql = $sql;
        $result     = mysqli_query($this->link, $sql);
        if ($result) {
            return $result;
        } else {
            die('错误SQL:' . $sql);
        }

    }
}
