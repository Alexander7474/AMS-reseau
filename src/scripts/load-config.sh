#!/bin/bash 

# Charge une sauvegarde de la config actuelle dans 
# src/config/saves/{config_folder}/
# Usage : sudo ./load-config.sh :config_folder

if [ "$#" -ne 1 ]; then
  echo "Erreur, usage: $0 :config_folder"
  exit 1
fi

# check si la save existe
SAVEFOLDER=/var/www/html/src/config/saves/$1
if [ -d "$SAVEFOLDER" ]; then
  echo "$SAVEFOLDER does exist."
else 
  echo "$SAVEFOLDER does not exist."
  exit 1
fi

# copy de la config
iptables-restore < ${SAVEFOLDER}/iptables
cp ${SAVEFOLDER}/interfaces /etc/network/interfaces 
cp -r ${SAVEFOLDER}/bind /etc/bind
cp ${SAVEFOLDER}/dhcpd.conf /etc/dhcp/dhcpd.conf 
cp ${SAVEFOLDER}/ip_forward /proc/sys/net/ipv4/ip_forward 

ifdown eth1 
ip addr flush dev eth1
ifup eth1
sudo systemctl restart isc-dhcp-server
sudo systemctl restart bind9

