<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/6 0006
 * Time: 19:07
 */
class CurrentCourse
{


    public static function init($sid, $password)
    {
        $role = Role;
        $hash = Hash;
        #获取课程表
        $edu_course_url = "https://api.sky31.com/PortalCode/edu-new/course.php?role=$role&hash=$hash&sid=$sid&password=$password&style=1";
        $Snoopy = new Snoopy();
        $edu_course = $Snoopy->fetch($edu_course_url);
        $edu_course = json_decode($edu_course->results, TRUE);
        if ($edu_course['code'] == 0) {
            $edu_course = $edu_course['data'];
        } else {
            return json_encode($edu_course, JSON_UNESCAPED_UNICODE);
            exit();
        }


#查看当前周数
        $current_week_url = "https://api.sky31.com/current_week.php?role=$role&hash=$hash&type=static";
        $Snoopy = new Snoopy();
        $weeks = $Snoopy->fetch($current_week_url);
        $weeks = json_decode($weeks->results, TRUE);
        if ($weeks['code'] == 0) {
            $weeks = $weeks['data'];
        } else {
            return json_encode($weeks, JSON_UNESCAPED_UNICODE);
            exit();
        }


#获取当前星期
        $week = date('w', time());
        if ($week == 0) {
            $week = 7;
        }


#获取时令
        $season = 'summer';
// $season = 'winter';
        if ($season == 'summer') {
            $jet_Lag = '';
        } else {
            $jet_Lag = '-30 minute';
        }

        $day = date('Y-m-d', time());


        $COURSE_TIME[1]['COURSE_START'] = strtotime($day . ' 8:00' . $jet_Lag);
        $COURSE_TIME[1]['COURSE_END'] = strtotime($day . ' 9:40' . $jet_Lag);
        $COURSE_TIME[2]['COURSE_START'] = strtotime($day . ' 10:10' . $jet_Lag);
        $COURSE_TIME[2]['COURSE_END'] = strtotime($day . ' 11:50' . $jet_Lag);
        $COURSE_TIME[3]['COURSE_START'] = strtotime($day . ' 14:30' . $jet_Lag);
        $COURSE_TIME[3]['COURSE_END'] = strtotime($day . ' 16:10' . $jet_Lag);
        $COURSE_TIME[4]['COURSE_START'] = strtotime($day . ' 16:40' . $jet_Lag);
        $COURSE_TIME[4]['COURSE_END'] = strtotime($day . ' 18:20' . $jet_Lag);
        $COURSE_TIME[5]['COURSE_START'] = strtotime($day . ' 19:30' . $jet_Lag);
        $COURSE_TIME[5]['COURSE_END'] = strtotime($day . ' 21:10' . $jet_Lag);

        $nowTime = strtotime(time());

#当前周数
        $data['weeks'] = $weeks['week'];


        $data['first_week_monday'] = $weeks['first_week_monday'];
#今日周几
        $data['week'] = $week;

        function Is_Course($weeks, $week, $lesson, $edu_course)
        {

            if (isset($edu_course["$week"]["$lesson"][0]['week'])) {
                $string = $edu_course["$week"]["$lesson"][0]['week'];
                preg_match("/" . $weeks . "/", $string, $is);
                if (isset($is[0]) && $is[0] != '') {
                    return 0;
                }
            }

            if (isset($edu_course["$week"]["$lesson"][1]['week'])) {
                $string = $edu_course["$week"]["$lesson"][1]['week'];
                preg_match("/" . $weeks . "/", $string, $is);
                if (isset($is[0]) && $is[0] != '') {
                    return 1;
                }
            }

            if (isset($edu_course["$week"]["$lesson"][2]['week'])) {
                $string = $edu_course["$week"]["$lesson"][2]['week'];
                preg_match("/" . $weeks . "/", $string, $is);
                if (isset($is[0]) && $is[0] != '') {
                    return 2;
                }
            }

            return -1;

        }

//        $data['weeks'] = 1;


// show(Is_Course($data['weeks'],$week,2,$edu_course));

        foreach ($COURSE_TIME as $key => $value) {

            if ($nowTime < $COURSE_TIME[1]['COURSE_START'] && Is_Course($data['weeks'], $week, 1, $edu_course) != -1) {

                $data['Lesson'] = 1;
                $data['ing'] = 0;
                $data['start'] = $COURSE_TIME[1]['COURSE_START'];
                $data['end'] = $COURSE_TIME[1]['COURSE_END'];

                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你即将上" . $data['course'] . ",请注意时间哦！";

                break;


            } elseif ($nowTime <= $COURSE_TIME[1]['COURSE_END'] && $nowTime >= $COURSE_TIME[1]['COURSE_START'] && Is_Course($data['weeks'], $week, 1, $edu_course) != -1) {

                $data['Lesson'] = 1;
                $data['ing'] = 1;
                $data['start'] = $COURSE_TIME[1]['COURSE_START'];
                $data['end'] = $COURSE_TIME[1]['COURSE_END'];

                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你正在上" . $data['course'] . ",请注意时间哦！";

                break;


            } elseif ($nowTime < $COURSE_TIME[2]['COURSE_START'] && Is_Course($data['weeks'], $week, 2, $edu_course) != -1) {

                $data['Lesson'] = 2;
                $data['ing'] = 0;
                $data['start'] = $COURSE_TIME[2]['COURSE_START'];
                $data['end'] = $COURSE_TIME[2]['COURSE_END'];

                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你即将上" . $data['course'] . ",请注意时间哦！";


                break;


            } elseif ($nowTime <= $COURSE_TIME[2]['COURSE_END'] && $nowTime >= $COURSE_TIME[2]['COURSE_START'] && Is_Course($data['weeks'], $week, 2, $edu_course) != -1) {

                $data['Lesson'] = 2;
                $data['ing'] = 1;
                $data['start'] = $COURSE_TIME[2]['COURSE_START'];
                $data['end'] = $COURSE_TIME[2]['COURSE_END'];

                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你正在上" . $data['course'] . ",请注意时间哦！";

                break;


            } elseif ($nowTime < $COURSE_TIME[3]['COURSE_START'] && Is_Course($data['weeks'], $week, 3, $edu_course) != -1) {

                $data['Lesson'] = 3;
                $data['ing'] = 0;
                $data['start'] = $COURSE_TIME[3]['COURSE_START'];
                $data['end'] = $COURSE_TIME[3]['COURSE_END'];


                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你即将上" . $data['course'] . ",请注意时间哦！";

                break;

            } elseif ($nowTime <= $COURSE_TIME[3]['COURSE_END'] && $nowTime >= $COURSE_TIME[3]['COURSE_START'] && Is_Course($data['weeks'], $week, 3, $edu_course) != -1) {

                $data['Lesson'] = 3;
                $data['ing'] = 1;
                $data['start'] = $COURSE_TIME[3]['COURSE_START'];
                $data['end'] = $COURSE_TIME[3]['COURSE_END'];


                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你正在上" . $data['course'] . ",请注意时间哦！";

                break;


            } elseif ($nowTime < $COURSE_TIME[4]['COURSE_START'] && Is_Course($data['weeks'], $week, 4, $edu_course) != -1) {

                $data['Lesson'] = 4;
                $data['ing'] = 0;
                $data['start'] = $COURSE_TIME[4]['COURSE_START'];
                $data['end'] = $COURSE_TIME[4]['COURSE_END'];


                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你即将上" . $data['course'] . ",请注意时间哦！";

                break;


            } elseif ($nowTime <= $COURSE_TIME[4]['COURSE_END'] && $nowTime >= $COURSE_TIME[4]['COURSE_START'] && Is_Course($data['weeks'], $week, 4, $edu_course) != -1) {

                $data['Lesson'] = 4;
                $data['ing'] = 1;
                $data['start'] = $COURSE_TIME[4]['COURSE_START'];
                $data['end'] = $COURSE_TIME[4]['COURSE_END'];


                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你正在上" . $data['course'] . ",请注意时间哦！";

                break;


            } elseif ($nowTime < $COURSE_TIME[5]['COURSE_START'] && Is_Course($data['weeks'], $week, 5, $edu_course) != -1) {

                $data['Lesson'] = 5;
                $data['ing'] = 0;
                $data['start'] = $COURSE_TIME[5]['COURSE_START'];
                $data['end'] = $COURSE_TIME[5]['COURSE_END'];


                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你即将上" . $data['course'] . ",请注意时间哦！";
                break;


            } elseif ($nowTime <= $COURSE_TIME[5]['COURSE_END'] && $nowTime >= $COURSE_TIME[5]['COURSE_START'] && Is_Course($data['weeks'], $week, 5, $edu_course) != -1) {

                $data['Lesson'] = 5;
                $data['ing'] = 1;
                $data['start'] = $COURSE_TIME[5]['COURSE_START'];
                $data['end'] = $COURSE_TIME[5]['COURSE_END'];

                $l = $data['Lesson'];
                $n = Is_Course($data['weeks'], $week, $l, $edu_course);
                $data['course'] = $edu_course["$week"]["$l"]["$n"]['course'];
                $data['location'] = $edu_course["$week"]["$l"]["$n"]['location'];
                $data['teacher'] = $edu_course["$week"]["$l"]["$n"]['teacher'];
                $data['week_string'] = $edu_course["$week"]["$l"]["$n"]['week_string'];
                $data['section_start'] = $edu_course["$week"]["$l"]["$n"]['section_start'];
                $data['section_end'] = $edu_course["$week"]["$l"]["$n"]['section_end'];
                $data['info'] = "你正在上" . $data['course'] . ",请注意时间哦！";

                break;


            } else {

                $data['Lesson'] = 0;
                $data['ing'] = 0;
                $data['course'] = '暂时未查到您今天的课程 亲，不要着急哦，自由时间多出去走走哦！';
                $data['info'] = '暂时未查到您今天的课程 亲，不要着急哦，自由时间多出去走走哦！';


            }


        }
// show($nowTime,1);
// show($data,1);

        unset($json);

        $json['code'] = 0;
        $json['msg'] = 'success!';
        $json['data'] = $data;

        $json = json_encode($json, JSON_UNESCAPED_UNICODE);
        return $json;
    }


}

