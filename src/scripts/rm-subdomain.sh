#!/bin/bash

# Configure le fichier /etc/bind/db.alexandre.ceri.com 
# pour retirer un sous domaine au réseau local

if (( $# != 1 )); then
  echo "error: 1 arg needed (:subdomain)" >&2; exit 1
fi

sed -i "/^$1\b/d" "/etc/bind/db.alexandre.ceri.com"
systemctl restart bind9
