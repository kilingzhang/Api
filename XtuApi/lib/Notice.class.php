<?php

/**
 * Created by PhpStorm.
 * User: Slight
 * Date: 2017/5/20 0020
 * Time: 02:11
 */
include_once "Api.class.php";

class Notice extends Api
{
    function __construct($limit = 25, $offset = 1, $page = 1, $per_page = 15)
    {
        $this->limit = $limit;
        $this->offset = $offset;
        $this->page = $page;
        $this->per_page = $per_page;
        $this->init();
        $this->url = 'http://www.xtu.edu.cn/gonggao/';
        $this->initData();
    }

    private function initData()
    {
        $pageCount = ceil(($this->offset + ($this->page - 1) * $this->per_page + $this->limit) / $this->per_num);
        $startPager = floor(($this->offset + ($this->page - 1) * $this->per_page) / $this->per_num);
        $rs = "";
        for ($i = $startPager; $i < $pageCount; $i++) {
            if ($i == 0) {
                $url = $this->url;
            } else {
                $page = $i + 1;
                $url = $this->url . "index_{$page}.html";
            }
            $res = $this->snoopy->fetch($url);
            if ($res->status != 200) {
                return $this->setMsg(3);
            }
            $rs .= $res->results;
        }
        preg_match_all('/<a href=\"(.*?)\" title=\".*?\">(.*?)<span>(.*?)<\/span><\/a>/', $rs, $links);
        if (count($links[1]) != 0 && !empty($links[1][0])) {
            $m = 0;
            $n = 0;
            for ($i = 0 + ($this->offset % ($this->per_num - 1)), $j = 0; $i < count($links[1]) && $j < $this->limit; $i++, $j++) {
                if ($j % $this->per_page == 0) {
                    $m++;
                    $n = 0;
                }
                $this->data[$m][$n]['title'] = strip_tags($links[2][$i]);
                $this->data[$m][$n]['link'] = 'http://www.xtu.edu.cn' . $links[1][$i];
                $this->data[$m][$n]['date'] = $links[3][$i];
                $n++;
            }
        } else {
            $this->data = array();
        }
        return $this->setMsg(0);
    }
}

//$api = new Notice(50, 0, 1, 25);
//echo $api->getData();