<?php

/**
 * Created by PhpStorm.
 * User.class: Slight
 * Date: 2017/2/10
 * Time: 20:06
 */
class Route
{
    public static function M($action, $text, $user_id, $other = "")
    {
        $action = trim($action);
        $text = trim($text);
        $user_id = trim($user_id);
        $User = new User($user_id);
        $userData = $User->getData();
        if (!empty($userData)) {
            $sid = $userData['sid'];
            $password = $userData['pw_portal'];
        }
        if (empty($userData) && $action != "YiBaoOrder" && $action != "tuling" && $action != "BindPoralt") {
            return ReplyUtil::Success(HOST . "也想帮助你,可是您还没绑定呢~要不绑定一下再试试~\n发送:\n绑定2015551439密码666666\n"
                . CQ::enFaceCode(12) . CQ::enFaceCode(12) . CQ::enFaceCode(12));
        }
        switch ($action) {
            case "YiBaoOrder":
                $msg =
                    "   ╭—————————————╮
             
     —" . HOST . "指令大全—
     ☆ ☆ 教务功能指令 ☆ ☆
         帮助|功能
         成绩|查成绩 (快速查询本学期成绩)
         上课        (快速查询最近课程)
         排名        (快速查询本学期排名)
         个人信息
         绑定2015551439密码666666 (信息门户账号密码)
        以上功能需绑定账号使用—
        更多便捷服务正在发开中~敬请期待~
    ╰—————————————╯
    ╭—————————————╮  
注意：
为了您的信息安全着想~最好私聊" . HOST . "为您绑定账号哦~
请不要多次回复同一指令，这样会加重服务器负载！
为了" . HOST . "能够提供更好的服务，感谢大家配合！
	        
    ╰—————————————╯ ";
                return ReplyUtil::Success($msg);
                break;
            case "tuling":
                $response = TulingMsg::Request($text, $user_id, $other);
                return TulingMsg::Reply($response);
                break;
            case "BindPoralt":
                /*
                 * 绑定账号
                 */
                preg_match_all("/绑定(.*?)密码(.*)/", $text, $data);
                if (count($data) == 3 && !empty($data[1]) && !empty($data[2])) {
                    $sid = $data[1][0];
                    $password = $data[2][0];
                    $Portal = Portal::Login($sid, $password);
                    if ($Portal['code'] != 0) {
                        return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您绑定失败了 ~\n{$Portal['msg']}");
                    } else {
                        $Portal = $Portal['data'];
                    }
                    if (empty($userData)) {
                        if (!$User->insertUser($user_id, $sid, $password, $Portal)) {
                            return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您绑定失败了 ~\n  未知原因~! 重试下呗~亲");
                        }
                    } else {
                        if (!$User->updateUser($user_id, $sid, $password, $Portal)) {
                            return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您绑定失败了 ~\n  未知原因~! 重试下呗~亲");
                        }
                    }
                    return ReplyUtil::Success("亲爱的~ {$Portal['name']} 同学,恭喜你绑定成功,从此我们就可以一起玩耍了~\n"
                        . CQ::enFaceCode(29) . CQ::enFaceCode(29) . CQ::enFaceCode(29) . CQ::enFaceCode(29) . CQ::enFaceCode(29)
                        . "\n快发送 功能 查看" . HOST . "的厉害吧~\n"
                        . CQ::enFaceCode(204) . CQ::enFaceCode(204) . CQ::enFaceCode(204) . CQ::enFaceCode(204) . CQ::enFaceCode(204));
                } else {
                    return ReplyUtil::Success("绑定失败,请按照正确格式进行绑定~如:\n绑定2015551439密码666666");
                }
                break;
            case "course":
                $Portal = Portal::Course($sid, $password);
                if ($Portal['code'] != 0) {
                    return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您查询失败了~ ~\n{$Portal['msg']}");
                } else {
                    $Portal = $Portal['data'];
                    return ReplyUtil::Success((json_encode($Portal, JSON_UNESCAPED_UNICODE)));
                }
                break;
            case "nowCourse":
                $Portal = Portal::NowCourse($sid, $password);
                if ($Portal['code'] != 0) {
                    return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您查询失败了~ ~\n{$Portal['info']}");
                } else {
                    $Portal = $Portal['data'];
                    return ReplyUtil::Success($Portal['info']);
                }
                break;
            case "grade_details":
                $Portal = Portal::GradeDetails($sid, $password);
                if ($Portal['code'] != 0) {
                    return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您查询失败了~ ~\n{$Portal['msg']}");
                } else {
                    $Portal = $Portal['data'];
                    $msg = "\n亲~! " . HOST . "帮您查询到当前的成绩为:\n";
                    $msg .= "------------------------------------\n";
                    foreach ($Portal as $value) {
                        $msg .= "课程: {$value['course']}  \n";
                        $msg .= "平时成绩: {$value['daily_grade']}  \n";
                        $msg .= "期末成绩: {$value['exam_grade']}  \n";
                        $msg .= "综合成绩: {$value['comp_grade']}  \n";
                        $msg .= "------------------------------------\n";
                    }
                    return ReplyUtil::Success($msg);
                }
                break;
            case "rank":
                $Portal = Portal::Rank($sid, $password);
                if ($Portal['code'] != 0) {
                    return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您查询失败了~ ~\n{$Portal['msg']}");
                } else {
                    $Portal = $Portal['data'];
                    $msg = "\n亲~! " . HOST . "帮您查询到当前的成绩排名为:\n";
                    $msg .= "------------------------------------\n";
                    $msg .= "平均绩点: {$Portal['gpa']}  \n";
                    $msg .= "平均分数: {$Portal['average_grade']}  \n";
                    $msg .= "班级排名: {$Portal['gpa_class_rank']}  \n";
                    $msg .= "专业排名: {$Portal['gpa_major_rank']}  \n";
                    $msg .= "------------------------------------\n";
                    return ReplyUtil::Success($msg);
                }
                break;
            case "info":
                $Portal = Portal::Info($sid, $password);
                if ($Portal['code'] != 0) {
                    return ReplyUtil::Success("" . HOST . "很悲伤的告诉您,您查询失败了~ ~\n{$Portal['msg']}");
                } else {
                    $Portal = $Portal['data'];
                    $msg = "\n亲爱的{$Portal['name']}~! " . HOST . "帮您查询到当前的个人信息为:\n";
                    $msg .= "------------------------------------\n";
                    $msg .= "学院: {$Portal['college']}  \n";
                    $msg .= "专业: {$Portal['major']}  \n";
                    $msg .= "班级: {$Portal['class']}  \n";
                    $msg .= "生日: {$Portal['data_of_birth']}  \n";
                    $msg .= "省份: {$Portal['province']}  \n";
                    $msg .= "种族: {$Portal['nation']}  \n";
                    $msg .= "------------------------------------\n";
                    return ReplyUtil::Success($msg);
                }
                break;
            default:
                return ReplyUtil::Success("暂未开通此服务");
                break;
        }
    }


}