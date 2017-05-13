<?php


class ReplyUtil
{

    public static function setMsg($retVal)
    {
        $retValToMsg = array(
            "-1" => '接口调用身份验证失败',
            "0" => '成功',
            "1" => '用户名或密码错误',
            "2" => '超时',
            "3" => '网络故障',
            "4" => '未知错误',
            "5" => '验证码错误',
            "6" => '成功(未修改密码)',
            "7" => '未知方法调用',
            "8" => '亲，您未绑定账号呦~ 请先绑定账号才可以和我玩耍~
            ---------------
            教务2015551439密码666666
            ---------------
            [face4end]',
            "65535" => '缺失参数'
        );
        if (array_key_exists($retVal, $retValToMsg)) {
            return json_encode(array(
                'code' => $retVal,
                'msg' => $retValToMsg["$retVal"],
                'data' => null,
            ), JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode(array(
                'code' => $retVal,
                'msg' => null,
                'data' => null,
            ), JSON_UNESCAPED_UNICODE);
        }

    }


    public static function Success($data, $param = "json")
    {
        $Reply = array(
            'code' => 0,
            'msg' => "success",
            'data' => $data,
        );
        return json_encode($Reply, JSON_UNESCAPED_UNICODE);
    }

    public static function Error($code, $error = "", $data = null, $param = "json")
    {

        if ($error == "") {
            return self::setMsg($code);
        } else {
            $Reply = array(
                'code' => $code,
                'msg' => $error,
                'data' => $data,
            );
        }
        return json_encode($Reply, JSON_UNESCAPED_UNICODE);
    }

}

?>