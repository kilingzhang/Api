<?php
include_once '../public/Snoopy.class.php';

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/1 0001
 * Time: 04:27
 */
class Url
{
    private $url;
    private $snoopy;
    private $param;
    private $data;

    function __construct($param)
    {
        $this->param = $param;
        $this->snoopy = new Snoopy();
        $this->snoopy->maxredirs = 0;
        $this->snoopy->cookies['ZDEDebuggerPresent'] = 'php,phtml,php3';
        $this->snoopy->cookies['path'] = '/';
        $this->snoopy->agent = "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/59.0.3067.6 Mobile Safari/537.36";
        $this->init();

    }

    private function init()
    {
        $this->snoopy->fetch("http://www.65yw.com/rooog1/index1.php?id=" . $this->param);
        $this->snoopy->setcookies();
        $rs = $this->snoopy->results;
        preg_match_all('/\{\"id\": \"(.*?)",\"type\": \"(.*?)\",\"siteuser\": \"(.*?)\",\"md5\": \"(.*?)\"\}/', $rs, $param);
        $param = json_decode($param[0][0], true);
        $this->snoopy->referer = "http://www.65yw.com/rooog1/index1.php?id=" . $this->param;
        $this->snoopy->submit("http://www.65yw.com/rooog1/url5.php", $param);
        $this->snoopy->data = $this->snoopy->results;
        @$this->url = json_decode($this->snoopy->data, true)['url'];
    }

    public function getData()
    {
        echo $this->snoopy->data;
    }

}

