#!/bin/bash

RULES=$(iptables -S | grep DROP)

OUTPUT=""

while read -r rule; do
    # Extract source IP
    SRC_IP=$(echo "$rule" | sed -n 's/.* -s \([0-9\.\/]*\).*/\1/p')

    # Extract destination IP
    DST_IP=$(echo "$rule" | sed -n 's/.* -d \([0-9\.\/]*\).*/\1/p')

    if [ -n "$SRC_IP" ]; then
        ENTRY="SRC:$SRC_IP"
    elif [ -n "$DST_IP" ]; then
        ENTRY="DST:$DST_IP"
    else
        continue
    fi

    if ! echo "$OUTPUT" | grep -q "$ENTRY"; then
        if [ -z "$OUTPUT" ]; then
            OUTPUT="$ENTRY"
        else
            OUTPUT="$OUTPUT|$ENTRY"
        fi
    fi
done <<< "$RULES"

echo "$OUTPUT"
