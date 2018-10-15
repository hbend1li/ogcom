<?php header('Content-Type: application/json; charset=utf-8');

//  if (!$fw->signupdate())
//  {
//    header('Location: ../bin/login.php');
//  }

// Lit un fichier, et le place dans une chaîne

$json_nav = false;

if (isset( $_GET['nav_menu'] ) && $_GET['nav_menu']>"" && file_exists("nav_menu/$_GET[nav_menu].json") )
{

  $filename = "nav_menu/$_GET[nav_menu].json";
  $handle = fopen($filename, "r");
  $json_nav = json_decode(fread($handle, filesize($filename)));
  fclose($handle);

  $acl = isset($_SESSION["user"])? json_decode($_SESSION["user"]->acl) : null;

  $i=0;
  foreach ($json_nav as $ele)
  {
    if ( $ele->acl === true || $acl->($ele->acl) == true || ($ele->acl == "signin" && $fw->signin()))
    {
      $j=0;
      foreach ($ele->sub_item as $sub_ele)
      {
        if( $sub_ele->acl !== true && !( $sub_ele->acl == "connect" && $fw->signin() ) && ( !isset($acl[$sub_ele->acl]) || $acl[$sub_ele->acl] != true ))
        unset($ele->sub_item[$j]);
        $j++;
      }
      $i++;
    }else{
      unset($json_nav[$i]);
    }
  }

}

die( json_encode( $json_nav ) ); ?>