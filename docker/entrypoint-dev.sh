#!/bin/bash

# Bail on any error

# Go to mounted application
cd /app

# Get the uid and gid from the README file
TARGET_UID=$(stat --format %u README.md)
TARGET_GID=$(stat --format %g README.md)

# Correct the localuser if it differs
if [ $(id --group localuser) -ne ${TARGET_GID} ]; then
	groupmod --non-unique -g ${TARGET_GID} localuser
fi
if [ $(id --user localuser) -ne ${TARGET_UID} ]; then
	usermod --non-unique -u ${TARGET_UID} localuser
fi

# Call on the original entrypoint
exec apache2-foreground
