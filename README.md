## About
E******* - assessment (RESTful API)

The API backend will have to connect to one or more remote sources, retrieve its data through an appropriate connector module and transform it accordingly before outputting it.

Project is build with Laravel 5.8

# Installation

composer install

NOTE: Redis is used as a cache, default config uses a local redis instance. 

In case you dont have a local redis :  provide a remote redis host or switch the cache driver to file.

To switch the default cache driver go to config/cache.php:

'default' => env('CACHE_DRIVER', 'file'),
