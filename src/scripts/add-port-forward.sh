#!/bin/bash

# Usage: ./portforward.sh <LISTEN_PORT> <DEST_IP> <DEST_PORT>

if [ "$#" -ne 3 ]; then
  echo "Erreur, usage: $0 <LISTEN_PORT> <DEST_IP> <DEST_PORT>"
  exit 1
fi

LISTEN_PORT="$1"
DEST_IP="$2"
DEST_PORT="$3"

# Add DNAT rule
iptables -t nat -A PREROUTING -p tcp --dport "$LISTEN_PORT" \
  -j DNAT --to-destination "$DEST_IP:$DEST_PORT"

# Allow forwarding
iptables -A FORWARD -p tcp -d "$DEST_IP" --dport "$DEST_PORT" -j ACCEPT

echo "Port $LISTEN_PORT forwarded to $DEST_IP:$DEST_PORT"
