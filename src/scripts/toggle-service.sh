#!/bin/bash 

# Active le service en paramètre 
# 1 activer, 0 desactiver

if (( $# != 2 )); then
  echo "error: 2 arg needed (:service :status)" >&2; exit 1
fi

re='^[0-9]+$'
if ! [[ $2 =~ $re ]] ; then
  echo "error: Not a number" >&2; exit 1
fi

if (( $2 == 1 )); then
  sudo systemctl start $1
else 
  sudo systemctl stop $1
fi
