#!/bin/bash 

# Script de configuration du nat
# Usage: cfg-nat.sh <toggle ipv4 forward: 1|0>

if (( $# != 1 )); then
  echo "error: 1 arg needed (:ip_forward)" >&2; exit 1
fi

re='^[0-9]+$'
if ! [[ $1 =~ $re ]] ; then
  echo "error: Ip forward need to be a number" >&2; exit 1
fi

echo $1 > /proc/sys/net/ipv4/ip_forward

# ajout de règles de routage
if (( $1 == 1 ));then 
  sudo iptables -t nat -A POSTROUTING -o eth2 -j MASQUERADE
  sudo iptables -A FORWARD -i eth1 -o eth2 -j ACCEPT
  sudo iptables -A FORWARD -i eth2 -o eth1 -m state --state RELATED,ESTABLISHED -j ACCEPT
else
  # Flush all rules
  iptables -F
  iptables -t nat -F
  iptables -t mangle -F
  iptables -t raw -F

  # Delete all user-defined chains
  iptables -X
  iptables -t nat -X
  iptables -t mangle -X
  iptables -t raw -X

  # Reset default policies
  iptables -P INPUT ACCEPT
  iptables -P FORWARD ACCEPT
  iptables -P OUTPUT ACCEPT
fi
