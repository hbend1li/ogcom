<?php header('Content-Type: application/json; charset=utf-8');

$json_nav = false;

if (isset( $_GET['nav_menu'] ) && $_GET['nav_menu']>"" && file_exists("nav_menu/$_GET[nav_menu].json") )
{

  $filename = "nav_menu/$_GET[nav_menu].json";
  $handle = fopen($filename, "r");
  $json_nav = json_decode(fread($handle, filesize($filename)));
  fclose($handle);
  foreach ($json_nav as $key => &$ele)
    if ( chk_ele($ele) )
      foreach ($ele->sub_item as $sKey => &$sEle){
        if ( !chk_ele($sEle) )
          unset($ele->sub_item[$sKey]);
      }
    else
      unset($json_nav[$key]);
}

function chk_ele($ele)
{
  global $_SESSION;
  $acl = isset($_SESSION["user"]) ? $_SESSION["user"]->acl : null;
  if (  $ele->acl === false || ($acl !== null && ( isset( $acl->{$ele->acl} ) && $acl->{$ele->acl} == true ) ) )
    return true;
  else
    return false;
}

die( json_encode( $json_nav ) ); ?>