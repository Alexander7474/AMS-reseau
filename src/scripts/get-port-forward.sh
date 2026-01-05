#!/bin/bash

RULES=$(iptables -t nat -S PREROUTING | grep DNAT)

OUTPUT=""

while read -r rule; do
    # Port d'entrée
    IN_PORT=$(echo "$rule" | sed -n 's/.*--dport \([0-9]\+\).*/\1/p')

    # Destination IP et port
    DEST=$(echo "$rule" | sed -n 's/.*--to-destination \([0-9\.]*\):\([0-9]\+\).*/\1\/\2/p')

    if [ -n "$IN_PORT" ] && [ -n "$DEST" ]; then
        ENTRY="$IN_PORT/$DEST"
        if [ -z "$OUTPUT" ]; then
            OUTPUT="$ENTRY"
        else
            OUTPUT="$OUTPUT|$ENTRY"
        fi
    fi
done <<< "$RULES"

echo "$OUTPUT"
