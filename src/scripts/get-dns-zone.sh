#!/bin/bash

FILE="/etc/bind/named.conf.local"

grep -E '^[[:space:]]*zone[[:space:]]+' "$FILE" \
| sed -E 's/.*zone[[:space:]]+"([^"]+)".*/\1/' \
| head -n 1 \
| sed 's/\.$//'
