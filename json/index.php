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

$acl = json_decode($_SESSION["user"]->acl);
$json = json_decode($json);

$i=0;


foreach ($json as $ele) {

    if ( isset($acl->($ele->item)) && $acl->($ele->item) == false ) {
        unset($json[$i]);
    }
    $i++;

}


echo json_encode( $json );