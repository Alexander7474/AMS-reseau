#!/bin/bash

CONF="/etc/dhcp/dhcpd.conf"

# Convert IPv4 to integer
ip_to_int() {
  local IFS=.
  read -r a b c d <<< "$1"
  echo $((a*256**3 + b*256**2 + c*256 + d))
}

ping_check=0

status=0
if systemctl is-active --quiet "isc-dhcp-server"; then
  status=1
fi

while IFS= read -r line; do
  # Supprimer les lignes commentées
  line=${line%%#*}

  # si la ligne est ping-check true; (elle arrivera toujours avant range donc pas de problème pour l'affichage) 
  if [[ $line =~ ^[[:space:]]*ping-check[[:space:]]+true[[:space:]]* ]]; then
    ping_check=1
  fi

  # Si "range x.x.x.x y.y.y.y;" alors on decoup pour recup les address
  if [[ $line =~ ^[[:space:]]*range[[:space:]]+([0-9.]+)[[:space:]]+([0-9.]+) ]]; then
    start_ip="${BASH_REMATCH[1]}"
    end_ip="${BASH_REMATCH[2]}"

    start_int=$(ip_to_int "$start_ip")
    end_int=$(ip_to_int "$end_ip")

    total=$((end_int - start_int + 1))

    echo "$start_ip|$end_ip|$total|$ping_check|$status"
  fi
done < "$CONF"
