<?php
session_start();
$racine_path = "";

//recup des hosts connexté au réseau
$output = shell_exec("/var/www/html/src/scripts/get-ip.sh");
$output = explode('|' , $output);
$ip = $output[0];
$mask = $output[1];
$network = $output[2];

$output = shell_exec("/var/www/html/src/scripts/get-hosts-up.sh ".$network." ".$mask);
$hosts = explode('|' , $output);
foreach ($hosts as &$host) {
  $host = explode('/', $host);
}
unset($host);

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/index.php");
include($racine_path."src/templates/footer.php");

?>
