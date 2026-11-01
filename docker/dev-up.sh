#!/bin/bash
##
# Creats or restart the development container
##

# Bail on any error
set -e

# Go into the directory where this script is
SCRIPT_DIR="$(dirname $0)"
cd "${SCRIPT_DIR}"

# Pull in the shared variables and functions
source envvars.sh

# Go one directory up, so that we can later mount correctly
cd ..

# Get the container status, while also checking if it exists
if ! STATUS=$(docker container inspect --format "{{.State.Running}}" ${DEV_CONTAINER_NAME} 2>/dev/null); then
	echo "Creating development container..."
	docker container run \
		--detach                                     \
		--name ${DEV_CONTAINER_NAME}                 \
		--publish 127.0.0.1:${DEV_CONTAINER_PORT}:80 \
		--volume "$(pwd):/app"                       \
		local/wordweb-dev
	# Done, so stop execution
	exit 0
fi

# Start the container if it was stopped
if [ "${STATUS}" = "false" ]; then
	echo "Restarting stopped container..."
	docker container start ${DEV_CONTAINER_NAME}
else
	echo "Container seems to be already running, nothing to do."
fi
