# Configuration

There are two kinds of configuration which are relevant to this project. The one of the underlying
Symfony framework itself, and configuration which governs the internal behavior of the application.

For the former, please refer to [the official Symfony
documentation](https://symfony.com/doc/current/configuration.html), and the setup guide.

The application configuration will be stored in the aptly named **configuration** database table.
Which uses the following format:

| Column    | Description                                             |
|-----------|---------------------------------------------------------|
| id        | Primary key                                             |
| parent_id | Points to the parent entry of this one (may be NULL)    |
| name      | The name of this entry                                  |
| value     | The entry's value (may be NULL)                         |
| type      | The entry's type                                        |

* Value is a simple string field
* Type must be one of: **GROUP**, or something prefixed with **TYPE_**

The idea hereby is that configuration values are encoded as a tree structure, whereby named groups
hold configuration entries. To keep things orderly, the following rules must be observed:

* No two entries with the same **parent_id** may have the same name
* Only an entry of type **GROUP** may be used as a parent
* The **value** of **GROUP** entries must be NULL
* On load, configuration values are to be converted from string depending on their **type**
