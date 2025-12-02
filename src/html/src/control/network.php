<?php 
$racine_path = "../../";
$page_reseau = true;

//recup ip/mask 
$output = shell_exec("/var/www/html/src/scripts/get-ip.sh");
$output = explode('|' , $output);
$ip3 = $output[2];
$ip4 = $output[3];
$mask3 = $output[6];
$mask4 = $output[7];

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_ip'])){
  $networkConfig = '/etc/network/interfaces';
  $tempConfig = $racine_path.'src/tmp/interfaces.temp';

  $newIp = $_POST["ip1"].".".$_POST["ip2"].".".$_POST["ip3"].".".$_POST["ip4"];
  $newMask = $_POST["mask1"].".".$_POST["mask2"].".".$_POST["mask3"].".".$_POST["mask4"];

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

  $message = "#Config modifier par srcipt-ip.php\n";
  if(end($section) != $message){
    $section[] = $message;
  }

  file_put_contents($tempConfig, $section);

  $output = shell_exec("sudo /var/www/html/src/scripts/cfg-eth1.sh");
}


if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_dhcp'])){
  $dhcp_client = $_POST["dhcp_client"];
  $output = shell_exec("sudo /var/www/html/src/scripts/cfg-dhcp.sh ".$dhcp_client); // TODO-- sanitize user entry
  echo $output; 
}

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/network-form.php");
include($racine_path."src/templates/footer.php");

?>
