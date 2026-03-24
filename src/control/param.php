<?php 
session_start();
$racine_path = "../../";

include $racine_path."src/model/User.php";
include $racine_path."src/model/Validator.php";
include $racine_path."src/model/Tools.php";
// refuse l'accès au utilisateur non connecté
User::checkIfConnected();

$page_param = true;

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_config'])){
        shell_exec("sudo /var/www/html/src/scripts/save-config.sh");
}       
        
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saved_config']) && isset($_POST['rm_config'])){
        if(Validator::isSafeString($_POST['saved_config'])){
                shell_exec("sudo /var/www/html/src/scripts/rm-saved-config.sh ".$_POST['saved_config']);
        }
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saved_config']) && isset($_POST['load_config'])){
        if(Validator::isSafeString($_POST['saved_config'])){
                shell_exec("sudo /var/www/html/src/scripts/load-config.sh ".$_POST['saved_config']);
        }
}


// charge la listes des configurations sauvegardées 
$save_folders = glob($racine_path.'src/config/saves/*');
$saves = array();

$json = file_get_contents($racine_path.'src/config/hosts.json');
$hosts = json_decode($json, true)["hosts"];

// traitement sur chaque configuration 
// TODO -- ajouter la possibilité de rennomer une config
foreach (array_reverse($save_folders) as $sf) {
        // list d'informations pour chaque config 
        $warnings = array();
        $dangers = array();
        $infos = array();

        $nHost = count($hosts);

        // Check du nombre d'hosts
        $content = file_get_contents('/etc/network/interfaces');
        preg_match('/Internal network.*?netmask\s+([\d.]+)/s', $content, $matches);
        $netmask = $matches[1] ?? null;
        $totalAddr = Tools::totalAddr($netmask);

        if($nHost > $totalAddr){
                $dangers[] = "Le nombre d'addresse de la configuration est insuffisant pour le réseaux actuelle ("
                        .$totalAddr.
                        " addresse(s) pour "
                        .$nHost.
                        " host(s))";
        }

        $nAddrFreeNeeded = 10;
        if($nHost > $totalAddr - $nAddrFreeNeeded && $totalAddr - $nHost >= 0){
                $warnings[] = "Le nombre d'addresse de la configuration peut devenir insuffisant à l'avenir ("
                        .($totalAddr - $nHost).
                        " addresse libre)";
        }

        // Check port forward et ip block 

        // Check DNS

        $saves[] = [
                "name" => $sf,
                "warnings" => $warnings,
                "dangers" => $dangers,
                "infos" => $infos
        ];
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['password2'])){
  if($_POST['password'] == $_POST['password2']){
    if(Validator::isSafeString($_POST['username'])){
      $user = new User($_POST['username'], $_POST['password']);
      $user->save();
    }else{
      $errorChangeLogin = "Le nom d'utilisateur est invalide/non sécurisé";
    }
  }else{
    $errorChangeLogin = "Les deux mots de passe doivent être identique";
  }
}

include($racine_path."src/templates/header.php");
include($racine_path."src/templates/navigation.php");
include($racine_path."src/templates/param.php");
include($racine_path."src/templates/footer.php");
?>
