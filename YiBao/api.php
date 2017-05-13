<?php
/**
 * Created by PhpStorm.
 * User.class: Slight
 * Date: 2017/2/10
 * Time: 19:24
 */
include_once "lib/config.php";
include_once "Route.php";
include_once "lib/Snoopy.class.php";
include_once "lib/ReplyUtil.php";
include_once "lib/db.class.php";
include_once "lib/URL.php";
include_once "lib/CQ.php";
include_once 'lib/VerifyHash.class.php';
include_once 'lib/User.class.php';
include_once 'lib/Portal.class.php';
include_once 'lib/TulingMsg.php';
include_once 'lib/MsgTool.php';
include_once 'lib/CurrentCourse.php';
$role = isset($_GET['role']) ? $_GET['role'] : "";
$hash = isset($_GET['hash']) ? $_GET['hash'] : "";
$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : "";
$text = isset($_GET['text']) ? urldecode($_GET['text']) : "";
$other = isset($_GET['other']) ? $_GET['other'] : "";
$on = isset($_GET['on']) ? $_GET['on'] : "false";

////Verify Hash
//$verify = new VerifyHash($role,$hash);
//if(!$verify->checkRole()){
//    exit(ReplyUtil::Error(-1));
//}

$order = array(
    "帮助|功能" => "YiBaoOrder",
    "成绩|查成绩" => "grade_details",
    "课表" => "course",
    "上课" => "nowCourse",
    "绑定" => "BindPoralt",
    "个人信息" => "info",
    "排名" => "rank",
);
if ($role == "" || $hash == "" || $text == "") {
    exit(ReplyUtil::Error(65535));
} else {
    if ($on == 'true') {
        $Reply = Route::M("tuling", $text, $user_id, $other);
        $Reply = json_decode($Reply, true);
        if (isset($Reply['code']) && $Reply['code'] == 0) {
            exit(ReplyUtil::Success($Reply['data']));
        } else {
            if (isset($Reply['code']) && isset($Reply['msg']) && $Reply['data']) {
                exit(ReplyUtil::Error($Reply['code'], $Reply['msg'], $Reply['data']));
            } else {
                exit(ReplyUtil::Error(4));
            }
        }
    }
    foreach ($order as $key => $value) {
        $item = explode("|", $key);
        if (!MsgTool::Array_In_String($text, $item)) {
            continue;
        } else {
            $Reply = Route::M($value, $text, $user_id, $other);
            $Reply = json_decode($Reply, true);
            if (isset($Reply['code']) && $Reply['code'] == 0) {
                exit(ReplyUtil::Success($Reply['data']));
            } else {
                if (isset($Reply['code']) && isset($Reply['msg']) && $Reply['data']) {
                    exit(ReplyUtil::Error($Reply['code'], $Reply['msg'], $Reply['data']));
                } else {
                    exit(ReplyUtil::Error(4));
                }
            }
        }
    }
    exit(ReplyUtil::Success(""));
}
