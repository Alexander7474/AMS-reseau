#!/bin/bash
# Enregistre tous les hosts du réseau dans hosts.json
# Ce script est lancé par un cronjob toutes les minutes

OUTPUT_FILE="/var/www/html/src/config/hosts.json"

get_networks() {
    ip route show 2>/dev/null \
        | grep -v '^default' \
        | grep -oP '\d+\.\d+\.\d+\.\d+/\d+' \
        | grep -v '^127\.' \
        | sort -u
}

scan_network() {
    local cidr="$1"
    # -sn  : ping scan uniquement (pas de port scan)
    # -T4  : timing agressif (plus rapide)
    # --oG : output grepable — contient IP, hostname ET adresse MAC
    nmap -sn -T4 --oG - "$cidr" 2>/dev/null | grep "^Host:"
    # Exemple de ligne grepable :
    # Host: 192.168.1.42 (router.local)  Status: Up
    # Host: 192.168.1.10 ()              Status: Up
    # La MAC apparaît sur la ligne suivante sous la forme :
    # # Nmap done... ou dans les commentaires selon la version ;
    # on utilise donc -oX (XML) via une 2ème passe pour les MACs
}

# Récupère la MAC d'une IP via le scan XML nmap
get_mac_for_ip() {
    local ip="$1"
    local cidr="$2"
    # On rescanne uniquement cet hôte en XML pour extraire la MAC
    # Address addrtype="mac" n'apparaît que si on est root (ARP)
    nmap -sn -T4 "$ip" -oX - 2>/dev/null \
        | grep -i 'addrtype="mac"' \
        | grep -oP 'addr="\K[^"]+'
}

echo "Démarrage du scan réseau (nmap)..."

declare -a NAMES=()
declare -a ADDRS=()
declare -a MACS=()

while IFS= read -r cidr; do
    [ -z "$cidr" ] && continue
    echo "Réseau détecté : $cidr"

    while IFS= read -r line; do
        # Extrait IP et hostname depuis la ligne grepable
        ip=$(echo "$line"   | grep -oP 'Host: \K[\d.]+')
        host=$(echo "$line" | grep -oP '\(\K[^)]+')
        [ -z "$host" ] && host="$ip"

        # Récupère la MAC (nécessite d'être root pour ARP)
        mac=$(get_mac_for_ip "$ip" "$cidr")
        [ -z "$mac" ] && mac=""

        ADDRS+=("$ip")
        NAMES+=("$host")
        MACS+=("$mac")
    done < <(scan_network "$cidr")
done < <(get_networks)

echo ""
echo "Hôtes trouvés : ${#ADDRS[@]}"
echo "Génération de $OUTPUT_FILE ..."

{
    echo '{'
    echo '  "hosts": ['
    total=${#ADDRS[@]}
    for i in "${!ADDRS[@]}"; do
        comma=","
        [ $((i + 1)) -eq "$total" ] && comma=""

        mac_field=""
        if [ -n "${MACS[$i]}" ]; then
            # Normalise la MAC en minuscules
            mac_lower=$(echo "${MACS[$i]}" | tr '[:upper:]' '[:lower:]')
            mac_field=", \"mac\": \"${mac_lower}\""
        fi

        echo "    {\"name\": \"${NAMES[$i]}\", \"addr\": \"${ADDRS[$i]}\"${mac_field}}${comma}"
    done
    echo '  ]'
    echo '}'
} > "$OUTPUT_FILE"

echo "Fichier généré : $OUTPUT_FILE"
cat "$OUTPUT_FILE"
