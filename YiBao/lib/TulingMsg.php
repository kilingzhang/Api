<?php

/**
 * Created by PhpStorm.
 * User.class: Slight
 * Date: 2017/2/10
 * Time: 21:16
 */
class TulingMsg
{

    public static function Request($text,$userId,$other = ""){
        $url = "http://www.tuling123.com/openapi/api?";
        $param = array(
            'key'    => APIkey ,
            'info'   => $text ,
            'userId' => $userId,
        );
        if($other == ""){
            $other = array();
        }else{
            if(json_decode($other,true)){
                $other = json_decode($other,true);
            }else{
                $other = array();
            }
        }
        $param   = http_build_query(array_merge($param,$other),JSON_UNESCAPED_UNICODE);
        $Snoopy = new Snoopy();
        $Snoopy->fetch($url . $param);
        if($Snoopy->status == 200){
            return $Snoopy->results;
        }else{
            return ReplyUtil::setMsg(3);
        }
    }

    public static function Reply($Reply){
        $Reply = json_decode($Reply,true);
        if(!array_key_exists("url",$Reply) && !array_key_exists("list",$Reply) && array_key_exists("text",$Reply)){
            return ReplyUtil::Success($Reply['text']);
        }elseif(array_key_exists("url",$Reply) && !array_key_exists("list",$Reply) && array_key_exists("text",$Reply)){
            return ReplyUtil::Success(array(
                'text'=>$Reply['text'],
                'url' =>$Reply['url'],
            ));
        }elseif(array_key_exists("list",$Reply) && array_key_exists("text",$Reply) && !array_key_exists("url",$Reply)){
            return ReplyUtil::Success(array(
                'text'=>$Reply['text'],
                'url' =>$Reply['list'],
            ));
        }elseif(!array_key_exists("list",$Reply) && !array_key_exists("text",$Reply) && !array_key_exists("url",$Reply)){
            return ReplyUtil::Error(4);
        }

    }


}