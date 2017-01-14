Fusio-Adapter-Cassandra
=====

[Fusio] adapter which provides a connection to work with a cassandra database. 
The adapter uses the `datastax/php-driver` package thus it requires the 
`cassandra` PHP extension. You can install the adapter with the following steps 
inside your Fusio project:

    composer require fusio/adapter-cassandra
    php bin/fusio system:register Fusio\Adapter\Cassandra\Adapter

[Fusio]: http://fusio-project.org/
