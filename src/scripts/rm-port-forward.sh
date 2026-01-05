#!/bin/bash

# Usage: ./del-portforward.sh <PORT_ENTREE>

if [ "$#" -ne 1 ]; then
  echo "Erreur, usage: $0 <PORT_ENTREE>"
  exit 1
fi

PORT="$1"

# Supprimer les règles DNAT correspondantes
iptables -t nat -S PREROUTING | grep DNAT | grep -E "(--dport ${PORT})( |$)" | while read -r rule; do
    iptables -t nat ${rule/-A/-D}
done

# FORWARD 
iptables -S FORWARD | grep -E "(--dport ${PORT})( |$)" | while read -r rule; do
    iptables ${rule/-A/-D}
done
