Fusio-Adapter-Cassandra
=====

[Fusio] adapter which provides a connection to work with a cassandra database. 
The adapter uses the `datastax/php-driver` package thus it requires the 
`cassandra` PHP extension. You can install the adapter with the following steps 
inside your Fusio project:

    composer require fusio/adapter-cassandra
    php bin/fusio system:register Fusio\Adapter\Cassandra\Adapter

NOTICE: Currently the cassandra extension supports only older PHP versions, so it
is not possible to use this extension until there is a newer version.

[Fusio]: http://fusio-project.org/
