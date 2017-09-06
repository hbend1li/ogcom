<?php 
require_once('../bin/fw.php');
header('Content-Type: text/html; charset=utf-8');


// Lit un fichier, et le place dans une chaîne
reset($_GET);
$filename = key($_GET).'.json';
$handle = fopen($filename, "r");
$nav_menu = json_decode(fread($handle, filesize($filename)));
fclose($handle);

$acl = isset($_SESSION["user"])? (array) $_SESSION["user"]->acl : null;

$i=0;
foreach ($nav_menu as $ele)
{
  if( $ele->acl !== true && !( $ele->acl == "connect" && $fw->signin() ) && ( !isset($acl[$ele->acl]) || $acl[$ele->acl] != true ))
    unset($nav_menu[$i]);
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

echo json_encode( $nav_menu );