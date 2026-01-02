#!/bin/bash 

# Récupère l'address ip et 
# le mask de sous réseau de 
# eth1

ip=$(ifconfig eth1 | grep "inet addr:" | cut -d ':' -f 2 | cut -d ' ' -f 1)
mask=$(ifconfig eth1 | grep "Mask:" | cut -d ':' -f 4 | cut -d ' ' -f 1)

# Split IP and MASK into arrays
IFS=. read -r i1 i2 i3 i4 <<< "$ip"
IFS=. read -r m1 m2 m3 m4 <<< "$mask"
# Bitwise AND to get network address
n1=$((i1 & m1))
n2=$((i2 & m2))
n3=$((i3 & m3))
n4=$((i4 & m4))

echo "$i1.$i2.$i3.$i4|$m1.$m2.$m3.$m4|$n1.$n2.$n3.$n4|"
