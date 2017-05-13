<?php
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/1 0001
 * Time: 04:58
 */
header("Content-Type:text/html;charset=utf-8");
include "Url.class.php";
$url = isset($_GET['url']) ? $_GET['url'] : "";
if($url == ""){
    exit('{"code": 65535,"msg": "缺失参数"}');
}
$api = new Url($url);
$api->getData();