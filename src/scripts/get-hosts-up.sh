#!/bin/bash

# Enregistre touts les hosts du réseaux dans hosts.json
# Ce script est lancé par un crownjob toutes les minutes 

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
    # --oG : output parseable (grepable)
    nmap -sn -T4 --oG - "$cidr" 2>/dev/null \
        | grep "^Host:" \
        | awk '{print $2, $3}' \
        | sed 's/[()]//g'
    # Sortie par ligne : "192.168.1.1 router.local" ou "192.168.1.42 "
}

echo "Démarrage du scan réseau (nmap)..."

declare -a NAMES=()
declare -a ADDRS=()

while IFS= read -r cidr; do
    [ -z "$cidr" ] && continue
    echo "Réseau détecté : $cidr"

    while IFS= read -r line; do
        ip=$(echo "$line" | awk '{print $1}')
        host=$(echo "$line" | awk '{print $2}')

        # Si nmap n'a pas résolu de hostname, utilise l'IP
        [ -z "$host" ] && host="$ip"

        ADDRS+=("$ip")
        NAMES+=("$host")
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
        echo "    {\"name\": \"${NAMES[$i]}\", \"addr\": \"${ADDRS[$i]}\"}${comma}"
    done

    echo '  ]'
    echo '}'
} > "$OUTPUT_FILE"

echo "Fichier généré : $OUTPUT_FILE"
cat "$OUTPUT_FILE"
