#!/bin/bash

# Ce script récupère les infos de configuration du
# service NAT.

ip_forward=0
if [[ "$(cat "/proc/sys/net/ipv4/ip_forward")" = "1" ]]; then
  ip_forward=1
fi

echo "$ip_forward|"
