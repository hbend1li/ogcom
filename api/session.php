<?php header('Content-Type: application/json; charset=utf-8');
  
  $return = isset($_SESSION['user']) ?  $_SESSION['user'] : false;

die(json_encode($return)); ?>