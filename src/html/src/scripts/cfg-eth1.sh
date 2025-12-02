#!/bin/bash

# Change la configuration de eth1
# en copiant interfaces.temp à la 
# place de /etc/network/interfaces

cp /var/www/html/src/tmp/interfaces.temp /etc/network/interfaces

ifdown eth1 
ip addr flush dev eth1
ifup eth1

echo "address changé !"
