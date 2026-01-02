#!/bin/bash

ZONE=$(/var/www/html/src/scripts/get-dns-zone.sh)
ZONE_FILE="/etc/bind/db.$ZONE"


grep -Ev '^\s*($|;)' "$ZONE_FILE" \
| grep -E '^[A-Za-z0-9@._-]+\s+.*\s+A\s+' \
| sed -E 's/^([A-Za-z0-9@._-]+).*A\s+([0-9.]+).*/\1\/\2/' \
| paste -sd'|' -

