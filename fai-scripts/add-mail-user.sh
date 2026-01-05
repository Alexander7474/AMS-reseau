#!/bin/bash

# Créé un nouvelle utilisateur pour envoyer
# des mails

if (( $# != 1 )); then
  echo "error: 2 arg needed (:subdomain)" >&2; exit 1
fi

sudo mkdir -p /home/alexandre/maildir/
sudo chown -R alexandre:alexandre /home/alexandre/maildir
sudo chmod -R 700 /home/alexandre/maildir

sudo systemctl restart postfix
