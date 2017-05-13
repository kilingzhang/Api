<?php
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/2/12
 * Time: 14:16
 */
include_once "lib/include.php";
$name   = isset($_GET['name']) ? $_GET['name'] : "";
$source = isset($_GET['source']) ? $_GET['source'] : "";

if($name == "" || $source == ""){
    exit('{"code":65535,"msg":"Missing Parameters"}');
}else{
    $source = strtolower($source);
    switch ($source){
        case "163":
            $api      = new NeteaseMusicAPI();
            $json     = $api->search($name);
            exit($json);
            $response = json_decode($json,true);
            if(isset($response['code']) && $response['code'] == 200){
                $songs = $response['result']['songs'];
                unset($response);
                $response = array();
                for ($i=0 ; count($songs) != 0, $i < count($songs) ; $i ++ ){
                    $response['data'][$i]['song_name'] = $songs[$i]['name'];
                    $response['data'][$i]['song_id'] = $songs[$i]['id'];
                    $response['data'][$i]['singer_id'] = $songs[$i]['ar'][0]['id'];
                    $response['data'][$i]['singer_name'] = $songs[$i]['ar'][0]['name'];
                    $response['data'][$i]['album_id'] = $songs[$i]['al']['id'];
                    $response['data'][$i]['album_name'] = $songs[$i]['al']['name'];
                    $response['data'][$i]['album_pic'] = $songs[$i]['al']['pic'];
                    $response['data'][$i]['album_pic_url'] = $songs[$i]['al']['picUrl'];
                }
            }else{
                exit($json);
            }

            break;
        case "tencent":
            $api = new TencentMusicAPI();
            $json     = $api->search($name);
            $response = json_decode($json,true);
            if(isset($response['code']) && $response['code'] == 0){
                $songs = $response['data']['song']['list'];
                unset($response);
                $response = array();
                for ($i=0 ; count($songs) != 0, $i < count($songs) ; $i ++ ){
                    $response['data'][$i]['song_name'] = $songs[$i]['songname'];
                    $response['data'][$i]['song_id'] = $songs[$i]['songmid'];
                    $response['data'][$i]['singer_id'] = $songs[$i]['singer'][0]['mid'];
                    $response['data'][$i]['singer_name'] = $songs[$i]['singer'][0]['name'];
                    $response['data'][$i]['album_id'] = $songs[$i]['albummid'];
                    $response['data'][$i]['album_name'] = $songs[$i]['albumname'];
                    $response['data'][$i]['media_mid'] = $songs[$i]['media_mid'];
                }
            }else{
                exit($json);
            }
            break;
        case "xiami":
            $api = new XiamiMusicAPI();
            $json     = $api->search($name);
            $response = json_decode($json,true);
            if(isset($response['state']) && $response['state'] == 0){
                $songs = $response['data']['songs'];
                unset($response);
                $response = array();
                for ($i=0 ; count($songs) != 0, $i < count($songs) ; $i ++ ){
                    $response['data'][$i]['song_name'] = $songs[$i]['song_name'];
                    $response['data'][$i]['song_id'] = $songs[$i]['song_id'];
                    $response['data'][$i]['singer_id'] = $songs[$i]['artist_id'];
                    $response['data'][$i]['singer_name'] = $songs[$i]['artist_name'];
                    $response['data'][$i]['artist_logo'] = $songs[$i]['artist_logo'];
                    $response['data'][$i]['album_id'] = $songs[$i]['album_id'];
                    $response['data'][$i]['album_name'] = $songs[$i]['album_name'];
                    $response['data'][$i]['album_logo'] = $songs[$i]['album_logo'];
                    $response['data'][$i]['url'] = $songs[$i]['listen_file'];
                }
            }else{
                exit($json);
            }
            break;
        default:
            exit('{"code":2333,"msg":"Illegal specification parameter"}');
            break;
    }
    $response['code'] = 0;
    $response['msg']  = "success";
    $response = json_encode($response,JSON_UNESCAPED_UNICODE);
    echo $response;
}