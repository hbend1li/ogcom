<?php 

reset($_GET);

// Lit un fichier, et le place dans une chaîne
$filename = key($_GET).'.json';
$handle = fopen($filename, "r");
$json = fread($handle, filesize($filename));
fclose($handle);

$json = json_decode($json);


$i=0;
foreach ($json as $ele) {
    if ( $ele->item == 'Achat' ) {
        unset($json[$i]);
    }
    $i++;
}

echo json_encode( $json );