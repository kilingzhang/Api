<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/6 0006
 * Time: 15:41
 */
class User
{
    public $sid;
    public $password;
    public $DB;
    public $data;

    function __construct($QQ)
    {
        $this->QQ = $QQ;
        $this->DB = new DB(dbHost, dbUser, dbPassword, dbTable, dbport);
        self::init();
    }

    public function init()
    {
        $sql = "select * from yibao_user where QQ = '$this->QQ'";
        $res = $this->DB->query($sql);
        if (mysqli_num_rows($res)) {
            $this->data = $this->DB->getone($res);
        } else {
            $this->data = array();
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function insertUser($QQ, $sid, $password, $data)
    {
        $sql = "insert into yibao_user VALUES('$QQ','$sid','$password','{$data['name']}','{$data['data_of_birth']}','{$data['college']}','{$data['major']}','{$data['class']}','{$data['nation']}','{$data['province']}','{$data['sex']}')";
        $res = $this->DB->query($sql);
        if ($res) {
            return true;
        } else {
            return false;

        }
    }


    public function updateUser($QQ, $sid, $password, $data)
    {
        $sql = "UPDATE `yibao_user` SET `sid`='$sid',`pw_portal`='$password',`name`='{$data['name']}',`data_of_birth`='{$data['data_of_birth']}',`college`='{$data['college']}',`major`='{$data['major']}',`class`='{$data['class']}',`nation`='{$data['nation']}',`province`='{$data['province']}',`sex`='{$data['sex']}' WHERE QQ = '$QQ'";
        $res = $this->DB->query($sql);
        if ($res) {
            return true;
        } else {
            return false;

        }
    }

}