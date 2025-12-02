#!/bin/bash 

# Configure le fichier /etc/dhcp/dhcpd.conf 
# pour que le server dhcp puisse accueillir
# le nombre de machine passé en paramètre 
# Le script gère uniquement le mask 255.255.255.0

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

box_ip=$(/var/www/html/src/scripts/get-ip.sh)
box_addr=$(echo $box_ip | cut -d '|' -f 4)

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
elif (( $bit_need >= 8 )); then
  mask="255.255.$(bc <<< 256-2^$(($bit_need-8))).0"
  addr="192.168.$(bc <<< 2^$(($bit_need-8))).0"
  addr_start="192.168.$(bc <<< 2^$(($bit_need-8))).1"
  end_pow=$(bc <<< 2^$(($bit_need-7)))
  end_pow=$((end_pow-1))
  addr_end="192.168.$end_pow.254"
else 
  addr="192.168.0.$(bc <<< 2^$bit_need)"
  mask="255.255.255.$(bc <<< 256-2^$bit_need)"
  start_pow=$(bc <<< 2^$bit_need)
  start_pow=$((start_pow+1))
  addr_start="192.168.0.$start_pow"
  end_pow=$(bc <<< 2^$(($bit_need+1)))
  end_pow=$((end_pow-1))
  addr_end="192.168.0.$end_pow"
fi

echo "# Auto config gen by /var/www/html/src/scripts/cfg-dhcp.sh" > /etc/dhcp/dhcpd.conf 
echo "# For eth1: ${addr}: " >> /etc/dhcp/dhcpd.conf
echo "" >> /etc/dhcp/dhcpd.conf 
echo "default-lease-time 600;" >> /etc/dhcp/dhcpd.conf 
echo "max-lease-time 7200;" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhcpd.conf 
echo "subnet ${addr} netmask ${mask} {" >> /etc/dhcp/dhcpd.conf 
echo "  range  ${addr_start} ${addr_end};" >> /etc/dhcp/dhcpd.conf 
echo "}" >> /etc/dhcp/dhcpd.conf 
echo "" >> /etc/dhcp/dhclient.conf
echo "host box-ams {" >> /etc/dhcp/dhclient.conf
echo "  hardware ethernet 08:00:27:55:9e:75;" >> /etc/dhcp/dhclient.conf
echo "  fixed-address ${addr_start};" >> /etc/dhcp/dhclient.conf
echo "}" >> /etc/dhcp/dhclient.conf

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

systemctl restart isc-dhcp-server
#changement d'address de la box
ifdown eth1 
ip addr flush dev eth1
ifup eth1

