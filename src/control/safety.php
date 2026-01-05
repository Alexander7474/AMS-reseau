<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$page_safety = true;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ip_forward'])){
  $ipForward = "0";
  if(isset($_POST["ip_forward"]) && $_POST['ip_forward'] == "ok"){
    $ipForward = "1";
  }
  shell_exec("sudo /var/www/html/src/scripts/cfg-nat.sh ".$ipForward);
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_port_forward'])){
  $port = $_POST['port-entry'];
  $port_dest = $_POST['port-destination'];
  $ip = $_POST["ip1"].".".$_POST["ip2"].".".$_POST["ip3"].".".$_POST["ip4"];
  shell_exec("sudo /var/www/html/src/scripts/add-port-forward.sh ".$port." ".$ip." ".$port_dest);
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rm_forward'])){
  $port = $_POST['port'];
  $output = shell_exec("sudo /var/www/html/src/scripts/rm-port-forward.sh ".$port);
}

// recup configuration nat et sécurité
$output = shell_exec("/var/www/html/src/scripts/get-nat.sh");
$output = explode('|' , $output);
$ipForward = $output[0];

$forwards = explode('|', shell_exec("sudo /var/www/html/src/scripts/get-port-forward.sh"));

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/safety.php");
include($racine_path."src/templates/footer.php");
?>
