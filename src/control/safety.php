<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
include $racine_path."src/model/Validator.php";
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
  $port = intval($_POST['port-entry']);
  $portDest = intval($_POST['port-destination']);
  $ip = $_POST["ip1"].".".$_POST["ip2"].".".$_POST["ip3"].".".$_POST["ip4"];
  if(Validator::isIntBet($port, 1 ,65535) && Validator::isIntBet($portDest, 1 ,65535) && Validator::isValidIp($ip)){
    shell_exec("sudo /var/www/html/src/scripts/add-port-forward.sh ".$port." ".$ip." ".$portDest);
  }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rm_forward'])){
  $port = $_POST['port'];
  if(Validator::isIntBet($port, 1 ,65535)){
    shell_exec("sudo /var/www/html/src/scripts/rm-port-forward.sh ".$port);
  }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_blocked_ip'])){
  $ip = $_POST["ip1"].".".$_POST["ip2"].".".$_POST["ip3"].".".$_POST["ip4"];
  if(Validator::isValidIp($ip)){
    shell_exec("sudo /var/www/html/src/scripts/add-ip-block.sh ".$ip);
  }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rm_ip_block'])){
  $ip = explode('/', $_POST['ip']);
  if(Validator::isValidIp($ip[0]) && Validator::isIntBet(intval($ip[1]), 0, 32)){
    shell_exec("sudo /var/www/html/src/scripts/rm-ip-block.sh ".$_POST['ip']);
  }
}

// recup configuration nat et sécurité
$output = shell_exec("/var/www/html/src/scripts/get-nat.sh");
$output = explode('|' , $output);
$ipForward = $output[0];

$forwards = explode('|', shell_exec("sudo /var/www/html/src/scripts/get-port-forward.sh"));
$ipBlocked = explode('|', shell_exec("sudo /var/www/html/src/scripts/get-ip-block.sh"));

// TODO -- utiliser un graphe pour afficher les tests de débit
// chargement de l'historique des tests de débit
$hist = file_get_contents($racine_path."src/config/hist_debit.json");
$histDebit = json_decode($hist);

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/safety.php");
include($racine_path."src/templates/footer.php");
?>
