
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

