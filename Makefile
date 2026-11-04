.PHONY: help build dev-build dev-up dev-stop dev-down dev-shell

## This Makefile lists every target/command needed to build this application. If no target is given,
## it will print the comments in it as a help text. See the first make target below for more info:
##

help: ## Lists every build target
	@echo "$(shell grep '##' Makefile | grep -v grep | sed -e 's/^\([a-z][^:]*\): *\#\# *\(.*$$\)/\\033[01;32m\1\\033[01;00m\\t\2\\n/g' -e 's/^\#\# *\(.*$$\)/\\033[01;33m\1\\033[01;00m\\n/g' | tr -d '\n')"

build: ## Builds the application and packages it into a Docker Container
	echo TODO: After we got a MVP
	@docker/build.sh prod

##
## These commands are used to help with the application's development:
##

dev-build: ## Builds the development Docker Image
	@docker/build.sh dev

dev-start: ## Starts the development container, and mounts this directory into it
	@docker/dev-up.sh

dev-stop: ## Stops the development container
	@docker/dev-stop.sh

dev-down: ## Removes the development container
	@docker/dev-stop.sh remove

dev-shell: ## Opens a shell into the development container
	@docker exec -it -u localuser ww-dev-env bash
