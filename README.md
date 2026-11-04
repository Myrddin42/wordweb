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

TODO
