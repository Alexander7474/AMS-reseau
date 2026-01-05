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

// recup configuration nat et sécurité
$output = shell_exec("/var/www/html/src/scripts/get-nat.sh");
$output = explode('|' , $output);
$ipForward = $output[0];

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/safety.php");
include($racine_path."src/templates/footer.php");
?>
