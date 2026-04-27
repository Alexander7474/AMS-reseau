#!/bin/bash

# =============================================================================
# apply_mac_filter.sh
# Crontab : 0 * * * * /usr/local/bin/apply_mac_filter.sh >> /var/log/mac_filter.log 2>&1
#
# Lit le filtre du jour courant + heure courante dans filter.json
# Vide la chaîne MAC_FILTER, puis applique les nouvelles règles iptables
# =============================================================================

FILTER_FILE="/var/www/html/src/config/filter.json"
CHAIN="MAC_FILTER"
LOG_PREFIX="[mac_filter]"

# Correspondance numéro de jour (date +%u) → nom français
# date +%u : 1=lundi ... 7=dimanche
DAY_NAMES="_ lundi mardi mercredi jeudi vendredi samedi dimanche"

log() {
    echo "$(date '+%Y-%m-%d %H:%M:%S') $LOG_PREFIX $*"
}

# --------------------------------------------------------------------------
# Heure et jour courants
# --------------------------------------------------------------------------
current_hour=$(date '+%H' | sed 's/^0*//')
[ -z "$current_hour" ] && current_hour=0

day_num=$(date '+%u')   # 1 = lundi, 7 = dimanche

# Récupère le nom du jour depuis la liste
current_day=""
idx=0
for name in $DAY_NAMES; do
    if [ "$idx" -eq "$day_num" ]; then
        current_day="$name"
        break
    fi
    idx=$((idx + 1))
done

if [ -z "$current_day" ]; then
    log "ERREUR : impossible de déterminer le jour (day_num=$day_num)"
    exit 1
fi

log "Jour : $current_day | Heure : $current_hour"

# --------------------------------------------------------------------------
# Vérifications
# --------------------------------------------------------------------------
if [ ! -f "$FILTER_FILE" ]; then
    log "ERREUR : fichier introuvable : $FILTER_FILE"; exit 1
fi

if ! command -v iptables > /dev/null 2>&1; then
    log "ERREUR : iptables non disponible."; exit 1
fi

if ! iptables -m mac --help > /dev/null 2>&1; then
    log "ERREUR : module iptables 'mac' non disponible."; exit 1
fi

# --------------------------------------------------------------------------
# Extraction des adresses MAC du créneau courant
# --------------------------------------------------------------------------
extract_macs() {
    local day="$1"
    local hour="$2"
    local content
    content=$(cat "$FILTER_FILE")

    local day_block
    day_block=$(echo "$content" | grep -o "\"${day}\":{[^}]*}")
    [ -z "$day_block" ] && return

    local inside
    inside=$(echo "$day_block" | grep -o "\"${hour}\":\[[^]]*\]" | grep -o '\[[^]]*\]' | tr -d '[]"')
    [ -z "$inside" ] && return

    local old_ifs="$IFS"; IFS=','
    for mac in $inside; do
        IFS="$old_ifs"
        mac=$(echo "$mac" | tr -d ' ')
        [ -n "$mac" ] && echo "$mac"
        IFS=','
    done
    IFS="$old_ifs"
}

# --------------------------------------------------------------------------
# Initialise la chaîne MAC_FILTER dans iptables
# --------------------------------------------------------------------------
init_chain() {
    if ! iptables -L "$CHAIN" -n > /dev/null 2>&1; then
        iptables -N "$CHAIN"
        log "Chaîne $CHAIN créée."
    fi
    if ! iptables -C FORWARD -j "$CHAIN" 2>/dev/null; then
        iptables -I FORWARD 1 -j "$CHAIN"
        log "Règle FORWARD -> $CHAIN ajoutée."
    fi
}

# --------------------------------------------------------------------------
# Vide la chaîne (supprime les règles de l'heure précédente)
# --------------------------------------------------------------------------
flush_chain() {
    iptables -F "$CHAIN" 2>/dev/null
    log "Chaîne $CHAIN vidée (règles heure précédente supprimées)."
}

# --------------------------------------------------------------------------
# Applique les nouvelles règles pour le créneau courant
# --------------------------------------------------------------------------
apply_rules() {
    local macs
    macs=$(extract_macs "$current_day" "$current_hour")

    if [ -z "$macs" ]; then
        log "Aucune adresse MAC à bloquer pour $current_day h$current_hour."
        return
    fi

    local count=0
    while IFS= read -r mac; do
        if echo "$mac" | grep -qE '^([0-9a-fA-F]{2}:){5}[0-9a-fA-F]{2}$'; then
            iptables -A "$CHAIN" -m mac --mac-source      "$mac" -j DROP
            iptables -A "$CHAIN" -m mac --mac-destination "$mac" -j DROP 2>/dev/null || true
            count=$((count + 1))
            log "Bloqué : $mac"
        else
            log "AVERTISSEMENT : MAC ignorée (format invalide) : '$mac'"
        fi
    done <<EOF
$macs
EOF

    log "$count adresse(s) bloquée(s) pour $current_day h$current_hour."
}

# --------------------------------------------------------------------------
# Main
# --------------------------------------------------------------------------
log "--- Début application règles ($current_day h$current_hour) ---"
init_chain
flush_chain
apply_rules
log "--- Fin ---"
exit 0
