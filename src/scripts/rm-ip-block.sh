#!/bin/bash

# Usage: ./del-blockip.sh <IP_TO_UNBLOCK>

if [ "$#" -ne 1 ]; then
  echo "Erreur, usage: $0 <IP_TO_UNBLOCK>"
  exit 1
fi

IP="$1"

# Remove INPUT DROP rules
iptables -S INPUT | grep DROP | grep -E "(-s ${IP})( |$)" | while read -r rule; do
    iptables ${rule/-A/-D}
done

# Remove OUTPUT DROP rules
iptables -S OUTPUT | grep DROP | grep -E "(-d ${IP})( |$)" | while read -r rule; do
    iptables ${rule/-A/-D}
done

# Remove FORWARD DROP rules (source)
iptables -S FORWARD | grep DROP | grep -E "(-s ${IP})( |$)" | while read -r rule; do
    iptables ${rule/-A/-D}
done

# Remove FORWARD DROP rules (destination)
iptables -S FORWARD | grep DROP | grep -E "(-d ${IP})( |$)" | while read -r rule; do
    iptables ${rule/-A/-D}
done

echo "IP $IP is now unblocked"
