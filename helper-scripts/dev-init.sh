#!/bin/bash
##
#
##

# Bail on any error
set -e

# Go into the root directory of the project
SCRIPT_DIR="$(dirname $0)"
cd "${SCRIPT_DIR}"

# Pull in the shared variables and functions
source envvars.sh

# Make sure the development container is actually there
check_dev_container_up

# Get PHP dependencies
docker exec -it -u localuser --workdir /app ww-dev-env composer install

# TODO: Run database migrations
#docker exec -it -u localuser --workdir /app ww-dev-env bin/console doctrine:migrations:migrate --all-or-nothing

# (For now only do schema update)
docker exec -it -u localuser --workdir /app ww-dev-env bin/console doctrine:schema:update --force
docker exec -it -u localuser --workdir /app ww-dev-env bin/console doctrine:schema:update --force --env=test