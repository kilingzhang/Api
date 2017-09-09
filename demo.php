<?php
/**
 * Created by PhpStorm.
 * User: Kilingzhang  <slight@kilingzhang.com>
 * Date: 2017/8/20
 * Time: 5:14
 */
include_once "public/Snoopy.class.php";

$snoopy = new Snoopy();
$snoopy->cookies['__remember_me'] = true;
$snoopy->cookies['MUSIC_U'] = '57f1691e07ba70528a1b5d5d956e14cdaeecf8792ea7a6b641cf16b9d6c28619149ff8957a8267ae276f4db17527d6a941049cea1c6bb9b6';
$snoopy->cookies['__csrf'] = 'e4f524a16605a1739f2f18f4149f5938';
$snoopy->fetch("http://api.netease.com/simi/artist?id=6452");
print_r($snoopy);
