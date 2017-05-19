<?php


$filePath = isset($_GET['url']) ? $_GET['url'] : '';
if ($filePath == "") {
    exit('{"success": 65535}');
} else {
    foreUpload($filePath);
}

/*存储文件*/
function _saveFile($fileName, $fileContent)
{
    $dir = dirname(dirname(__FILE__)) . "/tempFiles/";
    if (!is_dir($dir)) {
        mkdir($dir, 0777);
    }
    $file = $dir . $fileName;
    $localFile = fopen($dir . $fileName, 'w');
    if (false !== $localFile) {
        if (false !== fwrite($localFile, $fileContent)) {
            fclose($localFile);
        } else {
        }
    } else {
    }
    return $file;
}

function tempDownload($url)
{
    function curl_file_get_contents($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $r = curl_exec($ch);
        curl_close($ch);
        return $r;
    }

    $file = curl_file_get_contents($url);
    return _saveFile(md5(time()) . '.jpg', $file);

}


function minimime($fname)
{
    $fh = fopen($fname, 'rb');
    if ($fh) {
        $bytes6 = fread($fh, 6);
        echo $bytes6 . "<br>";
        fclose($fh);
        if ($bytes6 === false) return false;
        if (substr($bytes6, 0, 3) == "\xff\xd8\xff") return false;
        if ($bytes6 == "\x89PNG\x0d\x0a") return true;
        if ($bytes6 == "GIF87a" || $bytes6 == "GIF89a") return false;
        return false;
    }
    return false;
}

function png2jpg($srcPathName, $delOri = false)
{
    $srcFile = $srcPathName;
    $srcFileExt = strtolower(trim(substr(strrchr($srcFile, '.'), 1)));

    if (minimime($srcFile)) {
        $photoSize = GetImageSize($srcFile);
        $pw = $photoSize[0];
        $ph = $photoSize[1];
        $dstImage = ImageCreateTrueColor($pw, $ph);
        imagecolorallocate($dstImage, 255, 255, 255);
        //读取图片
        $srcImage = ImageCreateFromPNG($srcFile);
        //合拼图片
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $pw, $ph, $pw, $ph);
        imagejpeg($dstImage, $srcFile, 90);
        imagedestroy($srcImage);
    }

}

function foreUpload($filePath)
{
    set_time_limit(0);
    header("Content-Type:text/html; charset=utf-8");
    $pro1 = explode("http://", $filePath);
    $pro2 = explode("https://", $filePath);
    if (count($pro1) == 2 || count($pro2) == 2) {
        $filePath = tempDownload($filePath);
    }
    png2jpg($filePath);
    if (!file_exists($filePath)) die('{"success": -1}');
    $url = 'http://ocr.shouji.sogou.com/v2/ocr/json';
    $data = array('pic' => new CURLFile(realpath($filePath)));

    //curl设置
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //开启后从浏览器输出，curl_exec()方法没有返回值
    $result = curl_exec($ch);
    curl_close($ch);
    echo $result;
}




