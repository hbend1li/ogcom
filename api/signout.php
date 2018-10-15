<?php header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['signout']))
{
  //global $_SESSION;
  if (isset($_SESSION['user']))
  {
    $_SESSION['user'] = null;
    unset($_SESSION);
  }
  session_destroy();
}

die(json_encode(true)); ?>