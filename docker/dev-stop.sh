#!/bin/bash

# Bail on any error
set -e

# Go into the directory where this script is
SCRIPT_DIR="$(dirname $0)"
cd "${SCRIPT_DIR}"

# Pull in the shared variables and functions
source envvars.sh

# Check if the container really exists
if ! docker container inspect ${DEV_CONTAINER_NAME} >/dev/null 2>&1; then
	fail "The development container was not found, did you already delete it?"
fi

# Try to stop the container
echo Stopping container...
docker container stop ${DEV_CONTAINER_NAME}

# Check for given parameter
if [ $# -gt 0 ]; then
	if [ "$1" = "remove" ]; then
		docker container rm --force ${DEV_CONTAINER_NAME}
	else
		fail "Unknown argument, only 'remove' is a valid keyword"
	fi
fi
