
##
# Checks if the development container is actually there and running
##
function check_dev_container_up() {
    # Get the container status, while also checking if it exists
    if ! STATUS=$(docker container inspect --format "{{.State.Running}}" ${DEV_CONTAINER_NAME} 2>/dev/null); then
        fail "The development container doesn't exist!"
    fi

    # Sanity check
    if [ "${STATUS}" = "false" ]; then
        fail "The development container is not running!"
    fi
}

##
# Tells the user what error we encountered, then stops execution
#
# $1 - The error message to show
##
function fail() {
	echo ERROR: $1 >&2
	exit 1
}

# The name of the development container
DEV_CONTAINER_NAME=ww-dev-env

# The port the development container will be reachable under
DEV_CONTAINER_PORT=8888

