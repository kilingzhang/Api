<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/20 0020
 * Time: 02:17
 */
include_once "Snoopy.class.php";

class Api
{
    protected $snoopy;
    protected $code;
    protected $msg;
    protected $data;
    protected $url;
    protected $limit = 15;
    protected $offset = 0;
    protected $page = 1;
    protected $per_page = 15;
    protected $per_num = 25;


    /**
     * PortalCore constructor.
     * 参数已过滤SQL注入 调用时无需处理
     * @param string $sid
     * @param string $password
     * @param string $type
     */
    public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        header("Content-Type:application/json; charset=utf-8");
        $this->snoopy = new Snoopy();
    }

    /**
     * 日志
     * @param $logMsg
     */
    protected function insertLog($logMsg)
    {
    }

    /**
     * 格式化返回接口数据
     * @return string
     */
    public function getData()
    {
        $jsonArray = array(
            'code' => $this->code,
            'message' => urlencode($this->msg),
            'data' => $this->data,
        );
        $jsonData = json_encode($jsonArray, JSON_UNESCAPED_UNICODE);
        return urldecode($jsonData);
    }

    /**
     * 设置接口提示信息
     * @param $retVal
     * @return mixed
     */
    protected function setMsg($retVal)
    {
        $retValToMsg = array(
            0 => '成功',
            1 => '用户名或密码错误',
            2 => '超时',
            3 => '网络故障',
            4 => '未知错误',
            5 => '验证码错误',
            6 => '成功(未修改密码)',
            7 => '学校服务器500 请重新登陆',
            8 => '数据获取失败 请重试',
            65535 => '缺失参数',
        );
        $this->code = $retVal;
        $this->msg = $retValToMsg[$retVal];
        return $this->code;
    }


}