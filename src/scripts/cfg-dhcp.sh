#!/bin/bash 

# Configure le fichier /etc/dhcp/dhcpd.conf 
# pour que le server dhcp puisse accueillir
# le nombre de machine passé en paramètre 

if (( $# != 1 )); then
  echo "error: 1 arg needed (dhcp client)" >&2; exit 1
fi

re='^[0-9]+$'
if ! [[ $1 =~ $re ]] ; then
  echo "error: Not a number" >&2; exit 1
fi

if (( $1 >= $(bc <<< 2^16) )) || (( $1 < 1 )); then 
  echo "error: 1 < dhcp client < 65536" >&2; exit 1
fi

total_ip_needed=$(($1+2)) # broadcast + ip de la box
# Trouver la puissance de 2 suivante 
p=1
bit_need=0;
while (( total_ip_needed > p )) || (( p == 1 )); do
    p=$(( p << 1 ))
    bit_need=$(($bit_need+1))
done

if (( $bit_need == 16 )); then
  mask="255.255.0.0"
  addr="192.168.0.0"
  addr_start="192.168.0.1"
  addr_end="192.168.255.254"
elif (( $bit_need >= 9 )); then
  mask="255.255.$(bc <<< 256-2^$(($bit_need-8))).0"
  addr="192.168.$(bc <<< 2^$(($bit_need-8))).0"
  addr_start="192.168.$(bc <<< 2^$(($bit_need-8))).1"
  end_pow=$(bc <<< 2^$(($bit_need-7)))
  end_pow=$((end_pow-1))
  addr_end="192.168.$end_pow.254"
else 
  addr="192.168.10.0"
  mask="255.255.255.0"
  addr_start="192.168.10.1"
  addr_end="192.168.10.254"
fi

# TODO -- faire fonctionner ddns
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
echo "subnet ${addr} netmask ${mask} {" >> /etc/dhcp/dhcpd.conf 
echo "  range  ${addr_start} ${addr_end};" >> /etc/dhcp/dhcpd.conf 
echo "  option routers ${addr_start};" >> /etc/dhcp/dhcpd.conf 
echo "  option domain-name \"$(/var/www/html/src/scripts/get-dns-zone.sh)\";" >> /etc/dhcp/dhcpd.conf 
echo "  option domain-name-servers ${addr_start};" >> /etc/dhcp/dhcpd.conf 
echo "}" >> /etc/dhcp/dhcpd.conf 

echo "# Auto config gen by /var/www/html/src/scripts/cfg-dhcp.sh" > /etc/network/interfaces
echo "# The loopback network interface" >> /etc/network/interfaces
echo "auto lo" >> /etc/network/interfaces
echo "iface lo inet loopback " >> /etc/network/interfaces
echo "" >> /etc/network/interfaces
echo "# The primary network interface NAT" >> /etc/network/interfaces
echo "allow-hotplug eth0" >> /etc/network/interfaces
echo "iface eth0 inet dhcp" >> /etc/network/interfaces
echo "" >> /etc/network/interfaces
echo "# Internal network" >> /etc/network/interfaces
echo "allow-hotplug eth1" >> /etc/network/interfaces
echo "iface eth1 inet static" >> /etc/network/interfaces
echo "address ${addr_start}" >> /etc/network/interfaces
echo "netmask ${mask}" >> /etc/network/interfaces
echo "" >> /etc/network/interfaces
echo "# FAI network" >> /etc/network/interfaces
echo "allow-hotplug eth2" >> /etc/network/interfaces
echo "iface eth2 inet static" >> /etc/network/interfaces
echo "address 10.10.10.2" >> /etc/network/interfaces
echo "netmask 255.255.255.0" >> /etc/network/interfaces

systemctl restart isc-dhcp-server
#changement d'address de la box
ifdown eth1 
ip addr flush dev eth1
ifup eth1

#changement d'ip pour le sousdomaine box 
sudo /var/www/html/src/scripts/rm-subdomain.sh box
sudo /var/www/html/src/scripts/add-subdomain.sh box $addr_start

