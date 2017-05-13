<?php
/**
 * Created by PhpStorm.
 * User.class: Slight
 * Date: 2017/2/10
 * Time: 19:03
 */


/**
 *  Charset  UTF-8
 */
header('Content-type:text/html;charset=utf-8');

/**
 *  Access-Control-Allow-Origin
 */
header("Access-Control-Allow-Origin: *");

/**
 *  Sky31 API Verification
 *
 *  Role
 *  Hash
 */
define("Role", "YiBao");
define("Hash", "");


define("HOST", "技术菌");

/**
 *  Tuling Api Verification
 */
define("APIkey", "");
define("secret", "");


/**
 * Database
 * dbHost
 * dbUser
 * dbPassword
 * dbTable
 * dbport
 *
 */
define('dbHost', '127.0.0.1');
define("dbUser", "root");
define("dbPassword", "");
define("dbTable", "yibao");
define('dbport', '3306');


/**
 *  PASSWORD TOKEN
 */

//Encode
define('ENCODE_CIPHER', MCRYPT_RIJNDAEL_128);
define('ENCODE_MODE', MCRYPT_MODE_ECB);
define('ENCODE_KEY', '');
