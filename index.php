<?php
session_start();
$racine_path = "";

include $racine_path."src/model/User.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

//recup des hosts connexté au réseau
$output = shell_exec("/var/www/html/src/scripts/get-ip.sh");
$output = explode('|' , $output);
$ip = $output[0];
$mask = $output[1];
$network = $output[2];

// TODO -- trouver un meilleur moyen qu'un scan nmap pour déterminer les host connectés
$output = shell_exec("/var/www/html/src/scripts/get-hosts-up.sh ".$network." ".$mask);
$hosts = explode('|' , $output);
foreach ($hosts as &$host) {
  $host = explode('/', $host);
}
unset($host);

// recup des services en marche 
$services = shell_exec("/var/www/html/src/scripts/get-services-up.sh");
$services = explode('|', $services);
foreach ($services as &$sv) {
  $sv = explode('=', $sv);
}
unset($sv);

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/index.php");
include($racine_path."src/templates/footer.php");

?>
