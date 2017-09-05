<?php 
require_once('../bin/fw.php');

ini_set("display_errors", 1);
ini_set("track_errors", 1);
ini_set("html_errors", 1);
error_reporting(E_ALL);

header('Content-Type: text/html; charset=utf-8');

reset($_GET);

// Lit un fichier, et le place dans une chaîne
$filename = key($_GET).'.json';
$handle = fopen($filename, "r");
$json = fread($handle, filesize($filename));
fclose($handle);

$acl = isset($_SESSION["user"])? (array) $_SESSION["user"]->acl : null;
$json = json_decode($json);

$i=0;
foreach ($json as $ele)
{
  if( $ele->acl !== true && !( $ele->acl == "connect" && $fw->signin() ) && ( !isset($acl[$ele->acl]) || $acl[$ele->acl] != true ))
    unset($json[$i]);
  else
    $j=0;
    foreach ($ele->sub_item as $sub_ele)
    {
      if( $sub_ele->acl !== true && !( $sub_ele->acl == "connect" && $fw->signin() ) && ( !isset($acl[$sub_ele->acl]) || $acl[$sub_ele->acl] != true ))
        unset($ele->sub_item[$j]);
      $j++;
    }
  $i++;
}

echo json_encode( $json );