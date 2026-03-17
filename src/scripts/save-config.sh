#!/bin/bash 

# Créé une sauvegarde de la config actuelle dans 
# src/config/saves/{date}/

# creation du fichier de sauvegarde 
SAVEFOLDER=/var/www/html/src/config/saves/$(date "+%Y-%m-%d_%H:%M:%S")
mkdir -p $SAVEFOLDER

# copy de la config
iptables-save > ${SAVEFOLDER}/iptables
cp /etc/network/interfaces ${SAVEFOLDER}/interfaces
cp -r /etc/bind  ${SAVEFOLDER}/.
cp /etc/dhcp/dhcpd.conf ${SAVEFOLDER}/.
cp /proc/sys/net/ipv4/ip_forward ${SAVEFOLDER}/.
