#!/bin/bash 

# Configure le fichier /etc/dhcp/dhcpd.conf 
# avec une range en paramètre

if (( $# != 3 )); then
  echo "error: 2 arg needed (range_start - range_end)" >&2; exit 1
fi

#check regex pour être sur que ce qui est donné est une ip
if [[ ! $1 =~ ^192\.168\.([0-9]{1,3})\.([0-9]{1,3})$ ]]; then
  echo "error: $1 n'est pas une ip local valide"; exit 1
fi

if [[ ! $2 =~ ^192\.168\.([0-9]{1,3})\.([0-9]{1,3})$ ]]; then
  echo "error: $2 n'est pas une ip local valide"; exit 1
fi

if [[ ! $3 =~ ^(true|false)$ ]]; then
  echo "error: $3 est sois 'true', sois 'false'"; exit 1
fi

# TODO -- ajouter des warning (maybe avec javascript) quand la config est incorrect (mais attention a ne pas empêcher l'utilisateur de le faire en mode avancé)
interface_cfg=$(/var/www/html/src/scripts/get-ip.sh)
box_addr=$(echo $interface_cfg | cut -d '|' -f1)
network_addr=$(echo $interface_cfg | cut -d '|' -f3)
network_mask=$(echo $interface_cfg | cut -d '|' -f2)

echo "# Auto config gen by /var/www/html/src/scripts/cfg-dhcp.sh" > /etc/dhcp/dhcpd.conf 
echo "# For eth1: ${box_addr}: " >> /etc/dhcp/dhcpd.conf
echo "" >> /etc/dhcp/dhcpd.conf 
echo "default-lease-time 600;" >> /etc/dhcp/dhcpd.conf 
echo "max-lease-time 7200;" >> /etc/dhcp/dhcpd.conf 
echo "ping-check ${3};" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhcpd.conf 
echo "ddns-update-style interim;" >> /etc/dhcp/dhcpd.conf 
echo "ignore client-updates;" >> /etc/dhcp/dhcpd.conf 
echo "ddns-domainname \"$(/var/www/html/src/scripts/get-dns-zone.sh)\";" >> /etc/dhcp/dhcpd.conf 
echo "ddns-rev-domainname \"in-addr.arpa\";" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhcpd.conf 
echo "authoritative;" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhcpd.conf 
echo "# TSIG keys to sign DDNS updates" >> /etc/dhcp/dhcpd.conf 
echo "key dhcpupdate {" >> /etc/dhcp/dhcpd.conf 
echo "  algorithm hmac-md5;" >> /etc/dhcp/dhcpd.conf 
echo "  secret \"$(cat /var/www/html/keys/Kdhcpupdate.+157+18131.key | cut -d ' ' -f7)\";" >> /etc/dhcp/dhcpd.conf 
echo "}" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhcpd.conf 
echo "zone $(/var/www/html/src/scripts/get-dns-zone.sh). {" >> /etc/dhcp/dhcpd.conf 
echo "  primary 127.0.0.1;" >> /etc/dhcp/dhcpd.conf 
echo "  key dhcpupdate;" >> /etc/dhcp/dhcpd.conf 
echo "}" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhcpd.conf 
echo "zone 1.168.192.in-addr.arpa. {" >> /etc/dhcp/dhcpd.conf 
echo "  primary 127.0.0.1;" >> /etc/dhcp/dhcpd.conf 
echo "  key dhcpupdate;" >> /etc/dhcp/dhcpd.conf 
echo "}" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhcpd.conf 
echo "subnet ${network_addr} netmask ${network_mask} {" >> /etc/dhcp/dhcpd.conf 
echo "  range ${1} ${2};" >> /etc/dhcp/dhcpd.conf 
echo "  option routers ${box_addr};" >> /etc/dhcp/dhcpd.conf 
echo "  option domain-name \"$(/var/www/html/src/scripts/get-dns-zone.sh)\";" >> /etc/dhcp/dhcpd.conf 
echo "  option domain-name-servers ${box_addr};" >> /etc/dhcp/dhcpd.conf 
echo "}" >> /etc/dhcp/dhcpd.conf 

systemctl restart isc-dhcp-server
