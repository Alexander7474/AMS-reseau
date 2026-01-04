#!/bin/bash

# Vérifie que les services de 
# la box sont en marche.

if [[ "$(cat "/proc/sys/net/ipv4/ip_forward")" = "1" ]]; then
  echo "Accès Internet=1|"
else
  echo "Accès Internet=0|"
fi

if systemctl is-active --quiet "apache2"; then
  echo "Server HTTP=1|"
else
  echo "Server HTTP=0|"
fi

if systemctl is-active --quiet "ssh"; then
  echo "SSH=1|"
else
  echo "SSH=0|"
fi

if systemctl is-active --quiet "isc-dhcp-server"; then
  echo "DHCP=1|"
else
  echo "DHCP=0|"
fi

if systemctl is-active --quiet "bind9"; then
  echo "DNS=1|"
else
  echo "DNS=0|"
fi

if systemctl is-active --quiet "minecraft"; then
  echo "Server Minecraft=1|"
else
  echo "Server Minecraft=0|"
fi

if systemctl is-active --quiet "teamspeak"; then
  echo "Server Teamspeak=1"
else
  echo "Server Teamspeak=0"
fi
