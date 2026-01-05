#!/bin/bash

# Configure le fichier /etc/bind/db.alexandre.ceri.com 
# pour ajouter un sous domaine au réseau local

if (( $# != 2 )); then
  echo "error: 2 arg needed (:subdomain :ip)" >&2; exit 1
fi

#check regex pour être sur que ce qui est donné est une ip
if [[ ! $2 =~ ^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})$ ]]; then
  echo "error: $2 n'est pas une ip valide"; exit 1
fi

ZONE=$(/var/www/html/src/scripts/get-dns-zone.sh)

echo "$1  IN  A   $2" >> /etc/bind/db.$ZONE
systemctl restart bind9
