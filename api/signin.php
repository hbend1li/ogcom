<?php header('Content-Type: application/json; charset=utf-8');

$return = false;

if (isset($_GET['signin']))
{
  $json     = json_get(true);
  $username = sql_inj($json->email);
  $password = sql_inj($json->password); //base64_encode($password); //sha1($password);
  $result   = $fw->fetchAll("SELECT * FROM user WHERE ( username='$username' OR email='$username' ) AND password='$password'");
  if (count($result)>0){
    $_SESSION['user'] = $result[0];
    $_SESSION['user']->uuid = uniqid();
    unset($_SESSION['user']->password);
    $return = true;
  }else{
    $_SESSION['user'] = null;
    unset($_SESSION['user']);
  }

  // if ( isset($json->email) && ($json->email != "") && isset($json->password) && ($json->password != "") )
  //   if ($fw->signin( $json->email, $json->password ) ){
  //     $return = true;
  //   }
  // else
  //   $ret = $fw->signin();
}

die(json_encode($return)); ?>