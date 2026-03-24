#!/bin/bash 

# Supprime une sauvegarde de config dans 
# src/config/saves/{config_folder}/
# Usage : sudo ./rm-saved-config.sh :config_folder

if [ "$#" -ne 1 ]; then
  echo "Erreur, usage: $0 :config_folder"
  exit 1
fi

# check si la save existe
SAVEFOLDER=/var/www/html/src/config/saves/$1
if [ -d "$SAVEFOLDER" ]; then
  echo "$SAVEFOLDER does exist."
else 
  echo "$SAVEFOLDER does not exist."
  exit 1
fi

rm -rf ${SAVEFOLDER}
