#!/bin/bash
##
# Runs the tests in the development container via PHPUnit
##

# Bail on any error
set -e

# Go into the directory where this script is
SCRIPT_DIR="$(dirname $0)"
cd "${SCRIPT_DIR}"

# Pull in the shared variables and functions
source envvars.sh

# Make sure the development container is actually there
check_dev_container_up

# Run the tests
docker exec -it -u localuser --workdir /app ww-dev-env bin/phpunit
