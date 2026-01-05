<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$page_reseau = true;

// traitement des formulaire
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ip'])){
  $networkConfig = '/etc/network/interfaces';
  $tempConfig = $racine_path.'src/tmp/interfaces.temp';

  // TODO -- empêcher les mauvaises entrées
  $newIp = "192.168.".$_POST["ip3"].".".$_POST["ip4"];
  $newMask = "255.255.".$_POST["mask3"].".".$_POST["mask4"];

  if (!copy($networkConfig, $tempConfig)) {
      echo "failed to copy $networkConfig...\n";
  }

  $section = file($tempConfig);

  $interfaceKey = array_search("iface eth1 inet static\n", $section);
  if($interfaceKey === false){
    echo "failed to find interface in /etc/network/interfaces\n";
  }else{
    $section[$interfaceKey+1] = "address ".$newIp."\n";
    $section[$interfaceKey+2] = "netmask ".$newMask."\n";
  }

  $message = "#Config modifier par network.php\n";
  if(end($section) != $message){
    $section[] = $message;
  }

  file_put_contents($tempConfig, $section);

  // mise a jour de eth1
  $output = shell_exec("sudo /var/www/html/src/scripts/cfg-eth1.sh");

  // changement d'ip pour le sousdomaine box 
  shell_exec("sudo /var/www/html/src/scripts/rm-subdomain.sh box");
  shell_exec("sudo /var/www/html/src/scripts/add-subdomain.sh box ".$newIp);
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_dhcp'])){
  $dhcpClient = $_POST["dhcp_client"];
  $output = shell_exec("sudo /var/www/html/src/scripts/cfg-dhcp.sh ".$dhcpClient); // TODO-- sanitize user entry (report 001) unpached
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_dhcp_advanced'])){
  $rangeA = "192.168.".$_POST["range3a"].".".$_POST["range4a"];
  $rangeB = "192.168.".$_POST["range3b"].".".$_POST["range4b"];
  $pingCheck = "false";
  if(isset($_POST["conflict_detection"]) && $_POST['conflict_detection'] == "ok"){
    $pingCheck = "true";
  }
  $output = shell_exec("sudo /var/www/html/src/scripts/cfg-dhcp-advanced.sh ".$rangeA." ".$rangeB." ".$pingCheck);
}

// reset default 
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reset'])){
  $output = shell_exec("sudo /var/www/html/src/scripts/cfg-dhcp.sh 250");
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_toggle_dhcp'])){
  $dhcpToggle = "0";
  if(isset($_POST["dhcp"]) && $_POST['dhcp'] == "ok"){
    $dhcpToggle = "1";
  }
  shell_exec("sudo /var/www/html/src/scripts/toggle-service.sh isc-dhcp-server ".$dhcpToggle);
}

//recup ip/mask 
$output = shell_exec("/var/www/html/src/scripts/get-ip.sh");
$output = explode('|' , $output);
// TODO -- optimiser
$ip3 = explode('.' , $output[0])[2];
$ip4 = explode('.' , $output[0])[3];
$mask3 = explode('.' , $output[1])[2];
$mask4 = explode('.' , $output[1])[3];
$ip = $output[0];
$mask = $output[1];
$network = $output[2];

// recup range dhcp 
$output = shell_exec("/var/www/html/src/scripts/get-dhcp.sh");
$output = explode('|' , $output);
// TODO -- optimiser
$range3a = explode('.' , $output[0])[2];
$range4a = explode('.' , $output[0])[3];
$range3b = explode('.' , $output[1])[2];
$range4b = explode('.' , $output[1])[3];
$rangea = $output[0];
$rangeb = $output[1];
$totalAddr = $output[2];
$conflictDetection = $output[3];
$output[4] = preg_replace('/\s+/u', '', $output[4]); // remove space
$dhcpStatus = $output[4];

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/network.php");
include($racine_path."src/templates/footer.php");

?>
