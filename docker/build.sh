#!/bin/bash
##
# Builds the Docker Images for this application
##

# Bail on any error
set -e

# Sanity check
if [ $# -lt 1 ]; then
	fail "Please specify which image to build!"
fi

# Go into the directory where this script is
SCRIPT_DIR="$(dirname $0)"
cd "${SCRIPT_DIR}"

# Load shared stuff
source ../helper-scripts/envvars.sh

# Check if the Dockerfile exists
DOCKERFILE="$1.dockerfile"
if ! ls "${DOCKERFILE}" >/dev/null 2>&1; then
	fail "There is no dockerfile named: ${DOCKERFILE}"
fi

# Build the image
docker build -f "${DOCKERFILE}" -t local/wordweb-"$1" .
