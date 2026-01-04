#!/bin/bash

# Usage: ./create_dummy.sh <filename> <size_in_MB>
# Example: ./create_dummy.sh testfile.bin 500

FILENAME="$1"
SIZE_MB="$2"

if [[ -z "$FILENAME" || -z "$SIZE_MB" ]]; then
    echo "Usage: $0 <filename> <size_in_MB>"
    exit 1
fi

# Create the dummy file
dd if=/dev/zero of="$FILENAME" bs=1MiB count="$SIZE_MB" status=progress

echo "Created file '$FILENAME' of size ${SIZE_MB}MB"
