<?php
/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/2/12
 * Time: 15:32
 */
include_once "lib/include.php";
$id   = isset($_GET['id']) ? $_GET['id'] : "";
$source = isset($_GET['source']) ? $_GET['source'] : "";

if($id == "" || $source == ""){
    exit('{"code":65535,"msg":"Missing Parameters"}');
}else{
    $source = strtolower($source);
    switch ($source){
        case "163":
            $api = new NeteaseMusicAPI();
            $json = $api->playlist($id);
            $response = json_decode($json,true);
            if(isset($response['code']) && $response['code'] == 200){
                // todo

            }else{
                exit($json);
            }
            break;
        case "tencent":
            $api = new TencentMusicAPI();
            $json = $api->playlist($id);
            $response = json_decode($json,true);
            if(isset($response['code']) && $response['code'] == 0){
                // todo

            }else{
                exit($json);
            }
            break;
        case "xiami":
            $api = new XiamiMusicAPI();
            $json = $api->detail($id);
            $response = json_decode($json,true);
            if(isset($response['code']) && $response['code'] == 0){
                // todo

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

