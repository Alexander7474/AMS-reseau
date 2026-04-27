#!/bin/bash

# =============================================================================
# manage_filter.sh
# Gestion des adresses MAC dans /var/www/html/src/config/filter.json
#
# Structure JSON :
# {
#   "lundi":   { "0": [], "1": [], ..., "23": [] },
#   "mardi":   { ... },
#   ...
#   "dimanche":{ ... }
# }
#
# Usage:
#   ./manage_filter.sh add    <jour> <heure 0-23> <mac>
#   ./manage_filter.sh remove <jour> <heure 0-23> <mac>
#   ./manage_filter.sh list   <jour> <heure 0-23>
#   ./manage_filter.sh show                          → JSON brut (pour PHP)
#   ./manage_filter.sh show   <mac>                  → plages d'un host
# =============================================================================

FILTER_FILE="/var/www/html/src/config/filter.json"
JOURS="lundi mardi mercredi jeudi vendredi samedi dimanche"

# --------------------------------------------------------------------------
# Utilitaires
# --------------------------------------------------------------------------

usage() {
    echo "Usage:"
    echo "  $0 add    <jour> <heure 0-23> <mac>   Ajoute une MAC"
    echo "  $0 remove <jour> <heure 0-23> <mac>   Retire une MAC"
    echo "  $0 list   <jour> <heure 0-23>          Liste les MAC d'un créneau"
    echo "  $0 show                                 Affiche le JSON complet"
    echo "  $0 show   <mac>                         Affiche les plages d'un host"
    echo ""
    echo "Jours : lundi mardi mercredi jeudi vendredi samedi dimanche"
    exit 1
}

init_file() {
    if [ ! -f "$FILTER_FILE" ]; then
        echo "Fichier introuvable, création de $FILTER_FILE ..." >&2
        mkdir -p "$(dirname "$FILTER_FILE")"
        local json='{'
        local day_sep=''
        for jour in $JOURS; do
            json="${json}${day_sep}\"${jour}\":{"
            local hour_sep=''
            for i in $(seq 0 23); do
                json="${json}${hour_sep}\"${i}\":[]"
                hour_sep=','
            done
            json="${json}}"
            day_sep=','
        done
        json="${json}}"
        echo "$json" > "$FILTER_FILE"
        echo "Fichier créé." >&2
    fi
}

validate_mac() {
    local mac
    mac=$(echo "$1" | tr '[:upper:]' '[:lower:]')
    if echo "$mac" | grep -qE '^([0-9a-f]{2}:){5}[0-9a-f]{2}$'; then
        echo "$mac"; return 0
    fi
    echo "Erreur : MAC invalide '$1'" >&2; return 1
}

validate_hour() {
    if echo "$1" | grep -qE '^[0-9]+$' && [ "$1" -ge 0 ] && [ "$1" -le 23 ]; then
        return 0
    fi
    echo "Erreur : heure invalide '$1' (0-23)" >&2; return 1
}

validate_day() {
    local clean
    clean=$(echo "$1" | tr '[:upper:]' '[:lower:]')
    for j in $JOURS; do
        if [ "$j" = "$clean" ]; then echo "$clean"; return 0; fi
    done
    echo "Erreur : jour invalide '$1'" >&2; return 1
}

# --------------------------------------------------------------------------
# Lecture du JSON
# Le JSON est stocké sur une seule ligne, on extrait avec grep/tr uniquement
# --------------------------------------------------------------------------
get_mac_list_raw() {
    local day="$1"
    local hour="$2"
    local content
    content=$(cat "$FILTER_FILE")

    # Extrait le bloc du jour : "lundi":{...}
    # Le bloc jour ne contient pas de } imbriqués (les valeurs sont des tableaux)
    # donc on peut chercher jusqu'au premier } qui ferme l'objet jour
    local day_block
    day_block=$(echo "$content" | grep -o "\"${day}\":{[^}]*}")

    if [ -z "$day_block" ]; then echo ""; return; fi

    # Dans ce bloc, extrait le tableau de l'heure : "3":[...]
    local inside
    inside=$(echo "$day_block" | grep -o "\"${hour}\":\[[^]]*\]" | grep -o '\[[^]]*\]' | tr -d '[]')
    echo "$inside"
}

# --------------------------------------------------------------------------
# Reconstruction du JSON complet
# --------------------------------------------------------------------------
rebuild_json() {
    local mod_day="$1"
    local mod_hour="$2"
    local new_list="$3"

    local json='{'
    local day_sep=''
    for jour in $JOURS; do
        json="${json}${day_sep}\"${jour}\":{"
        local hour_sep=''
        for i in $(seq 0 23); do
            local list_content
            if [ "$jour" = "$mod_day" ] && [ "$i" -eq "$mod_hour" ]; then
                list_content="$new_list"
            else
                list_content=$(get_mac_list_raw "$jour" "$i")
            fi
            if [ -z "$list_content" ]; then
                json="${json}${hour_sep}\"${i}\":[]"
            else
                json="${json}${hour_sep}\"${i}\":[${list_content}]"
            fi
            hour_sep=','
        done
        json="${json}}"
        day_sep=','
    done
    json="${json}}"
    echo "$json"
}

# --------------------------------------------------------------------------
# Actions
# --------------------------------------------------------------------------

action_add() {
    local day hour mac current_list new_list new_json
    day=$(validate_day "$1")   || exit 1
    validate_hour "$2"          || exit 1; hour="$2"
    mac=$(validate_mac "$3")   || exit 1

    current_list=$(get_mac_list_raw "$day" "$hour")

    if echo "$current_list" | grep -q "\"${mac}\""; then
        echo "L'adresse $mac est déjà dans $day h$hour." >&2; exit 0
    fi

    if [ -z "$current_list" ]; then
        new_list="\"${mac}\""
    else
        new_list="${current_list},\"${mac}\""
    fi

    new_json=$(rebuild_json "$day" "$hour" "$new_list")
    echo "$new_json" > "$FILTER_FILE"
    echo "OK: $mac ajoutée dans $day h$hour."
}

action_remove() {
    local day hour mac current_list new_list new_json entry sep old_ifs
    day=$(validate_day "$1")   || exit 1
    validate_hour "$2"          || exit 1; hour="$2"
    mac=$(validate_mac "$3")   || exit 1

    current_list=$(get_mac_list_raw "$day" "$hour")

    if ! echo "$current_list" | grep -q "\"${mac}\""; then
        echo "L'adresse $mac n'est pas dans $day h$hour." >&2; exit 0
    fi

    new_list=""; sep=""
    old_ifs="$IFS"; IFS=','
    for entry in $current_list; do
        IFS="$old_ifs"
        entry=$(echo "$entry" | tr -d ' ')
        if [ "$entry" != "\"${mac}\"" ]; then
            new_list="${new_list}${sep}${entry}"; sep=','
        fi
        IFS=','
    done
    IFS="$old_ifs"

    new_json=$(rebuild_json "$day" "$hour" "$new_list")
    echo "$new_json" > "$FILTER_FILE"
    echo "OK: $mac retirée de $day h$hour."
}

action_list() {
    local day hour raw entry mac count old_ifs
    day=$(validate_day "$1")  || exit 1
    validate_hour "$2"         || exit 1; hour="$2"

    raw=$(get_mac_list_raw "$day" "$hour")
    echo "=== $day h$hour ==="
    if [ -z "$raw" ]; then echo "(vide)"; return; fi

    count=0; old_ifs="$IFS"; IFS=','
    for entry in $raw; do
        IFS="$old_ifs"
        mac=$(echo "$entry" | tr -d '"' | tr -d ' ')
        echo "  - $mac"; count=$((count + 1))
        IFS=','
    done
    IFS="$old_ifs"
    echo "Total : $count adresse(s)"
}

action_show() {
    local filter_mac="$1"

    if [ -n "$filter_mac" ]; then
        filter_mac=$(validate_mac "$filter_mac") || exit 1
        echo "=== Plages de $filter_mac ==="
        for jour in $JOURS; do
            local found=""
            for i in $(seq 0 23); do
                raw=$(get_mac_list_raw "$jour" "$i")
                if echo "$raw" | grep -q "\"${filter_mac}\""; then
                    found="${found} h${i}"
                fi
            done
            [ -n "$found" ] && echo "$jour :$found"
        done
    else
        # Sortie JSON brute — lue par PHP via shell_exec
        cat "$FILTER_FILE"
    fi
}

# --------------------------------------------------------------------------
# Main
# --------------------------------------------------------------------------
init_file

case "$1" in
    add)    [ $# -ne 4 ] && usage; action_add    "$2" "$3" "$4" ;;
    remove) [ $# -ne 4 ] && usage; action_remove "$2" "$3" "$4" ;;
    list)   [ $# -ne 3 ] && usage; action_list   "$2" "$3"      ;;
    show)   action_show "$2" ;;
    *)      usage ;;
esac
