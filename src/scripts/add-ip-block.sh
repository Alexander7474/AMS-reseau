#!/bin/bash

# Usage: ./blockip.sh <IP_TO_BLOCK>

if [ "$#" -ne 1 ]; then
  echo "Erreur, usage: $0 <IP_TO_BLOCK>"
  exit 1
fi

BLOCK_IP="$1"

# Block incoming traffic from the IP
iptables -A INPUT -s "$BLOCK_IP" -j DROP

# Block outgoing traffic to the IP
iptables -A OUTPUT -d "$BLOCK_IP" -j DROP

# (Optional) Block forwarded traffic involving the IP
iptables -A FORWARD -s "$BLOCK_IP" -j DROP
iptables -A FORWARD -d "$BLOCK_IP" -j DROP

echo "IP $BLOCK_IP is now blocked"
