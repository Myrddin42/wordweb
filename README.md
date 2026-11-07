# WordWeb

WordWeb is a hobby project, that aims to create a (self-hosted) webtool which provides me with a
neat editor I can write my books in. The idea being that it not only functions as an editor, but
also provides functionality to help organize character biographies, link them to the chapters the
characters appear in, and note down story events which can be displayed in different timelines.

This app is based in Symfony, see below for other major dependencies, or check the
[composer.json](composer.json).

For now, the documentation will be organized as a loose collection of Markdown files in the
[doc](doc) directory.

## License

See [LICENSE.md](LICENSE.md)

## Contributing

This is something I'm working on in my free time, to create a tool that is mainly for myself. I'm
not looking for help, I only put it online in case someone else might find it useful. If you find it
neat, and the license permits it, feel free to take it and make it your own.

## Building the Project

This project has the following build dependencies:

* Docker (with buildx component)
* The Make utility

After these dependencies have been installed, one can build the application as a Docker Image via the **build** Make Target:

```bash
make build
```

> **NOTE!** At the moment, there is no "production" build yet, as the application is still under development.

To see all available make targets, one can use the default **help** Make Target:

```bash
# "help" is the first target, so it will be called by default, if no other was given
make
# One can also call it directly, like so:
make help
```

## Development Environment

During development, this repository will be mounted into a special Docker Container which will serve it
via Apache with the PHP module. A prerequisite for this is that one has to first build the Docker Image
said container will be created from via the following make target:

```bash
make dev-build
```

After this, the container is to be created with:

```bash
make dev-start
```

> The same target will start the container, if it already exists. It will **NOT** deploy a newer version of the conainer, if one rebuild the image itself!

To stop, or even destroy the container, use these targets:

```bash
# Only stops the container
make dev-stop
# Removes the container
make dev-down
```

One the first start, the application will not be ready to run yet, as the PHP dependencies are not
included inside the repository, neither is any database. To initialize both, use the **dev-init**
target:

```bash
make dev-init
```

This will run **composer install** inside the development container, instruct Symfony to create
the database and run all migrations against it. After that, the application ought to be reachable
at [http://localhost:8888](http://localhost:8888)

To enter the development container use:

```bash
make dev-shell
```

This will open a shell into the development container, so that one can execute commands in it. Like
say [bin/phpunit](bin/phpunit), to run the tests to make sure that everything works correctly.
Alternatively, the tests can be run via:

```bash
make dev-test
```