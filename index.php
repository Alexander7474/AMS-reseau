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

// Fichier hosts.json mis a jour par le crownjob get-hosts-up.sh 
$json = file_get_contents($racine_path.'src/config/hosts.json');
$hosts = json_decode($json, true)["hosts"]; // true = associative array

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
