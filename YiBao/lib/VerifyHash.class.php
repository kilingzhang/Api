<?php

/**
 * VerifyHash.class.php
 * Author:       Andreas
 * Data:         2015/12/10
 * Description:  To Verify Role And Hash For API Request
 * Input:
 *               Role  The Role Of User.class
 *               Hash  Secret
 * Output:
 *               Boolean
 */

class VerifyHash
{
  private $staticSecrets = array(
    'sky31' => '5d8e6f9103d846c149e6c0a87bc7716c',
    'test' => 'test'
  );
  private $role;
  private $hash;

  public function __construct($role = '', $hash = ''){
    $this->role = addslashes($role);
    $this->hash = addslashes($hash);
  }

  public function checkRole(){

    if(isset($this->staticSecrets[$this->role])){
      if($this->hash == $this->staticSecrets[$this->role]){
        //Static Hash OK
        return true;
      }
      else{
        //Static Hash Error
        return false;
      }
    }
    $conn = new mysqli(dbHost, dbApiUser, dbApiPassword, dbApiTable);
    if($conn->connect_error){
      //DB.class Error
      return false;
    }
    $conn->query("SET NAMES 'UTF8'");
    $sql = "SELECT * FROM user WHERE id = '$this->role'";
    $result = $conn->query($sql);
    if($result->num_rows > 0){
      $rows = $result->fetch_assoc();
      if($this->hash == $rows['hash'] && $rows['status'] == 1 && $rows['left_times'] > 0){
        $sql = "UPDATE use_times SET day_1 = day_1 + 1, total = total +1 WHERE id = '$this->role';UPDATE user SET left_times = left_times - 1 WHERE id = '$this->role'";
        $conn->multi_query($sql);
        //DB.class Hash OK
        return true;
      }
      else{
        //DB.class Hash Error
        return false;
      }
    }
    else{
      //DB.class No User.class
      return false;
    }
    $conn->close();
  }
}

?>