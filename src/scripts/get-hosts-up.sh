#!/bin/bash

mask_to_cidr() {
    ipcalc 0.0.0.0 "$1" | cut -d ' ' -f6 | head -n 2 | tail -n 1
}

if [ $# -ne 2 ]; then
  echo "error: besoin de deux args (:networkaddress :maskaddress)"; exit 1
fi

if [[ ! $1 =~ ^192\.168\.([0-9]{1,3})\.([0-9]{1,3})$ ]]; then
  echo "error: $1 n'est pas une ip local valide"; exit 1
fi

if [[ ! $2 =~ ^255\.255\.([0-9]{1,3})\.([0-9]{1,3})$ ]]; then
  echo "error: $2 n'est pas une addresse masque"; exit 1
fi

cidr=$(mask_to_cidr $2)
NETWORK="$1/$cidr"
RESULT=""

while read -r ip host _; do
    # If hostname is missing, replace with "-"
    host=${host:-"-"}
    RESULT+="${ip}/${host}|"
done < <(
    nmap -sn "$NETWORK" 2>/dev/null \
    | awk '
        /^Nmap scan report/ {
            if ($NF ~ /^\(/) {
                gsub("[()]", "", $NF)
                print $NF, $5
            } else {
                print $5, "-"
            }
        }
    '
)

# Remove trailing |
echo "${RESULT%|}"
