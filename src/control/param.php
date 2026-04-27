<?php
session_start();
$racine_path = "../../";
include $racine_path."src/model/User.php";
include $racine_path."src/model/Validator.php";
include $racine_path."src/model/Tools.php";
include $racine_path."src/model/Database.php";
include $racine_path."src/model/Message.php";
include $racine_path."src/model/Subject.php";
include $racine_path."src/model/forum-config.php";

// Refuse l'accès aux utilisateurs non connectés
User::checkIfConnected();

$page_param = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_config'])) {
    shell_exec("sudo /var/www/html/src/scripts/save-config.sh");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saved_config']) && isset($_POST['rm_config'])) {
    if (Validator::isSafeString($_POST['saved_config'])) {
        shell_exec("sudo /var/www/html/src/scripts/rm-saved-config.sh " . $_POST['saved_config']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['saved_config']) && isset($_POST['load_config'])) {
    if (Validator::isSafeString($_POST['saved_config'])) {
        shell_exec("sudo /var/www/html/src/scripts/load-config.sh " . $_POST['saved_config']);
    }
}


// Liste des dossiers de sauvegarde (nommés par date ex: 2026-04-23_22:12:40)
$save_folders = glob($racine_path . 'src/config/saves/*');

$saves = array();

// Hôtes actuellement connus sur le réseau
$json  = file_get_contents($racine_path . 'src/config/hosts.json');
$hosts = json_decode($json, true)["hosts"];
$nHost = count($hosts);

// Historique des tests de débit (pour comparaison avec la date de sauvegarde)
$hist_debit_path = $racine_path . 'src/config/hist_debit.json';
// Chargement
$hist_debit = [];
if (file_exists($hist_debit_path)) {
    $decoded = json_decode(file_get_contents($hist_debit_path), true);
    $hist_debit = $decoded['tests'] ?? [];
}

// On charge une seule fois pour éviter un appel par sauvegarde
$database = new Database($host, $dbname, $user, $pass);
$subjectDb = new Subject($database->getConnection());
$forum_subjects = $subjectDb->getAll();
 
/*
 * Cherche les sujets du forum pertinents pour un message warning/danger.
 *
 * Stratégie :
 *  1. Découpe le message en mots
 *  2. Pour chaque mot, vérifie s'il matche dans le sujet du forum + de 2 fois
 *  4. Retourne les sujets trouvés par id
 */
function find_related_subjects($message, $subjects) {
    $related  = [];
    $msg_words = preg_split('/[\s\-\/]+/', $message);
    foreach ($subjects as $subject) {
        $title = $subject['title'];
        $cnt = 0;
        foreach ($msg_words as $word) {
            if(strlen($word) <= 3){
                continue;
            }
            if (strpos($title, $word) >= 1) {
                    $cnt+=1;
            }
            if($cnt >= 2){
                    $related[$subject['id']] = $subject;
                break;
            }
        }
    }

    return array_values($related);
}

foreach (array_reverse($save_folders) as $sf) {

    $warnings = array();
    $dangers  = array();
    $infos    = array();

    // Nom lisible du dossier (= date de sauvegarde)
    $save_name = basename($sf);

    // Chaque sauvegarde doit contenir : interfaces, ip_forward, iptables, dhcpd.conf, bind/
    // Si un fichier est absent, la config est incomplète → danger
    $expected_files = ['interfaces', 'ip_forward', 'iptables', 'dhcpd.conf'];
    foreach ($expected_files as $ef) {
        if (!file_exists($sf . '/' . $ef)) {
            $dangers[] = "Fichier manquant dans la sauvegarde : '$ef' — configuration incomplète.";
        }
    }
    if (!is_dir($sf . '/bind')) {
        $dangers[] = "Dossier 'bind' manquant dans la sauvegarde — configuration DNS absente.";
    }

    $interfaces_content = file_exists($sf . '/interfaces')
        ? file_get_contents($sf . '/interfaces')
        : '';

    // Extrait le masque du bloc #Internal network
    preg_match('/#\s*Internal network[\s\S]*?netmask\s+([\d.]+)/', $interfaces_content, $matches);
    $netmask   = $matches[1] ?? null;
    $totalAddr = $netmask ? Tools::totalAddr($netmask) : 0;

    if ($totalAddr === 0) {
        // Masque introuvable ou illisible → on ne peut pas valider la capacité
        $warnings[] = "Impossible de lire le masque réseau interne dans 'interfaces'.";
    } else {

        // DANGER : le réseau ne peut plus accueillir tous les hôtes connus
        if ($nHost > $totalAddr) {
            $dangers[] = "Capacité réseau insuffisante : {$totalAddr} adresse(s) disponible(s) "
                       . "pour {$nHost} hôte(s) connu(s).";
        }

        // WARNING : moins de 10 adresses libres — risque à court terme
        $nAddrFreeNeeded = 10;
        $nFree = $totalAddr - $nHost;
        if ($nHost <= $totalAddr && $nFree < $nAddrFreeNeeded) {
            $warnings[] = "Peu d'adresses libres : {$nFree} restante(s) "
                        . "(seuil conseillé : {$nAddrFreeNeeded}).";
        }

        // INFO : taux d'occupation du sous-réseau
        if ($totalAddr > 0) {
            $usage = round(($nHost / $totalAddr) * 100);
            $infos[] = "Taux d'occupation du sous-réseau : {$usage}% ({$nHost}/{$totalAddr} adresses).";
        }
    }

    // ip_forward contient "1" (activé) ou "0" (désactivé)
    if (file_exists($sf . '/ip_forward')) {
        $ip_forward = trim(file_get_contents($sf . '/ip_forward'));
        if ($ip_forward !== '1') {
            $dangers[] = "IP forwarding désactivé dans cette sauvegarde "
                       . "— le routage inter-interfaces ne fonctionnera pas.";
        } else {
            $infos[] = "IP forwarding activé.";
        }
    }

    if (file_exists($sf . '/iptables')) {
        $ipt_content = file_get_contents($sf . '/iptables');
 
        // INFO : nombre de règles sauvegardées (lignes non vides et non commentaires)
        $ipt_lines = array_filter(
            explode("\n", $ipt_content),
            function($l) {
                return trim($l) !== ''
                    && strlen($l) > 0
                    && $l[0] !== '#'
                    && $l[0] !== '*'
                    && $l[0] !== 'C';
            }
        );
        $infos[] = count($ipt_lines) . " règle(s) de sécurité réseau sauvegardée(s).";
 
        // WARNING : aucune politique de filtrage FORWARD → trafic entre interfaces non restreint
        if (!preg_match('/(FORWARD.*DROP|FORWARD.*REJECT|-P FORWARD DROP)/i', $ipt_content)) {
            $warnings[] = "Cette configuration ne bloque pas le trafic entre les interfaces "
                        . "— tous les appareils peuvent communiquer librement entre eux.";
        }
 
        // WARNING : pas de règle NAT MASQUERADE → accès Internet des clients potentiellement cassé
        if (!preg_match('/MASQUERADE/', $ipt_content)) {
            $warnings[] = "Cette configuration ne redirige pas correctement le trafic vers Internet "
                        . "— les appareils du réseau risquent de ne pas pouvoir naviguer.";
        }
 
        // Collecte toutes les IPs des hôtes connus
        $known_ips = array_map(function($h) { return $h['addr']; }, $hosts);
 
        // Extraire toutes les IPs mentionnées dans les règles iptables
        $ipt_lines_with_ip = array_filter(
            explode("\n", $ipt_content),
            function($l) { return preg_match('/\d+\.\d+\.\d+\.\d+/', $l); }
        );
 
        $ips_in_rules = [];
        foreach ($ipt_lines_with_ip as $line) {
            preg_match_all('/(\d+\.\d+\.\d+\.\d+)(?:\/\d+)?/', $line, $found);
            foreach ($found[1] as $ip) {
                if ($ip !== '0.0.0.0' && $ip !== '255.255.255.255') {
                    $ips_in_rules[] = $ip;
                }
            }
        }
        $ips_in_rules = array_unique($ips_in_rules);
 
        // WARNING : règle visant une IP qui n'est plus sur le réseau → règle probablement obsolète
        foreach ($ips_in_rules as $ip) {
            if (!in_array($ip, $known_ips)) {
                $warnings[] = "Une restriction vise l'appareil $ip "
                            . "qui n'est plus détecté sur le réseau — "
                            . "cette règle est peut-être obsolète.";
            }
        }
 
        // INFO : appareils actuellement bloqués ou restreints dans cette config
        foreach ($hosts as $host) {
            $ip = $host['addr'];
            foreach (explode("\n", $ipt_content) as $line) {
                if (strpos($line, $ip) !== false && preg_match('/(DROP|REJECT)/i', $line)) {
                    $mac   = isset($host['mac']) ? $host['mac'] : '';
                    $label = $mac ? "$ip ($mac)" : $ip;
                    $infos[] = "L'appareil $label a une restriction d'accès active "
                             . "dans cette configuration.";
                    break;
                }
            }
        }
 
    }


    if (file_exists($sf . '/dhcpd.conf')) {
        $dhcp_content = file_get_contents($sf . '/dhcpd.conf');

        // Extrait la plage DHCP (range x.x.x.x x.x.x.x)
        preg_match('/range\s+([\d.]+)\s+([\d.]+)/', $dhcp_content, $dhcp_range);
        if (!empty($dhcp_range)) {
            $infos[] = "Plage DHCP : " . $dhcp_range[1] . " → " . $dhcp_range[2] . ".";
        } else {
            $warnings[] = "Aucune plage DHCP (range) trouvée dans dhcpd.conf.";
        }

        // Extrait le lease time (default-lease-time)
        preg_match('/default-lease-time\s+(\d+)/', $dhcp_content, $lease);
        if (!empty($lease)) {
            $lease_h = round($lease[1] / 3600, 1);
            $infos[]  = "Durée de bail DHCP : {$lease[1]}s ({$lease_h}h).";
        }

        // WARNING : pas de DNS déclaré dans le DHCP → les clients n'auront pas de résolution
        if (!preg_match('/domain-name-servers/', $dhcp_content)) {
            $warnings[] = "Aucun serveur DNS déclaré dans dhcpd.conf (domain-name-servers manquant).";
        }
    }

    if (is_dir($sf . '/bind')) {
        $bind_files = glob($sf . '/bind/*');

        // INFO : nombre de fichiers de zone
        $zone_files = array_filter($bind_files, function($f) { return preg_match('/db\.|\.zone/', basename($f)); });
        $infos[] = count($zone_files) . " fichier(s) de zone DNS sauvegardé(s).";

        // WARNING : dossier bind vide → pas de config DNS
        if (empty($bind_files)) {
            $warnings[] = "Le dossier 'bind' est vide — aucune configuration DNS sauvegardée.";
        }
    }

    if (is_dir($sf . '/bind') && !empty($hosts)) {
        $bind_content = '';
        foreach (glob($sf . '/bind/*') as $bf) {
            $bind_content .= file_get_contents($bf);
        }
        $hosts_without_dns = [];
        foreach ($hosts as $host) {
            $name = $host['name'] ?? '';
            // On ignore les hôtes dont le nom est une IP (pas de hostname résolu)
            if ($name && !filter_var($name, FILTER_VALIDATE_IP)) {
                // Cherche le nom court (avant le premier point) dans les fichiers de zone
                $short = explode('.', $name)[0];
                if (!str_contains($bind_content, $short)) {
                    $hosts_without_dns[] = $name;
                }
            }
        }
        if (!empty($hosts_without_dns)) {
            $warnings[] = count($hosts_without_dns) . " hôte(s) connu(s) sans entrée DNS dans BIND : "
                        . implode(', ', array_slice($hosts_without_dns, 0, 5))
                        . (count($hosts_without_dns) > 5 ? '…' : '') . ".";
        }
    }

    // INFO si < 7 jours, WARNING si > 30 jours (config potentiellement obsolète)
    $save_date = DateTime::createFromFormat('Y-m-d_H:i:s', $save_name);
    if ($save_date) {
        $now      = new DateTime();
        $age_days = (int) $now->diff($save_date)->days;
        if ($age_days <= 7) {
            $infos[] = "Sauvegarde récente ({$age_days} jour(s)).";
        } elseif ($age_days > 30) {
            $warnings[] = "Sauvegarde ancienne ({$age_days} jours) — la configuration "
                        . "peut ne plus correspondre à l'état actuel du réseau.";
        }
    }

    // Si un test de débit existe pour la même journée → on l'affiche en info
    // Si aucun test n'existe autour de cette date → warning (on ne sait pas si le réseau était OK)
    if ($save_date && !empty($hist_debit)) {
            $debit_match = null;
            foreach ($hist_debit as $entry) {
                // Format du fichier : "dd/mm/yyyy HH:MM"
                $entry_date = DateTime::createFromFormat('d/m/Y H:i', $entry['date'] ?? '');
                if ($entry_date) {
                    $diff_min = abs((int)(($entry_date->getTimestamp() - $save_date->getTimestamp()) / 60));
                    if ($diff_min <= 120) {
                        $debit_match = $entry;
                        break;
                    }
                }
            }
            if ($debit_match) {
                $dl = $debit_match['result']['dlSpeed'] ?? '?';
                $ul = $debit_match['result']['upSpeed'] ?? '?';
                $infos[] = "Débit mesuré lors de cette sauvegarde : ↓ {$dl} Mbps / ↑ {$ul} Mbps.";
            } else {
                $warnings[] = "Aucun test de débit trouvé dans les 2h autour de cette sauvegarde.";
            }
    }

    // On agrège tous les messages problématiques et on cherche des sujets
    // qui pourraient aider à les résoudre.
    $related_subjects = [];
    $all_issues = array_merge($warnings, $dangers);
    foreach ($all_issues as $issue) {
        $found = find_related_subjects($issue, $forum_subjects);
        foreach ($found as $subject) {
            // Dédoublonnage par id sur l'ensemble des résultats de la sauvegarde
            $related_subjects[$subject['id']] = $subject;
        }
    }

    $saves[] = [
        "name"             => $sf,
        "warnings"         => $warnings,
        "dangers"          => $dangers,
        "infos"            => $infos,
        "related_subjects" => array_values($related_subjects),
    ];

}

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['username'], $_POST['password'], $_POST['password2'])
) {
    if ($_POST['password'] == $_POST['password2']) {
        if (Validator::isSafeString($_POST['username'])) {
            $user = new User($_POST['username'], $_POST['password']);
            $user->save();
        } else {
            $errorChangeLogin = "Le nom d'utilisateur est invalide/non sécurisé";
        }
    } else {
        $errorChangeLogin = "Les deux mots de passe doivent être identiques";
    }
}

include($racine_path . "src/templates/header.php");
include($racine_path . "src/templates/navigation.php");
include($racine_path . "src/templates/param.php");
include($racine_path . "src/templates/footer.php");
?>
