#!/bin/bash 

# Récupère l'address ip et 
# le mask de sous réseau de 
# eth1

ip=$(ifconfig eth1 | grep "inet addr:" | cut -d ':' -f 2 | cut -d ' ' -f 1)
echo "$(echo $ip | cut -d '.' -f 1)|$(echo $ip | cut -d '.' -f 2)|$(echo $ip | cut -d '.' -f 3)|$(echo $ip | cut -d '.' -f 4)|"
mask=$(ifconfig eth1 | grep "Mask:" | cut -d ':' -f 4 | cut -d ' ' -f 1)
echo "$(echo $mask | cut -d '.' -f 1)|$(echo $mask | cut -d '.' -f 2)|$(echo $mask | cut -d '.' -f 3)|$(echo $mask | cut -d '.' -f 4)|"
