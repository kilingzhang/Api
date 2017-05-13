<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/6 0006
 * Time: 15:42
 */
class Portal
{

    public static function Login($sid, $password)
    {
        $param = array(
            'role' => Role,
            'hash' => Hash,
            'sid' => $sid,
            'password' => $password
        );
        $url = URL::Login . http_build_query($param);
        $Snoopy = new Snoopy();
        $res = $Snoopy->fetch($url)->results;
        $data = json_decode($res, true);
        return $data;
    }


    public static function Course($sid, $password)
    {
        $param = array(
            'role' => Role,
            'hash' => Hash,
            'sid' => $sid,
            'password' => $password
        );
        $url = URL::Course . http_build_query($param);
        $Snoopy = new Snoopy();
        $res = $Snoopy->fetch($url)->results;
        $data = json_decode($res, true);
        return $data;
    }

    public static function NowCourse($sid, $password)
    {
        return json_decode(CurrentCourse::init($sid, $password), true);
    }

    public static function GradeDetails($sid, $password)
    {
        $param = array(
            'role' => Role,
            'hash' => Hash,
            'sid' => $sid,
            'password' => $password
        );
        $url = URL::GradeDetails . http_build_query($param);
        $Snoopy = new Snoopy();
        $res = $Snoopy->fetch($url)->results;
        $data = json_decode($res, true);
        return $data;
    }

    public static function Rank($sid, $password)
    {
        $param = array(
            'role' => Role,
            'hash' => Hash,
            'sid' => $sid,
            'password' => $password
        );
        $url = URL::Rank . http_build_query($param);
        $Snoopy = new Snoopy();
        $res = $Snoopy->fetch($url)->results;
        $data = json_decode($res, true);
        return $data;
    }

    public static function Info($sid, $password)
    {
        $param = array(
            'role' => Role,
            'hash' => Hash,
            'sid' => $sid,
            'password' => $password
        );
        $url = URL::Info . http_build_query($param);
        $Snoopy = new Snoopy();
        $res = $Snoopy->fetch($url)->results;
        $data = json_decode($res, true);
        return $data;
    }

}