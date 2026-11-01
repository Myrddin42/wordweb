# Security

As this webtool is only meant for local usage, there is currently no plan to implement a
user/password authorization. This is enforced by how the application's port will be "published" by
its Docker Container. As in, the port will only be opened on the IPv4 address 127.0.0.1, which is
the localhost which can't be reached from the outside. With that, the application can only be
reached from your own computer, which is assumed to be safe.

> **NOTE!** If you decide to forgo the Makefile and setup the application yourself, don't forget
> this part, or other people will be able to peek at your work! (Or, worse, delete it.)
