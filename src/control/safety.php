<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
include $racine_path."src/model/Graph.php";
include $racine_path."src/model/Validator.php";
include $racine_path."src/model/charts4php/inc/chartphp_dist.php";
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
  $port = intval($_POST['port']);
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

$script       = '';
$form_message = '';
$form_success = false;
 
$days = ['lundi','mardi','mercredi','jeudi','vendredi','samedi','dimanche'];

$actual_filter = shell_exec('sudo /var/www/html/src/scripts/manage_mac_filter.sh show 2>/dev/null');
$filter_data   = json_decode($actual_filter, true) ?: [];

$hosts_file  = '/var/www/html/src/config/hosts.json';
$hosts_by_mac = [];
if (file_exists($hosts_file)) {
    $hosts_json = json_decode(file_get_contents($hosts_file), true);
    foreach ($hosts_json['hosts'] ?? [] as $host) {
        if (!empty($host['mac'])) {
            $mac = strtolower(trim($host['mac']));
            $hosts_by_mac[$mac] = [
                'name' => $host['name'] ?? $host['addr'] ?? $mac,
                'addr' => $host['addr'] ?? '',
            ];
        }
    }
}
 
$macs_filter_only = [];
foreach ($filter_data as $day => $hours) {
    foreach ($hours as $hour => $macs) {
        foreach ($macs as $mac) {
            $mac = strtolower(trim($mac));
            if (!isset($hosts_by_mac[$mac]) && !in_array($mac, $macs_filter_only)) {
                $macs_filter_only[] = $mac;
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["selected_mac"])) {
 
    $mac   = $_POST['selected_mac'];
    $slots = $_POST['slots'] ?? [];
 
    if (!filter_var($mac, FILTER_VALIDATE_MAC)) {
        $form_message = 'Adresse MAC invalide. mac='.$mac;
        $form_success = false;
    } else {
        $errors = 0;
 
        foreach ($days as $day) {
            for ($h = 0; $h <= 23; $h++) {
                $should_block = isset($slots[$day][$h]);
                $current_macs = array_map('strtolower', $filter_data[$day][$h] ?? []);
                $is_blocked   = in_array($mac, $current_macs);
 
                if ($should_block && !$is_blocked) {
                    $out = shell_exec(
                        escapeshellcmd('sudo /var/www/html/src/scripts/manage_mac_filter.sh')
                        . ' add '
                        . escapeshellarg($day) . ' '
                        . intval($h) . ' '
                        . escapeshellarg($mac)
                        . ' 2>&1'
                    );
                    if (strpos($out, 'OK') === false && strpos($out, 'déjà') === false) $errors++;
 
                } elseif (!$should_block && $is_blocked) {
                    $out = shell_exec(
                        escapeshellcmd('sudo /var/www/html/src/scripts/manage_mac_filter.sh')
                        . ' remove '
                        . escapeshellarg($day) . ' '
                        . intval($h) . ' '
                        . escapeshellarg($mac)
                        . ' 2>&1'
                    );
                    if (strpos($out, 'OK') === false && strpos($out, "n'est pas") === false) $errors++;
                }
            }
        }
 
        /* Recharge la config après modifications */
        $actual_filter = shell_exec('sudo /var/www/html/src/scripts/manage_mac_filter.sh show 2>/dev/null');
        $filter_data   = json_decode($actual_filter, true) ?: [];
 
        $form_success = ($errors === 0);
        $form_message = $form_success
            ? 'Configuration appliquée pour ' . htmlspecialchars($mac) . '.'
            : $errors . ' erreur(s) lors de l\'application. Vérifiez les logs.';
    }
}


include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/safety.php");
include($racine_path."src/templates/footer.php");
?>
