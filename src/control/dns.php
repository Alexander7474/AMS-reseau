<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
include $racine_path."src/model/Validator.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$page_dns = true;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_zone_dns'])){
  $name = $_POST['domain_name'];
  if(Validator::isSafeString($name)){
    shell_exec("sudo /var/www/html/src/scripts/change-domain.sh ".$name);
  }else{
    $errorDnsZone = "Nouveau nom de zone invalide/non sécurisé";
  }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_subdomain'])){
  $subdomain = $_POST['subdomain_name'];
  $ip = $_POST["ip1"].".".$_POST["ip2"].".".$_POST["ip3"].".".$_POST["ip4"];
  if($subdomain != "box" && Validator::isSafeString($subdomain)){
    if(Validator::isValidIp($ip)){
      shell_exec("sudo /var/www/html/src/scripts/add-subdomain.sh ".$subdomain." ".$ip);
    }else{
      $errorDnsAdd = "Ip invalide";
    }
  }else{
    $errorDnsAdd = "Nouveau sous domaine invalide/non sécurisé";
  }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rm_subdomain'])){
  $subdomain = $_POST['subdomain_name'];
  if($subdomain != "box" && Validator::isSafeString($subdomain)){
    shell_exec("sudo /var/www/html/src/scripts/rm-subdomain.sh ".$subdomain);
  }
}

// recup zone dns
$zoneDns = shell_exec("/var/www/html/src/scripts/get-dns-zone.sh");

$subdomains = explode('|', shell_exec("sudo /var/www/html/src/scripts/get-subdomain.sh"));

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/dns.php");
include($racine_path."src/templates/footer.php");
?>
