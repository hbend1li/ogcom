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
    $_SESSION['user']->acl = json_decode($_SESSION['user']->acl);
    if (isset ($_SESSION['user']->acl->enable) && $_SESSION['user']->acl->enable === true){
      unset($_SESSION['user']->password);
      signin();
      $return = true;
    }else{
      $_SESSION['user'] = null;
      unset($_SESSION['user']);
      }
  }else{
    $_SESSION['user'] = null;
    unset($_SESSION['user']);
  }

}

die(json_encode($return)); ?>