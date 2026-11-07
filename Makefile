.PHONY: help build dev-build dev-up dev-stop dev-down dev-shell dev-test

## This Makefile lists every target/command needed to build this application. If no target is given,
## it will print the comments in it as a help text. See the first make target below for more info:
##

help: ## Lists every build target
	@echo "$(shell grep '##' Makefile | grep -v grep | sed -e 's/^\([a-z][^:]*\): *\#\# *\(.*$$\)/\\033[01;32m\1\\033[01;00m\\t\2\\n/g' -e 's/^\#\# *\(.*$$\)/\\033[01;33m\1\\033[01;00m\\n/g' | tr -d '\n')"

build: ## Builds the application and packages it into a Docker Container
	echo TODO: After we got a MVP
	@docker/build.sh prod

##
## These commands are used to build a Docker Image to aid in local development, and to control the
## container created from it:
##

dev-build: ## Builds the development Docker Image
	@docker/build.sh dev

dev-start: ## Starts the development container, and mounts this directory into it
	@helper-scripts/dev-up.sh

dev-stop: ## Stops the development container
	@helper-scripts/dev-stop.sh

dev-down: ## Removes the development container
	@helper-scripts/dev-stop.sh remove

dev-shell: ## Opens a shell into the development container
	@docker exec -it -u localuser ww-dev-env bash

##
## After the development container has been started, one can initialize the application via the
## dev-init make target. It will install the required PHP dependencies, and initialize the database.
##

dev-init: ## Initializes the development environment
	@helper-scripts/dev-init.sh

##
## If one does not want to go onto into the development container to run the tests, one can use the
## following command. NOTE! That this of course won't work if the container is not running.
##

dev-test: ## Executes the tests in the development container
	@helper-scripts/dev-run-tests.sh