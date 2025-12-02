#!/bin/bash

# Configure le fichier /etc/bind/db.alexandre.ceri.com 
# pour ajouter un sous domaine au réseau local

if (( $# != 2 )); then
  echo "error: 2 arg needed (:subdomain :ip)" >&2; exit 1
fi

echo "$1  IN  A   $2" >> /etc/bind/db.alexandre.ceri.com
systemctl restart bind9
